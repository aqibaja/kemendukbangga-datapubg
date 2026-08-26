# Brief Redesign Pixel-Directed — Laporan Capaian BKKBN Aceh

## Tujuan

Buat ulang halaman laporan capaian BKKBN Aceh agar **secara visual mengikuti struktur, ritme, tingkat kepadatan, dan kualitas infografik referensi MBG 3B**, tetapi seluruh isi, label, dan data tetap milik Program Pengendalian Lapangan Kemendukbangga/BKKBN Provinsi Aceh.

Dua gambar yang dipakai:
- **Target gaya/layout:** `WhatsApp-Image-2026-08-02-at-16.35.04-2.jpg` (MBG 3B).
- **Data/konten yang harus diwadahi:** `Laporan_Capaian_BKKBN_7_2026-1.jpg`.

Ini bukan tugas membuat dashboard SaaS modern minimalis. Ini adalah tugas membuat **infografik laporan resmi satu halaman**, padat, penuh ilustrasi yang seragam, mudah dibaca sebagai poster, dan tetap responsif sebagai halaman web.

## Diagnosa Perbedaan

| Aspek | Hasil sekarang | Target MBG 3B | Instruksi perbaikan |
|---|---|---|---|
| Komposisi | Enam blok panjang vertikal, setiap blok memiliki struktur sama | Informasi dibagi dalam beberapa panel berbeda dalam satu kanvas | Hindari kartu program full-width berulang; gunakan ringkasan top-level, panel status, dan kelompok kartu |
| Hierarki | Judul cukup jelas, tetapi setelah itu angka kecil dan ruang kosong dominan | Judul sangat besar, KPI utama besar, lalu panel tematik | Buat judul 2–3 tingkat dan angka utama berukuran besar; kurangi area kosong |
| Cerita data | Setiap program berdiri sendiri, sulit dipindai | Ada alur: status utama → distribusi/status → metode → sasaran | Susun: ringkasan capaian → status pelaporan → program → detail indikator → catatan/sumber |
| Visual | Campuran patung/gambar emas dan ikon kuning, tampak tidak konsisten | Ilustrasi kartun/vektor yang konsisten, relevan dengan isi | Ganti gambar emas/clipart campuran dengan set ilustrasi SVG/PNG satu gaya, atau gunakan ikon SVG seragam |
| Panel | Header gradasi dominan tetapi tubuh kartu minim struktur | Header navy, border tegas, judul panel, ikon status | Buat panel putih dengan border navy/abu tipis, header pendek, radius 14–18 px |
| Warna | Warna program sangat banyak dan gradasi luas | Navy sebagai kerangka, hijau/oranye/merah/ungu sebagai kode informasi | Pertahankan warna program hanya sebagai aksen; jadikan navy #063B76 sebagai struktur utama |
| Angka | Nilai `1` dan `1,00%` tampak kecil/tidak meyakinkan | Nilai utama sangat mudah terlihat dan diberi konteks | Pastikan format dan rumus benar sebelum styling; tampilkan pembilang/penyebut |

## Arahan Visual yang Harus Diikuti

### Kanvas dan proporsi
- Rancang desktop sebagai kanvas infografik lebar: `max-width: 1440px`, background luar `#EDF5FF`, permukaan utama putih.
- Untuk mode poster/print, sediakan proporsi kira-kira `1080 x 1350` atau A4 landscape/portrait sesuai kebutuhan final; **jangan memaksakan satu gambar vertikal sangat panjang**.
- Gunakan CSS Grid 12 kolom desktop dengan gap 16–24 px. Tablet 2 kolom dan mobile 1 kolom.
- Jaga density seperti MBG: lebih banyak informasi bermakna per viewport, tetapi dengan spacing dan garis pembatas yang jelas.

