<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('views.pimpinan.usermanagement', compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama'     => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username'),
                ],
                'role'     => 'required',
                'password' => 'required|min:6',
            ],
            [
                'username.unique' => 'Username sudah digunakan, silakan pakai username lain.',
                'password.min'    => 'Password minimal 6 karakter.',
                'nama.required'   => 'Nama wajib diisi.',
            ]
        );

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role'     => 'required|string',
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);

        $user->nama     = $request->nama;
        $user->username = $request->username;
        $user->role     = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
