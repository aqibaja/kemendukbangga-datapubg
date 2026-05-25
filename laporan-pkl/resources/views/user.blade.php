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
            </script>
        </div>
    </div>
</x-layout>