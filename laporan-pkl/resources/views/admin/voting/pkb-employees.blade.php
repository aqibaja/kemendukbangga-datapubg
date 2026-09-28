<x-layout>
    <x-slot:title>Manajemen Karyawan PKB</x-slot:title>

    <div class="w-full min-h-screen py-4 sm:py-8 lg:py-12 px-3 sm:px-4">
        <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8 lg:space-y-10">
            
            <div class="flex justify-between items-center">
                <a href="{{ url('/user') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                
                <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Karyawan PKB
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-2">Manajemen Karyawan PKB</h1>
                <p class="text-gray-500 mb-6">Kelola data Penyuluh Keluarga Berencana (PKB) untuk keperluan voting.</p>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <form action="{{ route('admin.pkb.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau unsur..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 font-semibold text-gray-700 border-b w-16 text-center">No</th>
                                <th class="p-3 font-semibold text-gray-700 border-b">Nama Karyawan</th>
                                <th class="p-3 font-semibold text-gray-700 border-b">Unsur / Jabatan</th>
                                <th class="p-3 font-semibold text-gray-700 border-b text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $emp)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="p-3 text-center">{{ $employees->firstItem() + $loop->index }}</td>
                                    <td class="p-3 font-medium text-gray-800">{{ $emp->nama }}</td>
                                    <td class="p-3 text-gray-600">{{ $emp->unsur ?? '-' }}</td>
                                    <td class="p-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button onclick="editPkb({{ $emp->id }}, '{{ addslashes($emp->nama) }}', '{{ addslashes($emp->unsur) }}')" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs">Edit</button>
                                            <form action="{{ route('admin.pkb.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Hapus data PKB ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data karyawan PKB.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Tambah Karyawan PKB</h3>
            <form action="{{ route('admin.pkb.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unsur / Jabatan (Opsional)</label>
                    <input type="text" name="unsur" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Edit Karyawan PKB</h3>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit-nama" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unsur / Jabatan (Opsional)</label>
                    <input type="text" name="unsur" id="edit-unsur" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editPkb(id, nama, unsur) {
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-unsur').value = unsur;
            document.getElementById('form-edit').action = "{{ url('admin/pkb-employees') }}/" + id;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
</x-layout>
