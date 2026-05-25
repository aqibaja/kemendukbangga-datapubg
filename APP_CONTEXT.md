# APP_CONTEXT: kemendukbangga-datapubg

> **Dokumen ini berisi konteks lengkap aplikasi agar AI agent dapat memahami arsitektur, struktur, dan cara kerja seluruh sistem.**

---

## 1. Ringkasan Aplikasi

**Nama**: kemendukbangga-datapubg  
**Domain**: `https://kemendukbangga-datapubg.com`  
**Organisasi**: BKKBN (Badan Kependudukan dan Keluarga Berencana Nasional)  
**Tujuan**: Website portal data publik untuk menampilkan, mengelola, dan membagikan dashboard visualisasi data (Looker/Tableau) serta menyediakan fitur pendukung operasional internal (absensi apel pagi, jadwal zoom meeting, presensi).

---

## 2. Arsitektur Umum

```
kemendukbangga-datapubg/          ← Root (document root web server)
├── index.php                     ← Entry point Laravel (proxy ke laporan-pkl/)
├── .htaccess                     ← Apache rewrite rules → index.php
├── absensi.html                  ← Standalone: Absensi Apel Pagi (GPS + QR)
├── favicon.ico
├── public/                       ← Aset statis (gambar poster, thumbnails)
│   ├── image/                    ← Poster/banner (bkkbn.png, poster 1-3.jpeg)
│   ├── thumbnails/               ← Default thumbnail gambar
│   └── robots.txt
├── storage/                      ← Storage tambahan (thumbnails)
├── zoomdesk/                     ← Aset Zoomdesk (logo.png)
└── laporan-pkl/                  ← ★ LARAVEL APP (core application)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── composer.json
    ├── package.json
    └── vite.config.js
```

### Hosting Setup
- **Web Server**: Apache (`.htaccess` mod_rewrite)
- **Document Root**: Folder root project (`/`)
- `index.php` di root mem-proxy semua request ke `laporan-pkl/` (Laravel app)
- Database server terpisah: `203.175.9.121:3306`

---

## 3. Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Backend** | Laravel (PHP) | 12.x |
| **PHP** | PHP | ≥ 8.2 |
| **Database** | MySQL | — |
| **Frontend CSS** | TailwindCSS (CDN) | via `cdn.tailwindcss.com` |
| **Frontend JS** | Alpine.js | 3.x (CDN) |
| **Build Tool** | Vite + laravel-vite-plugin | 7.x |
| **Icons** | Font Awesome | 7.0.1 (CDN) |
| **Charts** | Chart.js | (CDN) |
| **Font** | Inter | (rsms.me CDN) |
| **Tailwind Build** | @tailwindcss/vite | 4.x (dev only, belum dipakai di produksi) |

> **Catatan**: Layout utama (`layout.blade.php`) menggunakan TailwindCSS via CDN (`cdn.tailwindcss.com`), bukan Vite build. Vite config ada tapi belum terintegrasi di view.

---

## 4. Database Schema

### Tabel: `roles`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| nama_role | string | `admin_utama` (id=1), `user` (id=2) |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| nama | string | Nama lengkap |
| username | string (unique) | Login username |
| password | string | Bcrypt hashed |
| id_role | foreignId → roles | 1=admin_utama, 2=user |
| remember_token | string (nullable) | |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `dashboard_pages`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| nama_dashboard | string | Judul dashboard |
| slug | string (unique) | Auto-generated: `{id}-{slug(nama)}` |
| platform | enum | `looker`, `tableau` |
| embed_link | text | URL embed iframe |
| thumbnail | string (nullable) | Path file di storage (public disk) |
| dibuat_oleh | foreignId → users | Creator, cascade on delete |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `dashboard_views`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| dashboard_id | foreignId → dashboard_pages | Cascade on delete |
| user_id | foreignId → users (nullable) | Null jika guest, null on delete |
| ip_address | string(45) (nullable) | IP visitor |
| user_agent | text (nullable) | Browser user agent |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `presentation_links`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| name | string | Nama link (display) |
| url | string | URL tujuan |
| key | string | Identifier unik (`apel_senin`, `zoom_presensi`) |