### Palet dan tipografi
```css
:root {
  --navy: #063B76;
  --navy-dark: #022A59;
  --teal: #008B76;
  --green: #15803D;
  --orange: #F57C00;
  --red: #D7193F;
  --purple: #5B2BBE;
  --cyan: #078DCB;
  --surface: #FFFFFF;
  --canvas: #F1F7FF;
  --line: #CFDDEC;
  --text: #152238;
  --muted: #627086;
}
```
- Font: `Plus Jakarta Sans` atau `Inter`; gunakan font display tebal hanya untuk judul. Jika ingin nuansa poster MBG, gunakan `Barlow Condensed`/`Roboto Condensed` hanya untuk angka atau heading, bukan seluruh body.
- Angka memakai `font-variant-numeric: tabular-nums`; format `Intl.NumberFormat('id-ID')`.
- Gunakan **satu** pemisah angka konsisten: `1.234`, dan persen `100%` atau `1,00%` sesuai rumus yang telah diverifikasi.

### Ilustrasi dan ikon
- Gunakan Lucide/Heroicons SVG untuk status, tabel, pengguna, dokumen, target, verifikasi, dan kalender.
- Jika memakai ilustrasi program, gunakan satu set ilustrasi family/community yang konsisten (SVG transparan, flat vector/soft cartoon) dengan lisensi jelas.
- Jangan gunakan gambar AI yang memiliki teks pada gambar, tangan/wajah cacat, foto patung emas, atau clipart gaya berbeda.
- Batasi satu ilustrasi utama di hero dan satu ilustrasi kecil per kelompok/panel; ikon data lebih penting daripada dekorasi.

## Struktur Halaman yang Direkomendasikan

### 1. Header / Hero (12 kolom)
**Tampilan mirip prinsip bagian atas MBG 3B:** logo + judul besar + ilustrasi.

- Kiri atas: logo resmi dan teks `Kementerian Kependudukan dan Pembangunan Keluarga/BKKBN — Perwakilan Provinsi Aceh`.
- Tengah: `LAPORAN CAPAIAN PROGRAM` (kecil), `PENGENDALIAN LAPANGAN` (besar), `KEMENDUKBANGGA/BKKBN PROVINSI ACEH` (subjudul), `JULI 2026` (aksen teal).
- Kanan: ilustrasi keluarga/kader/aktivitas lapangan yang konsisten, tanpa teks tertanam.
- Tambahkan chip bawah: `Update data: 31 Juli 2026` dan `Sumber: Perwakilan BKKBN Provinsi Aceh`.

### 2. KPI ringkasan (12 kolom, 4 kartu)
Sebelum detail enam program, tampilkan empat kartu besar dengan pola visual seperti top KPI MBG:

1. `Program dilaporkan` — contoh `6 / 6`
2. `Cakupan laporan` — contoh `100%` (hanya jika valid)
3. `Total anggota hadir` — agregat seluruh program bila definisinya setara
4. `Status perlu tindak lanjut` — jumlah belum lapor/belum verifikasi

Format kartu: icon besar di kiri, label di atas, angka utama 36–48 px, caption rumus/konteks di bawah. Bila belum ada data agregat yang valid, tampilkan `—` dan pesan “Menunggu konsolidasi data”, bukan angka fiktif.

### 3. Panel “Status Pelaporan” (7 kolom) + “Program Aktif” (5 kolom)
Tujuannya mengadopsi panel status MBG yang sangat mudah dipindai.

- **Status Pelaporan:** tiga/four row status dengan ikon dan jumlah: `Sudah lengkap`, `Perlu verifikasi`, `Belum lapor`, `Data anomali`.
- **Program Aktif:** daftar enam program dengan mini-indicator/progress bar dan warna aksen masing-masing.
- Header kedua panel selalu navy; warna hijau-oranye-merah-ungu dipakai pada baris status.

### 4. Ringkasan enam program (12 kolom)
Gunakan grid 3 x 2 pada desktop, bukan enam section vertikal.

Kartu per program memuat:
- Badge ikon + singkatan program (`BKB`, `BKR`, `BKL`, `PIK-R`, `UPPKA`, `PPKS`).
- Nama lengkap program.
- Indikator utama: `Cakupan Laporan` dengan nilai pembilang/penyebut dan progress bar.
- Satu indikator layanan yang relevan, misalnya `Anak hadir KKA`, `Anggota hadir`, atau `Keluarga ikut KB`.
- Status pill dan tombol `Detail`.

