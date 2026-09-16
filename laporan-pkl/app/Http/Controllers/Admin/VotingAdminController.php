<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingSetting;
use App\Models\VotingCandidate;
use App\Models\VotingVote;
use App\Models\Employee;
use App\Models\PkbEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VotingAdminController extends Controller
{
    public function index()
    {
        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        $candidates = VotingCandidate::withCount(['votes1', 'votes2', 'votes3'])
            ->orderBy('golongan')
            ->orderBy('urutan')
            ->get();
        $stats = [
            'total_perwakilan' => Employee::count(),
            'total_pkb' => PkbEmployee::count(),
            'voted_perwakilan' => VotingVote::where('voter_type', 'perwakilan')->count(),
            'voted_pkb' => VotingVote::where('voter_type', 'pkb')->count(),
        ];

        return view('admin.voting.index', compact('setting', 'candidates', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'golongan_1_name' => 'required|string',
            'golongan_2_name' => 'required|string',
            'golongan_3_name' => 'required|string',
        ]);

        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        $setting->update([
            'golongan_1_name' => $request->golongan_1_name,
            'golongan_2_name' => $request->golongan_2_name,
            'golongan_3_name' => $request->golongan_3_name,
            'is_popup_active' => $request->has('is_popup_active'),
            'is_result_visible' => $request->has('is_result_visible'),
            'popup_inactive_at' => $request->popup_inactive_at ?: null,
            'result_visible_at' => $request->result_visible_at ?: null,
        ]);

        return redirect()->back()->with('success', 'Setting Voting berhasil diupdate.');
    }

    public function togglePopup(Request $request)
    {
        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        $setting->update(['is_popup_active' => !$setting->is_popup_active]);
        return response()->json(['success' => true, 'is_popup_active' => $setting->is_popup_active]);
    }

    public function toggleResult(Request $request)
    {
        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        $setting->update(['is_result_visible' => !$setting->is_result_visible]);
        return response()->json(['success' => true, 'is_result_visible' => $setting->is_result_visible]);
    }

    public function storeCandidates(Request $request)
    {
        $request->validate([
            'candidates' => 'required|array',
            'candidates.*.nama' => 'required|string',
            'candidates.*.golongan' => 'required|in:1,2,3',
            'candidates.*.foto' => 'nullable|image|max:2048',
        ]);

        foreach ($request->candidates as $id => $data) {
            if (strpos($id, 'new_') === 0) {
                $candidate = new VotingCandidate();
            } else {
                $candidate = VotingCandidate::find($id) ?? new VotingCandidate();
            }
            $candidate->nama = $data['nama'];
            $candidate->golongan = $data['golongan'];
            $candidate->unsur = $data['unsur'] ?? null;
            $candidate->urutan = $data['urutan'] ?? 0;

            if (isset($data['foto']) && $request->hasFile("candidates.{$id}.foto")) {
                if ($candidate->foto) {
                    Storage::disk('public')->delete($candidate->foto);
                }
                $path = $request->file("candidates.{$id}.foto")->store('voting_candidates', 'public');
                $candidate->foto = $path;
            }

            $candidate->save();
        }

        return redirect()->back()->with('success', 'Kandidat berhasil diupdate.');
    }

    public function deleteCandidate($id)
    {
        $candidate = VotingCandidate::findOrFail($id);
        if ($candidate->foto) {
            Storage::disk('public')->delete($candidate->foto);
        }
        $candidate->delete();

        return redirect()->back()->with('success', 'Kandidat berhasil dihapus.');
    }

    public function votersList()
    {
        $votes = VotingVote::with(['candidate1', 'candidate2', 'candidate3'])->orderBy('created_at', 'desc')->get();
        return view('admin.voting.voters', compact('votes'));
    }

    public function deleteVote($id)
    {
        VotingVote::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data voting berhasil dihapus (Pemilih bisa vote ulang).');
    }
}
