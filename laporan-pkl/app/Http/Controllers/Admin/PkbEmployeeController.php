<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PkbEmployee;
use Illuminate\Http\Request;

class PkbEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = PkbEmployee::query();
        
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('unsur', 'like', "%{$search}%");
        }
        
        $employees = $query->orderBy('nama')->paginate(50);
        
        return view('admin.voting.pkb-employees', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'unsur' => 'nullable|string|max:255',
        ]);

        PkbEmployee::create($request->all());

        return redirect()->back()->with('success', 'Karyawan PKB berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'unsur' => 'nullable|string|max:255',
        ]);

        $employee = PkbEmployee::findOrFail($id);
        $employee->update($request->all());

        return redirect()->back()->with('success', 'Data Karyawan PKB berhasil diupdate.');
    }

    public function destroy($id)
    {
        $employee = PkbEmployee::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Karyawan PKB berhasil dihapus.');
    }
}