Gunakan aksen program: BKB teal, BKR cyan, BKL orange, PIK-R magenta/red, UPPKA indigo, PPKS purple. Background tetap putih, jangan membanjiri kartu dengan gradasi.

### 5. Panel “Indikator Program” (8 kolom) + “Tindak Lanjut” (4 kolom)
- Kiri: tabel ringkas agar data dari gambar saat ini tetap lengkap dan dapat dibandingkan: Program | Lapor/Target | Cakupan | Indikator Kedua | Status.
- Kanan: tiga kartu status bertumpuk: `Perlu verifikasi`, `Belum lapor`, `Pembaruan terakhir`, lengkap dengan ikon dan jumlah.
- Bila data lebih dari enam baris/indikator, gunakan drawer atau accordion, bukan memperpanjang kartu utama.

### 6. Analisis (opsional, hanya jika ada data sebenarnya)
- Bar chart: perbandingan cakupan antar-program.
- Donut: komposisi status pelaporan.
- Hindari line chart jika tidak ada riwayat bulan sebelumnya.
- Selalu tampilkan nilai absolut, label, dan sumber data; chart tidak boleh menggantikan tabel/teks yang penting.

### 7. Footer resmi
Buat seperti footer MBG namun lebih bersih:
- Blok navy/teal berisi sumber data, bulan pembaruan, alamat web, dan akun sosial yang valid.
- Bar layanan pengaduan terpisah di bawah jika nomor dan kanal sudah dikonfirmasi.

## Data dan Validasi Wajib

Sebelum mengubah desain, tanyakan/validasi kepada pemilik data:
1. Apakah `Ada=1` dan `Lapor=1` berarti cakupan `100%`, bukan `1,00%`?
2. Mengapa BKR menampilkan `11,00%`—apakah typo format, target sebenarnya, atau formula berbeda?
3. Definisi metrik tiap program: `Anak Hadir KKA`, `Guna KKA`, `Keluarga Ikut KB`, `Pembinaan Baduta`, `Jumlah Kelompok`, dan `Total Hadir`.
4. Apakah data berasal dari satu API/dataset periode Juli 2026 dan kapan timestamp pembaruannya?

Aturan kode:
```ts
const percentage = target > 0 ? (actual / target) * 100 : null;
const numberID = new Intl.NumberFormat('id-ID');
const percentID = new Intl.NumberFormat('id-ID', {
  style: 'percent',
  minimumFractionDigits: 0,
  maximumFractionDigits: 2,
});
```

Jangan hardcode `1,00%`. Simpan `actual` dan `target`; hitung dari data di frontend atau backend yang dapat diaudit.

## Implementasi Teknis

- Stack yang disarankan: **Next.js + TypeScript + Tailwind CSS + shadcn/ui + Lucide React + Recharts**. Jika proyek telah memakai Laravel/React/Vue, pertahankan stack yang ada dan terapkan konsep komponen yang sama.
- Buat komponen reusable: `ReportHero`, `KpiCard`, `StatusPanel`, `ProgramSummaryCard`, `MetricTable`, `FollowUpCard`, `ReportFooter`, `LoadingSkeleton`, `EmptyState`.
- Data dipisah dari UI dengan tipe `DashboardData` dan `ProgramMetric`; jangan menyimpan angka langsung dalam JSX.
- Tampilkan skeleton saat loading, error state dengan retry saat API gagal, dan empty state bila periode tidak mempunyai laporan.
- Gunakan semantic HTML, heading berurutan, focus state, kontras WCAG AA, dan `aria-label` untuk chart/tombol.
- Cetak: sediakan `@media print` yang menghilangkan tombol/filter dan menjaga panel tidak terpotong (`break-inside: avoid`).

## Tahapan Kerja untuk Gemini

1. Analisis dua gambar dan tuliskan 10 perbedaan paling penting tanpa mengubah data bisnis.
2. Buat wireframe ASCII/teks desktop dan mobile berdasarkan struktur di atas.
3. Buat design tokens dan daftar komponen.
4. Buat halaman dengan mock data yang **secara eksplisit diberi label mock**.
5. Setelah API/data tersedia, mapping data dan implementasi formula yang telah disetujui.
6. Uji resolusi 1440, 1280, 1024, 768, dan 390 px; pastikan grid, angka, dan footer tidak overflow.
7. Berikan screenshot/preview desktop dan mobile untuk persetujuan stakeholder sebelum finalisasi.

