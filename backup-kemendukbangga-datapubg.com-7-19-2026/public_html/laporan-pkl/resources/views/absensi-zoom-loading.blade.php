<x-layout>
    <x-slot:title>Loading Data...</x-slot:title>
    
    <!-- Content Area Loading Screen -->
    <div class="w-full min-h-[75vh] relative overflow-hidden rounded-3xl shadow-2xl bg-gradient-to-br from-sky-900 via-blue-900 to-indigo-950 flex flex-col items-center justify-center transition-all duration-300">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        
        <!-- Glowing Orbs Behind Spinner -->
        <div class="absolute w-64 h-64 bg-sky-500/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute w-64 h-64 bg-amber-500/20 rounded-full blur-3xl animate-pulse animation-delay-2000 translate-x-10 translate-y-10"></div>
        
        <div class="relative w-32 h-32 mb-8">
            <div class="absolute inset-0 border-4 border-white/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-transparent border-t-amber-400 border-r-sky-400 rounded-full animate-spin drop-shadow-[0_0_15px_rgba(56,189,248,0.6)]"></div>
            <div class="absolute inset-2 border-4 border-transparent border-b-sky-400 border-l-amber-400 rounded-full animate-[spin_1s_ease-in-out_infinite_reverse] drop-shadow-[0_0_15px_rgba(251,191,36,0.6)]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-cloud-download-alt text-3xl text-white animate-pulse"></i>
            </div>
        </div>
        <h3 class="text-white text-2xl font-black tracking-widest uppercase drop-shadow-lg mb-2">Sinkronisasi Data</h3>
        <p class="text-sky-100/90 font-medium text-center max-w-sm drop-shadow">Menarik data terbaru dari Google Spreadsheet. Proses ini mungkin memakan waktu beberapa detik...</p>
    </div>

    <script>
        // Use AJAX to fetch the heavy page so the browser URL doesn't change
        setTimeout(function() {
            var url = "{!! url()->full() !!}";
            
            // Clean any old fetching parameters from URL just in case
            url = url.replace(/([&?])fetching=1/g, '');
            
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            var fetchUrl = url + separator + "_sync=1";

            fetch(fetchUrl)
                .then(response => {
                    if (response.ok) {
                        // Data is now successfully cached on the server!
                        // We reload cleanly without parameters so the user sees the real dashboard
                        window.location.href = url;
                    } else {
                        console.error('Server returned error:', response.status);
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    window.location.reload();
                });
        }, 100);
    </script>
</x-layout>
