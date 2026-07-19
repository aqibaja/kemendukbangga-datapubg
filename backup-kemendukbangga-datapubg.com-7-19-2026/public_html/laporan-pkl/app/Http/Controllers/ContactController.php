<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'nama'   => 'required',
            'email'  => 'required|email',
            'subjek' => 'required',
            'pesan'  => 'required',
        ]);

        $pesan = "Halo Admin,%0A%0A"
            . "Nama: {$request->nama}%0A"
            . "Email: {$request->email}%0A"
            . "Subjek: {$request->subjek}%0A%0A"
            . "Pesan:%0A{$request->pesan}";

        $noAdmin = '62811689537';

        return redirect("https://wa.me/$noAdmin?text=$pesan");
    }
}
