<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;
use App\Services\ApelSeninService;
use Illuminate\Http\Request;

class PublicLeaveController extends Controller
{
    protected ApelSeninService $apelSeninService;

    public function __construct(ApelSeninService $apelSeninService)
    {
        $this->apelSeninService = $apelSeninService;
    }

    public function create()
    {
        $members = $this->apelSeninService->getAllTeamMembers();

        return view('public-leave.create', [
            'title' => 'Input Izin & Sakit (Ketua Tim)',
            'members' => $members
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'required|in:Izin,Sakit,Dinas Luar,Cuti',
        ]);

        EmployeeLeave::create([
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Data izin/sakit berhasil ditambahkan.');
    }
}
