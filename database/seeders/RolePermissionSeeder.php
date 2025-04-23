<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // 1. Create Role
            $role = Role::firstOrCreate(['name' => 'admin']);

            // 2. Create Permissions (if not exist)
            $permissionNames = ['create', 'read', 'edit', 'delete'];
            foreach ($permissionNames as $name) {
                Permission::firstOrCreate(['name' => $name]);
            }

            // 3. Menu Structure (as JSON)
            $menuStructure = [
                [
                    'menu' => 'Dashboard',
                    'actions' => ['read'],
                ],
                [
                    'menu' => 'Users',
                    'actions' => ['create', 'read', 'edit', 'delete'],
                ],
                [
                    'menu' => 'Settings',
                    'actions' => ['read'],
                    'submenus' => [
                        [
                            'menu' => 'General',
                            'actions' => ['read', 'edit'],
                        ],
                        [
                            'menu' => 'Security',
                            'actions' => ['read'],
                        ],
                    ],
                ],
            ];

            // 4. Recursive menu creation
            foreach ($menuStructure as $menuItem) {
                $this->createMenuWithPermissions($menuItem, $role);
            }

            $user = User::firstOrCreate([
                'email' => 'admin@example.com',
            ], [
                'full_name' => 'Super Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'), // Ganti untuk production
                'status' => 1,
            ]);
            // 6. Attach role to user
            $user->roles()->syncWithoutDetaching([$role->id]);
        });
    }

    private function createMenuWithPermissions($item, $role, $parentId = null)
    {
        // Create menu
        $menu = Menu::firstOrCreate([
            'name' => $item['menu'],
            'parent_id' => $parentId,
        ]);

        // Attach permissions to menu-role
        foreach ($item['actions'] as $action) {
            $permission = Permission::where('name', $action)->first();
            DB::table('menu_permission_role')->updateOrInsert([
                'role_id' => $role->id,
                'menu_id' => $menu->id,
                'permission_id' => $permission->id,
            ]);
        }

        // Recursively handle submenus
        if (isset($item['submenus'])) {
            foreach ($item['submenus'] as $sub) {
                $this->createMenuWithPermissions($sub, $role, $menu->id);
            }
        }
    }
}
