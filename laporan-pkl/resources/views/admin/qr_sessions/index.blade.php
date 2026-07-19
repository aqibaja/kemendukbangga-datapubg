<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Sesi Presensi QR</h2>
            <a href="{{ route('admin.qr_sessions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Buat Sesi Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Sesi</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Radius</th>
                            <th scope="col" class="px-6 py-3">Dibuat Pada</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $session->title }}</td>
                                <td class="px-6 py-4">
                                    @if($session->is_active)
                                        @if($session->end_time && now()->greaterThanOrEqualTo($session->end_time))
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-400">Waktu Habis</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Aktif</span>
                                        @endif
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $session->radius_meters }} m</td>
                                <td class="px-6 py-4">{{ $session->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.qr_sessions.show', $session->id) }}" class="font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded text-xs mr-2 transition">
                                        <i class="fa-solid fa-qrcode mr-1"></i> Tampilkan QR
                                    </a>

                                    <form action="{{ route('admin.qr_sessions.toggle', $session->id) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        <button type="submit" class="font-medium {{ $session->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }} hover:underline">
                                            {{ $session->is_active ? 'Tutup Sesi' : 'Buka Sesi' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.qr_sessions.destroy', $session->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi ini beserta data absensinya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada sesi presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>
