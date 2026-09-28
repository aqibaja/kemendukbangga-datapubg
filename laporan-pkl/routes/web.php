
<?php
Route::get('/zoomdesk', function () {
    return view('zoomdesk', ['title' => 'Zoomdesk Jadwal Zoom Meeting']);
});

Route::get('/update-k0-sppg', function () {
    return view('update-k0-sppg', ['title' => 'Update K0 SPPG']);
});

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DashboardPage;
use App\Models\DashboardView;
use App\Models\LaporanCapaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardPageController;
use App\Http\Controllers\LaporanCapaianController;
use App\Http\Controllers\AbsensiZoomController;
use App\Http\Controllers\ApelSeninController;
use App\Http\Controllers\PublicLeaveController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\QrSessionController;
use App\Http\Controllers\QrAttendanceController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\VotingAdminController;
use App\Http\Controllers\Admin\PkbEmployeeController;
Route::get('/', function (Request $request) {
    $dashboardPages = DashboardPage::withCount('views')->get();
    
    $nativeDashboard = new DashboardPage([
        'nama_dashboard' => 'Dashboard Presensi Zoom',
        'slug' => 'absensi-zoom',
    ]);
    $nativeDashboard->views_count = \Illuminate\Support\Facades\Cache::get('native_dashboard_zoom_views', 0);
    $nativeDashboard->is_native = true;
    
    $dashboardPages->push($nativeDashboard);
    $dashboardPages = $dashboardPages->sortByDesc('views_count')->take(3);
    $selectedYear = $request->get('year', now()->year); // Default to the current year

    $startYear = DashboardView::orderBy('created_at')
        ->value('created_at')
        ?->year ?? now()->year;

    $currentYear = now()->year;

    $topUploaders = User::withCount('dashboardPages')
        ->orderByDesc('dashboard_pages_count')
        ->take(3)
        ->get();

    $topDashboard = DashboardPage::withCount('views')
        ->orderByDesc('views_count')
        ->take(3)
        ->get();

    $driver = DB::connection()->getDriverName();
    $monthSelect = $driver === 'sqlite' ? "strftime('%m', created_at)" : "MONTH(created_at)";

    $viewsPerMonth = DB::table('dashboard_views')
        ->selectRaw("{$monthSelect} as month, COUNT(*) as total")
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
    $activities = DashboardView::with(['user', 'dashboard'])
        ->latest()
        ->take(10)
        ->get();

    return view('dashboard', [
        'title' => 'Home Page',
        'topUploaders' => $topUploaders,
        'topDashboard' => $topDashboard,
        'viewsPerMonth' => $viewsPerMonth,
        'startYear' => $startYear,
        'currentYear' => $currentYear,
        'selectedYear' => $selectedYear,
        'dashboards' => $dashboardPages,
        'activities' => $activities
    ]);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'Contact']);
});

Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');


Route::get('/datas', function (Request $request) {
    $query = DashboardPage::withCount('views');

    // Filter pencarian
    if ($request->has('search') && $request->search != '') {
        $query->where('nama_dashboard', 'like', '%' . $request->search . '%');
    }

    return view('datas', [
        'title' => 'Datas',
        'datas' => $query->latest()->get()
    ]);
});

Route::get('/user', function () {

    if (!Auth::check()) {
        abort(404);
    }

    $authUser = Auth::user();

    // Ambil users hanya jika admin utama
    $users = $authUser->id_role == 1 ? User::where('id_role', '!=', 1)->latest()->get() : collect();

    // Ambil pages: kalau admin utama semua, kalau bukan admin hanya miliknya
    $pages = $authUser->id_role == 1
        ? DashboardPage::latest()->get()
        : DashboardPage::where('dibuat_oleh', $authUser->id)->latest()->get();

    // Ambil presentation links untuk admin
    $presentationLinks = $authUser->id_role == 1 ? \App\Models\PresentationLink::all() : collect();

    // Ambil laporan capaian
    $laporanCapaian = $authUser->id_role == 1
        ? LaporanCapaian::latest()->get()
        : LaporanCapaian::where('dibuat_oleh', $authUser->id)->latest()->get();

    return view('user', [
        'title' => 'User',
        'pages' => $pages,
        'users' => $users,
        'authUser' => $authUser,
        'presentationLinks' => $presentationLinks,
        'laporanCapaian' => $laporanCapaian,
    ]);
});

