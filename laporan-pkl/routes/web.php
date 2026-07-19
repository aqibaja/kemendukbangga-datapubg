
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
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\QrSessionController;
use App\Http\Controllers\QrAttendanceController;
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
Route::get('/data/apel-senin/team/{team}', [ApelSeninController::class, 'teamDetail'])->name('apel-senin.team');

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
});

// Presensi Publik (Scan QR)
Route::get('/qr-absen', [QrAttendanceController::class, 'scan'])->name('qr_attendance.scan');
Route::post('/qr-absen/submit', [QrAttendanceController::class, 'submit'])->name('qr_attendance.submit');

Route::get('/login', function () {
    return view('login', ['title' => 'Login']);
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======= LAPORAN CAPAIAN =======
Route::get('/laporan-capaian', function (Request $request) {
    $bulan = $request->get('bulan', now()->month);
    $tahun = $request->get('tahun', now()->year);

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

Route::get('/jalankan-migrasi', function() {
    try {
        $messages = [];
        
        $newMigrations = [
            'database/migrations/2026_05_20_000000_create_presentation_links_table.php',
            'database/migrations/2026_05_20_150000_create_laporan_capaian_table.php',
            'database/migrations/2026_07_06_091305_create_employees_table.php',
            'database/migrations/2026_07_06_091306_create_qr_sessions_table.php',
            'database/migrations/2026_07_06_091307_create_qr_attendances_table.php',
            'database/migrations/2026_07_07_020931_add_refresh_time_to_qr_sessions_table.php',
            'database/migrations/2026_07_07_045226_add_end_time_to_qr_sessions_table.php'
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
        
        return '<b>Status Migrasi:</b><br><br>' . implode('<br>', $messages);
    } catch (\Throwable $e) {
        return 'Terjadi Error: ' . $e->getMessage();
    }
});

Route::get('/bersihkan-cache', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'Cache berhasil dibersihkan!';
    } catch (\Throwable $e) {
        return 'Terjadi Error: ' . $e->getMessage();
    }
});
