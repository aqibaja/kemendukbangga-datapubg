<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('nama')->get();
        return view('admin.employees.index', [
            'title' => 'Master Data Pegawai',
            'employees' => $employees
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'unsur' => 'nullable|string|max:255',
        ]);

        Employee::create($request->all());

        return redirect()->route('admin.employees.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'unsur' => 'nullable|string|max:255',
        ]);

        $employee->update($request->all());

        return redirect()->route('admin.employees.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function sync()
    {
        $membersMap = \App\Services\ApelSeninService::$teamMembers;
        $updated = 0;
        $created = 0;

        foreach ($membersMap as $team => $members) {
            foreach ($members as $memberName) {
                $cleanName = trim($memberName);
                
                $emp = Employee::where('nama', $cleanName)->first();
                if (!$emp) {
                    $emp = Employee::where('nama', rtrim($cleanName, '.'))->first();
                }
                if (!$emp) {
                    $baseName = trim(str_ireplace(['dr. ', 'DR. ', 'dr '], '', explode(',', $cleanName)[0]));
                    $emp = Employee::where('nama', 'LIKE', '%' . $baseName . '%')->first();
                }

                if ($emp) {
                    $emp->nama = $cleanName;
                    $emp->unsur = $team;
                    $emp->save();
                    $updated++;
                } else {
                    Employee::create([
                        'nama' => $cleanName,
                        'unsur' => $team,
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('admin.employees.index')
            ->with('success', "Sinkronisasi database pegawai berhasil! (Diperbarui: {$updated}, Ditambahkan: {$created})");
    }
}
