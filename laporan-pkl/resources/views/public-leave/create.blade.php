<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full px-4 sm:px-6 lg:px-12 py-10 space-y-8">
        <section class="max-w-2xl mx-auto space-y-6">
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-bold text-gray-800">Form Izin & Sakit Apel</h2>
                    <p class="text-sm text-gray-500 mt-1">Silakan isi data pegawai yang tidak dapat mengikuti apel.</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('public.leaves.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
                        <select id="nama" name="nama" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" x-data x-init="new TomSelect($el, {create: false, sortField: {field: 'text', direction: 'asc'}, placeholder: 'Ketik atau cari nama pegawai...', plugins: ['clear_button', 'dropdown_input']})">
                            <option value="">Ketik atau cari nama pegawai...</option>
                            @foreach($members as $member)
                                <option value="{{ $member }}">{{ $member }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <select id="keterangan" name="keterangan" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled selected>Pilih Keterangan...</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Dinas Luar">Dinas Luar</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-transparent px-5 py-2.5 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-layout>
