<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $userId = Auth::id(); // ambil ID user login

        // VALIDASI
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $userId,
            'old_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Ambil user sebagai Eloquent
        $user = User::findOrFail($userId);

        // Update nama & username
        $user->nama = $request->nama;
        $user->username = $request->username;

        // Jika ganti password
        if ($request->filled('new_password')) {

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors([
                    'old_password' => 'Password lama salah'
                ]);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}