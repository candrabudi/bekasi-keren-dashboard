<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string|min:6',
        ], [
            'username.exists' => 'Username tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status != 1) {
                return redirect()->back()->with('error', 'Akun Anda tidak aktif.');
            }

            Auth::login($user, true);

            $menus = $user->getReadableMenus();
            $firstRoute = $this->findFirstRouteFromMenus($menus);

            if ($firstRoute) {
                return redirect($firstRoute)->with('success', 'Login berhasil.');
            }

            return redirect('/')->with('warning', 'Tidak ada menu yang bisa diakses.');
        }

        return redirect()->back()->with('error', 'Password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('backstreet.login')->with('success', 'Anda telah logout.');
    }


    private function findFirstRouteFromMenus($menus)
    {
        foreach ($menus as $menu) {
            if (!empty($menu->route)) {
                return $menu->route;
            }

            if (!empty($menu->children)) {
                $childRoute = $this->findFirstRouteFromMenus($menu->children);
                if ($childRoute) {
                    return $childRoute;
                }
            }
        }

        return null;
    }


}