### Tabel Sistem (Laravel Default)
- `password_reset_tokens` — Token reset password
- `sessions` — Session storage
- `cache`, `cache_locks` — Cache database driver
- `jobs`, `job_batches`, `failed_jobs` — Queue system

---

## 5. Eloquent Models & Relationships

### `User` (`app/Models/User.php`)
```
fillable: nama, username, password, id_role
hidden: password, remember_token
casts: password → hashed

→ belongsTo(Role, 'id_role')
→ hasMany(DashboardPage, 'dibuat_oleh')
→ hasMany(DashboardView)
```

### `DashboardPage` (`app/Models/DashboardPage.php`)
```
fillable: nama_dashboard, slug, platform, embed_link, thumbnail, dibuat_oleh

→ belongsTo(User, 'dibuat_oleh') as 'creator'
→ hasMany(DashboardView, 'dashboard_id') as 'views'

Boot: on created → auto-set slug = "{id}-{Str::slug(nama_dashboard)}"
```

### `DashboardView` (`app/Models/DashboardView.php`)
```
fillable: dashboard_id, user_id, ip_address, user_agent

→ belongsTo(DashboardPage, 'dashboard_id') as 'dashboard'
→ belongsTo(User) as 'user'
```

### `Role` (`app/Models/role.php`)
```
table: roles
fillable: nama_role

→ hasMany(User, 'id_role')
```

### `PresentationLink` (`app/Models/PresentationLink.php`)
```
table: presentation_links
fillable: name, url, key
```

---

## 6. Routes (web.php)

### Public Routes (Tanpa Auth)

| Method | URI | Handler | View | Deskripsi |
|--------|-----|---------|------|-----------|
| GET | `/` | Closure | `dashboard` | Homepage: slider poster, top 3 dashboard, chart views per bulan, aktivitas |
| GET | `/about` | Closure | `about` | Halaman tentang |
| GET | `/contact` | Closure | `contact` | Form kontak |
| POST | `/contact` | ContactController@send | — | Redirect ke WhatsApp admin (`62811689537`) |
| GET | `/datas` | Closure | `datas` | Daftar semua dashboard (paginated, searchable) |
| GET | `/data/{slug}` | Closure (route model binding) | `data` | Detail dashboard + iframe embed + catat view |
| GET | `/zoomdesk` | Closure | `zoomdesk` | Jadwal Zoom Meeting dari Google Sheet |
| GET | `/login` | Closure | `login` | Form login |
| POST | `/login` | AuthController@login | — | Proses login (username/password) |
| POST | `/logout` | AuthController@logout | — | Logout |

### Auth-Protected Routes

| Method | URI | Handler | Deskripsi |
|--------|-----|---------|-----------|
| GET | `/user` | Closure → `user` view | Admin panel (profile, user management, dashboard management) |
| POST | `/user/profile` | ProfileController@update | Update profil sendiri (nama, username, password) |
| POST | `/user/store` | UserController@store | Tambah user baru (admin only, auto role=2) |
| POST | `/user/update` | UserController@update | Update user |
| DELETE | `/user/{id}` | UserController@destroy | Hapus user |
| POST | `/dashboard/store` | DashboardPageController@store | Tambah dashboard page |
| POST | `/dashboard/{id}/update` | DashboardPageController@update | Update dashboard page |
| DELETE | `/dashboard/{id}` | DashboardPageController@destroy | Hapus dashboard page |
| POST | `/presentation-link/{id}` | UserController@updatePresentationLink | Update link presentasi navbar |

---

## 7. Controllers

### `AuthController`
- **login()**: Validasi username → cek user exists → cek password hash → `Auth::login()` → redirect `/`
- **logout()**: `Auth::logout()` → invalidate session → redirect `/`

### `DashboardPageController`
- **store()**: Validasi → generate unique slug → simpan thumbnail ke `public` disk → create dashboard
- **update()**: Cari by ID → update slug jika nama berubah → replace thumbnail jika upload baru → save
- **destroy()**: Hapus thumbnail file → delete record → return JSON

