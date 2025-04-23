<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')
            ->paginate(25);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        return view('users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'status'    => 'required|in:0,1',
        ]);
    
        $user = User::create([
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => $request->status,
        ]);

        if($request->department_id) {
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
            $userDetail->department_id = $request->department_id;
            $userDetail->phone_number = $request->phone;
            $userDetail->save();
        }
    
        $user->roles()->syncWithoutDetaching([$request->role_id]);
    
        return redirect()->route('backstreet.users.index')->with('success', 'User created successfully.');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRole = $user->roles->first()?->id;
    
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8',
            'status'    => 'required|in:0,1',
            'roles'     => 'nullable|array',
        ]);
    
        $user->update([
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'email'     => $request->email,
            'status'    => $request->status,
        ]);
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
    
        $user->roles()->syncWithoutDetaching([$request->role_id]);
    
        return redirect()->route('backstreet.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        UserDetail::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('backstreet.users.index')->with('success', 'User deleted successfully.');
    }


}
