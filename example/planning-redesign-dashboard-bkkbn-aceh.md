# Planning Redesign Dashboard Laporan Capaian BKKBN Aceh

**Tujuan:** Mengubah halaman dashboard `Laporan Capaian BKKBN 7/2026` yang saat ini tampil seperti dokumen statis menjadi infografik operasional yang padat, berhierarki kuat, mudah dipindai, dan sedekat mungkin dengan kualitas visual referensi MBG 3B.

## 1. Pembacaan Referensi

### Kondisi saat ini (Laporan Capaian)
- Satu kolom vertikal sangat panjang dengan ruang kosong besar; informasi inti baru terbaca setelah scroll panjang.
- Enam program disajikan sebagai kartu berulang dengan struktur hampir sama, tetapi angka `1` dan `1,00%` terlalu dominan sehingga halaman terasa seperti template, bukan dashboard.
- Angka menggunakan pemisah `1,00%`; untuk bahasa Indonesia gunakan `100%` atau `1,00%` hanya jika benar-benar bermakna persentase satu persen. Validasi sumber datanya terlebih dahulu.
- Header program memakai gradasi yang kuat, namun konsistensi visualnya tidak diikuti oleh layout data, ikon, dan CTA.
- Ilustrasi emas di atas kotak hijau tampak tidak seragam dengan gaya dashboard dan kurang membantu pembacaan data.

### Karakter yang harus ditiru dari referensi MBG 3B
- Hero/header yang sangat jelas: judul besar, subjudul, branding, dan ilustrasi tematik dalam satu area.
- Hierarki angka: KPI utama besar di bagian atas, lalu status, distribusi, dan sasaran pada kartu grid.
- Panel berkontur, header biru tua, sudut membulat, ikon yang konsisten, dan warna sebagai kode makna (hijau=baik, oranye=perlu perhatian, merah=masalah, ungu=informasi).
- Komposisi padat tetapi rapi: grid 2–3 kolom desktop, pemisah jelas, whitespace secukupnya, bukan section kosong tinggi.
- Bentuk visual bervariasi sesuai data: kartu KPI, status, tabel mini, progress bar, ranking, dan donut chart; bukan hanya kartu yang sama berulang.

## 2. Prinsip Desain

1. **Dashboard dahulu, laporan kemudian.** Di viewport pertama harus langsung terlihat periode, total program, cakupan laporan, total kelompok/anggota, dan status yang perlu ditindaklanjuti.
2. **Satu bahasa visual.** Hindari campuran foto/ilustrasi 3D/clipart. Gunakan ikon SVG flat atau ilustrasi vektor seragam bertema keluarga.
3. **Data jujur dan terverifikasi.** Jangan mengubah `1` menjadi `100%` tanpa memastikan definisi metrik. Tampilkan nilai pembilang/penyebut dan label yang eksplisit.
4. **Warna menyampaikan status, bukan dekorasi.** Warna program boleh dipakai sebagai aksen; warna status harus konsisten lintas kartu.
5. **Responsif dan aksesibel.** Desktop 3 kolom, tablet 2 kolom, mobile 1 kolom; kontras teks tinggi, angka tidak terpotong, tooltip/label tidak bergantung warna saja.

## 3. Arsitektur Halaman Baru

### A. Top bar
- Logo Kemendukbangga/BKKBN Aceh di kiri.
- Di kanan: chip `Periode: Juli 2026`, tombol `Unduh PDF`, tombol `Filter wilayah`.
- Tinggi ringkas (56–64 px), sticky saat scroll.

### B. Hero dashboard
- Background terang dengan motif Aceh sangat samar (opacity rendah) dan aksen teal/biru tua.
- Judul: `Laporan Capaian Program Pengendalian Lapangan`.
- Subjudul: `Kemendukbangga/BKKBN Perwakilan Provinsi Aceh`.
- Sisi kanan: ilustrasi keluarga/kader bergaya flat **atau** kolase ikon program; jangan gunakan gambar AI yang wajah/teksnya berpotensi cacat.
- Tambahkan badge `Data diperbarui: 31 Juli 2026` dan ringkasan status data.

### C. KPI ringkas (4 kartu)
Contoh struktur; gunakan data yang benar dari API/dataset:
- Program terlapor: `6 / 6`
- Cakupan laporan: `100%`
- Total kelompok aktif: `[nilai]`
- Total anggota hadir: `[nilai]`

Setiap kartu: ikon, label kecil, angka besar, pembanding/periode bila tersedia. Jangan tampilkan metrik yang tidak memiliki makna agregat.

### D. Ringkasan capaian program
Gunakan grid 3 x 2 untuk BKB, BKR, BKL, PIK-R, UPPKA, dan PPKS. Setiap kartu tidak perlu diulang penuh; cukup:
- Ikon program, nama, warna aksen tipis.
- Cakupan laporan (angka besar + progress bar).
- Satu metrik operasional paling relevan (mis. keluarga/kelompok/anggota hadir).
- Status pill: `Lengkap`, `Perlu verifikasi`, atau `Belum masuk`.
- Tombol `Lihat detail` (accordion/drawer/modal).

