#!/bin/bash
rm -f update_cpanel.zip
zip -r update_cpanel.zip \
    app/Http/Controllers/LaporanCapaianController.php \
    app/Models/LaporanCapaian.php \
    routes/web.php \
    database/migrations/2026_05_20_000000_create_presentation_links_table.php \
    database/migrations/2026_05_20_150000_create_laporan_capaian_table.php \
    resources/views/components/lc-empty.blade.php \
    resources/views/components/lc-metric-card.blade.php \
    resources/views/components/lc-stat-card.blade.php \
    resources/views/components/navbar.blade.php \
    resources/views/laporan-capaian-input.blade.php \
    resources/views/laporan-capaian.blade.php \
    resources/views/user.blade.php \
    public/image/
