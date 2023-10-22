<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();
        $users = $query->get();

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|alpha_num:ascii|unique:users,username|min:5|max:255',
            'password' => 'required|confirmed|min:5|max:255',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.max' => 'Maksimal 255 karakter!',
            'username.unique' => 'Username telah digunakan oleh akun lain!',
            'username.required' => 'Username harus diisi!',
            'username.alpha_num' => 'Username hanya boleh diisi karakter A-Z a-z 0-9!',
            'username.max' => 'Maksimal 255 karakter!',
            'username.min' => 'Minimal 5 karakter!',
            'password.required' => 'Password harus diisi!',
            'password.max' => 'Maksimal 255 karakter!',
            'password.min' => 'Minimal 5 karakter!',
            'password.confirmed' => 'Password dan Password konfirmasi tidak sama!',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->withSuccess('Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        return view('pages.users.edit', compact('user'));
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|alpha_num:ascii|unique:users,username,' . $user->id . '|min:5|max:255',
            'password' => 'nullable|confirmed|min:5|max:255',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.max' => 'Maksimal 255 karakter!',
            'username.required' => 'Username harus diisi!',
            'username.unique' => 'Username telah digunakan oleh akun lain!',
            'username.alpha_num' => 'Username hanya boleh diisi karakter A-Z a-z 0-9!',
            'username.max' => 'Maksimal 255 karakter!',
            'username.min' => 'Minimal 5 karakter!',
            'password.max' => 'Maksimal 255 karakter!',
            'password.min' => 'Minimal 5 karakter!',
            'password.confirmed' => 'Password dan Password konfirmasi tidak sama!',
        ]);

        if ($request->password_confirmation && !$request->password) {
            return back()->withError('Password baru tidak sesuai!');
        }

        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->withSuccess('Pengguna berhasil diubah!');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $user->delete();

        return redirect()->route('users.index')->withSuccess('Pengguna berhasil dihapus!');
    }
}