### E. Panel analitik
Tata dalam grid dua kolom:
- **Capaian per program:** horizontal bar chart agar enam program mudah dibandingkan.
- **Status pelaporan:** donut chart (lengkap, belum diverifikasi, belum lapor) + legenda dan jumlah absolut.
- **Tren bulanan:** line chart bila data historis ada. Jika tidak ada, jangan buat tren palsu; ganti dengan “catatan data bulan ini”.
- **Wilayah prioritas:** tabel ringkas 5 kabupaten/kota yang perlu tindak lanjut, berisi wilayah, program, status, dan aksi.

### F. Detail program
- Accordion per program atau halaman detail terpisah.
- Tampilkan definisi metrik, pembilang/penyebut, tanggal pembaruan, sumber, dan tabel wilayah.
- Sediakan empty state yang informatif bila data belum tersedia.

### G. Footer
- Sumber data, unit penanggung jawab, kontak, timestamp pembaruan, dan versi dashboard.

## 4. Sistem Visual

| Elemen | Ketentuan |
|---|---|
| Primary | `#063B76` navy untuk judul/header; `#007C6C` teal untuk brand/aksi |
| Status sukses | `#15803D` dengan latar `#DCFCE7` |
| Perlu perhatian | `#EA580C` dengan latar `#FFEDD5` |
| Risiko/error | `#C62828` dengan latar `#FEE2E2` |
| Informasi | `#5B3CC4` dengan latar `#EDE9FE` |
| Latar | `#F6F9FC`, permukaan kartu putih |
| Tipografi | Inter atau Plus Jakarta Sans; heading 700–800, body 400–500; gunakan `font-variant-numeric: tabular-nums` untuk angka |
| Radius & shadow | Radius 16 px; border `#DCE6F2`; shadow sangat lembut, jangan shadow tebal |
| Grid | Maks. lebar 1440 px, padding desktop 32 px, gap 20–24 px; gunakan 12-column CSS grid |
| Ikon | Lucide/Heroicons SVG 24–32 px, stroke konsisten; tanpa emoji atau ikon bitmap campuran |

## 5. Spesifikasi Komponen

### Kartu KPI
- Tinggi seragam 140–160 px pada desktop.
- Ikon dalam kotak 44 px; label 14 px; angka 32–40 px; caption 12–13 px.
- Klik kartu membuka detail/definisi metrik jika diperlukan.

### Kartu program
- Header nama + ikon; tidak perlu banner gradasi besar.
- Nilai utama, progress bar, satu insight, dan status pill.
- Warna aksen hanya pada garis atas 4 px atau ikon, bukan seluruh background.

### Chart
- Gunakan Recharts/Chart.js/ECharts, bukan grafik CSS manual.
- Selalu sertakan label data atau tooltip, unit, legenda, dan `aria-label`.
- Jangan memakai pie chart untuk lebih dari 4 kategori.

### Empty/loading/error state
- Skeleton cards saat data dimuat.
- Jika data tidak ada: `Belum ada laporan untuk periode ini`, tampilkan langkah tindak lanjut.
- Jika API gagal: pesan ringkas + tombol coba lagi; jangan menampilkan angka nol sebagai data nyata.

## 6. Model Data Minimum

```ts
type ProgramCode = 'BKB' | 'BKR' | 'BKL' | 'PIK_R' | 'UPPKA' | 'PPKS';
type ReportStatus = 'COMPLETE' | 'PENDING_VERIFICATION' | 'NOT_REPORTED';

interface ProgramMetric {
  program: ProgramCode;
  label: string;
  reported: number;
  target: number;
  coveragePercent: number;
  groupCount?: number;
  attendanceCount?: number;
  status: ReportStatus;
  updatedAt: string;
}

interface DashboardData {
  period: '2026-07';
  province: 'Aceh';
  metrics: ProgramMetric[];
  regionPriorities: Array<{ region: string; program: ProgramCode; status: ReportStatus; note: string }>;
  source: string;
  updatedAt: string;
}
```

Aturan kalkulasi: `coveragePercent = target > 0 ? (reported / target) * 100 : null`. Format Indonesia dengan `Intl.NumberFormat('id-ID')`; persentase maksimum dua desimal bila memang perlu. Tampilkan `—` untuk nilai yang belum tersedia.

## 7. Rencana Implementasi

