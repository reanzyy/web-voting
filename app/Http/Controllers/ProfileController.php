<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('pages.auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|max:255|min:5',
            'username' => 'required|alpha_num:ascii|unique:users,username,' . $user->id . '|min:5|max:255',
            'password' => 'nullable|confirmed|min:5|max:255',
            'old_password' => 'required|required_with:password|max:255',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.max' => 'Nama maksimal 255 karakter!',
            'name.min' => 'Nama minimal 5 karakter',
            'username.required' => 'Username harus diisi!',
            'username.alpha_num' => 'Username hanya boleh diisi karakter A-Z a-z 0-9!',
            'username.unique' => 'Username telah digunakan oleh akun lain!',
            'username.min' => 'Username minimal 5 karakter',
            'username.max' => 'Username maximal 255 karakter',
            'password.max' => 'Maksimal 255 karakter!',
            'password.min' => 'Minimal 5 karakter!',
            'password.confirmed' => 'Password dan Password konfirmasi tidak sama!',
            'old_password.required' => 'Password harus diisi!',
            'old_password.required_with' => 'Password lama harus diisi!',
            'old_password.max' => 'Password lama maksimal 255 karakter!',
        ]);

        if ($request->old_password) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withError('Password lama tidak sesuai!');
            }
        }

        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return redirect()->back()->withSuccess('Profil berhasil diubah!');
    }
}