<div x-data="{ openMenu: false }"
    class="z-50 sticky top-0 flex items-center justify-between h-20 px-4 lg:px-8 bg-gradient-to-r from-[#b0f5fd97] to-[#eaf9ff7f] border-b-2 border-black print:hidden">

   {{-- KIRI: LOGO --}}
    <div class="flex-1 flex items-center lg:gap-6 z-10 h-full">
        <a href="/"
           class="flex items-center h-full pl-3 hover:bg-black/5 transition rounded-lg">
            <img src="{{ asset('public/image/bkkbn.png') }}"
                 class="h-full w-auto object-contain">
        </a>
    </div>

    @php
        $presentationLinks = \App\Models\PresentationLink::all()->keyBy('key');
        $apelSenin = $presentationLinks->get('apel_senin')?->url ?? 'https://s.id/APELYOK';
        $zoomPresensi = $presentationLinks->get('zoom_presensi')?->url ?? 'https://forms.gle/XkWbaiBoRmqTBAd9A';
    @endphp

    {{-- NAV ICON TENGAH (DESKTOP) --}}
    <div class="hidden lg:flex flex-none justify-center items-center gap-1 xl:gap-2">
        @if(!auth()->check() || auth()->user()->id_role != 1)
            <x-nav-link href="/" :active="request()->is('/')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-house text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Beranda</span>
            </x-nav-link>

            <x-nav-link href="/about" :active="request()->is('about')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-circle-info text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Informasi</span>
            </x-nav-link>

            <x-nav-link href="/contact" :active="request()->is('contact')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-envelope text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Kontak</span>
            </x-nav-link>

            <x-nav-link href="/datas" :active="request()->is('datas')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-database text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Data</span>
            </x-nav-link>

            <x-nav-link href="/zoomdesk" :active="request()->is('zoomdesk')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-desktop text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Zoom Desk</span>
            </x-nav-link>

            <x-nav-link href="/laporan-capaian" :active="request()->is('laporan-capaian')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-chart-line text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[200px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Laporan Capaian</span>
            </x-nav-link>

            <x-nav-link href="/update-k0-sppg" :active="request()->is('update-k0-sppg')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-file-pen text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[200px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Update K0 SPPG</span>
            </x-nav-link>
        @endif

        @auth
            @if(auth()->user()->id_role == 1)
                <x-nav-link href="/admin/employees" :active="request()->is('admin/employees*')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                    <i class="fa-solid fa-users text-2xl shrink-0"></i>
                    <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Master Pegawai</span>
                </x-nav-link>
                <x-nav-link href="/admin/qr-sessions" :active="request()->is('admin/qr-sessions*')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                    <i class="fa-solid fa-qrcode text-2xl shrink-0"></i>
                    <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Sesi QR</span>
                </x-nav-link>
            @endif
            <x-nav-link href="/user" :active="request()->is('user')" class="group !w-auto !px-3 flex items-center overflow-hidden transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-gear text-2xl shrink-0"></i>
                <span class="max-w-0 overflow-hidden opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 group-hover:ml-2 transition-all duration-300 ease-in-out font-medium text-sm whitespace-nowrap">Pengaturan</span>
            </x-nav-link>
        @endauth
    </div>

    {{-- KANAN DESKTOP --}}
    <div class="hidden lg:flex flex-1 justify-end items-center gap-3 xl:gap-5">
        @auth
            @php
                $nama = auth()->user()->nama;
                $inisial = strtoupper(substr($nama, 0, 1));
                $isAdmin = auth()->user()->id_role == 1;
            @endphp

            <!-- PROFILE USER -->
            <div
                class="flex items-center gap-3 px-4 py-2 rounded-full
                        bg-white/10 backdrop-blur-sm border border-white/20">

                <!-- INISIAL -->
                <div
                    class="w-9 h-9 rounded-full
                    {{ $isAdmin ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : 'bg-gradient-to-br from-blue-400 to-blue-600' }}
                    text-white flex items-center justify-center text-sm font-bold shadow-md">
                    {{ $inisial }}
                </div>

                <!-- INFO -->
                <div class="flex flex-col leading-tight">
                    <span class="text-xs font-medium {{ $isAdmin ? 'text-emerald-400' : 'text-blue-400' }}">
                        {{ $isAdmin ? 'Admin' : 'User' }}
                    </span>
                    <span class="text-sm text-black font-medium">
                        {{ $nama }}
                    </span>
                </div>
            </div>
        @else
        {{-- TOMBOL PRESENSI --}}
        <div class="flex items-center gap-2">
            <a href="{{ $apelSenin }}"
               class="flex items-center gap-2 px-3 xl:px-4 py-2 rounded-md
                      bg-white text-black border border-black/20
                  hover:bg-gray-100 transition text-xs xl:text-sm font-medium whitespace-nowrap">
                <i class="fa-solid fa-clipboard-check"></i>
                <span class="hidden xl:inline">Presensi</span> Apel Senin
            </a>

            <a href="{{ $zoomPresensi }}"
               class="flex items-center gap-2 px-3 xl:px-4 py-2 rounded-md
                      rounded-lg bg-blue-600 text-white border border-blue-700/30
                  hover:bg-blue-700 transition text-xs xl:text-sm font-medium whitespace-nowrap">
                <i class="fa-solid fa-video"></i>
                <span class="hidden xl:inline">Presensi</span> Zoom
            </a>
        </div>
            <a href="/login"
                title="Login"
                class="w-10 h-10 flex items-center justify-center rounded-full
                        bg-[#b0f5fd97]/90 text-black border
                        hover:bg-[#91bec3d4] transition text-base">
                <i class="fa-solid fa-right-to-bracket"></i>
            </a>
        @endauth
    </div>

    {{-- KANAN MOBILE: BURGER + ICON --}}
    <div class="lg:hidden ml-auto flex items-center gap-2 z-10">
        {{-- BURGER --}}
        <button @click="openMenu = !openMenu"
            class="w-11 h-11 flex items-center justify-center
                    rounded-lg hover:bg-black/10 transition">
            <i class="fa-solid fa-bars text-2xl"></i>
        </button>
    
        {{-- ICON PRESENSI APEL SENIN --}}
        <a href="{{ $apelSenin }}"
           class="w-11 h-11 flex items-center justify-center
                  rounded-lg bg-white text-black border border-black/20
                  hover:bg-gray-100 transition"
           title="Presensi Apel Senin">
            <i class="fa-solid fa-clipboard-check text-lg"></i>
        </a>
        
        {{-- ICON PRESENSI APEL ZOOM (BIRU) --}}
        <a href="{{ $zoomPresensi }}"
           class="w-11 h-11 flex items-center justify-center
                  rounded-lg bg-blue-600 text-white border border-blue-700/30
                  hover:bg-blue-700 transition"
           title="Presensi Apel Zoom">
            <i class="fa-solid fa-video text-lg"></i>
        </a>
    
        {{-- PROFILE / LOGIN MOBILE --}}
        @auth
            @php $inisial = strtoupper(substr(auth()->user()->nama, 0, 1)); @endphp
            <div class="w-10 h-10 rounded-full bg-[#F0DEDE]
                        flex items-center justify-center
                        text-sm font-semibold text-[#4C4F6E]">
                {{ $inisial }}
            </div>
        @else
            <a href="/login"
                class="w-11 h-11 flex items-center justify-center
                        rounded-lg bg-[#b0f5fd97]/90 border
                        hover:bg-[#91bec3d4] transition">
                <i class="fa-solid fa-right-to-bracket text-lg"></i>
            </a>
        @endauth
    </div>


    {{-- MOBILE MENU --}}
    <div x-show="openMenu" x-transition @click.outside="openMenu = false"
        class="absolute top-full right-4 mt-2
                bg-white rounded-2xl shadow-xl
                p-3 flex flex-col gap-2 lg:hidden z-50 min-w-[200px]">
        
        @if(!auth()->check() || auth()->user()->id_role != 1)
            <x-nav-link href="/" :active="request()->is('/')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-house text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Beranda</span>
            </x-nav-link>
            
            <x-nav-link href="/about" :active="request()->is('about')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-circle-info text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Informasi</span>
            </x-nav-link>
            
            <x-nav-link href="/contact" :active="request()->is('contact')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-envelope text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Kontak</span>
            </x-nav-link>
            
            <x-nav-link href="/datas" :active="request()->is('datas')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-database text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Data</span>
            </x-nav-link>
            
            <x-nav-link href="/zoomdesk" :active="request()->is('zoomdesk')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-desktop text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Zoom Desk</span>
            </x-nav-link>
            
            <x-nav-link href="/laporan-capaian" :active="request()->is('laporan-capaian')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-chart-line text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Laporan Capaian</span>
            </x-nav-link>
            
            <x-nav-link href="/update-k0-sppg" :active="request()->is('update-k0-sppg')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-file-pen text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Update K0 SPPG</span>
            </x-nav-link>
        @endif
        
        @auth
            @if(auth()->user()->id_role == 1)
                <x-nav-link href="/admin/employees" :active="request()->is('admin/employees*')" class="!w-full !justify-start px-4 gap-3">
                    <i class="fa-solid fa-users text-xl w-6 text-center"></i>
                    <span class="font-medium text-sm">Master Pegawai</span>
                </x-nav-link>
                <x-nav-link href="/admin/qr-sessions" :active="request()->is('admin/qr-sessions*')" class="!w-full !justify-start px-4 gap-3">
                    <i class="fa-solid fa-qrcode text-xl w-6 text-center"></i>
                    <span class="font-medium text-sm">Sesi QR</span>
                </x-nav-link>
            @endif
            <x-nav-link href="/user" :active="request()->is('user')" class="!w-full !justify-start px-4 gap-3">
                <i class="fa-solid fa-gear text-xl w-6 text-center"></i>
                <span class="font-medium text-sm">Pengaturan</span>
            </x-nav-link>
        @endauth
    </div>
</div>
