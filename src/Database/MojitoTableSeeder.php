<?php

namespace Moell\Mojito\Database;

use Illuminate\Database\Seeder;
use Moell\Mojito\AdminUserFactory;
use Moell\Mojito\Models\Menu;
use Moell\Mojito\Models\PermissionGroup;
use Moell\Mojito\Models\Role;
use Spatie\Permission\Models\Permission;

class MojitoTableSeeder extends Seeder
{
    private $permissions = [
        [
            'name' => 'admin-user.index',
            'display_name' => 'index',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.show',
            'display_name' => 'show',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.store',
            'display_name' => 'store',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.update',
            'display_name' => 'update',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.destroy',
            'display_name' => 'destroy',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.roles',
            'display_name' => 'role list',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'admin-user.assign-roles',
            'display_name' => 'assign role',
            'sequence' => 0,
            'pg_id' => 1,
        ],
        [
            'name' => 'system.dialog',
            'display_name' => 'dialog permission',
            'sequence' => 0,
            'pg_id' => 1,
        ],

        [
            'name' => 'role.index',
            'display_name' => 'index',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.show',
            'display_name' => 'show',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.store',
            'display_name' => 'store',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.update',
            'display_name' => 'update',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.destroy',
            'display_name' => 'destroy',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.permissions',
            'display_name' => 'role permissions',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.assign-permissions',
            'display_name' => 'role assignment authority',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.guard-name-roles',
            'display_name' => 'Specify the role of guard name',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.menus',
            'display_name' => 'role menus',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.assign-menus',
            'display_name' => 'role assign menus',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'role.toggle-menus',
            'display_name' => 'role toggle-menus',
            'sequence' => 0,
            'pg_id' => 2,
        ],
        [
            'name' => 'permission.index',
            'display_name' => 'index',
            'sequence' => 0,
            'pg_id' => 3,
        ],
        [
            'name' => 'permission.show',
            'display_name' => 'show',
            'sequence' => 0,
            'pg_id' => 3,
        ],
        [
            'name' => 'permission.store',
            'display_name' => 'store',
            'sequence' => 0,
            'pg_id' => 3,
        ],
        [
            'name' => 'permission.update',
            'display_name' => 'update',
            'sequence' => 0,
            'pg_id' => 3,
        ],
        [
            'name' => 'permission.destroy',
            'display_name' => 'destroy',
            'sequence' => 0,
            'pg_id' => 3,
        ],
        [
            'name' => 'menu.index',
            'display_name' => 'index',
            'sequence' => 0,
            'pg_id' => 4,
        ],
        [
            'name' => 'menu.show',
            'display_name' => 'show',
            'sequence' => 0,
            'pg_id' => 4,
        ],
        [
            'name' => 'menu.store',
            'display_name' => 'store',
            'sequence' => 0,
            'pg_id' => 4,
        ],
        [
            'name' => 'menu.update',
            'display_name' => 'update',
            'sequence' => 0,
            'pg_id' => 4,
        ],
        [
            'name' => 'menu.destroy',
            'display_name' => 'destroy',
            'sequence' => 0,
            'pg_id' => 4,
        ],
        [
            'name' => 'permission-group.index',
            'display_name' => 'index',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.show',
            'display_name' => 'show',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.store',
            'display_name' => 'store',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.update',
            'display_name' => 'update',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.destroy',
            'display_name' => 'destroy',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.guard-name-for-permission',
            'display_name' => 'Guard name for permissions',
            'sequence' => 0,
            'pg_id' => 5,
        ],
        [
            'name' => 'permission-group.all',
            'display_name' => 'All permission groups',
            'sequence' => 0,
            'pg_id' => 5,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @author moell<moel91@foxmail.com>
     * @return void
     */
    public function run()
    {
        if (!AdminUserFactory::adminUser()->first()) {

            app()['cache']->forget('spatie.permission.cache');

            $this->createdAdminUser();

            $this->createPermissionGroup();

            $this->createRole();

            $this->createPermission();

            $this->createMenu();

            $this->associateRolePermissions();

            $this->associateRoleMenus();
        }
    }
    /**
     * @author moell<moel91@foxmail.com>
     */
    private function createdAdminUser()
    {
        AdminUserFactory::adminUser()->truncate();

        AdminUserFactory::adminUser()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * @author moell<moel91@foxmail.com>
     */
    private function createPermission()
    {
        Permission::query()->delete();

        foreach ($this->permissions as $permission) {
            $permission['guard_name'] = 'admin';
            Permission::create($permission);
        }
    }

    /**
     * @author moell<moel91@foxmail.com>
     */
    private function createPermissionGroup()
    {
        PermissionGroup::truncate();
        PermissionGroup::insert([
            [
                'id' => 1,
                'name' => 'Admin users',
            ], [
                'id' => 2,
                'name' => 'Role',
            ], [
                'id' => 3,
                'name' => 'Permission',
            ], [
                'id' => 4,
                'name' => 'Menu',
            ], [
                'id' => 5,
                'name' => 'Permission groups',
            ],
        ]);
    }

    /**
     * @author moell<moel91@foxmail.com>
     */
    private function createRole()
    {
        Role::query()->delete();
        Role::create([
            'name' => 'admin',
            'guard_name' => 'admin',
        ]);
    }

    /**
     * @author moell<moel91@foxmail.com>
     */
    private function createMenu()
    {
        Menu::query()->delete();
        Menu::insert([
            [
                'id' => 1,
                'parent_id' => 0,
                'uri' => '/admin/dashboard',
                'name' => trans('mojito.Dashboard'),
                'icon' => 'mofont mo-icon-dashboard mo-menu',
                'guard_name' => 'admin',
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'uri' => '/admin/admin',
                'name' => trans('mojito.Admin'),
                'icon' => 'mofont mo-icon-admin mo-menu',
                'guard_name' => 'admin',
            ],
            [
                'id' => 3,
                'parent_id' => 2,
                'uri' => '/admin/admin-user',
                'name' => trans('mojito.AdminUser'),
                'icon' => '',
                'guard_name' => 'admin',
            ],
            [
                'id' => 4,
                'parent_id' => 2,
                'uri' => '/admin/role',
                'name' => trans('mojito.Role'),
                'icon' => '',
                'guard_name' => 'admin',
            ],
            [
                'id' => 5,
                'parent_id' => 2,
                'uri' => '/admin/permission',
                'name' => trans('mojito.Permission'),
                'icon' => '',
                'guard_name' => 'admin',
            ],
            [
                'id' => 6,
                'parent_id' => 2,
                'uri' => '/admin/permission-group',
                'name' => trans('mojito.PermissionGroup'),
                'icon' => '',
                'guard_name' => 'admin',
            ],
            [
                'id' => 7,
                'parent_id' => 2,
                'uri' => '/admin/menu',
                'name' => trans('mojito.Menu'),
                'icon' => '',
                'guard_name' => 'admin',
            ],

        ]);
    }

    /**
     * @author moell<moel91@foxmail.com>
     */
    private function associateRolePermissions()
    {
        $role = Role::first();

        AdminUserFactory::adminUser()->first()->assignRole($role->name);

        foreach ($this->permissions as $permission) {
            $role->givePermissionTo($permission['name']);
        }
    }
    /**
     * osindex<yaoiluo@gmail.com>
     */
    private function associateRoleMenus()
    {
        $role = Role::first();
        $role->menus()->toggle(Menu::pluck('id'));
    }
}
