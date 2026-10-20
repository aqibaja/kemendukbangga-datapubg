<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full min-h-screen flex justify-center py-4 sm:py-8 lg:py-12 px-3 sm:px-4">
        <div class="max-w-6xl w-full space-y-6 sm:space-y-8 lg:space-y-10">

            <!-- ================= PROFILE SECTION (ADMIN) ================= -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Informasi Pribadi Saya</h2>
                    <button onclick="openEditProfile()"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                        Edit Profil
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Nama Lengkap</label>
                        <p class="text-base sm:text-lg text-gray-800 mt-1">{{ auth()->user()->nama }}</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Username</label>
                        <p class="text-base sm:text-lg text-gray-800 mt-1">{{ auth()->user()->username }}</p>
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-2">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Password</label>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500">(terenkripsi)</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($authUser->id_role == 1)
                <!-- ================= PRESENTATION LINKS MANAGEMENT ================= -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Link Presentasi Navbar</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Kelola link presentasi di navbar</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($presentationLinks as $link)
                            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                                <div class="flex-1 w-full">
                                    <label class="text-xs sm:text-sm font-semibold text-gray-600">{{ $link->name }}</label>
                                    <input type="text" value="{{ $link->url }}" 
                                        data-link-id="{{ $link->id }}" 
                                        class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        id="link_{{ $link->id }}">
                                </div>
                                <button onclick="updatePresentationLink({{ $link->id }})"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                                    Update
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= MANAJEMEN INOVASI WEBSITE ================= -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6 lg:p-8" id="section-inovasi">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="p-2.5 rounded-xl bg-amber-100 text-amber-600 shadow-sm">
                                    <i class="fa-solid fa-lightbulb text-xl"></i>
                                </span>
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen Inovasi Website</h2>
                                    <p class="text-xs sm:text-sm text-gray-500">Kelola link inovasi yang tampil pada hover dropdown menu 'Inovasi' di navbar</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="openAddInnovation()"
                            class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl shadow-md hover:shadow-lg transition text-sm sm:text-base font-semibold flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Inovasi
                        </button>
                    </div>

                    <!-- Search Bar Inovasi -->
                    <div class="mb-4">
                        <input type="text" id="searchInnovation" onkeyup="searchTable('searchInnovation', 'innovationTableBody')"
                            placeholder="Cari inovasi berdasarkan nama atau link..."
                            class="w-full px-3 sm:px-4 py-2.5 text-sm sm:text-base border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-sm">
                    </div>

                    <div class="overflow-x-auto max-h-[440px] overflow-y-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[700px]">
                            <thead class="bg-slate-50 sticky top-0 border-b border-slate-200 z-10">
                                <tr>
                                    <th class="p-3 font-semibold text-slate-700 w-12 text-center">No</th>
                                    <th class="p-3 font-semibold text-slate-700">Nama Inovasi</th>
                                    <th class="p-3 font-semibold text-slate-700">Link URL Website</th>
                                    <th class="p-3 font-semibold text-slate-700">Deskripsi</th>
                                    <th class="p-3 font-semibold text-slate-700 text-center w-20">Urutan</th>
                                    <th class="p-3 font-semibold text-slate-700 text-center w-28">Status</th>
                                    <th class="p-3 font-semibold text-slate-700 text-center w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="innovationTableBody">
                                @forelse ($innovations as $inno)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition" data-innovation-id="{{ $inno->id }}">
                                        <td class="p-3 text-center text-slate-500 font-medium">{{ $loop->iteration }}</td>
                                        <td class="p-3 font-semibold text-slate-800">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                                                    <i class="{{ $inno->icon ?: 'fa-solid fa-lightbulb' }} text-xs"></i>
                                                </span>
                                                <span class="truncate max-w-[200px]" title="{{ $inno->title }}">{{ $inno->title }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3 text-blue-600">
                                            <a href="{{ $inno->url }}" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1.5 hover:underline break-all max-w-[240px] truncate"
                                               title="{{ $inno->url }}">
                                                <span>{{ $inno->url }}</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 shrink-0"></i>
                                            </a>
                                        </td>
                                        <td class="p-3 text-slate-500 max-w-[200px] truncate" title="{{ $inno->description }}">
                                            {{ $inno->description ?: '-' }}
                                        </td>
                                        <td class="p-3 text-center font-medium text-slate-700">
                                            {{ $inno->order }}
                                        </td>
                                        <td class="p-3 text-center">
                                            <button type="button" onclick="toggleInnovationStatus({{ $inno->id }})"
                                                id="status_badge_{{ $inno->id }}"
                                                class="px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition border {{ $inno->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}"
                                                title="Klik untuk mengubah status aktif/nonaktif">
                                                {{ $inno->is_active ? '● Aktif' : '○ Nonaktif' }}
                                            </button>
                                        </td>
                                        <td class="p-3 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button onclick="editInnovation({{ json_encode($inno) }})"
                                                    class="px-2.5 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-medium transition flex items-center gap-1">
                                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                                                </button>
                                                <button onclick="deleteInnovation({{ $inno->id }})"
                                                    class="px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition flex items-center gap-1">
                                                    <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyInnovationRow">
                                        <td colspan="7" class="p-6 text-center text-slate-400 italic">
                                            Belum ada data inovasi. Klik tombol "+ Tambah Inovasi" untuk menambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= USER TABLE (MANAGEMENT) ================= -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6">

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen User</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Kelola akses pengguna sistem</p>
                        </div>

                        <button onclick="openAddUser()"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                            + Tambah User
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <input type="text" id="searchUser" onkeyup="searchTable('searchUser', 'userTableBody')"
                            placeholder="Cari user..."
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto border rounded-lg">
                        <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[600px]">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">No</th>
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Nama</th>
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Username</th>
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Password</th>
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                @foreach ($users as $user)
                                    <tr class="border-b hover:bg-gray-50" data-user-id="{{ $user->id }}">
                                        <td class="p-2 sm:p-3">{{ $loop->iteration }}</td>
                                        <td class="p-2 sm:p-3 font-medium text-gray-800">{{ $user->nama }}</td>
                                        <td class="p-2 sm:p-3 text-gray-600">{{ $user->username }}</td>
                                        <td class="p-2 sm:p-3 text-gray-400 italic">terenkripsi</td>
                                        <td class="p-2 sm:p-3 text-center">
                                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                                                <button
                                                    onclick="editUser({{ $user->id }}, '{{ $user->nama }}', '{{ $user->username }}')"
                                                    class="px-2 sm:px-3 py-1 bg-blue-500 text-white rounded text-xs whitespace-nowrap">
                                                    Edit
                                                </button>
                                                <button onclick="deleteUser({{ $user->id }})"
                                                    class="px-2 sm:px-3 py-1 bg-red-500 text-white rounded text-xs whitespace-nowrap">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= SERVER UTILITIES ================= -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-server mr-2 text-red-500"></i> Alat Server & Utility</h2>
                    <p class="text-gray-500 mb-6 text-sm">Gunakan alat ini untuk maintenance server. <b>Hati-hati!</b> Beberapa aksi dapat mempengaruhi performa website sementara.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="/diagnosa-voting" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-stethoscope"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Diagnosa Sistem</h4><p class="text-xs text-gray-500">Cek status file & queue</p></div>
                        </a>
                        <a href="/cek-queue" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">
                            <div class="bg-orange-100 text-orange-600 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-list-ol"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Status Antrian (Queue)</h4><p class="text-xs text-gray-500">Lihat data belum terkirim</p></div>
                        </a>
                        <a href="/proses-queue" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">
                            <div class="bg-green-100 text-green-600 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-play"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Jalankan Queue Worker</h4><p class="text-xs text-gray-500">Proses antrian manual</p></div>
                        </a>
                        <a href="/bersihkan-cache" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">
                            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-broom"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Bersihkan Cache</h4><p class="text-xs text-gray-500">Wajib sblm deploy file baru</p></div>
                        </a>
                        <a href="/optimize-produksi" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">
                            <div class="bg-purple-100 text-purple-600 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-bolt"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Optimize Produksi</h4><p class="text-xs text-gray-500">Cache ulang route & config</p></div>
                        </a>
                        <a href="/jalankan-migrasi" target="_blank" onclick="return confirm('Yakin ingin menjalankan Auto-Fix Database?')" class="flex items-center p-4 bg-teal-50 rounded-xl border border-teal-100 hover:bg-teal-100 transition">
                            <div class="bg-teal-200 text-teal-700 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-database"></i></div>
                            <div><h4 class="font-bold text-sm text-teal-800">Jalankan Migrasi</h4><p class="text-xs text-teal-600">Auto-fix struktur tabel DB</p></div>
                        </a>
                        <a href="/kirim-ulang-votes" onclick="return confirm('Kirim ulang semua data voting ke Google Sheets? Ini akan memicu antrian besar.')" target="_blank" class="flex items-center p-4 bg-red-50 rounded-xl border border-red-100 hover:bg-red-100 transition">
                            <div class="bg-red-200 text-red-700 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-sync-alt"></i></div>
                            <div><h4 class="font-bold text-sm text-red-800">Kirim Ulang Vote</h4><p class="text-xs text-red-600">Re-dispatch seluruh data</p></div>
                        </a>
                        <a href="/simulasi-voting/100" onclick="return confirm('Masukkan 100 data voting dummy untuk keperluan load testing?')" target="_blank" class="flex items-center p-4 bg-indigo-50 rounded-xl border border-indigo-100 hover:bg-indigo-100 transition">
                            <div class="bg-indigo-200 text-indigo-700 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-robot"></i></div>
                            <div><h4 class="font-bold text-sm text-indigo-800">Simulasi 100 Vote</h4><p class="text-xs text-indigo-600">Test beban queue server</p></div>
                        </a>
                        <a href="/hapus-simulasi" onclick="return confirm('Hapus semua data simulasi dummy dari database?')" target="_blank" class="flex items-center p-4 bg-gray-100 rounded-xl border border-gray-200 hover:bg-gray-200 transition">
                            <div class="bg-gray-200 text-gray-700 p-3 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-eraser"></i></div>
                            <div><h4 class="font-bold text-sm text-gray-800">Hapus Data Simulasi</h4><p class="text-xs text-gray-600">Bersihkan DB dari dummy</p></div>
                        </a>
                    </div>
                </div>
            @endif

            <!-- ================= DATA HALAMAN TABLE ================= -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Data Halaman Dashboard</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Kelola halaman dashboard dan embed</p>
                    </div>

                    <button onclick="openAddPage()"
                        class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm sm:text-base">
                        + Tambah Halaman
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchPage" onkeyup="searchTable('searchPage', 'pageTableBody')"
                        placeholder="Cari halaman..."
                        class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="overflow-x-auto max-h-[400px] overflow-y-auto border rounded-lg">
                    <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[700px]">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">No</th>
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Nama Dashboard</th>
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Slug</th>
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Embed Link</th>
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Thumbnail</th>
                                @if ($authUser->id_role == 1)
                                    <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b">Dibuat Oleh</th>
                                @endif
                                <th class="p-2 sm:p-3 font-semibold text-gray-700 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="pageTableBody">
                            @foreach ($pages as $page)
                                <tr class="border-b hover:bg-gray-50 transition" data-page-id="{{ $page->id }}">
                                    <td class="p-2 sm:p-3">{{ $loop->iteration }}</td>
                                    <td class="p-2 sm:p-3 font-medium text-gray-800">{{ $page->nama_dashboard }}</td>
                                    <td class="p-2 sm:p-3 text-gray-600">{{ $page->slug }}</td>
                                    <td class="p-2 sm:p-3 text-blue-600">
                                        <a href="{{ $page->embed_link ?? '-' }}" class="hover:underline break-all">
                                            {{ Str::limit($page->embed_link ?? '-', 30) }}
                                        </a>
                                    </td>
                                    <td class="p-1 sm:p-2">
                                        <img src="{{ $page->thumbnail ? asset('laporan-pkl/storage/app/public/' . $page->thumbnail) : asset('public/thumbnails/default.jpg') }}"
                                            alt="Thumbnail"
                                            class="w-16 h-16 sm:w-20 sm:h-20 object-scale-down rounded-lg">
                                    </td>
                                    @if ($authUser->id_role == 1)
                                        <td class="p-2 sm:p-3">
                                            {{ $page->creator->nama ?? '-' }}
                                        </td>
                                    @endif
                                    <td class="p-2 sm:p-3 text-center">
                                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                                            <button class="text-blue-600 hover:underline text-xs sm:text-sm"
                                                data-id="{{ $page->id }}" data-name="{{ $page->nama_dashboard }}"
                                                data-embed="{{ $page->embed_link }}" onclick="openEditPage(this)">
                                                Edit
                                            </button>
                                            <button onclick="deletePage({{ $page->id }})"
                                                class="text-red-600 hover:underline text-xs sm:text-sm">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= LAPORAN CAPAIAN TABLE ================= -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg p-4 sm:p-6">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Data Laporan Capaian</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Kelola data laporan capaian bulanan</p>
                    </div>

                    <a href="/laporan-capaian/input"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base text-center">
                        + Tambah Laporan
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchLaporan" onkeyup="searchTable('searchLaporan', 'laporanTableBody')"
                        placeholder="Cari laporan..."
                        class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="overflow-x-auto max-h-[400px] overflow-y-auto border rounded-lg">
                    <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[700px]">
                        <thead class="bg-gray-100 sticky top-0 text-slate-800">
                            <tr>
                                <th class="p-2 sm:p-3 font-semibold border-b">No</th>
                                <th class="p-2 sm:p-3 font-semibold border-b">Tipe Laporan</th>
                                <th class="p-2 sm:p-3 font-semibold border-b">Judul Laporan</th>
                                <th class="p-2 sm:p-3 font-semibold border-b">Bulan</th>
                                <th class="p-2 sm:p-3 font-semibold border-b">Tahun</th>
                                @if ($authUser->id_role == 1)
                                    <th class="p-2 sm:p-3 font-semibold border-b">Dibuat Oleh</th>
                                @endif
                                <th class="p-2 sm:p-3 font-semibold border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="laporanTableBody">
                            @foreach ($laporanCapaian as $laporan)
                                <tr class="border-b hover:bg-gray-50 transition text-slate-700" data-laporan-id="{{ $laporan->id }}">
                                    <td class="p-2 sm:p-3">{{ $loop->iteration }}</td>
                                    <td class="p-2 sm:p-3 font-semibold text-blue-700">
                                        {{ \App\Models\LaporanCapaian::labelTipe($laporan->tipe) }}
                                    </td>
                                    <td class="p-2 sm:p-3 font-medium text-gray-800">{{ $laporan->judul }}</td>
                                    <td class="p-2 sm:p-3">{{ \App\Models\LaporanCapaian::namaBulan($laporan->bulan) }}</td>
                                    <td class="p-2 sm:p-3 font-medium">{{ $laporan->tahun }}</td>
                                    @if ($authUser->id_role == 1)
                                        <td class="p-2 sm:p-3">
                                            {{ $laporan->creator->nama ?? '-' }}
                                        </td>
                                    @endif
                                    <td class="p-2 sm:p-3 text-center">
                                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                                            <a href="{{ route('laporan-capaian.edit', $laporan->id) }}" class="px-2 sm:px-3 py-1 bg-blue-500 text-white rounded text-xs whitespace-nowrap text-center">
                                                Edit
                                            </a>
                                            <button onclick="deleteLaporan({{ $laporan->id }})"
                                                class="px-2 sm:px-3 py-1 bg-red-500 text-white rounded text-xs whitespace-nowrap">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= LOGOUT BUTTON (Fixed Bottom Right) ================= -->
            <form method="POST" action="{{ route('logout') }}"
                class="fixed bottom-4 right-4 sm:bottom-8 sm:right-8 z-30">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition-all hover:scale-105 text-sm sm:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.293 4.293a1 1 0 01-1.414 0L4 7.414 5.414 6l3.293 3.293L13.414 6 15 7.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>

            <!-- ================= EDIT PROFILE POPUP ================= -->
            <div id="editProfilePanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-40 p-4">
                <div
                    class="bg-gray-200 w-full max-w-[500px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto">
                    <button onclick="closeEditProfile()"
                        class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl">✕</button>

                    <h2 class="text-center text-lg sm:text-xl font-semibold mb-6">EDIT PROFIL SAYA</h2>

                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf

                        <!-- Nama -->
                        <label class="text-xs sm:text-sm font-semibold">Nama Lengkap</label>
                        <input name="nama" class="w-full border rounded p-2 mb-4 text-sm sm:text-base"
                            value="{{ auth()->user()->nama }}" required>

                        <!-- Username -->
                        <label class="text-xs sm:text-sm font-semibold">Username</label>
                        <input name="username" class="w-full border rounded p-2 mb-4 text-sm sm:text-base"
                            value="{{ auth()->user()->username }}" required>

                        <!-- Password Lama -->
                        <label class="text-xs sm:text-sm font-semibold">Password Lama</label>
                        <input name="old_password" type="password"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base">

                        <!-- Password Baru -->
                        <label class="text-xs sm:text-sm font-semibold">Password Baru</label>
                        <input name="new_password" type="password"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base">

                        <!-- Konfirmasi Password -->
                        <label class="text-xs sm:text-sm font-semibold">Konfirmasi Password</label>
                        <input name="new_password_confirmation" type="password"
                            class="w-full border rounded p-2 mb-6 text-sm sm:text-base">

                        <div class="flex justify-center">
                            <button
                                class="w-full sm:w-auto px-6 py-2 bg-gray-800 text-white rounded-lg text-sm sm:text-base">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= EDIT USER POPUP ================= -->
            <div id="editUserPanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-40 p-4">
                <div
                    class="bg-gray-200 w-full max-w-[450px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto">
                    <button onclick="closeEditUser()"
                        class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl">✕</button>
                    <h2 class="text-center text-lg sm:text-xl font-semibold mb-6">EDIT USER</h2>

                    <form method="POST" action="{{ route('user.update') }}">
                        @csrf

                        <!-- ID USER -->
                        <input type="hidden" name="id" id="edit_user_id">
                        <label class="text-xs sm:text-sm font-semibold">Nama Lengkap</label>
                        <input id="edit_name" name="nama"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base" required>
                        <label class="text-xs sm:text-sm font-semibold">Username</label>
                        <input id="edit_username" name="username"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base" required>
                        <label class="text-xs sm:text-sm font-semibold">
                            Password Baru (Kosongkan jika tidak ingin ubah)
                        </label>
                        <div class="relative mb-6">
                            <input id="edit_password" name="password" type="password"
                                class="w-full border rounded p-2 pr-10 text-sm sm:text-base">
                            <button type="button" onclick="togglePassword('edit_password', this)"
                                class="absolute right-3 top-2.5 text-gray-500">
                                👁
                            </button>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                                Update
                            </button>
                            <button type="button" onclick="closeEditUser()"
                                class="w-full sm:w-auto px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= ADD USER POPUP ================= -->
            <div id="addUser" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div
                    class="bg-[#e5e9f0] w-full max-w-[450px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto">
                    <button onclick="closeAddUser()"
                        class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl">✕</button>

                    <h2 class="text-center text-base sm:text-lg font-semibold mb-6">
                        TAMBAH USER BARU
                    </h2>

                    <form method="POST" action="{{ route('user.store') }}">
                        @csrf

                        <label class="text-xs sm:text-sm font-semibold">Nama Lengkap</label>
                        <input name="nama" class="w-full border rounded p-2 mb-4 text-sm sm:text-base" required>
                        <label class="text-xs sm:text-sm font-semibold">Username</label>
                        <input name="username" class="w-full border rounded p-2 mb-4 text-sm sm:text-base" required>
                        <label class="text-xs sm:text-sm font-semibold">Password</label>
                        <div class="relative mb-4">
                            <input name="password" id="add_password" type="password"
                                class="w-full border rounded p-2 pr-10 text-sm sm:text-base" required>
                            <button type="button" onclick="togglePassword('add_password', this)"
                                class="absolute right-3 top-2.5 text-gray-500">👁</button>
                        </div>
                        <label class="text-xs sm:text-sm font-semibold">Confirm Password</label>
                        <div class="relative mb-4">
                            <input name="password_confirmation" id="add_password_confirm" type="password"
                                class="w-full border rounded p-2 pr-10 text-sm sm:text-base" required>
                            <button type="button" onclick="togglePassword('add_password_confirm', this)"
                                class="absolute right-3 top-2.5 text-gray-500">👁</button>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
                            <button type="submit"
                                class="w-full sm:w-auto bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 text-sm sm:text-base">
                                Simpan
                            </button>
                            <button type="button" onclick="closeAddUser()"
                                class="w-full sm:w-auto bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= EDIT PAGE POPUP ================= -->
            <div id="editPagePanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-40 p-4">
                <div
                    class="bg-gray-200 w-full max-w-[500px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto">
                    <button onclick="closeEditPage()"
                        class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl">✕</button>

                    <h2 class="text-center text-lg sm:text-xl font-semibold mb-6">EDIT HALAMAN</h2>

                    <form method="POST" id="editPageForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="edit_page_id">

                        <label class="text-xs sm:text-sm font-semibold">Nama Dashboard</label>
                        <input name="nama_dashboard" id="edit_page_name"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base">

                        <label class="text-xs sm:text-sm font-semibold">Embed Link</label>
                        <input name="embed_link" id="edit_page_embed" type="url"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base">

                        <label class="text-xs sm:text-sm font-semibold">Thumbnail</label>
                        <input name="thumbnail" type="file"
                            class="w-full border rounded p-2 mb-6 text-sm sm:text-base">

                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm sm:text-base">
                                Update
                            </button>
                            <button type="button" onclick="closeEditPage()"
                                class="w-full sm:w-auto px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= ADD PAGE POPUP ================= -->
            <div id="addPagePanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div
                    class="bg-[#e5e9f0] w-full max-w-[500px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto">
                    <button onclick="closeAddPage()"
                        class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl">✕</button>

                    <h2 class="text-center text-base sm:text-lg font-semibold mb-6">
                        TAMBAH HALAMAN BARU
                    </h2>

                    <form method="POST" action="{{ route('dashboard.store') }}" enctype="multipart/form-data">
                        @csrf

                        <label class="text-xs sm:text-sm font-semibold">Nama Dashboard</label>
                        <input name="nama_dashboard" class="w-full border rounded p-2 mb-4 text-sm sm:text-base"
                            placeholder="Dashboard Sales">

                        <label class="text-xs sm:text-sm font-semibold">Embed Link</label>
                        <input name="embed_link" type="url"
                            class="w-full border rounded p-2 mb-4 text-sm sm:text-base"
                            placeholder="https://embed.example.com/sales">

                        <label class="text-xs sm:text-sm font-semibold">Thumbnail URL</label>
                        <input name="thumbnail" type="file" name="thumbnail" accept="image/*"
                            class="w-full border rounded p-2 mb-6 text-sm sm:text-base">

                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button
                                class="w-full sm:w-auto bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition text-sm sm:text-base">
                                Simpan
                            </button>
                            <button type="button" onclick="closeAddPage()"
                                class="w-full sm:w-auto bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= ADD INNOVATION POPUP ================= -->
            <div id="addInnovationPanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div class="bg-[#e5e9f0] w-full max-w-[520px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto shadow-2xl">
                    <button onclick="closeAddInnovation()" type="button" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl text-slate-500 hover:text-slate-800">✕</button>
                    <h2 class="text-center text-base sm:text-lg font-bold text-gray-800 mb-6">
                        TAMBAH INOVASI WEBSITE BARU
                    </h2>
                    <form id="addInnovationForm" method="POST" action="{{ route('admin.innovations.store') }}">
                        @csrf
                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Nama Inovasi / Website <span class="text-red-500">*</span></label>
                        <input name="title" required class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Contoh: SiPantau Stunting">

                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Link URL Website <span class="text-red-500">*</span></label>
                        <input name="url" type="url" required class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="https://sipantau.bkkbn.go.id">

                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="2" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Sistem Pemantauan Stunting Terpadu..."></textarea>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Urutan (Order)</label>
                                <input name="order" type="number" value="0" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Icon FontAwesome</label>
                                <input name="icon" value="fa-solid fa-lightbulb" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="fa-solid fa-lightbulb">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-6">
                            <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="w-4 h-4 text-amber-600 rounded">
                            <label for="add_is_active" class="text-xs sm:text-sm font-semibold text-gray-700 cursor-pointer">Status Aktif (Tampilkan di Menu Navbar)</label>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-medium px-6 py-2.5 rounded-xl transition text-sm sm:text-base">
                                Simpan Inovasi
                            </button>
                            <button type="button" onclick="closeAddInnovation()" class="w-full sm:w-auto bg-gray-400 hover:bg-gray-500 text-white font-medium px-6 py-2.5 rounded-xl transition text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= EDIT INNOVATION POPUP ================= -->
            <div id="editInnovationPanel" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div class="bg-[#e5e9f0] w-full max-w-[520px] max-h-[90vh] rounded-2xl sm:rounded-3xl p-6 sm:p-8 relative overflow-y-auto shadow-2xl">
                    <button onclick="closeEditInnovation()" type="button" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-xl sm:text-2xl text-slate-500 hover:text-slate-800">✕</button>
                    <h2 class="text-center text-base sm:text-lg font-bold text-gray-800 mb-6">
                        EDIT INOVASI WEBSITE
                    </h2>
                    <form id="editInnovationForm" method="POST">
                        @csrf
                        <input type="hidden" id="edit_innovation_id" name="id">

                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Nama Inovasi / Website <span class="text-red-500">*</span></label>
                        <input id="edit_innovation_title" name="title" required class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">

                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Link URL Website <span class="text-red-500">*</span></label>
                        <input id="edit_innovation_url" name="url" type="url" required class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">

                        <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Deskripsi Singkat (Opsional)</label>
                        <textarea id="edit_innovation_desc" name="description" rows="2" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Urutan (Order)</label>
                                <input id="edit_innovation_order" name="order" type="number" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="text-xs sm:text-sm font-semibold text-gray-700 block mb-1">Icon FontAwesome</label>
                                <input id="edit_innovation_icon" name="icon" class="w-full bg-white border border-slate-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-6">
                            <input type="checkbox" name="is_active" id="edit_innovation_active" value="1" class="w-4 h-4 text-amber-600 rounded">
                            <label for="edit_innovation_active" class="text-xs sm:text-sm font-semibold text-gray-700 cursor-pointer">Status Aktif (Tampilkan di Menu Navbar)</label>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-medium px-6 py-2.5 rounded-xl transition text-sm sm:text-base">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeEditInnovation()" class="w-full sm:w-auto bg-gray-400 hover:bg-gray-500 text-white font-medium px-6 py-2.5 rounded-xl transition text-sm sm:text-base">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                const editPageForm = document.getElementById('editPageForm');
                editPageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const id = document.getElementById('edit_page_id').value;
                    const formData = new FormData(this);

                    fetch(`/dashboard/${id}/update`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal update halaman');
                            return res.text();
                        })
                        .then(() => {
                            alert('Halaman berhasil diperbarui!');
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat update halaman');
                        });
                });
            </script>
            <!-- ================= SCRIPT ================= -->
            <script>
                // Untuk password di tabel
                function togglePasswordInTable(btn) {
                    const passwordSpan = btn.previousElementSibling;
                    const realPass = passwordSpan.getAttribute('data-password');

                    if (passwordSpan.textContent === '••••••••••') {
                        passwordSpan.textContent = realPass;
                    } else {
                        passwordSpan.textContent = '••••••••••';
                    }
                }

                // Search function untuk kedua tabel
                function searchTable(searchInputId, tableBodyId) {
                    const input = document.getElementById(searchInputId);
                    const filter = input.value.toLowerCase();
                    const tbody = document.getElementById(tableBodyId);
                    const rows = tbody.getElementsByTagName('tr');

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.getElementsByTagName('td');
                        let found = false;

                        for (let j = 0; j < cells.length; j++) {
                            const cell = cells[j];
                            if (cell) {
                                const textValue = cell.textContent || cell.innerText;
                                if (textValue.toLowerCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }

                        if (found) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                }

                // Popup functions - Profile
                function openEditProfile() {
                    document.getElementById('editProfilePanel').classList.remove('hidden');
                    document.getElementById('editProfilePanel').classList.add('flex');
                }

                function closeEditProfile() {
                    document.getElementById('editProfilePanel').classList.add('hidden');
                }

                // Popup functions - User
                function openAddUser() {
                    document.getElementById('addUser').classList.remove('hidden');
                    document.getElementById('addUser').classList.add('flex');
                }

                function closeAddUser() {
                    document.getElementById('addUser').classList.add('hidden');
                }

                function editUser(id, name, username) {
                    document.getElementById('edit_user_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_username').value = username;

                    document.getElementById('editUserPanel').classList.remove('hidden');
                    document.getElementById('editUserPanel').classList.add('flex');
                }

                function closeEditUser() {
                    document.getElementById('editUserPanel').classList.add('hidden');
                }

                function deleteUser(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                        return;
                    }

                    fetch(`/user/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal menghapus');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const row = document.querySelector(
                                `#userTableBody tr[data-user-id="${id}"]`
                            );
                            if (row) {
                                row.remove();
                            }
                            alert('User berhasil dihapus');
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan saat menghapus user');
                            console.error(error);
                        });
                }

                // Popup functions - Page
                function openAddPage() {
                    document.getElementById('addPagePanel').classList.remove('hidden');
                    document.getElementById('addPagePanel').classList.add('flex');
                }

                function closeAddPage() {
                    document.getElementById('addPagePanel').classList.add('hidden');
                }

                // Open edit modal
                function openEditPage(el) {
                    document.getElementById('edit_page_id').value = el.dataset.id;
                    document.getElementById('edit_page_name').value = el.dataset.name;
                    document.getElementById('edit_page_embed').value = el.dataset.embed;

                    const panel = document.getElementById('editPagePanel');
                    panel.classList.remove('hidden');
                    panel.classList.add('flex');
                }


                function closeEditPage() {
                    document.getElementById('editPagePanel').classList.add('hidden');
                }

                // Delete page
                function deletePage(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus halaman ini?')) return;

                    fetch(`/dashboard/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menghapus halaman');
                            return res.json();
                        })
                        .then(data => {
                            const row = document.querySelector(`#pageTableBody tr[data-page-id="${id}"]`);
                            if (row) row.remove();
                            alert('Halaman berhasil dihapus!');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat menghapus halaman');
                        });
                }

                // Delete Laporan Capaian
                function deleteLaporan(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus laporan ini?')) return;

                    fetch(`/laporan-capaian/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menghapus laporan');
                            return res.json();
                        })
                        .then(data => {
                            const row = document.querySelector(`#laporanTableBody tr[data-laporan-id="${id}"]`);
                            if (row) row.remove();
                            alert('Laporan berhasil dihapus!');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat menghapus laporan');
                        });
                }

                // Update presentation link
                function updatePresentationLink(id) {
                    const url = document.getElementById(`link_${id}`).value;

                    if (!url) {
                        alert('URL tidak boleh kosong');
                        return;
                    }

                    fetch(`/presentation-link/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ url: url })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Gagal update link');
                        return res.json();
                    })
                    .then(data => {
                        alert('Link berhasil diupdate!');
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat update link');
                    });
                }

                // Toggle password untuk input fields
                function togglePassword(id, btn) {
                    const input = document.getElementById(id);
                    input.type = input.type === 'password' ? 'text' : 'password';
                }

                // ================= FUNCTIONS INOVASI WEBSITE =================
                function openAddInnovation() {
                    const panel = document.getElementById('addInnovationPanel');
                    panel.classList.remove('hidden');
                    panel.classList.add('flex');
                }

                function closeAddInnovation() {
                    const panel = document.getElementById('addInnovationPanel');
                    panel.classList.add('hidden');
                    panel.classList.remove('flex');
                }

                function editInnovation(data) {
                    document.getElementById('edit_innovation_id').value = data.id;
                    document.getElementById('edit_innovation_title').value = data.title;
                    document.getElementById('edit_innovation_url').value = data.url;
                    document.getElementById('edit_innovation_desc').value = data.description || '';
                    document.getElementById('edit_innovation_order').value = data.order || 0;
                    document.getElementById('edit_innovation_icon').value = data.icon || 'fa-solid fa-lightbulb';
                    document.getElementById('edit_innovation_active').checked = !!data.is_active;

                    const panel = document.getElementById('editInnovationPanel');
                    panel.classList.remove('hidden');
                    panel.classList.add('flex');
                }

                function closeEditInnovation() {
                    const panel = document.getElementById('editInnovationPanel');
                    panel.classList.add('hidden');
                    panel.classList.remove('flex');
                }

                // Submit Add Innovation via AJAX
                const addInnovationForm = document.getElementById('addInnovationForm');
                if (addInnovationForm) {
                    addInnovationForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        fetch("{{ route('admin.innovations.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Inovasi website berhasil ditambahkan!');
                                location.reload();
                            } else {
                                alert(data.message || 'Gagal menambahkan inovasi');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat menyimpan inovasi');
                        });
                    });
                }

                // Submit Edit Innovation via AJAX
                const editInnovationForm = document.getElementById('editInnovationForm');
                if (editInnovationForm) {
                    editInnovationForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const id = document.getElementById('edit_innovation_id').value;
                        const formData = new FormData(this);

                        fetch(`/admin/innovations/${id}/update`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Inovasi website berhasil diperbarui!');
                                location.reload();
                            } else {
                                alert(data.message || 'Gagal update inovasi');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat memperbarui inovasi');
                        });
                    });
                }

                // Delete Innovation
                function deleteInnovation(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus inovasi ini?')) return;

                    fetch(`/admin/innovations/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.querySelector(`#innovationTableBody tr[data-innovation-id="${id}"]`);
                            if (row) row.remove();
                            alert('Inovasi berhasil dihapus!');
                        } else {
                            alert(data.message || 'Gagal menghapus inovasi');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus inovasi');
                    });
                }

                // Toggle Status Active
                function toggleInnovationStatus(id) {
                    fetch(`/admin/innovations/${id}/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const badge = document.getElementById(`status_badge_${id}`);
                            if (badge) {
                                if (data.is_active) {
                                    badge.className = 'px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition border bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100';
                                    badge.innerText = '● Aktif';
                                } else {
                                    badge.className = 'px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition border bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200';
                                    badge.innerText = '○ Nonaktif';
                                }
                            }
                        } else {
                            alert(data.message || 'Gagal mengubah status');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat mengubah status inovasi');
                    });
                }
            </script>
        </div>
    </div>
</x-layout>