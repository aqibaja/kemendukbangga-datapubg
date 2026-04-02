<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PresentationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_role' => 2, // otomatis role 2
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function updatePresentationLink(Request $request, $id)
    {
        $link = PresentationLink::findOrFail($id);
        
        $request->validate([
            'url' => 'required|url',
        ]);

        $link->url = $request->url;
        $link->save();

        return response()->json(['success' => true, 'message' => 'Link berhasil diupdate']);
    }
}