Route::post('/user/profile', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('user.profile.update');

Route::post('/user/store', [UserController::class, 'store'])
    ->middleware('auth')
    ->name('user.store');

Route::get('/data/absensi-zoom', [AbsensiZoomController::class, 'index'])->name('absensi-zoom');
Route::get('/data/absensi-zoom/person/{name}', [AbsensiZoomController::class, 'personDetail'])->name('absensi-zoom.person');
Route::get('/data/absensi-zoom/city/{city}', [AbsensiZoomController::class, 'cityDetail'])->name('absensi-zoom.city');

// === APEL SENIN DASHBOARD ===
Route::get('/data/apel-senin', [ApelSeninController::class, 'index'])->name('apel-senin');
Route::get('/data/apel-senin/team/{team}', [ApelSeninController::class, 'teamDetail'])->where('team', '.*')->name('apel-senin.team');

// === FORM IZIN / SAKIT (PUBLIC) ===
Route::get('/izin-sakit', [PublicLeaveController::class, 'create'])->name('public.leaves.create');
Route::post('/izin-sakit', [PublicLeaveController::class, 'store'])->name('public.leaves.store');

Route::get('/data/{dashboardPage:slug}', function (DashboardPage $dashboardPage) {
    // Load relasi creator
    $dashboardPage->load('creator');

    // Catat view
    DashboardView::create([
        'dashboard_id' => $dashboardPage->id,
        'user_id' => Auth::id(), // null kalau guest
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return view('data', [
        'title' => $dashboardPage->nama_dashboard,
        'data'  => $dashboardPage
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/store', [DashboardPageController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/{id}/update', [DashboardPageController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{id}', [DashboardPageController::class, 'destroy'])->name('dashboard.destroy');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/presentation-link/{id}', [UserController::class, 'updatePresentationLink'])->name('presentation-link.update');
    
    // Master Pegawai
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::post('/admin/employees/sync', [EmployeeController::class, 'sync'])->name('admin.employees.sync');
    Route::put('/admin/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    
    // Sesi Presensi QR
    Route::get('/admin/qr-sessions', [QrSessionController::class, 'index'])->name('admin.qr_sessions.index');
    Route::get('/admin/qr-sessions/create', [QrSessionController::class, 'create'])->name('admin.qr_sessions.create');
    Route::post('/admin/qr-sessions', [QrSessionController::class, 'store'])->name('admin.qr_sessions.store');
    Route::get('/admin/qr-sessions/{qr_session}', [QrSessionController::class, 'show'])->name('admin.qr_sessions.show');
    Route::get('/admin/qr-sessions/{qr_session}/generate', [QrSessionController::class, 'generateQr'])->name('admin.qr_sessions.generate');
    Route::post('/admin/qr-sessions/{qr_session}/toggle', [QrSessionController::class, 'toggleActive'])->name('admin.qr_sessions.toggle');
    Route::delete('/admin/qr-sessions/{qr_session}', [QrSessionController::class, 'destroy'])->name('admin.qr_sessions.destroy');

    // Manajemen Izin & Sakit Apel
    Route::get('/admin/leaves', [App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('admin.leaves.index');
    Route::post('/admin/leaves', [App\Http\Controllers\Admin\LeaveController::class, 'store'])->name('admin.leaves.store');
    Route::delete('/admin/leaves/{leave}', [App\Http\Controllers\Admin\LeaveController::class, 'destroy'])->name('admin.leaves.destroy');

    // Voting ASN KEREN Admin
    Route::get('/admin/voting', [VotingAdminController::class, 'index'])->name('admin.voting.index');
    Route::post('/admin/voting/settings', [VotingAdminController::class, 'updateSettings'])->name('admin.voting.settings');
    Route::post('/admin/voting/candidates', [VotingAdminController::class, 'storeCandidates'])->name('admin.voting.candidates.store');
    Route::delete('/admin/voting/candidates/{id}', [VotingAdminController::class, 'deleteCandidate'])->name('admin.voting.candidates.destroy');
    Route::post('/admin/voting/toggle-popup', [VotingAdminController::class, 'togglePopup'])->name('admin.voting.toggle-popup');
    Route::post('/admin/voting/toggle-result', [VotingAdminController::class, 'toggleResult'])->name('admin.voting.toggle-result');
    Route::get('/admin/voting/voters', [VotingAdminController::class, 'votersList'])->name('admin.voting.voters');
    Route::delete('/admin/voting/voters/{id}', [VotingAdminController::class, 'deleteVote'])->name('admin.voting.voters.destroy');
    
    // PKB Employees Admin
    Route::get('/admin/pkb-employees', [PkbEmployeeController::class, 'index'])->name('admin.pkb.index');
    Route::post('/admin/pkb-employees', [PkbEmployeeController::class, 'store'])->name('admin.pkb.store');
    Route::put('/admin/pkb-employees/{id}', [PkbEmployeeController::class, 'update'])->name('admin.pkb.update');
    Route::delete('/admin/pkb-employees/{id}', [PkbEmployeeController::class, 'destroy'])->name('admin.pkb.destroy');
});

// Presensi Publik (Scan QR)
Route::get('/qr-absen', [QrAttendanceController::class, 'scan'])->name('qr_attendance.scan');
Route::post('/qr-absen/submit', [QrAttendanceController::class, 'submit'])->name('qr_attendance.submit');

Route::get('/login', function () {
    return view('login', ['title' => 'Login']);
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======= VOTING ASN KEREN (PUBLIC) =======
Route::get('/voting', [VotingController::class, 'showVoting'])->name('voting.show');
Route::post('/voting/submit', [VotingController::class, 'submitVote'])->name('voting.submit');
Route::get('/voting/dashboard', [VotingController::class, 'dashboard'])->name('voting.dashboard');
Route::get('/voting/dashboard/voters', [VotingController::class, 'voterStatus'])->name('voting.dashboard.voters');
Route::get('/voting/dashboard/voters/export', [VotingController::class, 'exportUnvoted'])->name('voting.dashboard.voters.export');
Route::view('/voting/success', 'voting.voting-success')->name('voting.success');
Route::get('/api/voting/check-popup', [VotingController::class, 'checkPopup']);
Route::get('/api/voting/voters', [VotingController::class, 'getVoterList']);
Route::get('/api/voting/candidates', [VotingController::class, 'getCandidates']);
Route::post('/api/voting/check-voted', [VotingController::class, 'checkVoted']);
Route::post('/api/voting/verify-nip', [VotingController::class, 'verifyNip']);

// ======= LAPORAN CAPAIAN =======
Route::get('/laporan-capaian', function (Request $request) {
    $tipe = $request->get('tipe', 'pengendalian_lapangan');

    // Jika bulan atau tahun tidak diisi di URL, otomatis cari bulan & tahun terbaru yang ADA DATANYA
    if (!$request->filled('bulan') || !$request->filled('tahun')) {
        $latestRecord = LaporanCapaian::where('tipe', $tipe)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();

        // Jika tidak ditemukan untuk tipe ini, cari data terbaru apapun tipenya
        if (!$latestRecord) {
            $latestRecord = LaporanCapaian::orderByDesc('tahun')
                ->orderByDesc('bulan')
                ->first();
        }

        if ($latestRecord) {
            $bulan = $request->get('bulan', $latestRecord->bulan);
            $tahun = $request->get('tahun', $latestRecord->tahun);
        } else {
            $defaultDate = now()->subMonth();
            $bulan = $request->get('bulan', $defaultDate->month);
            $tahun = $request->get('tahun', $defaultDate->year);
        }
    } else {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
    }

    $laporans = LaporanCapaian::where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->get()
        ->keyBy('tipe');

    $availableMonths = LaporanCapaian::selectRaw('DISTINCT bulan, tahun')
        ->orderByDesc('tahun')
        ->orderByDesc('bulan')
        ->get();

    return view('laporan-capaian', [
        'title'           => 'Laporan Capaian',
        'laporans'        => $laporans,
        'bulan'           => (int) $bulan,
        'tahun'           => (int) $tahun,
        'availableMonths' => $availableMonths,
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/laporan-capaian/input', function () {
        if (!Auth::check()) abort(404);

        // Ambil data elsimil terakhir di tahun yang sama untuk pre-fill form
        $latestElsimil = LaporanCapaian::where('tipe', 'elsimil')
            ->where('tahun', now()->year)
            ->orderByDesc('bulan')
            ->first();

        $dummyLaporan = new LaporanCapaian();
        if ($latestElsimil) {
            $dummyLaporan->data = [
                'catin' => $latestElsimil->data['catin'] ?? [],
                'bumil' => $latestElsimil->data['bumil'] ?? [],
            ];
        }

        return view('laporan-capaian-input', [
            'title'    => 'Input Laporan Capaian',
            'laporan'  => $dummyLaporan,
            'editMode' => false,
        ]);
    });

    Route::get('/laporan-capaian/edit/{id}', [LaporanCapaianController::class, 'edit'])->name('laporan-capaian.edit');
    Route::post('/laporan-capaian/store', [LaporanCapaianController::class, 'store'])->name('laporan-capaian.store');
    Route::post('/laporan-capaian/{id}/update', [LaporanCapaianController::class, 'update'])->name('laporan-capaian.update');
    Route::delete('/laporan-capaian/{id}', [LaporanCapaianController::class, 'destroy'])->name('laporan-capaian.destroy');
});

// === ROUTE UTILITAS / SERVER (HANYA ADMIN) ===
Route::middleware(['auth'])->group(function () {

Route::get('/jalankan-migrasi', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    try {
        $messages = [];
        
        $newMigrations = [
            'database/migrations/2026_05_20_000000_create_presentation_links_table.php',
            'database/migrations/2026_05_20_150000_create_laporan_capaian_table.php',
            'database/migrations/2026_07_06_091305_create_employees_table.php',
            'database/migrations/2026_07_06_091306_create_qr_sessions_table.php',
            'database/migrations/2026_07_06_091307_create_qr_attendances_table.php',
            'database/migrations/2026_07_07_020931_add_refresh_time_to_qr_sessions_table.php',
            'database/migrations/2026_07_07_045226_add_end_time_to_qr_sessions_table.php',
            'database/migrations/2026_09_10_000001_create_voting_tables.php',
            'database/migrations/2026_09_12_000001_add_nip_to_employees_and_pkb.php',
        ];
        
        foreach ($newMigrations as $path) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--path' => $path,
                    '--force' => true
                ]);
                $messages[] = "✅ Migrasi " . basename($path) . " sukses.";
            } catch (\Throwable $e) {
                if (str_contains($e->getMessage(), 'already exists')) {
                    $messages[] = "⏭️ Migrasi " . basename($path) . " dilewati (tabel sudah ada).";
                } else {
                    $messages[] = "❌ Error pada " . basename($path) . ": " . $e->getMessage();
                }
            }
        }

        // Auto-fix kolom voting jika tabel terlanjur dibuat tanpa kolom ini
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('voting_candidates') && !\Illuminate\Support\Facades\Schema::hasColumn('voting_candidates', 'urutan')) {
                \Illuminate\Support\Facades\Schema::table('voting_candidates', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->integer('urutan')->default(0)->after('unsur');
                });
                $messages[] = "✅ Kolom 'urutan' berhasil ditambahkan ke 'voting_candidates'.";
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('voting_votes')) {
                \Illuminate\Support\Facades\Schema::table('voting_votes', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('voting_votes', 'voter_name')) {
                        $table->string('voter_name')->nullable()->after('id');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('voting_votes', 'user_agent')) {
                        $table->string('user_agent')->nullable()->after('ip_address');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('voting_votes', 'device_cookie_id')) {
                        $table->string('device_cookie_id')->nullable()->after('user_agent');
                    }
                });
                $messages[] = "✅ Kolom 'voter_name', 'user_agent', 'device_cookie_id' pada 'voting_votes' terverifikasi.";
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('voting_settings')) {
                \Illuminate\Support\Facades\Schema::table('voting_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('voting_settings', 'popup_inactive_at')) {
                        $table->timestamp('popup_inactive_at')->nullable()->after('is_popup_active');
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('voting_settings', 'result_visible_at')) {
                        $table->timestamp('result_visible_at')->nullable()->after('is_result_visible');
                    }
                });
                $messages[] = "✅ Kolom jadwal otomatis ditambahkan ke 'voting_settings'.";
            }
        } catch (\Throwable $e) {
            $messages[] = "⚠️ Skema auto-fix: " . $e->getMessage();
        }
        
        return '<b>Status Migrasi:</b><br><br>' . implode('<br>', $messages);
    } catch (\Throwable $e) {
        return 'Terjadi Error: ' . $e->getMessage();
    }
});

