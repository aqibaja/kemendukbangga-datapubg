

<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full px-3 sm:px-4 lg:px-6 py-4 sm:py-6">

        {{-- Search Bar --}}
        <div class="mb-4 sm:mb-6">
            <form action="" method="GET" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dashboard..."
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 pl-9 sm:pl-12 pr-3 sm:pr-4 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    @if (request('search'))
                        <a href="{{ url()->current() }}"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 sm:pr-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @php
            $showNative = false;
            $search = request('search');
            if (!$search || stripos('Dashboard Presensi Zoom', $search) !== false || stripos('absensi', $search) !== false || stripos('zoom', $search) !== false) {
                $showNative = true;
            }
        @endphp

        {{-- Empty State - Tampilkan jika tidak ada data DAN native dashboard tidak muncul --}}
        @if ($datas->isEmpty() && !$showNative)
            <div class="flex flex-col items-center justify-center py-12 sm:py-16">
                <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                @if (request('search'))
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">
                        Data Tidak Ditemukan
                    </h3>
                    <p class="text-sm sm:text-base text-gray-500 text-center mb-4 px-4">
                        Tidak ada hasil untuk pencarian "<span class="font-semibold">{{ request('search') }}</span>"
                    </p>
                    <a href="{{ url()->current() }}"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Hapus Pencarian
                    </a>
                @else
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">
                        Belum Ada Data
                    </h3>
                    <p class="text-sm sm:text-base text-gray-500 text-center px-4">
                        Belum ada dashboard yang tersedia saat ini
                    </p>
                @endif
            </div>
        @else
            {{-- Grid Cards --}}
            <article class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                


                @if($showNative)
                {{-- NATIVE DASHBOARD: ABSENSI ZOOM --}}
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 relative">
                    
                    {{-- Thumbnail dengan aspect ratio 1:1 --}}
                    <div class="relative w-full pt-[100%] overflow-hidden bg-gray-200">
                        <img 
                            src="{{ asset('public/image/presensi-zoom.png') }}"
                            alt="Dashboard Presensi Zoom"
                            class="absolute top-0 left-0 w-full h-full object-cover transition duration-300 hover:scale-105">
                    </div>

                    {{-- Content --}}
                    <div class="p-2 sm:p-3">
                        <a href="{{ route('absensi-zoom') }}" class="block">
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-800 hover:text-blue-600 transition-colors line-clamp-2 min-h-8 sm:min-h-10">
                                Dashboard Presensi Zoom
                            </h3>
                        </a>
                        <div class="flex items-center justify-end mt-1.5 sm:mt-2">
                            
                            {{-- Native Views Counter --}}
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-[10px] sm:text-xs">
                                    {{ number_format(\Illuminate\Support\Facades\Cache::get('native_dashboard_zoom_views', 0)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                @foreach ($datas as $data)
                    <div
                        class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">

                        {{-- Thumbnail dengan aspect ratio 1:1 --}}
                        <div class="relative w-full pt-[100%] overflow-hidden bg-gray-200">
                            <img 
    src="{{ $data->thumbnail 
        ? asset('laporan-pkl/storage/app/public/' . $data->thumbnail) 
        : asset('public/thumbnails/default.jpg') 
    }}"
    alt="{{ $data->nama_dashboard }}"
    class="absolute top-0 left-0 w-full h-full object-cover transition duration-300 hover:scale-105">

                        </div>

                        {{-- Content --}}
                        <div class="p-2 sm:p-3">
                            <a href="{{ url('/data/' . $data->slug) }}" class="block">
                                <h3
                                    class="text-xs sm:text-sm font-semibold text-gray-800 hover:text-blue-600 transition-colors line-clamp-2 min-h-8 sm:min-h-10">
                                    {{ $data->nama_dashboard }}
                                </h3>
                            </a>

                            {{-- Views Counter --}}
                            <div class="flex items-center gap-1 mt-1.5 sm:mt-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-[10px] sm:text-xs">
                                    {{ number_format($data->views_count ?? 0) }}
                                </span>
                                    
                            </div>
                        </div>
                    </div>
                @endforeach
            </article>


        @endif

    </div>

</x-layout>