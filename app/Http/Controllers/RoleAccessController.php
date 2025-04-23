<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleAccessController extends Controller
{
    public function edit(Role $role)
    {
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        $permissions = Permission::all();

        $access = DB::table('menu_permission_role')
            ->where('role_id', $role->id)
            ->get()
            ->groupBy('menu_id')
            ->map(fn($items) => collect($items)->pluck('permission_id')->toArray());

        return view('roles.access', compact('role', 'menus', 'permissions', 'access'));
    }

    public function update(Request $request, Role $role)
    {
        DB::table('menu_permission_role')->where('role_id', $role->id)->delete();

        foreach ($request->input('access', []) as $menuId => $permIds) {
            foreach ($permIds as $permissionId) {
                DB::table('menu_permission_role')->insert([
                    'role_id' => $role->id,
                    'menu_id' => $menuId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Akses role berhasil diperbarui.');
    }
}
