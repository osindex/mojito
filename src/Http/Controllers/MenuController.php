<?php

namespace Moell\Mojito\Http\Controllers;

use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Moell\Mojito\Http\Requests\Menu\CreateOrUpdateRequest;
use Moell\Mojito\Models\Menu;
use Moell\Mojito\Models\PermissionGroup;
use Moell\Mojito\Resources\Menu as MenuResource;

class MenuController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $searchName = $request->input('name');
        $menus = Menu::query()
            ->where('guard_name', $request->input('guard_name', 'admin'))
            ->when($searchName, function ($q) use ($searchName) {
                $q->where('name', 'like', $searchName);
            })
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
        $permission_name = $menu->permission_name ?: $comName . ".index";

        // create new files
        $toRoutes = resource_path('js/views/admin/' . $folder . '/routes.js');
        if (!file_exists($toRoutes)) {
            $closureCom = function ($line) use ($permission_name) {
                return "      permission: '" . $permission_name . "'" . PHP_EOL;
            };
            $compRoutes = resource_path('js/views/admin/example/routes.js');
            recodeFile($compRoutes, 'example.index', ["example", [$comName, $folder, $comName]], $toRoutes, null, $closureCom);
        }
        $tovueFile = resource_path('js/views/admin/' . $folder . '/index.vue');
        if (!file_exists($tovueFile)) {
            $vueFile = resource_path('js/views/admin/example/index.vue');
            recodeFile($vueFile, null, ["example", [$comName, $comName, $comName, $comName, $comName]], $tovueFile, null, null);
        }

        $langFile = resource_path('js/lang/' . config('app.locale') . '.js');
        $langFileContent = recodeFile($langFile);
        $insertLang = '            ' . $comName . ": '" . $menu->name . "'," . PHP_EOL;
        if (strpos($langFileContent, '            ' . $comName . ": '") === false) {
            $closureLang = function ($line) use ($insertLang) {
                return $line . $insertLang;
            };
            recodeFile($langFile, 'roleAssignPermission', [], $langFile, null, $closureLang);
        }
        // change baseRoute
        $baseRoutes = resource_path('js/router/routers.js');
        $baseRoutesFileContent = recodeFile($baseRoutes);
        if (strpos($baseRoutesFileContent, "import " . $comName . " from") === false) {
            // callback
            $closure = function ($line) use ($comName) {
                return rtrim($line, PHP_EOL) . ', ...' . $comName . PHP_EOL;
            };
            recodeFile($baseRoutes, ['...adminDashboard'], ["import adminDashboard from '../views/admin/dashboard/routes'", ["import adminDashboard from '../views/admin/dashboard/routes'" . PHP_EOL . "import " . $comName . " from '../views/admin/" . $folder . "/routes'"]], $baseRoutes, null, $closure);
        }
        // auto permission
        $pg_id = 0;
        if ($folder === $comName) {
            // $pg_id = PermissionGroup::insertGetId(['name' => $comName]);
            $pg_id = PermissionGroup::firstOrCreate(['name' => $comName])->id;
        } else {
            $pg_id = optional(Permission::where('name', 'like', $comNameArray[0] . '%')->first())->pg_id;
        }
        $permission = [
            'guard_name' => config('mojito.super_admin.guard'),
            'name' => $permission_name,
            'display_name' => $menu->name,
            'icon' => $menu->icon,
            'pg_id' => $pg_id ?: 1, //if not set 1
        ];
        $perm = Permission::updateOrCreate(['name' => $permission_name], $permission);

        $role = Auth::user()->roles->first();
        $role->givePermissionTo($perm);

        return $this->created();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function my(Request $request)
    {
        $guardName = data_get(Auth::user()->currentAccessToken(), "name", "admin");

        $userPermissions = Auth::user()->getAllPermissions()->pluck('name');
        $menus = Auth::user()->roles->flatMap(function ($role) {
            return $role->menus()->orderBy('sequence', 'desc')->orderBy('id')->get();
        })
            ->where('guard_name', $guardName)
            ->where('is_display', true)
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
