<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    // 1️⃣ Cek apakah username ada
    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return back()->withErrors([
            'username' => 'Username tidak ditemukan'
        ])->withInput();
    }

    // 2️⃣ Cek password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'Password atau username salah'
        ])->withInput();
    }

    // 3️⃣ Login manual
    Auth::login($user);
    $request->session()->regenerate();

    return redirect('/');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
