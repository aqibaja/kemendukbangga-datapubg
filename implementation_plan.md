# Rencana Penambahan Karakter 3D Isometric

## Analisis & Konsep
Anda menginginkan penambahan aset visual modern (karakter & ikon) untuk ke-6 section di infografis Anda. Desain yang paling cocok untuk tren web saat ini adalah **3D Isometric Illustration** dengan palet warna *earth tones / pastel* agar terlihat premium dan menyatu dengan *background* gradasi gelap infografis Anda.

## Langkah-langkah Eksekusi

### 1. Generate 6 Aset Gambar (AI Generation)
Saya akan meng-generate 6 gambar menggunakan *prompt* spesifik untuk mendapatkan gaya 3D Isometric yang konsisten dengan latar belakang putih murni (`#FFFFFF`):
- **BKB**: Orang tua menemani balita menyusun balok huruf.
- **BKR**: Orang tua mengobrol santai dengan remaja.
- **BKL**: Sepasang lansia berkebun/olahraga didampingi keluarga.
- **PIK-R**: Dua remaja (laki & perempuan) melakukan *high-five* / *thumbs up*.
- **UPPKA**: Perempuan memegang tablet dengan grafik penjualan dan produk UMKM.
- **PPKS**: Konselor menyambut keluarga di meja informasi modern.

### 2. Penghapusan Latar Belakang (Transparansi)
Karena gambar yang di-generate AI biasanya memiliki *background* solid (JPEG), saya akan menjalankan skrip *image processing* khusus di *server* Anda untuk menghapus latar belakang putihnya sehingga menjadi **PNG Transparan Murni**, agar bisa ditempatkan di atas latar belakang web Anda tanpa kotak putih yang mengganggu.

### 3. Penempatan di UI (`laporan-capaian.blade.php`)
Saya akan merombak sedikit tata letak agar gambar-gambar ini bisa ditempatkan secara estetis:
- Gambar akan diletakkan di dekat judul masing-masing *section* (Pill text kuning).
- Gambar akan diberi ukuran yang proporsional (sekitar 100-150px) agar tidak mendesak data angka, namun tetap menjadi fokus visual yang menarik.
- Posisi gambar dibuat sedikit *overlap* (menimpa) tepi kotak agar terlihat dinamis (gaya *out-of-bounds* yang modern).

## Persetujuan Pengguna (User Review Required)
> [!IMPORTANT]
> **Keputusan Gaya Gambar**: Saya akan menggunakan gaya **3D Isometric Pastel/Earth Tones** berlatar belakang transparan. 
> **Posisi Gambar**: Gambar akan diselipkan melayang di atas/samping judul (*pill*) masing-masing *section* agar memecah kekosongan ruang namun tidak menutupi teks angka capaian.
> 
> **Apakah Anda setuju dengan pendekatan tata letak dan gaya gambar ini?** Jika ya, saya akan langsung memulai proses *generate* gambar dan pengkodean!
