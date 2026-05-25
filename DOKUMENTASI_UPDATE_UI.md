# Dokumentasi Pembaruan UI - Laporan Capaian Program

Dokumen ini merangkum semua pembaruan besar yang dilakukan pada tampilan **Laporan Capaian Program Pengendalian Lapangan BKKBN Provinsi Aceh** agar mempermudah proses pemeliharaan (*maintenance*) dan pembaruan sistem (*deployment*) ke hosting/cPanel.

## 1. Perubahan Struktur Tata Letak (Layout)
File yang diperbarui: `laporan-pkl/resources/views/laporan-capaian.blade.php`

- **Desain Side-Badge (Lencana Samping)**: Ikon untuk 6 program (BKB, BKR, BKL, PIK-R, UPPKA, PPKS) kini menggunakan teknik *absolute positioning* pada sisi kiri judul (*pill text*). Ini menghemat ruang vertikal secara drastis (hingga lebih dari 500px).
- **Lebar Kontainer**: Kontainer utama (kertas laporan) diperlebar dari `max-w-6xl` menjadi `max-w-7xl` agar desain lebih merentang ke samping, mengisi kekosongan halaman layar dan mengoptimalkan proporsi saat diekspor menjadi gambar/PDF.
- **Efisiensi Jarak (Spacing)**: 
  - Jarak (*padding*) kiri pada *Pill text* ditingkatkan (`pl-16 sm:pl-20`) untuk memberi ruang bagi ikon lencana agar tidak menabrak teks.
  - Jarak antar *section* disesuaikan (`mt-10`) untuk memberikan jeda visual yang teratur namun tidak membuang batas kertas.
- **Perbaikan Header**: *Background* putih berbentuk lingkaran di logo BKKBN utama bagian header dihilangkan untuk menjaga estetika transparan dan menyatu dengan *background* hijau gelap.

## 2. Pembaruan Aset Visual (Images)
Folder penyimpanan: `laporan-pkl/public/image/`

- **Format Flat Vector Emas**: Keseluruhan 6 ikon program telah didesain ulang menjadi siluet 2D monokrom emas (`#FFD700`).
- **File Aktif** (Wajib diunggah ke cPanel):
  - `bkb_icon_v2.png`
  - `bkr_icon_v2.png`
  - `bkl_icon_v2.png`
  - `pikr_icon_v2.png`
  - `uppka_icon_v2.png`
  - `ppks_icon_v2.png`
- File ikon 3D versi lama (`bkb_icon.png`, dll) **tidak lagi digunakan** dan tidak perlu diikutsertakan saat memindahkan proyek ke hosting.

## 3. Pembersihan File (*Clean-up*)
Semua *file temporary* yang dihasilkan selama proses pengembangan telah dihapus dari direktori proyek agar tidak memberatkan server saat di-*upload* ke cPanel:
1. Skrip pemrosesan latar belakang gambar (`remove_white_bg.php` & `remove_white_bg_v2.php`) - **Dihapus**.
2. Folder *image* salah target (`/kemendukbangga-datapubg/public/image/`) - **Dihapus**.
3. *File backup* desain (`*.checkpoint`, `*.checkpoint_v2`, `*.checkpoint_v3`) di dalam `resources/views/` - **Dihapus**.

## 4. Instruksi Deployment ke cPanel
Saat memperbarui aplikasi ini ke cPanel, Anda hanya perlu memastikan:
- Mengganti/meniban file `laporan-capaian.blade.php` yang ada di server dengan file terbaru dari *local*.
- Mengunggah 6 gambar `_v2.png` ke folder `public/image/` di cPanel.
- Pastikan membersihkan *cache* browser (Hard Refresh) jika desain belum langsung berubah di server produksi.
