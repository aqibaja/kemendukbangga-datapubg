<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full px-3 sm:px-4 lg:px-6 py-4 sm:py-6" x-data="{ showModal: false, editMode: false, currentId: null, formAction: '{{ route('admin.employees.store') }}' }">
        
        <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
            <h2 class="text-2xl font-bold text-gray-800">Master Data Pegawai</h2>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.employees.sync') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyinkronkan data pegawai database dengan daftar Tim Kerja Apel Senin?');">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-rotate"></i> Sync Data Tim Kerja
                    </button>
                </form>
                <button @click="showModal = true; editMode = false; currentId = null; formAction = '{{ route('admin.employees.store') }}'; document.getElementById('employeeForm').reset();" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Pegawai
                </button>
            </div>
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
                            <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-3">Tim Kerja</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $emp->nama }}</td>
                                <td class="px-6 py-4">{{ $emp->unsur ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="showModal = true; editMode = true; currentId = {{ $emp->id }}; formAction = '{{ url('/admin/employees') }}/' + currentId; document.getElementById('nama').value = '{{ addslashes($emp->nama) }}'; document.getElementById('unsur').value = '{{ addslashes($emp->unsur) }}'; document.getElementById('method_put').disabled = false;" class="font-medium text-blue-600 hover:underline mr-3">Edit</button>
                                    
                                    <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="formAction" method="POST" id="employeeForm">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" id="method_put" disabled>
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="editMode ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru'"></h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama" id="nama" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="unsur" class="block text-sm font-medium text-gray-700">Tim Kerja</label>
                                            <input type="text" name="unsur" id="unsur" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: PELAPORAN STATISTIK DAN PENGELOLAAN TIK">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layout>
