<x-layout>
    <x-slot:title>Daftar Partisipan Voting</x-slot:title>

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Partisipan Voting</h2>
                <p class="text-gray-500">Melihat siapa saja yang sudah melakukan voting dan menghapus datanya jika diperlukan.</p>
            </div>
            <a href="{{ route('admin.voting.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Setting Voting
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-3 px-4 font-bold text-gray-600 w-12">No</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Nama Pemilih</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Tipe</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Pilihan Gol I</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Pilihan Gol II</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Pilihan Gol III</th>
                            <th class="py-3 px-4 font-bold text-gray-600">Waktu Voting</th>
                            <th class="py-3 px-4 font-bold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($votes as $vote)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-gray-800">{{ $vote->voter_name }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-{{ $vote->voter_type == 'perwakilan' ? 'blue' : 'green' }}-100 text-{{ $vote->voter_type == 'perwakilan' ? 'blue' : 'green' }}-700 rounded text-xs font-bold uppercase">
                                        {{ $vote->voter_type }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $vote->candidate1->nama ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $vote->candidate2->nama ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $vote->candidate3->nama ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500">
                                    {{ $vote->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="{{ route('admin.voting.voters.destroy', $vote->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data voting ini? Pemilih akan bisa voting ulang.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus Data">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data partisipan voting.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
