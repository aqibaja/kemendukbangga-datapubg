<x-layout>
    <x-slot:title>Detail Status Pemilih - ASN KEREN Kemendukbangga</x-slot:title>

    <style>
        @keyframes floatBg {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
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
    </style>

    <div class="w-full min-h-screen py-8 sm:py-14 px-4 sm:px-6 relative bg-slate-50/70 font-sans" x-data="{ tab: 'perwakilan', search: '' }">
        
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-blob blob-1"></div>
            <div class="bg-blob blob-2"></div>
        </div>

        <div class="max-w-6xl mx-auto space-y-8 relative z-10">
            
            <!-- Top Navigation Bar & Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('voting.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/80 hover:bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 shadow-sm transition font-bold text-sm">
                    <i class="fas fa-arrow-left text-[#4CA3E6]"></i>
                    <span>Kembali ke Dashboard</span>
                </a>

                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-[#4CA3E6]/10 to-[#DFA53A]/15 text-[#2B82C9] border border-[#4CA3E6]/25">
                    <i class="fas fa-users-gear text-[#DFA53A]"></i> MONITORING PARTISIPASI
                </span>
            </div>

            <div class="text-center max-w-2xl mx-auto">
                <h1 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#2B82C9] via-[#4CA3E6] to-[#DFA53A] mb-2 tracking-tight">
                    Status Partisipasi Pemilih
                </h1>
                <p class="text-slate-500 font-medium text-sm sm:text-base">
                    Daftar seluruh pegawai beserta status apakah telah memberikan hak suaranya.
                </p>
            </div>

            <!-- Table Card Container -->
            <div class="bg-white/85 backdrop-blur-xl rounded-[2.5rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-8 border border-white">
                
                <!-- Controls: Tabs & Search -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                    <!-- Segmented Tabs -->
                    <div class="flex bg-slate-100/90 p-1.5 rounded-2xl w-full sm:w-auto border border-slate-200/60 shadow-inner">
                        <button @click="tab = 'perwakilan'" 
                                :class="tab === 'perwakilan' ? 'bg-gradient-to-r from-[#4CA3E6] to-[#2B82C9] text-white shadow-md' : 'text-slate-500 hover:text-slate-800'" 
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl font-black text-sm transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-building"></i>
                            <span>Perwakilan</span>
                            <span class="text-xs px-2 py-0.5 rounded-full" :class="tab === 'perwakilan' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'">{{ $perwakilan->count() }}</span>
                        </button>
                        <button @click="tab = 'pkb'" 
                                :class="tab === 'pkb' ? 'bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white shadow-md' : 'text-slate-500 hover:text-slate-800'" 
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl font-black text-sm transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-users"></i>
                            <span>PKB / PLKB</span>
                            <span class="text-xs px-2 py-0.5 rounded-full" :class="tab === 'pkb' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'">{{ $pkb->count() }}</span>
                        </button>
                    </div>

                    <!-- Live Search Box -->
                    <div class="relative w-full sm:w-80">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari nama atau unsur..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200/90 rounded-2xl text-sm font-medium focus:outline-none focus:border-[#4CA3E6] focus:ring-4 focus:ring-[#4CA3E6]/15 transition shadow-sm">
                    </div>
                </div>

                <!-- Perwakilan List Table -->
                <div x-show="tab === 'perwakilan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/70 text-slate-500 text-xs font-black uppercase tracking-wider">
                                    <th class="py-4 px-5 w-16 text-center">No</th>
                                    <th class="py-4 px-5">Nama Karyawan</th>
                                    <th class="py-4 px-5">Unsur / Divisi</th>
                                    <th class="py-4 px-5 w-44 text-center">Status Suara</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($perwakilan as $index => $emp)
                                    <tr class="hover:bg-blue-50/40 transition-colors" x-show="'{{ strtolower($emp->nama) }}'.includes(search.toLowerCase()) || '{{ strtolower($emp->unsur) }}'.includes(search.toLowerCase())">
                                        <td class="py-3.5 px-5 text-center text-xs font-bold text-slate-400">{{ $loop->iteration }}</td>
                                        <td class="py-3.5 px-5 font-black text-slate-900 text-sm">{{ $emp->nama }}</td>
                                        <td class="py-3.5 px-5 text-xs text-slate-500 font-medium">{{ $emp->unsur ?? '-' }}</td>
                                        <td class="py-3.5 px-5 text-center">
                                            @if($emp->has_voted)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                    <i class="fas fa-check-circle"></i> Sudah Voting
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400">
                                                    <i class="fas fa-clock"></i> Belum Memilih
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PKB List Table -->
                <div x-show="tab === 'pkb'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/70 text-slate-500 text-xs font-black uppercase tracking-wider">
                                    <th class="py-4 px-5 w-16 text-center">No</th>
                                    <th class="py-4 px-5">Nama Karyawan PKB</th>
                                    <th class="py-4 px-5">Wilayah / Unsur</th>
                                    <th class="py-4 px-5 w-44 text-center">Status Suara</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pkb as $index => $emp)
                                    <tr class="hover:bg-amber-50/40 transition-colors" x-show="'{{ strtolower($emp->nama) }}'.includes(search.toLowerCase()) || '{{ strtolower($emp->unsur) }}'.includes(search.toLowerCase())">
                                        <td class="py-3.5 px-5 text-center text-xs font-bold text-slate-400">{{ $loop->iteration }}</td>
                                        <td class="py-3.5 px-5 font-black text-slate-900 text-sm">{{ $emp->nama }}</td>
                                        <td class="py-3.5 px-5 text-xs text-slate-500 font-medium">{{ $emp->unsur ?? '-' }}</td>
                                        <td class="py-3.5 px-5 text-center">
                                            @if($emp->has_voted)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                    <i class="fas fa-check-circle"></i> Sudah Voting
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400">
                                                    <i class="fas fa-clock"></i> Belum Memilih
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
