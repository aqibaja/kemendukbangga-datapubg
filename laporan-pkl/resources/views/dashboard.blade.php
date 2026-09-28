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
                <a href="{{ isset($d->is_native) && $d->is_native ? route('absensi-zoom') : url('/data/' . $d->slug) }}" class="flex">
                    <div
                        class="bg-white rounded-lg sm:rounded-2xl p-2 sm:p-4 md:p-6 shadow-md hover:shadow-lg transition-shadow flex flex-col w-full relative">
                        <h3
                            class="font-semibold text-[8px] sm:text-xs md:text-sm lg:text-base mb-1 line-clamp-2 h-6 sm:h-8 md:h-10">
                            {{ $d->nama_dashboard }}</h3>
                        <p class="text-[5px] sm:text-[10px] lg:text-sm md:text-xs text-slate-400 mb-2 sm:mb-4">
                            {{ $d->views_count }} Kali</p>
                        <div class="h-24 sm:h-32 md:h-40 lg:h-48 mb-2 sm:mb-5 bg-slate-100 rounded flex items-center justify-center overflow-hidden">
                            @if(isset($d->is_native) && $d->is_native)
                                <img src="{{ asset('public/image/presensi-zoom.png') }}"
                                    alt="Thumbnail {{ $d->nama_dashboard }}" class="w-full h-full object-cover transition duration-300 hover:scale-105">
                            @else
                                <img src="{{ $d->thumbnail ? asset('laporan-pkl/storage/app/public/' . $d->thumbnail) : asset('public/image/logoBKKBN.png') }}"
                                    alt="Thumbnail {{ $d->nama_dashboard }}" class="max-h-full max-w-full object-contain">
                            @endif
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
        <!-- Modal Popup Voting Premium Kemendukbangga -->
        <div x-data="{ showModal: false, isResultVisible: false }" 
             x-init="fetch('/api/voting/check-popup')
                        .then(res => res.json())
                        .then(data => { 
                            isResultVisible = data.is_result_visible;
                            if(data.is_popup_active) setTimeout(() => showModal = true, 600) 
                        })" 
             x-show="showModal"
             style="display: none;"
             class="fixed inset-0 z-[100] !m-0 flex items-center justify-center bg-slate-950/65 backdrop-blur-md p-4 sm:p-6"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.outside="showModal = false" 
                 class="bg-white/95 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_25px_70px_rgba(76,163,230,0.25)] max-w-md w-full overflow-hidden relative border-2 border-white"
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-500 transform"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-6"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-250 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-6">
                
                <!-- Ambient Glow Blobs inside Modal -->
                <div class="absolute -top-20 -left-20 w-48 h-48 bg-[#4CA3E6]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-[#DFA53A]/25 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Close Button -->
                <button @click="showModal = false" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 bg-slate-100/90 hover:bg-slate-200/90 rounded-full w-9 h-9 flex items-center justify-center transition-all z-20 hover:rotate-90">
                    <i class="fas fa-times text-sm"></i>
                </button>

                <div class="p-7 sm:p-9 text-center relative z-10">
                    <!-- Logo Kemendukbangga in Floating Glow Badge -->
                    <div class="relative inline-block mb-3">
                        <div class="absolute -inset-2 bg-gradient-to-r from-[#4CA3E6]/30 via-[#DFA53A]/25 to-[#4CA3E6]/30 rounded-3xl blur-lg opacity-80 animate-pulse"></div>
                        <div class="relative p-3 rounded-2xl bg-white shadow-md border border-slate-100 flex items-center justify-center">
                            <img src="{{ asset('image/logoBKKBN.png') }}" 
                                 onerror="this.onerror=null; this.src='{{ asset('public/image/logoBKKBN.png') }}';" 
                                 alt="Logo Kemendukbangga" 
                                 class="w-14 h-14 object-contain filter drop-shadow-sm">
                        </div>
                    </div>

                    <!-- Category / Status Badge -->
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-gradient-to-r from-[#4CA3E6]/10 to-[#DFA53A]/15 text-[#2B82C9] border border-[#4CA3E6]/25 shadow-sm mb-2.5">
                            <i class="fas fa-award text-[#DFA53A]"></i>
                            PEMILIHAN RESMI BKKBN ACEH
                        </span>
                    </div>
                    
                    <h2 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#2B82C9] via-[#4CA3E6] to-[#DFA53A] mb-2 tracking-tight" x-text="isResultVisible ? 'Voting Telah Berakhir!' : 'Pilih ASN KEREN!'">
                    </h2>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6" x-text="isResultVisible ? 'Terima kasih atas partisipasi Anda. Hasil akhir dari pemilihan ASN KEREN Perwakilan BKKBN Aceh sudah dapat dilihat.' : 'Mari berpartisipasi menentukan figur ASN berprestasi dan teladan di lingkungan Perwakilan BKKBN Aceh. Suara Anda sangat berarti!'">
                    </p>

                    <!-- Feature Highlights -->
                    <div x-show="!isResultVisible" class="grid grid-cols-3 gap-2 mb-6 py-2.5 px-3 bg-slate-50/80 rounded-2xl border border-slate-100">
                        <div class="flex flex-col items-center text-center">
                            <i class="fas fa-shield-halved text-xs text-[#4CA3E6] mb-1"></i>
                            <span class="text-[10px] font-bold text-slate-600">Rahasia & Aman</span>
                        </div>
                        <div class="flex flex-col items-center text-center border-x border-slate-200/80">
                            <i class="fas fa-bolt text-xs text-[#DFA53A] mb-1"></i>
                            <span class="text-[10px] font-bold text-slate-600">Cepat 1 Menit</span>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <i class="fas fa-medal text-xs text-[#2B82C9] mb-1"></i>
                            <span class="text-[10px] font-bold text-slate-600">3 Kategori</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a x-show="!isResultVisible" href="{{ route('voting.show') }}" class="w-full py-4 px-6 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black text-base rounded-2xl shadow-xl shadow-[#4CA3E6]/30 hover:shadow-2xl hover:shadow-[#4CA3E6]/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                            <span>Ikuti Voting Sekarang</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('voting.dashboard') }}" class="w-full py-3.5 px-6 bg-white hover:bg-slate-50 text-slate-700 hover:text-[#2B82C9] font-bold text-sm rounded-2xl border border-slate-200/80 hover:border-[#4CA3E6]/40 transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="fas fa-chart-pie text-[#4CA3E6]"></i>
                            <span x-text="isResultVisible ? 'Lihat Hasil Voting' : 'Lihat Hasil Sementara'"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= FLOATING CONTACT US WHATSAPP WIDGET ================= -->
    <style>
        @keyframes waPopIn {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.6);
            }
            60% {
                opacity: 1;
                transform: translateY(-8px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes waFloatWiggle {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            20% {
                transform: translateY(-8px) rotate(0deg);
            }
            40% {
                transform: translateY(0px) rotate(0deg);
            }
            55% {
                transform: translateY(-5px) rotate(0deg);
            }
            70% {
                transform: translateY(0px) rotate(0deg);
            }
            78% {
                transform: translateY(-9px) rotate(-6deg) scale(1.04);
            }
            84% {
                transform: translateY(-9px) rotate(6deg) scale(1.04);
            }
            90% {
                transform: translateY(-5px) rotate(-3deg) scale(1.02);
            }
            96% {
                transform: translateY(-2px) rotate(2deg) scale(1.01);
            }
        }

        @keyframes waPulseGlow {
            0%, 100% {
                transform: scale(0.9);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.65;
            }
        }
    </style>

    <div x-data="{ 
             isHidden: sessionStorage.getItem('hide_wa_contact') === 'true',
             closeWidget() {
                 this.isHidden = true;
                 sessionStorage.setItem('hide_wa_contact', 'true');
             }
         }"
         x-show="!isHidden"
         x-transition:leave="transition ease-in duration-250 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-75 translate-y-4"
         class="fixed bottom-4 right-3 sm:bottom-6 sm:right-6 z-40 select-none pointer-events-auto"
         style="animation: waPopIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both 0.4s;">
        
        <!-- Relative Wrapper -->
        <div class="relative group">
            <!-- Close Button ('x') -->
            <button type="button"
                    @click.stop="closeWidget()"
                    class="absolute -top-1.5 -right-1 sm:-top-2 sm:-right-2 w-6 h-6 sm:w-7 sm:h-7 bg-slate-800/85 hover:bg-slate-950 text-white rounded-full flex items-center justify-center shadow-lg border border-white/40 backdrop-blur-md transition-all duration-200 hover:scale-110 active:scale-90 z-50 cursor-pointer"
                    title="Tutup / Sembunyikan"
                    aria-label="Tutup logo WhatsApp">
                <i class="fas fa-times text-[10px] sm:text-xs"></i>
            </button>

            <a id="floatingContactWa"
               href="https://web.whatsapp.com/send?phone=6285361209387&text=Halo%20Admin%2C%20saya%20ingin%20bertanya%20terkait%20layanan."
               target="_blank"
               rel="noopener noreferrer"
               class="relative flex flex-col items-end cursor-pointer decoration-transparent focus:outline-none"
               title="Hubungi Kami via WhatsApp">

                <!-- Image Wrapper with Glow & Floating/Wiggle Animation -->
                <div class="relative flex items-center justify-center transition-transform duration-300 hover:scale-105 active:scale-95">
                    <!-- Pulsing Green Halo Behind Image -->
                    <div class="absolute inset-0 bg-emerald-400/35 rounded-full blur-xl -z-10"
                         style="animation: waPulseGlow 3s ease-in-out infinite;"></div>

                    <!-- Main Mascot Image -->
                    <img src="{{ asset('public/image/contact_us.png') }}"
                         onerror="this.onerror=null; this.src='{{ asset('image/contact_us.png') }}';"
                         alt="Contact Us WhatsApp"
                         class="w-32 sm:w-44 md:w-52 h-auto object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.18)] hover:drop-shadow-[0_15px_25px_rgba(37,211,102,0.35)] transition-all duration-300"
                         style="animation: waFloatWiggle 6s ease-in-out infinite;">
                </div>
            </a>
        </div>
    </div>

    <!-- WhatsApp Redirect Handler (Desktop -> WhatsApp Web, Mobile -> WhatsApp App) -->
    <script>
        (function() {
            const phone = '6285361209387';
            const defaultText = encodeURIComponent('Halo Admin, saya ingin bertanya terkait layanan.');
            const btn = document.getElementById('floatingContactWa');
            
            // Check if user is on mobile
            const isMobileDevice = () => {
                const ua = navigator.userAgent || navigator.vendor || window.opera;
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua)
                    || (window.innerWidth <= 768 && ('ontouchstart' in window || navigator.maxTouchPoints > 0));
            };

            if (btn) {
                if (isMobileDevice()) {
                    btn.href = `whatsapp://send?phone=${phone}&text=${defaultText}`;
                    btn.target = '_self';
                } else {
                    btn.href = `https://web.whatsapp.com/send?phone=${phone}&text=${defaultText}`;
                    btn.target = '_blank';
                }

                btn.addEventListener('click', function(e) {
                    if (isMobileDevice()) {
                        e.preventDefault();
                        // Open native WhatsApp App directly on mobile
                        window.location.href = `whatsapp://send?phone=${phone}&text=${defaultText}`;
                        
                        // Fallback to web/api in case WhatsApp protocol handler isn't registered
                        setTimeout(function() {
                            window.location.href = `https://api.whatsapp.com/send?phone=${phone}&text=${defaultText}`;
                        }, 1200);
                    }
                });
            }
        })();
    </script>
</x-layout>