## Kriteria Berhasil

- Dalam satu layar desktop, pengguna langsung memahami periode, status umum, dan program yang perlu perhatian.
- Tampilan jelas terasa satu keluarga dengan kualitas referensi MBG 3B: headline tegas, KPI besar, panel terstruktur, ikon/ilustrasi konsisten, border/radius rapi, dan warna status bermakna.
- Konten tetap merupakan laporan BKKBN Aceh, tidak mengambil angka/label/ilustrasi MBG 3B.
- Tidak ada enam section kosong dan panjang seperti desain sekarang.
- Semua persentase dapat ditelusuri ke actual/target yang valid.

## Prompt Final untuk Gemini Pro

```text
Saya melampirkan dua gambar:
1) `Laporan_Capaian_BKKBN_7_2026-1.jpg` = halaman saya saat ini dan sumber konten program.
2) `WhatsApp-Image-2026-08-02-at-16.35.04-2.jpg` = target referensi kualitas visual/komposisi infografik MBG 3B.

Bertindaklah sebagai art director infografik pemerintah Indonesia dan senior frontend engineer. Redesign halaman laporan BKKBN Aceh agar jauh lebih dekat pada kualitas referensi: headline besar, hero yang berisi logo + judul + ilustrasi konsisten, KPI besar, panel status, grid informasi rapat, border rapi, ilustrasi relevan, serta footer resmi. Jangan menyalin branding, angka, atau konten MBG 3B; yang ditiru hanya prinsip desainnya.

Konteks: Ini HARUS terasa seperti infografik laporan resmi satu halaman, bukan dashboard SaaS minimalis dan bukan enam blok kartu vertikal yang berulang. Target pengguna adalah pimpinan dan operator BKKBN Aceh yang perlu memahami kondisi laporan dengan cepat.

Gunakan struktur berikut:
- Header/hero 12 kolom: logo, judul "Laporan Capaian Program Pengendalian Lapangan", BKKBN Aceh, periode Juli 2026, update data, ilustrasi keluarga/kader.
- Empat KPI: program dilaporkan, cakupan laporan, total anggota hadir, status perlu tindak lanjut.
- Panel status pelaporan + daftar program aktif.
- Grid 3x2 untuk BKB, BKR, BKL, PIK-R, UPPKA, PPKS; setiap kartu memuat nama, cakupan actual/target + progress, satu indikator layanan, status, tombol detail.
- Tabel indikator program + kartu tindak lanjut.
- Bar/donut chart hanya bila ada data asli.
- Footer sumber data dan kontak resmi.

Gaya wajib:
- CSS Grid 12 kolom; max-width 1440px; 3 kolom desktop, 2 tablet, 1 mobile.
- Navy #063B76 sebagai warna kerangka; teal #008B76; status hijau/oranye/merah/ungu; permukaan kartu putih; canvas #F1F7FF.
- Font Plus Jakarta Sans/Inter; Lucide SVG icons; radius 16px; border #CFDDEC; shadow sangat lembut.
- Jangan gunakan patung/gambar emas, clipart campuran, emoji, gradien besar pada seluruh kartu, atau gambar AI dengan teks.
- Buat state loading, error, dan empty; aksesibilitas WCAG AA; format angka id-ID.
- Jangan mengasumsikan `Ada=1` dan `Lapor=1` sebagai `1,00%`. Simpan `actual`/`target`, hitung persentase dari formula, dan beri flag jika definisi data belum terkonfirmasi.

Keluaran secara bertahap:
A. Audit visual dalam 10 poin.
B. Wireframe teks desktop dan mobile.
C. Design tokens CSS dan arsitektur komponen.
D. Kode production-ready [GANTI DENGAN STACK PROYEK SAYA], dengan data mock TypeScript yang mudah diganti API.
E. Checklist QA responsif, aksesibilitas, print/PDF, dan validasi data.

Mulai dari A-C dahulu. Jangan langsung menulis halaman panjang atau membuat angka bisnis baru tanpa konfirmasi.
```
