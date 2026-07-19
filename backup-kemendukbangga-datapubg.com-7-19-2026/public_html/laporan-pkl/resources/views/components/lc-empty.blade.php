<div class="text-center py-16 px-4">
    <i class="fa-solid fa-clipboard-list text-6xl block mb-4" style="color: #d97706; opacity: 0.5;"></i>
    <p class="text-lg font-extrabold mb-1" style="color: #064e3b; font-family: 'Poppins', sans-serif;">{{ $message }}</p>
    <p class="text-sm" style="color: #92400e;">untuk bulan {{ \App\Models\LaporanCapaian::namaBulan($bulan) }} {{ $tahun }}</p>
</div>