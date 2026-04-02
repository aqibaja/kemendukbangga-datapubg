<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

   <!-- ================= CONTAINER UTAMA ================= -->
    <div class="w-full px-2 sm:px-6 pb-8 space-y-6 sm:space-y-10">

        <!-- ================= CARD IKLAN BESAR ================= -->
        <div
            class="relative bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 h-auto sm:h-[400px] md:h-[555px] overflow-hidden">

            <!-- PANAH KIRI -->
            <button onclick="slideLeft()"
                class="absolute left-0 sm:left-3 top-1/2 -translate-y-1/2
                       bg-white shadow rounded-full p-1 sm:p-3 z-10 text-sm sm:text-base">
                ❮
            </button>

            <!-- PANAH KANAN -->
            <button onclick="slideRight()"
                class="absolute right-0 sm:right-3 top-1/2 -translate-y-1/2
                       bg-white shadow rounded-full p-1 sm:p-3 z-10 text-sm sm:text-base">
                ❯
            </button>

            <!-- SLIDER -->
            <div id="slider" class="flex h-full overflow-hidden scroll-smooth">

                <div
                    class="my-auto min-w-full h-1/2 lg:h-full rounded-lg sm:rounded-xl p-0.5
                            flex items-center justify-center text-lg sm:text-2xl font-semibold">
                    <img src="{{ asset('public/image/poster 1.jpeg') }}" class="h-full w-auto object-cover">
                </div>
                <div
                    class="min-w-full h-1/2 lg:h-full rounded-lg sm:rounded-xl p-0.5
                            flex items-center justify-center text-lg sm:text-2xl font-semibold">
                    <img src="{{ asset('public/image/poster 2.jpeg') }}" class="h-full w-auto object-cover">
                </div>

                <div
                    class="min-w-full h-1/2 lg:h-full rounded-lg sm:rounded-xl p-0.5
                            flex items-center justify-center text-lg sm:text-2xl font-semibold">
                    <img src="{{ asset('public/image/poster 3.jpeg') }}" class="h-full w-auto object-cover">
                </div>
            </div>
        </div>

        <!-- ================= SCRIPT SLIDER ================= -->
        <script>
            const slider = document.getElementById('slider');
            const totalSlide = slider.children.length;
            let index = 0;

            function slideRight() {
                index++;
                if (index >= totalSlide) index = 0;

                slider.scrollTo({
                    left: slider.clientWidth * index,
                    behavior: 'smooth'
                });
            }

            function slideLeft() {
                index--;
                if (index < 0) index = totalSlide - 1;

                slider.scrollTo({
                    left: slider.clientWidth * index,
                    behavior: 'smooth'
                });
            }

            // AUTO SLIDE SETIAP 3 DETIK
            setInterval(() => {
                slideRight();
            }, 5000);
        </script>

        <!-- ================= TOP 3 ================= -->
        <div>
            <h1 class="text-base sm:text-2xl md:text-3xl font-extrabold">
                TOP 3 Halaman Paling Sering Dikunjungi
            </h1>
        </div>

        <!-- Grid selalu 3 kolom di semua ukuran layar -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4 md:gap-6">
            @foreach ($dashboards as $d)
                <a href="{{ url('/data/' . $d->slug) }}" class="flex">
                    <div
                        class="bg-white rounded-lg sm:rounded-2xl p-2 sm:p-4 md:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col w-full">
                        <h3
                            class="font-semibold text-[8px] sm:text-xs md:text-sm lg:text-base mb-1 line-clamp-2 h-6 sm:h-8 md:h-10">
                            {{ $d->nama_dashboard }}</h3>
                        <p class="text-[5px] sm:text-[10px] lg:text-sm md:text-xs text-slate-400 mb-2 sm:mb-4">
                            {{ $d->views_count }} Kali</p>
                        <div class="h-24 sm:h-32 md:h-40 lg:h-48 mb-2 sm:mb-5 bg-slate-100 rounded flex items-center justify-center">
                            <img src="{{ $d->thumbnail ? asset('laporan-pkl/storage/app/public/' . $d->thumbnail) : asset('public/thumbnails/default.jpg') }}"
                                alt="Thumbnail {{ $d->nama_dashboard }}"  class="max-h-full max-w-full object-contain">
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <!-- ================= AKTIVITAS CHART ================= -->
        <div class="bg-white rounded-lg sm:rounded-2xl p-3 sm:p-6 shadow-md">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <h3 class="text-sm sm:text-lg font-semibold text-slate-800">
                    Aktivitas
                </h3>
            </div>
            
            <div class="relative h-48 sm:h-64 md:h-80">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        
        <!-- SCRIPT CHART.JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @php
                    $labels = [];
                    $data = [];
                    
                    // Loop 1-12 bulan, isi 0 jika tidak ada data
                    for ($i = 1; $i <= 12; $i++) {
                        $labels[] = date('M', mktime(0, 0, 0, $i, 1));
                        $found = $viewsPerMonth->firstWhere('month', $i);
                        $data[] = $found ? $found->total : 0;
                    }
                @endphp
                
                new Chart(document.getElementById('activityChart'), {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Jumlah Views',
                            data: @json($data),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 640 ? 10 : 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 640 ? 10 : 12
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>
</x-layout>