1. Audit data: konfirmasi arti `Ada`, `Lapor`, `Target`, `Hadir`, dan angka 1/1,00%; tetapkan owner serta timestamp setiap sumber.
2. Buat wireframe desktop dan mobile berdasarkan struktur A–G, lalu minta persetujuan stakeholder sebelum coding visual penuh.
3. Bangun design tokens (warna, spacing, typography, status) dan komponen reusable: `TopBar`, `Hero`, `KpiCard`, `ProgramCard`, `StatusPill`, `ChartCard`, `PriorityTable`.
4. Implementasi layout responsif dan data mock yang mengikuti tipe data di atas.
5. Sambungkan API; tambahkan loading, error, empty state, formatter, dan validasi angka.
6. Tambahkan chart dan drill-down detail setelah KPI/grid disetujui.
7. Uji desktop 1440 px, laptop 1280 px, tablet 768 px, dan mobile 390 px; cek overflow, contrast, keyboard navigation, serta print/PDF.
8. Lakukan UAT dengan pengguna: minta mereka menemukan tiga hal—cakupan, program bermasalah, dan wilayah prioritas—dalam maksimal 10 detik.

## 8. Kriteria Penerimaan

- Viewport pertama menampilkan judul, periode, minimal 4 KPI, dan status ringkasan tanpa scroll berlebihan.
- Enam program terbaca sebagai ringkasan yang berbeda, bukan enam blok template identik.
- Tidak ada angka, persen, target, atau status yang dibuat-buat; definisi metrik tersedia pada detail/tooltip.
- Desktop memakai 3 kolom dan mobile 1 kolom tanpa teks/angka terpotong.
- Warna status konsisten dan tetap dapat dipahami dari teks/ikon.
- Lighthouse accessibility target >= 90 dan semua elemen interaktif dapat diakses keyboard.

## 9. Prompt Siap Pakai untuk Gemini Pro

```text
Anda adalah senior product designer dan senior frontend engineer. Redesign halaman dashboard “Laporan Capaian Program Pengendalian Lapangan Kemendukbangga/BKKBN Provinsi Aceh — Juli 2026”. Saya melampirkan dua gambar: gambar dashboard laporan saat ini dan gambar referensi kualitas/komposisi infografik MBG 3B. Jangan menyalin konten MBG; ambil prinsip hierarki visualnya: hero kuat, KPI besar, kartu status, grid rapi, kode warna status, dan kepadatan informasi yang terkontrol.

Tujuan: dashboard operasional modern yang membantu pimpinan melihat cakupan laporan, status verifikasi, performa enam program (BKB, BKR, BKL, PIK-R, UPPKA, PPKS), serta wilayah yang harus ditindaklanjuti dalam kurang dari 10 detik.

WAJIB:
1. Buat layout desktop 12-column, maksimal lebar 1440 px; responsif 3 kolom desktop, 2 tablet, 1 mobile.
2. Susunan: sticky top bar; hero dengan judul/periode/update; 4 KPI; grid 6 kartu program; panel bar chart dan donut status; tabel wilayah prioritas; accordion/detail program; footer sumber data.
3. Gunakan navy #063B76 dan teal #007C6C; sukses hijau, perhatian oranye, error merah, informasi ungu. Background #F6F9FC, kartu putih dengan border halus dan radius 16 px.
4. Gunakan Inter atau Plus Jakarta Sans, Lucide SVG icons, dan chart library yang aksesibel. Jangan pakai emoji, clipart bitmap campuran, gradien besar, atau gambar AI berteks.
5. Data harus diperlakukan sebagai data dinamis. Jangan mengasumsikan angka 1 berarti 100% tanpa aturan bisnis. Gunakan formatter id-ID, tampilkan pembilang/penyebut, dan “—” jika nilai tidak tersedia.
6. Sertakan loading skeleton, error state, empty state, tooltip definisi metrik, dan aria-label.
7. Jangan membuat halaman panjang berisi kartu berulang. Variasikan visualisasi berdasarkan tujuan data.

Keluaran yang saya minta:
A. Ringkasan audit desain saat ini (maks 10 poin).
B. Struktur informasi/wireframe teks untuk desktop dan mobile.
C. Design tokens dalam CSS variables.
D. Implementasi production-ready menggunakan [ISI STACK SAYA: misalnya Next.js + TypeScript + Tailwind + shadcn/ui + Recharts], dengan komponen reusable dan mock data TypeScript.
E. Jelaskan mapping setiap komponen ke API/data source dan rumus persentase.
F. Checklist QA responsif, aksesibilitas, dan validasi data.

Kerjakan bertahap: tampilkan dahulu A–C dan wireframe. Setelah itu buat kode lengkap. Jangan lanjut mengubah data bisnis tanpa konfirmasi saya.
```

## 10. Informasi yang Perlu Dikonfirmasi

- Apakah nilai `1,00%` memang satu persen, atau tampilan yang seharusnya `100%` karena 1 laporan dari target 1?
- Apa definisi dan sumber resmi tiap metrik per program?
- Apakah pengguna utama pimpinan provinsi, operator kabupaten/kota, atau publik? Ini menentukan kedalaman drill-down dan data yang boleh tampil.
- Stack aplikasi yang digunakan dan sumber API/database saat ini.
