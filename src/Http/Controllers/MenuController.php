<?php

namespace Moell\Mojito\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Moell\Mojito\Http\Requests\Menu\CreateOrUpdateRequest;
use Moell\Mojito\Models\Menu;
use Moell\Mojito\Models\PermissionGroup;
use Moell\Mojito\Resources\Menu as MenuResource;
use SMartins\PassportMultiauth\Config\AuthConfigHelper;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $menus = Menu::query()
            ->where('guard_name', $request->input('guard_name', 'admin'))
            ->orderBy('sequence', 'desc')
            ->get();

        return response()->json(['data' => make_tree($menus->toArray())]);
    }
    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        $menu = Menu::create($request->all());
        $folder = ltrim($menu->uri, '/');
        // mkdir
        $dir = resource_path('js/views/admin/' . $folder);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // get component name
        $comNameArray = explode("/", $folder);
        $comName = Arr::last($comNameArray);

        $baseRoutes = resource_path('js/router/routers.js');

        // callback
        $closure = function ($line) use ($comName) {
            return rtrim($line, PHP_EOL) . ', ...' . $comName . PHP_EOL;
        };
        // change baseRoute
        recodeFile($baseRoutes, ['...adminDashboard'], ["import adminDashboard from '../views/admin/dashboard/routes'", ["import adminDashboard from '../views/admin/dashboard/routes'" . PHP_EOL . "import " . $comName . " from '../views/admin/" . $folder . "/routes'"]], $baseRoutes, null, $closure);

        // create new files
        $compRoutes = resource_path('js/views/admin/example/routes.js');
        $toRoutes = resource_path('js/views/admin/' . $folder . '/routes.js');

        $permission_name = $menu->permission_name ?: $comName . ".index";
        $closureCom = function ($line) use ($permission_name) {
            return "      permission: '" . $permission_name . "'" . PHP_EOL;
        };
        recodeFile($compRoutes, 'example.index', ["example", [$comName, $folder, $comName]], $toRoutes, null, $closureCom);

        $vueFile = resource_path('js/views/admin/example/index.vue');
        $tovueFile = resource_path('js/views/admin/' . $folder . '/index.vue');
        recodeFile($vueFile, null, ["example", [$comName, $comName, $comName, $comName, $comName]], $tovueFile, null, null);

        $langFile = resource_path('js/lang/' . config('app.locale') . '.js');

        $closureLang = function ($line) use ($comName, $menu) {
            return $line . '            ' . $comName . ": '" . $menu->name . "'," . PHP_EOL;
        };
        recodeFile($langFile, 'roleAssignPermission', [], $langFile, null, $closureLang);

        // auto permission
        $pg_id = 0;
        if ($folder === $comName) {
            $pg_id = PermissionGroup::insertGetId(['name' => $comName]);
        } else {
            $pg_id = optional(Permission::where('name', 'like', $comNameArray[0] . '%')->first())->pg_id;
        }
        $permission = [
            'guard_name' => config('mojito.super_admin.guard'),
            'name' => $permission_name,
            'display_name' => $menu->name,
            'icon' => $menu->icon,
            'pg_id' => $pg_id ?? 1, //if not set 1
        ];
        $perm = Permission::create($permission);

        $role = Auth::user()->roles->first();
        $role->givePermissionTo($perm);

        return $this->created();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function my()
    {
        $guardName = AuthConfigHelper::getUserGuard(Auth::user());

        $userPermissions = Auth::user()->getAllPermissions()->pluck('name');
        $menus = Menu::query()
            ->where('guard_name', $guardName)
            ->orderBy('sequence', 'desc')
            ->get()
            ->filter(function ($item) use ($userPermissions) {
                return !$item->permission_name || $userPermissions->contains($item->permission_name);
            });

        return response()->json(['data' => make_tree($menus->toArray())]);
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        $menu = Menu::query()->findOrFail($id);

        $menu->update($request->toArray());

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return MenuResource
     */
    public function show($id)
    {
        return new MenuResource(Menu::query()->findOrFail($id));
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::query()->findOrFail($id);

        if (Menu::query()->where('parent_id', $menu->id)->count()) {
            return $this->unprocesableEtity([
                'parent_id' => 'Please delete the submenu first.',
            ]);
        }

        $menu->delete();

        return $this->noContent();
    }
}
