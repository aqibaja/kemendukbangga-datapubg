<x-layout>
    <x-slot:title>Dashboard Apel Senin — Memuat Data</x-slot:title>

    <div class="min-h-[60vh] flex flex-col items-center justify-center py-20 space-y-8">
        <!-- Animated Loader -->
        <div class="relative w-28 h-28">
            <div class="absolute inset-0 rounded-full border-8 border-transparent border-t-emerald-500 border-b-emerald-500 animate-[spin_1.5s_linear_infinite]"></div>
            <div class="absolute inset-3 rounded-full border-8 border-transparent border-l-teal-400 border-r-teal-400 animate-[spin_1s_linear_infinite_reverse]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-flag text-3xl text-emerald-600 animate-bounce"></i>
            </div>
        </div>

        <div class="text-center">
            <h2 class="text-2xl font-black tracking-widest uppercase text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 animate-pulse">
                Memuat Data Apel Senin
            </h2>
            <p class="text-gray-500 font-medium text-sm mt-2">Sedang mengambil data dari server...</p>
        </div>

        <!-- Progress bar animation -->
        <div class="w-64 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full animate-[loading_2s_ease-in-out_infinite]"
                 style="animation: loading 2s ease-in-out infinite;">
            </div>
        </div>
    </div>

    <style>
        @keyframes loading {
            0%   { width: 0%; margin-left: 0; }
            50%  { width: 70%; margin-left: 15%; }
            100% { width: 0%; margin-left: 100%; }
        }
    </style>

    <script>
        // Auto-reload after 2 seconds to try fetching again (with _sync param)
        setTimeout(() => {
            window.location.href = "{{ route('apel-senin') }}?_sync=1";
        }, 2000);
    </script>
</x-layout>