Route::get('/bersihkan-cache', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'Cache berhasil dibersihkan!';
    } catch (\Throwable $e) {
        return 'Terjadi Error: ' . $e->getMessage();
    }
});

Route::get('/optimize-produksi', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    try {
        $results = [];

        \Illuminate\Support\Facades\Artisan::call('config:cache');
        $results[] = '✅ Config cache dibuat';

        \Illuminate\Support\Facades\Artisan::call('route:cache');
        $results[] = '✅ Route cache dibuat';

        \Illuminate\Support\Facades\Artisan::call('view:cache');
        $results[] = '✅ View cache dibuat';

        \Illuminate\Support\Facades\Artisan::call('event:cache');
        $results[] = '✅ Event cache dibuat';

        // Cek tabel jobs (untuk Queue)
        $jobsTableExists = \Illuminate\Support\Facades\Schema::hasTable('jobs');
        if ($jobsTableExists) {
            $results[] = '✅ Tabel <b>jobs</b> sudah ada — Queue siap digunakan!';
        } else {
            $results[] = '❌ Tabel <b>jobs</b> BELUM ADA — kunjungi <a href="/jalankan-migrasi">/jalankan-migrasi</a> dulu!';
        }

        // Cek tabel cache (untuk Cache::remember)
        $cacheTableExists = \Illuminate\Support\Facades\Schema::hasTable('cache');
        $results[] = $cacheTableExists
            ? '✅ Tabel <b>cache</b> sudah ada'
            : '⚠️ Tabel <b>cache</b> belum ada — kunjungi <a href="/jalankan-migrasi">/jalankan-migrasi</a>';

        return '<b>Optimasi Produksi Selesai:</b><br><br>' . implode('<br>', $results) .
               '<br><br><small style="color:gray">Selesai pada: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB</small>';
    } catch (\Throwable $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

Route::get('/buat-tabel-queue', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    $results = [];

    // === TABEL CACHE ===
    if (!\Illuminate\Support\Facades\Schema::hasTable('cache')) {
        \Illuminate\Support\Facades\Schema::create('cache', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
        $results[] = '✅ Tabel <b>cache</b> berhasil dibuat';
    } else {
        $results[] = '⏭️ Tabel <b>cache</b> sudah ada, dilewati';
    }

    if (!\Illuminate\Support\Facades\Schema::hasTable('cache_locks')) {
        \Illuminate\Support\Facades\Schema::create('cache_locks', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
        $results[] = '✅ Tabel <b>cache_locks</b> berhasil dibuat';
    } else {
        $results[] = '⏭️ Tabel <b>cache_locks</b> sudah ada, dilewati';
    }

    // === TABEL JOBS (Queue) ===
    if (!\Illuminate\Support\Facades\Schema::hasTable('jobs')) {
        \Illuminate\Support\Facades\Schema::create('jobs', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
        $results[] = '✅ Tabel <b>jobs</b> berhasil dibuat — Queue siap!';
    } else {
        $results[] = '⏭️ Tabel <b>jobs</b> sudah ada, dilewati';
    }

    if (!\Illuminate\Support\Facades\Schema::hasTable('job_batches')) {
        \Illuminate\Support\Facades\Schema::create('job_batches', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });
        $results[] = '✅ Tabel <b>job_batches</b> berhasil dibuat';
    } else {
        $results[] = '⏭️ Tabel <b>job_batches</b> sudah ada, dilewati';
    }

    if (!\Illuminate\Support\Facades\Schema::hasTable('failed_jobs')) {
        \Illuminate\Support\Facades\Schema::create('failed_jobs', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
        $results[] = '✅ Tabel <b>failed_jobs</b> berhasil dibuat';
    } else {
        $results[] = '⏭️ Tabel <b>failed_jobs</b> sudah ada, dilewati';
    }

    return '<b>Hasil Pembuatan Tabel Queue & Cache:</b><br><br>' . implode('<br>', $results) .
           '<br><br>✨ <b>Selesai!</b> Silakan kunjungi <a href="/optimize-produksi">/optimize-produksi</a> untuk verifikasi.' .
           '<br><small style="color:gray">Selesai pada: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB</small>';
});

Route::get('/cek-error', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'File log tidak ditemukan di: ' . $logFile;
    }
    $lines = file($logFile);
    $lastLines = array_slice($lines, -80);
    return '<pre style="background:#1e1e1e;color:#eee;padding:15px;border-radius:8px;font-size:12px;overflow:auto;line-height:1.5;">' . htmlspecialchars(implode('', $lastLines)) . '</pre>';
});

Route::get('/cek-queue', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    $jobs = \Illuminate\Support\Facades\DB::table('jobs')->get();
    $failed = \Illuminate\Support\Facades\DB::table('failed_jobs')->get();

    $html = '<b>Jobs Menunggu di Antrian: ' . $jobs->count() . '</b><br><br>';
    foreach ($jobs as $job) {
        $payload = json_decode($job->payload, true);
        $jobClass = $payload['displayName'] ?? 'Unknown';
        $html .= "🕐 ID: {$job->id} | Class: <b>{$jobClass}</b> | Attempts: {$job->attempts} | Created: " . date('d/m/Y H:i:s', $job->created_at) . '<br>';
    }

    $html .= '<br><b>Jobs Gagal: ' . $failed->count() . '</b><br><br>';
    foreach ($failed as $job) {
        $html .= "❌ UUID: {$job->uuid} | Gagal: {$job->failed_at}<br>";
    }

    $html .= '<br><a href="/proses-queue">👉 Klik di sini untuk proses antrian sekarang</a>';

    return $html;
});

Route::get('/proses-queue', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    try {
        set_time_limit(120); // maks 2 menit
        \Illuminate\Support\Facades\Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--max-jobs'        => 50,
            '--timeout'         => 60,
        ]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return '<b>Queue Worker selesai:</b><br><pre style="background:#1e1e1e;color:#eee;padding:15px;">' 
            . htmlspecialchars($output ?: 'Semua job telah diproses (atau antrian kosong).') 
            . '</pre>'
            . '<br><a href="/cek-queue">Cek status antrian</a>';
    } catch (\Throwable $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

Route::get('/diagnosa-voting', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    $results = [];

    // Cek 1: Apakah Job file ada di server?
    $jobFile = app_path('Jobs/SendVotingToGoogleSheets.php');
    $results[] = file_exists($jobFile)
        ? '✅ File <b>app/Jobs/SendVotingToGoogleSheets.php</b> ADA di server'
        : '❌ File <b>app/Jobs/SendVotingToGoogleSheets.php</b> TIDAK ADA — belum diupload!';

    $jobFile2 = app_path('Jobs/SendAttendanceToGoogleSheets.php');
    $results[] = file_exists($jobFile2)
        ? '✅ File <b>app/Jobs/SendAttendanceToGoogleSheets.php</b> ADA di server'
        : '❌ File <b>app/Jobs/SendAttendanceToGoogleSheets.php</b> TIDAK ADA — belum diupload!';

    // Cek 2: Apakah VotingController pakai Queue (versi baru)?
    $controllerFile = app_path('Http/Controllers/VotingController.php');
    $controllerContent = file_get_contents($controllerFile);
    $usesQueue = str_contains($controllerContent, 'SendVotingToGoogleSheets::dispatch');
    $results[] = $usesQueue
        ? '✅ <b>VotingController</b> sudah pakai Queue (versi baru)'
        : '❌ <b>VotingController</b> masih versi lama (Http::post langsung) — belum diupload!';

    // Cek 3: Apakah class Job bisa di-load?
    try {
        if (class_exists(\App\Jobs\SendVotingToGoogleSheets::class)) {
            $results[] = '✅ Class <b>SendVotingToGoogleSheets</b> berhasil di-load';
        } else {
            $results[] = '❌ Class <b>SendVotingToGoogleSheets</b> tidak bisa di-load (autoload belum diupdate?)';
        }
    } catch (\Throwable $e) {
        $results[] = '❌ Error load class: ' . $e->getMessage();
    }

    // Cek 4: Apakah VOTING_ASN_KEREN_SCRIPT_URL ada?
    $gasUrl = env('VOTING_ASN_KEREN_SCRIPT_URL');
    $results[] = !empty($gasUrl)
        ? '✅ <b>VOTING_ASN_KEREN_SCRIPT_URL</b> terkonfigurasi di .env'
        : '❌ <b>VOTING_ASN_KEREN_SCRIPT_URL</b> kosong di .env server!';

    // Cek 5: Total voting di DB
    $totalVotes = \Illuminate\Support\Facades\DB::table('voting_votes')->count();
    $results[] = "📊 Total voting di database: <b>{$totalVotes}</b> suara";

    // Cek 6: Jobs table count
    $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
    $results[] = "🕐 Jobs di antrian sekarang: <b>{$pendingJobs}</b>";

    return '<b>🔍 Diagnosa Voting System:</b><br><br>'. implode('<br>', $results) .
           '<br><br><small style="color:gray">Dicek pada: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB</small>';
});

Route::get('/kirim-ulang-votes', function() {
    if (auth()->user()->id_role != 1) abort(403, 'Akses khusus Admin');
    $apiUrl = env('VOTING_ASN_KEREN_SCRIPT_URL');
    if (empty($apiUrl)) {
        return '❌ VOTING_ASN_KEREN_SCRIPT_URL tidak terkonfigurasi di .env';
    }

    $votes = \App\Models\VotingVote::with(['candidate1', 'candidate2', 'candidate3'])->get();

    if ($votes->isEmpty()) {
        return '⚠️ Tidak ada data voting di database.';
    }

    $dispatched = 0;
    foreach ($votes as $vote) {
        \App\Jobs\SendVotingToGoogleSheets::dispatch(
            $apiUrl,
            $vote->voter_name ?? 'Tidak Diketahui',
            $vote->voter_type ?? 'perwakilan',
            $vote->candidate1 ? $vote->candidate1->nama : '',
            $vote->candidate2 ? $vote->candidate2->nama : '',
            $vote->candidate3 ? $vote->candidate3->nama : '',
            $vote->ip_address ?? '',
            $vote->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s'),
        );
        $dispatched++;
    }

    return "✅ <b>{$dispatched} vote</b> berhasil dimasukkan ke antrian!<br><br>"
         . "Sekarang buka <a href='/proses-queue'>/proses-queue</a> untuk mengirimkan ke Google Sheets.<br>"
         . "<small style='color:gray'>⚠️ Catatan: Ini akan mengirim ulang SEMUA vote ke Sheets, termasuk yang sudah ada. Hapus duplikat di Sheets secara manual jika perlu.</small>";
});

Route::get('/simulasi-voting/{jumlah}', function($jumlah) {
    if (auth()->user()->id_role != 1) abort(403);
    
    $jumlah = min(intval($jumlah), 500); // max 500
    $apiUrl = env('VOTING_ASN_KEREN_SCRIPT_URL');
    
    $kandidat1 = \App\Models\VotingCandidate::where('golongan', 1)->first();
    $kandidat2 = \App\Models\VotingCandidate::where('golongan', 2)->first();
    $kandidat3 = \App\Models\VotingCandidate::where('golongan', 3)->first();

    if(!$kandidat1 || !$kandidat2 || !$kandidat3) return 'Kandidat belum lengkap';

    for ($i = 1; $i <= $jumlah; $i++) {
        $nama = "SIMULASI VOTER $i " . \Illuminate\Support\Str::random(4);
        
        \App\Models\VotingVote::create([
            'voter_name'           => $nama,
            'voter_type'           => 'perwakilan',
            'voter_employee_id'    => null,
            'candidate_golongan_1' => $kandidat1->id,
            'candidate_golongan_2' => $kandidat2->id,
            'candidate_golongan_3' => $kandidat3->id,
            'ip_address'           => '127.0.0.1',
            'user_agent'           => 'Simulasi Load Test',
            'device_cookie_id'     => 'simulasi-' . \Illuminate\Support\Str::uuid(),
        ]);

        if (!empty($apiUrl)) {
            \App\Jobs\SendVotingToGoogleSheets::dispatch(
                $apiUrl, $nama, 'perwakilan',
                $kandidat1->nama, $kandidat2->nama, $kandidat3->nama,
                '127.0.0.1', now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s')
            );
        }
    }
    
    return "✅ Berhasil memasukkan <b>$jumlah</b> data simulasi ke database dan antrian (queue).<br><br>"
         . "Sekarang silakan buka <a href='/proses-queue' target='_blank'>/proses-queue</a> untuk memproses antriannya.";
});

Route::get('/hapus-simulasi', function() {
    if (auth()->user()->id_role != 1) abort(403);
    $deleted = \App\Models\VotingVote::where('voter_name', 'like', 'SIMULASI VOTER%')->delete();
    return "🗑️ Berhasil menghapus <b>$deleted</b> data simulasi dari database.";
});

}); // End of Admin Utility Routes
