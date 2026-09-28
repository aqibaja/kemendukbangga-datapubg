Route::get('/perbaiki-storage', function() {
    try {
        $publicStorage = public_path('storage');
        if (file_exists($publicStorage) || is_link($publicStorage)) {
            unlink($publicStorage);
        }
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Symlink storage berhasil diperbaiki!';
    } catch (\Throwable $e) {
        return 'Terjadi Error: ' . $e->getMessage();
    }
});
