<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'check.menu.permission:Menus,read'])->only(['index', 'show']);
    //     $this->middleware(['auth', 'check.menu.permission:Menus,create'])->only(['create', 'store']);
    //     $this->middleware(['auth', 'check.menu.permission:Menus,edit'])->only(['edit', 'update']);
    //     $this->middleware(['auth', 'check.menu.permission:Menus,delete'])->only(['destroy']);
    // }

    public function index()
    {
        $menus = Menu::with('parent')->orderBy('parent_id')->orderBy('name')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->get();
        $permissions = Permission::all();
        return view('menus.create', compact('parents', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
        ]);
    
        $duplicateName = Menu::where('name', $request->name)
            ->where('parent_id', $request->parent_id)
            ->exists();
    
        if ($duplicateName) {
            return redirect()->back()
                ->withErrors(['name' => 'A menu with the same name already exists under this parent.'])
                ->withInput();
        }
    
        if ($request->filled('route')) {
            $duplicateRoute = Menu::where('route', $request->route)->exists();
            if ($duplicateRoute) {
                return redirect()->back()
                    ->withErrors(['route' => 'The route name is already used by another menu.'])
                    ->withInput();
            }
        }
    
        DB::transaction(function () use ($request) {
            Menu::create($request->only('name', 'route', 'parent_id'));
        });
    
        return redirect()->route('menus.index')->with('success', 'Menu created.');
    }
    
    
    public function edit($id)
    {
        $menu = Menu::find($id);
    
        if (!$menu) {
            return redirect()->route('menus.index')
                ->with('error', 'Menu not found.')
                ->withInput();
        }
    
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        
        $permissions = Permission::all();
        
        if ($parents->isEmpty() || $permissions->isEmpty()) {
            return redirect()->route('menus.index')
                ->with('error', 'No parent menus or permissions found.')
                ->withInput();
        }
    
        return view('menus.edit', compact('menu', 'parents', 'permissions'));
    }
    

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
        ]);

        DB::transaction(function () use ($request, $menu) {
            $menu->update($request->only('name', 'route', 'parent_id'));
        });

        return redirect()->route('menus.index')->with('success', 'Menu updated.');
    }

    public function destroy($a)
    {
        $menu = Menu::find($a);
    
        if (!$menu) {
            return redirect()->route('menus.index')
                ->with('error', 'Menu not found.');
        }
    
        DB::table('menu_permission_role')
            ->where('menu_id', $menu->id)
            ->delete();
    
        $menu->delete();
    
        return redirect()->route('menus.index')->with('success', 'Menu deleted.');
    }
    
}
