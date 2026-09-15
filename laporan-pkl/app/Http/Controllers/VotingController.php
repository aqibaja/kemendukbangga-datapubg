<?php

namespace App\Http\Controllers;

use App\Jobs\SendVotingToGoogleSheets;
use App\Models\Employee;
use App\Models\PkbEmployee;
use App\Models\VotingCandidate;
use App\Models\VotingSetting;
use App\Models\VotingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VotingController extends Controller
{
    public function showVoting(Request $request)
    {
        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        return view('voting.voting', compact('setting'));
    }

    public function dashboard(Request $request)
    {
        $setting = VotingSetting::firstOrCreate(['id' => 1]);
        
        $isResultVisible = $setting->is_result_visible;
        if ($setting->result_visible_at && now()->timezone('Asia/Jakarta') >= \Carbon\Carbon::parse($setting->result_visible_at, 'Asia/Jakarta')) {
            $isResultVisible = true;
        }

        // Stats
        $totalPerwakilan = Employee::count();
        $totalPkb = PkbEmployee::count();

        $votedPerwakilan = VotingVote::where('voter_type', 'perwakilan')->count();
        $votedPkb = VotingVote::where('voter_type', 'pkb')->count();

        // Hasil Voting
        $candidates = VotingCandidate::withCount(['votes1', 'votes2', 'votes3'])->get();
        
        $golongan1 = $candidates->where('golongan', 1)->values();
        $golongan2 = $candidates->where('golongan', 2)->values();
        $golongan3 = $candidates->where('golongan', 3)->values();

        return view('voting.voting-dashboard', compact(
            'setting', 'isResultVisible', 'totalPerwakilan', 'totalPkb', 'votedPerwakilan', 'votedPkb',
            'golongan1', 'golongan2', 'golongan3'
        ));
    }

    public function voterStatus()
    {
        // Ambil list ID yang sudah voting (Perwakilan & PKB)
        $votedPerwakilanIds = VotingVote::where('voter_type', 'perwakilan')->pluck('voter_employee_id')->toArray();
        $votedPkbIds = VotingVote::where('voter_type', 'pkb')->pluck('voter_pkb_id')->toArray();

        // Ambil semua Employee (Perwakilan) dan set status
        $perwakilan = Employee::orderBy('nama')->get()->map(function($emp) use ($votedPerwakilanIds) {
            $emp->has_voted = in_array($emp->id, $votedPerwakilanIds);
            return $emp;
        });

        // Ambil semua PkbEmployee (PKB) dan set status
        $pkb = PkbEmployee::orderBy('nama')->get()->map(function($emp) use ($votedPkbIds) {
            $emp->has_voted = in_array($emp->id, $votedPkbIds);
            return $emp;
        });

        return view('voting.voting-voters-status', compact('perwakilan', 'pkb'));
    }

    public function checkPopup()
    {
        // Cache selama 10 detik untuk mengurangi query DB saat banyak pengunjung polling
        $isActive = Cache::remember('voting_popup_active', 10, function () {
            $setting = VotingSetting::firstOrCreate(['id' => 1]);
            
            $active = $setting->is_popup_active;
            if ($setting->popup_inactive_at && now()->timezone('Asia/Jakarta') >= \Carbon\Carbon::parse($setting->popup_inactive_at, 'Asia/Jakarta')) {
                $active = false;
            }
            return $active;
        });
        return response()->json(['is_popup_active' => $isActive]);
    }

    public function getVoterList(Request $request)
    {
        $type = $request->query('type'); // 'perwakilan' or 'pkb'
        if ($type === 'perwakilan') {
            // Jangan kirim NIP ke frontend untuk keamanan
            $voters = Employee::orderBy('nama')->get(['id', 'nama', 'unsur']);
        } else {
            $voters = PkbEmployee::orderBy('nama')->get(['id', 'nama', 'unsur']);
        }
        return response()->json($voters);
    }

    public function getCandidates()
    {
        $candidates = VotingCandidate::orderBy('urutan')->get();
        
        return response()->json([
            'golongan_1' => $candidates->where('golongan', 1)->values(),
            'golongan_2' => $candidates->where('golongan', 2)->values(),
            'golongan_3' => $candidates->where('golongan', 3)->values(),
        ]);
    }

    /**
     * Verifikasi NIP voter. NIP digunakan sebagai password.
     */
    public function verifyNip(Request $request)
    {
        $request->validate([
            'type' => 'required|in:perwakilan,pkb',
            'id'   => 'required|integer',
            'nip'  => 'required|string',
        ]);

        $nip = trim($request->nip);

        if ($request->type === 'perwakilan') {
            $employee = Employee::find($request->id);
        } else {
            $employee = PkbEmployee::find($request->id);
        }

        if (!$employee) {
            return response()->json(['valid' => false, 'message' => 'Data pegawai tidak ditemukan.'], 404);
        }

        if (empty($employee->nip)) {
            return response()->json(['valid' => false, 'message' => 'NIP pegawai belum terdaftar di sistem. Hubungi admin.'], 422);
        }

        if ($employee->nip !== $nip) {
            return response()->json(['valid' => false, 'message' => 'NIP tidak cocok. Silakan coba lagi.'], 401);
        }

        return response()->json(['valid' => true, 'message' => 'NIP terverifikasi.']);
    }

    public function checkVoted(Request $request)
    {
        $request->validate([
            'type' => 'required|in:perwakilan,pkb',
            'id' => 'required|integer',
        ]);

        $query = VotingVote::where('voter_type', $request->type);
        if ($request->type === 'perwakilan') {
            $query->where('voter_employee_id', $request->id);
        } else {
            $query->where('voter_pkb_id', $request->id);
        }

        $hasVoted = $query->exists();

        // Cek device cookie fallback (DISABLED SEMENTARA)
        // if (!$hasVoted && $request->hasCookie('voting_asn_keren_device')) {
        //     $hasVoted = VotingVote::where('device_cookie_id', $request->cookie('voting_asn_keren_device'))->exists();
        // }

        return response()->json(['voted' => $hasVoted]);
    }

    public function submitVote(Request $request)
    {
        $request->validate([
            'voter_type'           => 'required|in:perwakilan,pkb',
            'voter_id'             => 'required|integer',
            'voter_name'           => 'required|string',
            'nip'                  => 'required|string',
            'candidate_golongan_1' => 'required|exists:voting_candidates,id',
            'candidate_golongan_2' => 'required|exists:voting_candidates,id',
            'candidate_golongan_3' => 'required|exists:voting_candidates,id',
        ]);

        // Verifikasi NIP sekali lagi di server sebelum menyimpan vote (double-check)
        if ($request->voter_type === 'perwakilan') {
            $employee = Employee::find($request->voter_id);
        } else {
            $employee = PkbEmployee::find($request->voter_id);
        }

        if (!$employee || $employee->nip !== trim($request->nip)) {
            return response()->json(['success' => false, 'message' => 'Verifikasi NIP gagal. Voting ditolak.'], 401);
        }

        // Double check if voted
        $query = VotingVote::where('voter_type', $request->voter_type);
        if ($request->voter_type === 'perwakilan') {
            $query->where('voter_employee_id', $request->voter_id);
        } else {
            $query->where('voter_pkb_id', $request->voter_id);
        }

        if ($query->exists()) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan voting.'], 400);
        }

        // (DISABLED SEMENTARA UNTUK TESTING / 1 DEVICE BISA BANYAK VOTE)
        // if ($request->hasCookie('voting_asn_keren_device') && VotingVote::where('device_cookie_id', $request->cookie('voting_asn_keren_device'))->exists()) {
        //     return response()->json(['success' => false, 'message' => 'Perangkat ini sudah digunakan untuk voting.'], 400);
        // }

        // Kirim data ke Google Sheets via Queue Job (benar-benar async/background)
        // Entry Process LANGSUNG BEBAS setelah dispatch, tidak menunggu GAS sama sekali
        $apiUrl = env('VOTING_ASN_KEREN_SCRIPT_URL');
        if (!empty($apiUrl)) {
            $candidate1 = VotingCandidate::find($request->candidate_golongan_1);
            $candidate2 = VotingCandidate::find($request->candidate_golongan_2);
            $candidate3 = VotingCandidate::find($request->candidate_golongan_3);

            SendVotingToGoogleSheets::dispatch(
                $apiUrl,
                $request->voter_name,
                $request->voter_type,
                $candidate1 ? $candidate1->nama : '',
                $candidate2 ? $candidate2->nama : '',
                $candidate3 ? $candidate3->nama : '',
                $request->ip(),
                now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s'),
            );
        }

        $deviceCookieId = Str::uuid()->toString();

        VotingVote::create([
            'voter_name'           => $request->voter_name,
            'voter_type'           => $request->voter_type,
            'voter_employee_id'    => $request->voter_type === 'perwakilan' ? $request->voter_id : null,
            'voter_pkb_id'         => $request->voter_type === 'pkb' ? $request->voter_id : null,
            'candidate_golongan_1' => $request->candidate_golongan_1,
            'candidate_golongan_2' => $request->candidate_golongan_2,
            'candidate_golongan_3' => $request->candidate_golongan_3,
            'ip_address'           => $request->ip(),
            'user_agent'           => $request->userAgent(),
            'device_cookie_id'     => $deviceCookieId,
        ]);

        Cookie::queue('voting_asn_keren_device', $deviceCookieId, 60 * 24 * 30); // 30 hari

        return response()->json([
            'success'  => true,
            'message'  => 'Voting berhasil dikirim!',
            'redirect' => route('voting.success')
        ]);
    }
}
