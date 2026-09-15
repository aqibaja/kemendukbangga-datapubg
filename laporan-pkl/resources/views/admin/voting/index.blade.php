<x-layout>
    <x-slot:title>Manajemen Voting ASN KEREN</x-slot:title>

    <div class="w-full min-h-screen py-4 sm:py-8 lg:py-12 px-3 sm:px-4">
        <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8 lg:space-y-10">
            
            <!-- BACK BUTTON -->
            <a href="{{ url('/user') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard User
            </a>

            <!-- ================= HEADER & STATS ================= -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-2">Manajemen Voting ASN KEREN</h1>
                <p class="text-gray-500 mb-6">Kelola kandidat, nama golongan, dan aktivasi fitur voting.</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <p class="text-sm text-blue-600 font-semibold">Total Perwakilan</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $stats['total_perwakilan'] }}</p>
                        <p class="text-xs text-blue-500 mt-1">Sudah Voting: {{ $stats['voted_perwakilan'] }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                        <p class="text-sm text-green-600 font-semibold">Total PKB</p>
                        <p class="text-2xl font-bold text-green-800">{{ $stats['total_pkb'] }}</p>
                        <p class="text-xs text-green-500 mt-1">Sudah Voting: {{ $stats['voted_pkb'] }}</p>
                    </div>
                </div>
                
                <div class="mt-6 border-t pt-6">
                    <a href="{{ route('admin.voting.voters') }}" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition inline-flex items-center shadow-md">
                        <i class="fas fa-list-ul mr-2"></i> Daftar Partisipan (Hapus Data Voting)
                    </a>
                </div>
            </div>

            <!-- ================= SETTINGS ================= -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4"><i class="fas fa-cog mr-2 text-gray-500"></i> Pengaturan Umum</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('admin.voting.settings') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tampilkan Popup di Halaman Utama</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_popup_active" value="1" class="sr-only peer" {{ $setting->is_popup_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">Aktif</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tampilkan Dashboard Hasil Voting</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_result_visible" value="1" class="sr-only peer" {{ $setting->is_result_visible ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">Aktif</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Jika aktif, hasil voting bisa dilihat oleh publik di <a href="{{ route('voting.dashboard') }}" target="_blank" class="text-blue-500 hover:underline">/voting/dashboard</a>.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Golongan 1</label>
                            <input type="text" name="golongan_1_name" value="{{ $setting->golongan_1_name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Golongan 2</label>
                            <input type="text" name="golongan_2_name" value="{{ $setting->golongan_2_name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Golongan 3</label>
                            <input type="text" name="golongan_3_name" value="{{ $setting->golongan_3_name }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Pengaturan
                    </button>
                </form>
            </div>

            <!-- ================= KANDIDAT ================= -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-users mr-2 text-gray-500"></i> Data Kandidat (9 Orang)</h2>
                </div>
                
                <form action="{{ route('admin.voting.candidates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @foreach(range(1, 3) as $gol)
                        <h3 class="text-lg font-bold text-gray-700 mt-6 mb-3 border-b pb-2">
                            Kandidat - {{ $setting->{"golongan_{$gol}_name"} }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @php
                                $golCandidates = $candidates->where('golongan', $gol)->values();
                            @endphp
                            
                            @foreach(range(0, 2) as $index)
                                @php
                                    $candidate = $golCandidates->get($index);
                                    $id = $candidate ? $candidate->id : 'new_' . $gol . '_' . $index;
                                @endphp
                                
                                <div class="bg-gray-50 p-4 rounded-xl border relative">
                                    @if($candidate)
                                        <div class="absolute top-2 right-2">
                                            <button type="button" onclick="if(confirm('Hapus kandidat ini?')) document.getElementById('delete-form-{{$candidate->id}}').submit();" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                    
                                    <input type="hidden" name="candidates[{{$id}}][golongan]" value="{{ $gol }}">
                                    <input type="hidden" name="candidates[{{$id}}][urutan]" value="{{ $index }}">
                                    
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kandidat</label>
                                        <input type="text" name="candidates[{{$id}}][nama]" value="{{ $candidate ? $candidate->nama : '' }}" class="w-full px-3 py-1.5 text-sm border rounded focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nama..." required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Unsur / Bidang (Opsional)</label>
                                        <input type="text" name="candidates[{{$id}}][unsur]" value="{{ $candidate ? $candidate->unsur : '' }}" class="w-full px-3 py-1.5 text-sm border rounded focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Unsur...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Banner / Foto Kandidat</label>
                                        <input type="file" name="candidates[{{$id}}][foto]" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                    
                                    @if($candidate && $candidate->foto)
                                        <div class="mt-2 text-center">
                                            <img src="{{ asset('laporan-pkl/storage/app/public/' . $candidate->foto) }}" class="h-20 object-contain mx-auto rounded" alt="Foto Kandidat">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg">
                            <i class="fas fa-save mr-2"></i> Simpan Semua Kandidat
                        </button>
                    </div>
                </form>
                
                <!-- Hidden delete forms -->
                @foreach($candidates as $candidate)
                    <form id="delete-form-{{$candidate->id}}" action="{{ route('admin.voting.candidates.destroy', $candidate->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
                
            </div>
        </div>
    </div>
</x-layout>