### `UserController`
- **store()**: Validasi (termasuk `password_confirmation`) → create user dgn `id_role=2`
- **update()**: Update nama, username → update password jika diisi
- **destroy()**: Delete user → return JSON
- **updatePresentationLink()**: Update URL presentation link → return JSON

### `ProfileController`
- **update()**: Update profil user yang sedang login → validasi old_password jika ganti password

### `ContactController`
- **send()**: Validasi form → redirect ke `wa.me/{noAdmin}` dengan pesan ter-format

---

## 8. Views (Blade Templates)

### Layout System
```
components/layout.blade.php       ← Master layout (DOCTYPE, head, body, CDN links)
components/navbar.blade.php       ← Navigation bar (responsive, Alpine.js toggle)
components/nav-link.blade.php     ← Reusable nav link component
```

### Halaman
| View File | Route | Deskripsi |
|-----------|-------|-----------|
| `dashboard.blade.php` | `/` | Homepage: slider poster, top 3 dashboard, chart views per bulan |
| `about.blade.php` | `/about` | Halaman tentang organisasi |
| `contact.blade.php` | `/contact` | Form kontak → redirect WhatsApp |
| `datas.blade.php` | `/datas` | Grid card dashboard (paginated, responsive per_page) |
| `data.blade.php` | `/data/{slug}` | Detail dashboard: iframe embed, info creator, views count |
| `login.blade.php` | `/login` | Form login username/password |
| `user.blade.php` | `/user` | Admin panel: profile edit, user CRUD, dashboard page CRUD, presentation links |
| `zoomdesk.blade.php` | `/zoomdesk` | Jadwal zoom meeting (data dari Google Apps Script) |

### Fitur UI Penting di Views
- **Navbar**: Menampilkan link Presensi Apel Senin & Presensi Zoom (dari `presentation_links` table)
- **Dashboard home**: Auto-sliding poster (5 detik), Chart.js line chart untuk views per bulan
- **Datas**: Responsive grid (2/3/4/5 kolom), auto per_page berdasarkan screen width
- **User panel**: Modal popup untuk CRUD (Alpine.js/vanilla JS), inline table search, delete via fetch API

---

## 9. Fitur Standalone: Absensi Apel Pagi (`absensi.html`)

File HTML mandiri (bukan bagian Laravel) yang berjalan di path `/absensi.html`.

### Fungsionalitas
1. **Scan QR Code** — Menggunakan library `html5-qrcode` untuk scan via kamera
2. **GPS Location** — Mengambil koordinat GPS perangkat (high accuracy)
3. **Pilih Nama Pegawai** — Load daftar pegawai dari Google Apps Script, searchable
4. **Submit Absensi** — POST ke Google Apps Script dengan payload JSON

### Integrasi
- **Backend**: Google Apps Script (bukan Laravel)
- **URL**: `https://script.google.com/macros/s/AKfycbwsku71C1HGIupmzJfgSgSlhUGtE_A0XL5opCvGHAAGzzTgG9YqJeD61W6jDh2dCk7n/exec`
- **Device ID**: Generated & persisted di localStorage
- **Validasi**: QR code + GPS location + nama pegawai harus terisi sebelum submit
- **Anti-fraud**: Deteksi lokasi tidak biasa, double attendance, device reuse

### Payload Submit
```json
{
  "employeeName": "string",
  "qrCode": "string",
  "latitude": "number",
  "longitude": "number",
  "deviceId": "string"
}
```

### Response
```json
{
  "success": true,
  "status": "Hadir|Terlambat",
  "time": "HH:MM",
  "message": "string",
  "distance": "number (meters)",
  "locationName": "string",
  "suspicious": "boolean",
  "suspiciousReason": "string"
}
```

---

## 10. Fitur: Zoomdesk (Jadwal Zoom Meeting)

Terintegrasi dalam Laravel tapi data diambil dari **Google Sheets via Apps Script**.

### Data Source
- **URL**: `https://script.google.com/macros/s/AKfycbx_JqXKxsNanPlK_M-IbQk-883hGKpm483PpMBlixWcEwhbhe5XJfxQAiLmJ4mvzsU8/exec`
- **Format**: JSON array dari Google Sheet

