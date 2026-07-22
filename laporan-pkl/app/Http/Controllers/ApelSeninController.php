<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApelSeninService;
use Illuminate\Support\Facades\Cache;

class ApelSeninController extends Controller
{
    public function index(Request $request, ApelSeninService $service)
    {
        $cacheKey = 'apel_senin_data_gsheet';

        // Jika user memaksa sinkronisasi: hapus cache, fetch data baru, redirect ke URL bersih
        if ($request->has('_sync')) {
            $service->clearCache();
            $service->getAllData(); // fetch & simpan ke cache (blocking ~5-10s)

            $params = $request->except(['_sync', 't']);
            return redirect()->route('apel-senin', $params);
        }

        // Jika cache kosong → tampilkan halaman loading (loading akan auto-redirect ke ?_sync=1)
        if (!Cache::has($cacheKey)) {
            return view('apel-senin-loading');
        }

        // Increment view counter
        $views = Cache::get('native_dashboard_apel_views', 0);
        Cache::forever('native_dashboard_apel_views', $views + 1);

        // Ambil daftar tanggal apel
        $apelDates     = $service->getApelDates();
        $selectedDate  = $request->get('date');
        $tab           = $request->get('tab', 'tim'); // tim | peringkat

        // Default ke tanggal terbaru jika baru buka halaman (tidak ada parameter 'date')
        if (!$request->has('date') && count($apelDates) > 0) {
            $selectedDate = array_key_first($apelDates);
        }

        // Jika user pilih "Semua Tanggal", value yang dikirim adalah 'all'
        $queryDate = $selectedDate === 'all' ? null : $selectedDate;

        // Data per tim kerja
        $teamsCount      = [];
        $overallRankings = [];

        $teamsStats = [];
        if ($tab === 'tim') {
            $rawCounts = $service->getStatsByTeam($queryDate);
            $totalApels = $queryDate === null ? count($apelDates) : 1;
            
            foreach ($rawCounts as $team => $count) {
                $members = \App\Services\ApelSeninService::getTeamMembers($team);
                $totalMembers = count($members);
                $denominator = $totalMembers * $totalApels;
                
                $percentage = $denominator > 0 ? ($count / $denominator) * 100 : 0;
                if ($percentage > 100) $percentage = 100;
                
                $teamsStats[$team] = [
                    'count' => $count,
                    'total' => $totalMembers,
                    'denominator' => $denominator,
                    'percentage' => $percentage
                ];
            }
            
            uasort($teamsStats, function($a, $b) {
                if ($a['percentage'] == $b['percentage']) {
                    return $b['count'] <=> $a['count'];
                }
                return $b['percentage'] <=> $a['percentage'];
            });
        }

        // Akumulasi total (selalu dihitung untuk stats bar)
        $totalAllTime = $service->getStatsByTeam(null);
        $totalSum     = array_sum($totalAllTime);
        $totalToday   = array_sum($service->getStatsByTeam($queryDate));

        return view('apel-senin', [
            'title'         => 'Dashboard Apel Senin',
            'apelDates'     => $apelDates,
            'selectedDate'  => $selectedDate,
            'tab'           => $tab,
            'teamsStats'    => $teamsStats,
            'overallRank'   => $overallRankings,
            'totalSum'      => $totalSum,
            'totalToday'    => $totalToday,
            'timKerjaInfo'  => ApelSeninService::$timKerja,
        ]);
    }

    public function teamDetail(string $team, Request $request, ApelSeninService $service)
    {
        // Jika user memaksa sinkronisasi, hapus cache terlebih dahulu
        if ($request->has('_sync')) {
            $service->clearCache();
        }

        $decodedTeam  = urldecode($team);
        $selectedDate = $request->get('date');

        $apelDates    = $service->getApelDates();
        
        // Default ke 'all' (akumulasi) jika tidak ada param date, atau sesuai logic yang diinginkan
        $queryDate    = $selectedDate === 'all' ? null : $selectedDate;

        $rankings     = $service->getMemberRankingByTeam($decodedTeam);
        $trend        = $service->getTrendByTeam($decodedTeam);
        $attendees    = $service->getAttendeesByTeam($decodedTeam, $queryDate);
        $teamInfo     = ApelSeninService::$timKerja[$service->normalizeTeamName($decodedTeam)] ?? null;
        $photoUrl     = ApelSeninService::getPhotoUrl($service->normalizeTeamName($decodedTeam));
        $csvMembers   = ApelSeninService::getTeamMembers($service->normalizeTeamName($decodedTeam));

        $tab           = $request->get('tab', 'tim');

        // Statistik ringkas
        $totalApel     = count($apelDates);
        $totalAttended = count($rankings) > 0 ? array_sum(array_column($rankings, 'attended_count')) : 0;
        $totalAllTime  = $service->getStatsByTeam(null);
        $totalSum      = array_sum($totalAllTime);
        $totalToday    = array_sum($service->getStatsByTeam($queryDate));

        return view('apel-senin-team', [
            'title'         => 'Tim: ' . $decodedTeam,
            'team'          => $service->normalizeTeamName($decodedTeam),
            'teamInfo'      => $teamInfo,
            'photoUrl'      => $photoUrl,
            'apelDates'     => $apelDates,
            'selectedDate'  => $selectedDate,
            'tab'           => $tab,
            'rankings'      => $rankings,
            'trend'         => $trend,
            'attendees'     => $attendees,
            'totalApel'     => $totalApel,
            'totalAttended' => $totalAttended,
            'totalSum'      => $totalSum,
            'totalToday'    => $totalToday,
            'timKerjaInfo'  => ApelSeninService::$timKerja,
            'csvMembers'    => $csvMembers,
        ]);

    }
}
