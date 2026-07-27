<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Services\ApelSeninService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    protected ApelSeninService $apelSeninService;

    public function __construct(ApelSeninService $apelSeninService)
    {
        $this->apelSeninService = $apelSeninService;
    }

    public function index(Request $request)
    {
        $leaves = EmployeeLeave::orderBy('tanggal', 'desc')->get();
        $members = $this->apelSeninService->getAllTeamMembers();

        return view('admin.leaves.index', [
            'title' => 'Izin & Sakit Apel',
            'leaves' => $leaves,
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

        return redirect()->route('admin.leaves.index')->with('success', 'Data izin/sakit berhasil ditambahkan.');
    }

    public function destroy(EmployeeLeave $leave)
    {
        $leave->delete();
        return redirect()->route('admin.leaves.index')->with('success', 'Data izin/sakit berhasil dihapus.');
    }
}