### Kolom Data
| Field dari Sheet | Variabel JS | Deskripsi |
|-----------------|-------------|-----------|
| Jadwal Zoom | tanggal, jam | Tanggal + waktu zoom |
| Durasi Zoom (Sampai Jam Berapa) | durasi | Waktu selesai |
| Nama PIC | nama | Person in charge |
| Tim Kerja | tim | Tim kerja |
| Topik/Judul Kegiatan | topik | Topik meeting |
| Peserta Zoom | peserta | Daftar peserta |
| Tipe Zoom | tipe | Tipe meeting |

### Fitur UI
- Filter tanggal (dari-sampai)
- Search (nama PIC / tim kerja)
- Sort per kolom (toggle asc/desc)
- Resizable columns (drag)
- Highlight tanggal yang punya >1 meeting (merah muda)
- Request Link Zoom → redirect ke Google Form
- Loading spinner saat fetch data

---

## 11. File Storage

### Thumbnail Dashboard
- **Upload path**: `laporan-pkl/storage/app/public/thumbnails/`
- **Akses URL**: `{domain}/laporan-pkl/storage/app/public/{thumbnail_path}`
- **Default**: `{domain}/public/thumbnails/default.jpg`
- **Max size**: 5120 KB (5 MB)
- **Validasi**: `image` type

### Poster/Banner
- **Path**: `public/image/`
- **Files**: `poster 1.jpeg`, `poster 2.jpeg`, `poster 3.jpeg`, `bkkbn.png`

---

## 12. Autentikasi & Otorisasi

### Sistem Auth
- **Method**: Session-based (Laravel built-in)
- **Login field**: `username` (bukan email)
- **Password**: Bcrypt (12 rounds)
- **Session driver**: `file`
- **Session lifetime**: 120 menit

### Roles
| id_role | nama_role | Akses |
|---------|-----------|-------|
| 1 | admin_utama | Full access: CRUD user, CRUD semua dashboard, manage presentation links |
| 2 | user | CRUD dashboard milik sendiri, edit profil sendiri |

### Otorisasi Pattern
- Role check dilakukan di route closure/view: `$authUser->id_role == 1`
- **Belum ada middleware role** — otorisasi manual di route `/user`
- User non-admin yang akses `/user` hanya melihat dashboardnya sendiri

---

## 13. External Integrations

| Service | Tujuan | URL |
|---------|--------|-----|
| Google Apps Script (Absensi) | Backend absensi apel pagi | `AKfycbwsku71C1...` |
| Google Apps Script (Zoomdesk) | Data jadwal zoom meeting | `AKfycbx_JqXKx...` |
| Google Forms | Request link zoom | `1FAIpQLSc46r2n...` |
| WhatsApp API | Contact form redirect | `wa.me/62811689537` |
| CDN - TailwindCSS | CSS framework | `cdn.tailwindcss.com` |
| CDN - Alpine.js | JS reactive framework | `cdn.jsdelivr.net` |
| CDN - Chart.js | Charting library | `cdn.jsdelivr.net` |
| CDN - Font Awesome | Icons | `cdnjs.cloudflare.com` |
| CDN - Inter Font | Typography | `rsms.me/inter` |
| CDN - html5-qrcode | QR scanner | `unpkg.com` |

---

## 14. Environment Configuration

### Database
```
DB_CONNECTION=mysql
DB_HOST=203.175.9.121
DB_PORT=3306
DB_DATABASE=kemw8233_data_pubg
DB_USERNAME=kemw8233_aqib
```

### Application
```
APP_ENV=local
APP_DEBUG=true
APP_URL=https://kemendukbangga-datapubg.com
APP_FAKER_LOCALE=id_ID
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=file
```

---

## 15. Seed Data Default

### Roles
- `(1, 'admin_utama')`
- `(2, 'user')`

### Users
- `(14, 'Admin', 'admin', role=1)` — Admin utama
- `(15, 'Rian Indra Pratam', 'rian123', role=2)` — User biasa

