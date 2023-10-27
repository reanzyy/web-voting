<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ], [
            'username.required' => 'Username harus diisi!',
            'username.max' => 'Username hanya boleh diisi maksimal 255 karakter!',
            'password.required' => 'Password harus diisi!',
            'password.max' => 'Password hanya boleh diisi maksimal 255 karakter',
        ]);

        $user = User::where('username', $request->username)
            ->first();

        if (!$user) {
            return redirect('login')->with('error', 'Username dan password tidak sesuai!');
        }

        if (Hash::check($request->password, $user->password)) {
            Auth::loginUsingId($user->id);
            if ($user->role == 'admin') {
                return redirect()->route('dashboard')->withSuccess("Selamat Datang {$user->name}");
            } else {
                return redirect()->route('users.index')->withSuccess("Selamat Datang {$user->name}");
            }
        }

        return redirect('login')->with('error', 'Username dan password tidak sesuai!');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }
}