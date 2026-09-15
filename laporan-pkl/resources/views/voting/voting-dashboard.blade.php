<x-layout>
    <x-slot:title>Dashboard Hasil Voting ASN KEREN - Kemendukbangga Aceh</x-slot:title>

    <style>
        @keyframes floatBg {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .bg-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.55;
            z-index: -1;
            animation: floatBg 20s ease-in-out infinite;
        }
        .blob-1 { top: -5%; left: -10%; width: 500px; height: 500px; background: #E0F2FE; }
        .blob-2 { bottom: 10%; right: -10%; width: 550px; height: 550px; background: #FEF3C7; animation-delay: -5s; }
        .blob-3 { top: 40%; left: 40%; width: 420px; height: 420px; background: #BAE6FD; animation-delay: -10s; }
    </style>

    <div class="w-full min-h-screen py-10 sm:py-16 px-4 sm:px-6 relative bg-slate-50/70 font-sans">
        
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-blob blob-1"></div>
            <div class="bg-blob blob-2"></div>
            <div class="bg-blob blob-3"></div>
        </div>

        <div class="max-w-6xl mx-auto space-y-10 sm:space-y-12 relative z-10">
            
            <!-- ================================ -->
            <!-- HEADER SECTION                   -->
            <!-- ================================ -->
            <div class="text-center max-w-3xl mx-auto">
                <!-- Floating Emblem Logo -->
                <div class="relative inline-block mb-4">
                    <div class="absolute -inset-2 bg-gradient-to-r from-[#4CA3E6]/35 via-[#DFA53A]/25 to-[#4CA3E6]/35 rounded-3xl blur-xl opacity-75 animate-pulse"></div>
                    <div class="relative p-3 sm:p-3.5 rounded-3xl bg-white/90 backdrop-blur-xl border border-white shadow-[0_12px_30px_rgba(76,163,230,0.18)] flex items-center justify-center">
                        <img src="{{ asset('image/logo-kemendukbangga.png') }}" 
                             onerror="this.onerror=null; this.src='{{ asset('public/image/logo-kemendukbangga.png') }}';" 
                             alt="Logo Kemendukbangga" 
                             class="w-14 h-14 sm:w-16 sm:h-16 object-contain filter drop-shadow-sm">
                    </div>
                </div>

                <!-- Organization Badge -->
                <div>
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-[11px] sm:text-xs font-black tracking-widest uppercase bg-gradient-to-r from-[#4CA3E6]/10 via-[#DFA53A]/10 to-[#4CA3E6]/10 text-[#2B82C9] border border-[#4CA3E6]/25 shadow-sm mb-2.5">
                        <i class="fas fa-award text-[#DFA53A]"></i>
                        KEMENDUKBANGGA / BKKBN ACEH
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#2B82C9] via-[#4CA3E6] to-[#DFA53A] mb-2 tracking-tight">
                    DASHBOARD VOTING ASN KEREN
                </h1>
                <p class="text-slate-500 font-medium text-sm sm:text-base mb-5">
                    Live Result & Rekapitulasi Suara Pemilihan Kandidat ASN Teladan
                </p>

                <!-- Live Indicator & Refresh -->
                <div class="inline-flex items-center gap-3 bg-white/80 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/80 shadow-sm text-xs">
                    <span class="inline-flex items-center gap-1.5 font-extrabold text-emerald-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>LIVE COUNT</span>
                    </span>
                    <span class="text-slate-300">|</span>
                    <button onclick="window.location.reload()" class="text-slate-500 hover:text-[#2B82C9] font-bold inline-flex items-center gap-1 transition-colors">
                        <i class="fas fa-rotate-right"></i>
                        <span>Segarkan Data</span>
                    </button>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STATISTIK PARTISIPASI (KPI CARDS)-->
            <!-- ================================ -->
            @php
                $percentPerwakilan = $totalPerwakilan > 0 ? round(($votedPerwakilan / $totalPerwakilan) * 100) : 0;
                $percentPkb = $totalPkb > 0 ? round(($votedPkb / $totalPkb) * 100) : 0;
                $totalSemuaVoters = $totalPerwakilan + $totalPkb;
                $totalSemuaVoted = $votedPerwakilan + $votedPkb;
                $percentTotal = $totalSemuaVoters > 0 ? round(($totalSemuaVoted / $totalSemuaVoters) * 100) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Perwakilan Card -->
                <div class="bg-white/85 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-white/90 shadow-[0_15px_35px_rgba(76,163,230,0.08)] relative overflow-hidden group hover:shadow-[0_20px_40px_rgba(76,163,230,0.15)] transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#4CA3E6] to-[#2B82C9]"></div>
                    <i class="fas fa-building absolute -right-4 -bottom-4 text-8xl text-[#4CA3E6]/10 transition-transform group-hover:scale-110 pointer-events-none"></i>
                    
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-[#4CA3E6]/10 text-[#2B82C9] text-xs font-black">
                            <i class="fas fa-building"></i>
                            <span>PEGAWAI PERWAKILAN</span>
                        </div>
                        <span class="text-2xl sm:text-3xl font-black text-[#2B82C9]">{{ $percentPerwakilan }}%</span>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-3xl sm:text-4xl font-black text-slate-900">{{ $votedPerwakilan }}</span>
                            <span class="text-base sm:text-lg font-bold text-slate-400">/ {{ $totalPerwakilan }} Suara</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mb-4">
                            {{ $totalPerwakilan - $votedPerwakilan }} pegawai belum memberikan suara
                        </p>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 rounded-full h-3 p-0.5 overflow-hidden shadow-inner">
                            <div class="bg-gradient-to-r from-[#4CA3E6] to-[#2B82C9] h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden" style="width: {{ $percentPerwakilan }}%">
                                <div class="absolute inset-0 bg-white/30 w-full" style="animation: shimmer 2s infinite;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PKB Card -->
                <div class="bg-white/85 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-white/90 shadow-[0_15px_35px_rgba(223,165,58,0.08)] relative overflow-hidden group hover:shadow-[0_20px_40px_rgba(223,165,58,0.15)] transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#DFA53A] to-[#F3C76A]"></div>
                    <i class="fas fa-users absolute -right-4 -bottom-4 text-8xl text-[#DFA53A]/10 transition-transform group-hover:scale-110 pointer-events-none"></i>
                    
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-[#DFA53A]/15 text-[#B8811C] text-xs font-black">
                            <i class="fas fa-users"></i>
                            <span>PKB / PLKB LAPANGAN</span>
                        </div>
                        <span class="text-2xl sm:text-3xl font-black text-[#B8811C]">{{ $percentPkb }}%</span>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-3xl sm:text-4xl font-black text-slate-900">{{ $votedPkb }}</span>
                            <span class="text-base sm:text-lg font-bold text-slate-400">/ {{ $totalPkb }} Suara</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mb-4">
                            {{ $totalPkb - $votedPkb }} penyuluh belum memberikan suara
                        </p>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 rounded-full h-3 p-0.5 overflow-hidden shadow-inner">
                            <div class="bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden" style="width: {{ $percentPkb }}%">
                                <div class="absolute inset-0 bg-white/30 w-full" style="animation: shimmer 2s infinite;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Callout Bar -->
            <div class="bg-gradient-to-r from-[#4CA3E6]/10 via-white to-[#DFA53A]/10 rounded-3xl p-5 sm:p-6 border border-white/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-2xl bg-[#4CA3E6]/15 text-[#2B82C9] flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="fas fa-chart-simple"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base sm:text-lg">Partisipasi Keseluruhan: {{ $totalSemuaVoted }} dari {{ $totalSemuaVoters }} Pemilih ({{ $percentTotal }}%)</h3>
                        <p class="text-xs font-medium text-slate-500">Cek rincian pegawai yang sudah maupun yang belum memberikan suara.</p>
                    </div>
                </div>
                <a href="{{ route('voting.dashboard.voters') }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-[#2B82C9] font-black text-sm rounded-2xl border border-[#4CA3E6]/30 hover:border-[#4CA3E6] shadow-sm transition-all flex items-center justify-center gap-2 flex-shrink-0">
                    <i class="fas fa-list-check"></i>
                    <span>Status Daftar Pemilih</span>
                    <i class="fas fa-arrow-right text-xs opacity-70"></i>
                </a>
            </div>

            <!-- ================================ -->
            <!-- HASIL KANDIDAT PER GOLONGAN      -->
            <!-- ================================ -->
            <div class="space-y-16">
                
                <div class="text-center">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#4CA3E6]/10 text-[#2B82C9] border border-[#4CA3E6]/20 mb-2">
                        <i class="fas fa-ranking-star text-[#DFA53A]"></i>
                        REKAPITULASI RESMI
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Perolehan Suara Kandidat</h2>
                    <p class="text-slate-500 text-sm font-medium">Klasemen sementara berdasarkan suara yang telah masuk dan terverifikasi.</p>
                </div>

                @php
                    $golonganConfigs = [
                        [
                            'name' => $setting->golongan_1_name,
                            'badge' => 'Tahap 1: Golongan II',
                            'candidates' => $golongan1,
                            'voteField' => 'votes1_count',
                        ],
                        [
                            'name' => $setting->golongan_2_name,
                            'badge' => 'Tahap 2: Golongan III',
                            'candidates' => $golongan2,
                            'voteField' => 'votes2_count',
                        ],
                        [
                            'name' => $setting->golongan_3_name,
                            'badge' => 'Tahap 3: Golongan IV',
                            'candidates' => $golongan3,
                            'voteField' => 'votes3_count',
                        ]
                    ];
                @endphp

                @foreach($golonganConfigs as $gIndex => $gConfig)
                    @php
                        $sortedCandidates = $gConfig['candidates']->sortByDesc($gConfig['voteField'])->values();
                        $totalCategoryVotes = $sortedCandidates->sum($gConfig['voteField']);
                        $highestVote = $sortedCandidates->first() ? $sortedCandidates->first()->{$gConfig['voteField']} : 0;
                    @endphp

                    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] p-6 sm:p-10 border border-white/90 shadow-[0_15px_40px_rgba(76,163,230,0.06)] relative">
                        
                        <!-- Section Category Header with Plenty of Spacing to prevent any overlap -->
                        <div class="text-center pb-8 border-b border-slate-100 mb-8 sm:mb-12">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#DFA53A]/15 text-[#B8811C] border border-[#DFA53A]/30 mb-2">
                                <i class="fas fa-medal"></i> {{ $gConfig['badge'] }}
                            </span>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $gConfig['name'] }}</h3>
                            <p class="text-xs font-semibold text-slate-400 mt-1">
                                Total Suara Masuk Kategori Ini: <strong class="text-slate-700">{{ $totalCategoryVotes }} Suara</strong>
                            </p>
                        </div>

                        <!-- Podium Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-end max-w-5xl mx-auto pt-4">
                            @foreach($sortedCandidates as $index => $candidate)
                                @php
                                    $votes = $candidate->{$gConfig['voteField']};
                                    $percent = $totalCategoryVotes > 0 ? round(($votes / $totalCategoryVotes) * 100) : 0;
                                    $isWinner = ($index === 0 && $votes > 0);
                                @endphp

                                <div class="flex flex-col items-center rounded-3xl p-6 transition-all duration-500 relative w-full h-full
                                    {{ $index === 0 ? 'order-1 md:order-2 bg-gradient-to-b from-amber-50/60 via-white to-white ring-4 ring-[#DFA53A] shadow-[0_20px_45px_rgba(223,165,58,0.22)] md:-translate-y-4 z-10' : '' }}
                                    {{ $index === 1 ? 'order-2 md:order-1 bg-white/90 border-2 border-slate-200/80 shadow-md hover:shadow-xl' : '' }}
                                    {{ $index === 2 ? 'order-3 md:order-3 bg-white/90 border-2 border-slate-200/80 shadow-md hover:shadow-xl' : '' }}">
                                    
                                    <!-- Rank Floating Badge -->
                                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-20">
                                        @if($isWinner)
                                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white shadow-lg shadow-[#DFA53A]/40 border border-white">
                                                <i class="fas fa-crown text-amber-100"></i> SUARA TERBANYAK
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                                                Peringkat #{{ $index + 1 }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Candidate Avatar / Photo -->
                                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl mb-4 mt-3 overflow-hidden relative border-4 {{ $isWinner ? 'border-[#DFA53A] ring-4 ring-[#DFA53A]/20' : 'border-slate-100' }} shadow-md bg-slate-100 flex-shrink-0">
                                        @if($setting->is_result_visible)
                                            @if($candidate->foto)
                                                <img src="{{ asset('laporan-pkl/storage/app/public/' . $candidate->foto) }}" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-slate-100 text-slate-300\'><i class=\'fas fa-user-tie text-4xl\'></i></div>';" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-300">
                                                    <i class="fas fa-user-tie text-4xl mb-1"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-slate-200 to-slate-300 text-slate-600">
                                                <i class="fas fa-user-shield text-4xl mb-1 opacity-70"></i>
                                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500">Dirahasiakan</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Candidate Info -->
                                    <div class="text-center w-full mb-4 flex-1 flex flex-col justify-start">
                                        @if($setting->is_result_visible)
                                            <h4 class="font-black text-slate-900 text-lg sm:text-xl leading-snug mb-1 line-clamp-2">
                                                {{ $candidate->nama }}
                                            </h4>
                                            <p class="text-xs font-semibold text-slate-500 line-clamp-2">{{ $candidate->unsur }}</p>
                                        @else
                                            <h4 class="font-black text-slate-400 text-xl tracking-widest mt-2 mb-1">??????</h4>
                                            <p class="text-xs font-semibold text-slate-400">Hasil dirahasiakan oleh panitia</p>
                                        @endif
                                    </div>

                                    <!-- Vote Bar Card -->
                                    <div class="w-full mt-auto pt-3 border-t {{ $isWinner ? 'border-amber-200/60' : 'border-slate-100' }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-bold text-slate-400">Perolehan</span>
                                            <span class="text-xs font-black {{ $isWinner ? 'text-[#B8811C]' : 'text-slate-600' }}">{{ $percent }}%</span>
                                        </div>
                                        <div class="w-full py-3 px-4 rounded-2xl text-center font-black {{ $isWinner ? 'bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white shadow-md shadow-[#DFA53A]/25' : 'bg-slate-100 text-slate-800' }}">
                                            <span class="text-2xl leading-none">{{ $votes }}</span>
                                            <span class="text-xs font-bold opacity-90 ml-1">Suara</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Navigation -->
            <div class="pt-8 pb-12 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('voting.show') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black text-base rounded-2xl shadow-xl shadow-[#4CA3E6]/25 hover:shadow-2xl hover:shadow-[#4CA3E6]/35 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-vote-yea"></i>
                    <span>Ikuti Pemilihan Sekarang</span>
                </a>
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-4 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-2xl border border-slate-200/80 shadow-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-house"></i>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
            
        </div>
    </div>
</x-layout>