---

## 16. Presentation Links (Navbar)

Link dinamis di navbar yang bisa diubah admin dari panel `/user`:

| Key | Default URL | Deskripsi |
|-----|-------------|-----------|
| `apel_senin` | `https://s.id/APELYOK` | Link presensi apel Senin |
| `zoom_presensi` | `https://forms.gle/XkWbaiBoRmqTBAd9A` | Link presensi zoom |

---

## 17. Konvensi & Pola Penting

### Naming Convention
- Field database menggunakan **Bahasa Indonesia**: `nama_dashboard`, `dibuat_oleh`, `nama_role`
- Model class menggunakan **English**: `DashboardPage`, `DashboardView`
- View files menggunakan **English**: `dashboard.blade.php`, `datas.blade.php`
- Route path menggunakan **English**: `/datas`, `/data/{slug}`, `/user`

### Arsitektur Pattern
- **Thin controller, fat route**: Beberapa logika query langsung di route closure (bukan controller)
- **CDN-first**: Semua library frontend via CDN (tidak di-bundle via Vite)
- **Modal/popup**: Menggunakan vanilla JS show/hide CSS class
- **Delete operations**: Via `fetch()` API → JSON response → DOM manipulation
- **No API routes**: Semua route di `web.php`, AJAX calls juga ke web routes

### File Upload
- Storage disk: `public`
- Path: `thumbnails/`
- Hapus file lama saat update/delete
- Nullable (bisa tanpa thumbnail)

---

## 18. Known Issues & Catatan Teknis

1. **Vite tidak terintegrasi di view**: `layout.blade.php` menggunakan CDN TailwindCSS, bukan `@vite()` directive. `vite.config.js` ada tapi tidak digunakan.
2. **Role authorization tanpa middleware**: Pengecekan role dilakukan manual di route/view, bukan via Laravel middleware atau Gate/Policy.
3. **Tidak ada API authentication**: Semua AJAX calls mengandalkan session CSRF token.
4. **Mixed language**: Kode campuran Indonesia-Inggris di naming convention.
5. **`data.blade.php` tidak menggunakan layout component**: View ini punya `<!DOCTYPE html>` sendiri (standalone), berbeda dari view lain yang pakai `<x-layout>`.
6. **Model `role.php` lowercase**: Penamaan class `role` tidak mengikuti PSR-4 convention (seharusnya `Role`).
7. **`content copy.js`**: Ada file duplikat di root yang mungkin sisa development.
8. **Presentation links query di navbar**: Setiap page load melakukan query `PresentationLink::all()` di navbar blade component.

---

## 19. Peta File Lengkap (Source Code Only)

```
ROOT
├── index.php                          → Laravel bootstrap proxy
├── .htaccess                          → Apache rewrite to index.php
├── absensi.html                       → Standalone absensi (GPS+QR)

laporan-pkl/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php         → Login/logout
│   │   ├── ContactController.php      → Contact form → WhatsApp
│   │   ├── DashboardPageController.php → CRUD dashboard pages
│   │   ├── ProfileController.php      → Edit profil user login
│   │   └── UserController.php         → CRUD users + presentation links
│   ├── Models/
│   │   ├── DashboardPage.php          → Dashboard page model
│   │   ├── DashboardView.php          → View tracking model
│   │   ├── PresentationLink.php       → Navbar link model
│   │   ├── role.php                   → Role model (lowercase!)
│   │   └── User.php                   → User model
│   ├── Providers/
│   └── View/Components/
│       ├── layout.php
│       ├── main.php
│       ├── Navbar.php
│       ├── NavLink.php
│       └── sidebar.php
├── database/
│   ├── migrations/
│   │   ├── 0000_..._create_roles_table.php
│   │   ├── 0001_..._create_users_table.php
│   │   ├── 0001_..._create_cache_table.php
│   │   ├── 0001_..._create_jobs_table.php
│   │   ├── 2025_..._create_dashboard_pages_table.php
│   │   ├── 2025_..._create_dashboard_views_table.php
│   │   └── 2025_..._add_slug_to_dashboard_pages_table.php
│   ├── database.sqlite                → SQLite (backup/dev?)
│   └── laporan-pkl-data.sql           → Seed data SQL
├── resources/views/
│   ├── components/
│   │   ├── layout.blade.php           → Master layout
│   │   ├── navbar.blade.php           → Responsive navbar
│   │   └── nav-link.blade.php         → Nav link component
│   ├── dashboard.blade.php            → Homepage
│   ├── about.blade.php                → About page
│   ├── contact.blade.php              → Contact form
│   ├── datas.blade.php                → Dashboard listing
│   ├── data.blade.php                 → Dashboard detail (iframe)
│   ├── login.blade.php                → Login form
│   ├── user.blade.php                 → Admin panel
│   └── zoomdesk.blade.php             → Zoom schedule
├── routes/
│   ├── web.php                        → All routes
│   └── console.php                    → Artisan commands
├── config/                            → Laravel config files
├── composer.json                      → PHP dependencies
├── package.json                       → NPM dependencies
└── vite.config.js                     → Vite build config
```

