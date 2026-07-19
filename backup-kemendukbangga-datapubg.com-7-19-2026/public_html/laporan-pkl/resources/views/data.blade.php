<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="h-full">
    <div class="flex w-screen h-screen text-gray-400 bg-gray-50">
        <div class="flex flex-col w-full">
            <div class="p-3 sm:p-4 lg:p-6 overflow-auto bg-gray-800 h-full">
                @php
                    /** @var \App\Models\DashboardPage $data */
                @endphp

                <article class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8">
                    
                    {{-- Back Button --}}
                    <a href="/datas"
                        class="inline-flex items-center text-cyan-500 hover:text-cyan-400 mb-4 sm:mb-6 transition-colors duration-200 font-medium text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Back to Dashboard</span>
                    </a>

                    {{-- Header Section --}}
                    <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6 border border-gray-200">
                        <h1 class="text-lg sm:text-2xl lg:text-3xl xl:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 leading-tight">
                            {{ $data->nama_dashboard ?? 'Untitled Dashboard' }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-gray-600">
                            
                            {{-- Creator Info --}}
                            <div class="flex items-center bg-gray-50 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 rounded-md lg:rounded-lg text-xs sm:text-sm">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 lg:w-5 lg:h-5 mr-1.5 sm:mr-2 text-cyan-600 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium truncate">{{ $data->creator->nama ?? 'Anonymous' }}</span>
                            </div>

                            {{-- Date Info --}}
                            <div class="flex items-center bg-gray-50 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 rounded-md lg:rounded-lg text-xs sm:text-sm">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 lg:w-5 lg:h-5 mr-1.5 sm:mr-2 text-cyan-600 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">
                                    {{ $data->created_at?->translatedFormat('d M Y') ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Views Count --}}
                            <div class="flex items-center bg-gray-50 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 rounded-md lg:rounded-lg text-xs sm:text-sm">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 lg:w-5 lg:h-5 mr-1.5 sm:mr-2 text-cyan-600 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="font-medium">
                                    {{ number_format($data->views()->count() ?? 0) }} Views
                                </span>
                            </div>
                                <a href="{{ $data->embed_link }}"
                                target="_blank"
                                class="ml-auto flex items-center bg-gray-50 px-3 py-2 rounded-md
                                     hover:bg-cyan-50 hover:border-cyan-400
                                        border border-transparent
                                transition duration-200 group">

                                <!-- Icon -->
                                <div class="flex-shrink-0 text-cyan-600">
                                    <i class="fa-solid fa-link"></i>
                                </div>
                                <!-- Text -->
                                <span
                                    class="ml-2 text-sm font-medium text-cyan-600 group-hover:text-cyan-800 transition">
                                    DOWNLOAD
                                </span>
                            </a>
                        </div>
                    </div>

                    {{-- Content Section --}}
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-cyan-500">
                        {{-- Iframe Container with Responsive Height --}}
                        <div class="relative w-full" style="height: 350px; sm:height: 450px; lg:height: 600px;">
                            <iframe class="absolute top-0 left-0 w-full h-full" src="{{ $data->embed_link }}"
                                frameborder="0" style="border:0" allowfullscreen
                                sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>

                    {{-- Footer Info --}}
                    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                            <div class="flex items-start sm:items-center text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-cyan-600 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs sm:text-sm font-medium">
                                    Visualisasi dasbor • Diperbarui secara berkala
                                </span>
                            </div>

                            <a href="/datas"
                                class="text-cyan-600 hover:text-cyan-800 text-xs sm:text-sm font-medium transition-colors whitespace-nowrap">
                                View all dashboards →
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    {{-- Responsive iframe height script --}}
    <script>
        function setIframeHeight() {
            const iframe = document.querySelector('iframe');
            const container = iframe.parentElement;
            const width = window.innerWidth;
            
            let height;
            if (width < 640) {
                // Mobile
                height = '350px';
            } else if (width < 1024) {
                // Tablet
                height = '450px';
            } else {
                // Desktop
                height = '600px';
            }
            
            container.style.height = height;
        }

        // Set height on load
        setIframeHeight();

        // Update on resize with debounce
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(setIframeHeight, 200);
        });
    </script>
</body>

</html>