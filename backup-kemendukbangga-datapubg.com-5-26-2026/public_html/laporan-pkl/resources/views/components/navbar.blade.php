<div x-data="{ openMenu: false }"
    class="z-50 sticky top-0 flex items-center h-20 px-6 lg:px-8 bg-gradient-to-r from-[#b0f5fd97] to-[#eaf9ff7f] border-b-2 border-black">

   {{-- KIRI: LOGO --}}
    <div class="flex items-center lg:gap-6 z-10 h-full">
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
    <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center gap-4">
        <x-nav-link href="/" :active="request()->is('/')">
            <i class="fa-solid fa-house text-2xl"></i>
        </x-nav-link>
        <x-nav-link href="/about" :active="request()->is('about')">
            <i class="fa-solid fa-circle-info text-2xl"></i>
        </x-nav-link>
        <x-nav-link href="/contact" :active="request()->is('contact')">
            <i class="fa-solid fa-envelope text-2xl"></i>
        </x-nav-link>
        <x-nav-link href="/datas" :active="request()->is('datas')">
            <i class="fa-solid fa-database text-2xl"></i>
        </x-nav-link>
        <x-nav-link href="/zoomdesk" class="flex items-center px-3 py-2 rounded-md hover:bg-black/10 transition">
            <i class="fa-solid fa-desktop text-2xl"></i>
        </x-nav-link>
        @auth
            <x-nav-link href="/user" :active="request()->is('user')">
                <i class="fa-solid fa-gear text-2xl"></i>
            </x-nav-link>
        @endauth
    </div>

    {{-- KANAN DESKTOP --}}
    <div class="hidden lg:flex ml-auto items-center gap-5">
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
               class="flex items-center gap-2 px-4 py-2 rounded-md
                      bg-white text-black border border-black/20
                  hover:bg-gray-100 transition text-sm font-medium">
                <i class="fa-solid fa-clipboard-check"></i>
                Presensi Apel Senin
            </a>

            <a href="{{ $zoomPresensi }}"
               class="flex items-center gap-2 px-4 py-2 rounded-md
                      rounded-lg bg-blue-600 text-white border border-blue-700/30
                  hover:bg-blue-700 transition text-sm font-medium">
                <i class="fa-solid fa-video"></i>
                Presensi Zoom
            </a>
        </div>
            <a href="/login"
                class="flex items-center gap-2 px-4 py-2 rounded-md
                        bg-[#b0f5fd97]/90 text-black border
                        hover:bg-[#91bec3d4] transition text-sm font-medium">
                <i class="fa-solid fa-right-to-bracket"></i>
                Login
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
                p-3 flex flex-col gap-2 lg:hidden z-50">
        <x-nav-link href="/"><i class="fa-solid fa-house text-2xl"></i></x-nav-link>
        <x-nav-link href="/about"><i class="fa-solid fa-circle-info text-2xl"></i></x-nav-link>
        <x-nav-link href="/contact"><i class="fa-solid fa-envelope text-2xl"></i></x-nav-link>
        <x-nav-link href="/datas"><i class="fa-solid fa-database text-2xl"></i></x-nav-link>
        <x-nav-link href="/zoomdesk" :active="request()->is('zoomdesk')">
            <i class="fa-solid fa-desktop text-2xl"></i>
        </x-nav-link>
        @auth
            <x-nav-link href="/user"><i class="fa-solid fa-gear text-2xl"></i></x-nav-link>
        @endauth
    </div>
</div>