---

## 20. Cara Menjalankan

### Development
```bash
cd laporan-pkl
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
composer dev   # runs: php artisan serve + queue:listen + pail + npm run dev
```

### Setup Shortcut
```bash
cd laporan-pkl
composer setup  # install, env, key:generate, migrate, npm install, npm run build
```

---

*Dokumen ini di-generate otomatis berdasarkan analisis source code pada 20 Mei 2026.*

---

## 21. Fitur Baru: Laporan Capaian (Mei 2026)

### Overview
Halaman `/laporan-capaian` menampilkan data capaian program BKKBN Aceh dalam bentuk **poster/flayer interaktif** yang bisa di-download sebagai PNG atau PDF.

### 3 Tipe Dashboard
| Tab | Tipe | Route View |
|-----|------|------------|
| Pengendalian Lapangan | `pengendalian_lapangan` | 6 program (BKB, BKR, BKL, PIK-R, UPPKA, PPKS) |
| Capaian Program | `capaian_program` | Fasyankes, Stock Opname, KB Baru, KB Aktif, mCPR/Unmet Need |
| ELSIMIL | `elsimil` | Trend Catin & Bumil Jan-Apr (line chart) |

### Tech Stack (View)
- **html2canvas** — render DOM ke canvas untuk PNG/PDF download
- **jsPDF** — generate PDF A4 dari canvas
- **Chart.js** — line chart untuk ELSIMIL
- **TailwindCSS CDN** + **Font Awesome 7** + **Google Fonts Inter**
- **SVG circular gauges** — persentase di semua metric cards

### Color System (Logogram BKKBN)
- **Header**: `teal-600 → blue-800 → blue-500 → amber-500 → amber-700` (diagonal 135deg)
- **Content bg**: `sky-100 → sky-200 → amber-100 → amber-200` (vertical soft blend)
- **Capaian Program**: dark section `#0f172a → #1e293b` + gold accent
- **Program cards**: unique colors: teal, cyan, indigo, orange, pink, lime
- **Footer**: `emerald-950 → blue-dark`

### File Terkait
| File | Peran |
|------|-------|
| `resources/views/laporan-capaian.blade.php` | View utama (~345 lines) — 3 tab, download buttons, Chart.js |
| `app/Http/Controllers/LaporanCapaianController.php` | Controller — store/update/destroy/edit, 3 assembler methods |
| `app/Models/LaporanCapaian.php` | Model — casts data→array, helper `namaBulan()`, `labelTipe()` |
| `database/migrations/2026_05_20_150000_create_laporan_capaian_table.php` | Migration |
| `database/seeders/LaporanCapaianSeeder.php` | Seeder — data April 2026 asli |
| `tests/Feature/LaporanCapaianTest.php` | 5 test cases |

### Cara Download
- Tombol **🖼️ PNG** & **📄 PDF** di kanan bawah
- Download **per tab** (tidak gabung): `laporan-pengendalian-lapangan-...`, `laporan-capaian-program-...`, `laporan-elsimil-...`
- PDF single page, no split
