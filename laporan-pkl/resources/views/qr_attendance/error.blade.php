<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Gagal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden p-8 text-center border-t-4 border-red-500">
        
        <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-circle-xmark text-4xl"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Tidak Dapat Mengakses Sesi</h1>
        
        <p class="text-gray-600 mb-8">{{ $message }}</p>
        
        <button onclick="window.close()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-6 rounded-lg transition-colors">
            Tutup Halaman
        </button>

    </div>

</body>
</html>
