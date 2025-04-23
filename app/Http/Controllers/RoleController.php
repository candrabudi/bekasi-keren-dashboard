<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['menuPermissions.menu', 'menuPermissions.permission'])->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $menus = Menu::with(['children', 'permissions'])->whereNull('parent_id')->get();
        $permissions = Permission::all();
    
        return view('roles.create', compact('menus', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        foreach ($request->permissions as $menuSlug => $actions) {
            $menu = Menu::whereRaw('LOWER(REPLACE(name, " ", "_")) = ?', [$menuSlug])->first();
            if (!$menu) continue;
        
            foreach ($actions as $action) {
                $permission = Permission::where('name', $action)->first();
                if (!$permission) continue;
        
                DB::table('menu_permission_role')->updateOrInsert([
                    'role_id' => $role->id,
                    'menu_id' => $menu->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        $permissions = Permission::all();
        $rolePermissions = DB::table('menu_permission_role')
            ->where('role_id', $role->id)
            ->pluck('permission_id', 'menu_id');

        return view('roles.edit', compact('role', 'menus', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);
    
        $role->update(['name' => $request->name]);
    
        // Delete old role-menu-permission
        DB::table('menu_permission_role')->where('role_id', $role->id)->delete();
    
        if ($request->has('permissions')) {
            foreach ($request->permissions as $menuId => $permissionIds) {
                foreach ($permissionIds as $permissionId) {
                    DB::table('menu_permission_role')->insert([
                        'role_id' => $role->id,
                        'menu_id' => $menuId,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
    
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
