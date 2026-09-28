<x-layout>
    <x-slot:title>ASN KEREN Voting - Kemendukbangga Aceh</x-slot:title>

    <style>
        /* Custom Premium Animations */
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
            opacity: 0.6;
            z-index: -1;
            animation: floatBg 20s ease-in-out infinite;
        }
        .blob-1 { top: -10%; left: -10%; width: 520px; height: 520px; background: #E0F2FE; }
        .blob-2 { bottom: -15%; right: -10%; width: 620px; height: 620px; background: #FEF3C7; animation-delay: -5s; }
        .blob-3 { top: 35%; left: 50%; width: 440px; height: 440px; background: #BAE6FD; animation-delay: -10s; }

        /* nip-input styles replaced with responsive Tailwind classes */
        /* Glassmorphism Input */
        .glass-input {
            background: rgba(255, 255, 255, 0.8);
            border: 1.5px solid rgba(226, 232, 240, 0.9);
            backdrop-filter: blur(12px);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }
        .glass-input:focus {
            background: #ffffff;
            border-color: #4CA3E6;
            box-shadow: 0 0 0 4px rgba(76, 163, 230, 0.2);
            outline: none;
        }

        /* TomSelect Theme overrides */
        .ts-control {
            border-radius: 1rem !important;
            padding: 1rem 1.25rem !important;
            border: 1.5px solid #e2e8f0 !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(10px) !important;
            font-size: 1rem !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s ease !important;
        }
        .ts-control.focus {
            border-color: #4CA3E6 !important;
            box-shadow: 0 0 0 4px rgba(76, 163, 230, 0.15) !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(16px) !important;
        }
        .ts-dropdown .active {
            background-color: #4CA3E6 !important;
            color: #ffffff !important;
        }
    </style>

    <div class="w-full py-12 sm:py-16 px-4 sm:px-6 flex flex-col items-center justify-start relative bg-slate-50/60 font-sans min-h-[calc(100vh-64px)]" x-data="votingWizard()">
        
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-blob blob-1"></div>
            <div class="bg-blob blob-2"></div>
            <div class="bg-blob blob-3"></div>
        </div>
        
        <!-- ================================ -->
        <!-- HEADER WITH LOGO & STEPPER       -->
        <!-- ================================ -->
        <div class="max-w-4xl w-full mb-8 sm:mb-10 z-10 text-center">
            
            <!-- Emblem Logo Kemendukbangga -->
            <div class="relative inline-block mb-4">
                <div class="absolute -inset-2 bg-gradient-to-r from-[#4CA3E6]/35 via-[#DFA53A]/25 to-[#4CA3E6]/35 rounded-3xl blur-xl opacity-75 animate-pulse"></div>
                <div class="relative p-3.5 sm:p-4 rounded-3xl bg-white/90 backdrop-blur-xl border border-white shadow-[0_12px_30px_rgba(76,163,230,0.18)] hover:shadow-[0_16px_35px_rgba(223,165,58,0.25)] hover:scale-105 transition-all duration-300 flex items-center justify-center">
                    <img src="{{ asset('image/logoBKKBN.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('public/image/logoBKKBN.png') }}';" 
                         alt="Logo Kemendukbangga" 
                         class="w-16 h-16 sm:w-20 sm:h-20 object-contain filter drop-shadow-sm">
                </div>
            </div>

            <!-- Organization Badge -->
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-[11px] sm:text-xs font-black tracking-widest uppercase bg-gradient-to-r from-[#4CA3E6]/10 via-[#DFA53A]/10 to-[#4CA3E6]/10 text-[#2B82C9] border border-[#4CA3E6]/25 shadow-sm mb-2.5">
                    <i class="fas fa-award text-[#DFA53A]"></i>
                    KEMENDUKBANGGA / BKKBN ACEH
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#2B82C9] via-[#4CA3E6] to-[#DFA53A] mb-2 tracking-tight drop-shadow-sm">
                ASN KEREN
            </h1>
            <p class="text-slate-500 font-medium text-sm sm:text-base mb-6 max-w-lg mx-auto">
                Pemilihan Kandidat ASN Berprestasi & Teladan Perwakilan BKKBN Aceh
            </p>

            <!-- Premium Interactive Stepper Progress -->
            <div class="max-w-2xl mx-auto">
                <!-- Status Pill & Counter -->
                <div class="flex items-center justify-between mb-2.5 px-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 border border-white/80 shadow-sm text-xs font-bold text-[#2B82C9]">
                        <span class="w-2 h-2 rounded-full bg-[#4CA3E6] animate-pulse"></span>
                        <span x-text="step === 1 ? 'Tahap 1: Identitas Pemilih' : (step === 2 ? 'Tahap 2: Otentikasi NIP' : (step === 3 ? 'Tahap 3: {{ $setting->golongan_1_name }}' : (step === 4 ? 'Tahap 4: {{ $setting->golongan_2_name }}' : (step === 5 ? 'Tahap 5: {{ $setting->golongan_3_name }}' : 'Tahap 6: Finalisasi Voting'))))"></span>
                    </div>
                    <span class="text-xs font-black text-slate-500 bg-white/80 px-3 py-1 rounded-full border border-white/80 shadow-sm" x-text="step + ' / 6'"></span>
                </div>

                <!-- Progress Track -->
                <div class="w-full bg-slate-200/70 rounded-full h-2.5 p-0.5 overflow-hidden backdrop-blur-sm border border-white/60 shadow-inner mb-3">
                    <div class="bg-gradient-to-r from-[#4CA3E6] via-[#5CB1ED] to-[#DFA53A] h-full rounded-full transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] relative overflow-hidden" :style="`width: ${(step / 6) * 100}%`">
                        <div class="absolute inset-0 bg-white/30 w-full" style="animation: shimmer 2s infinite;"></div>
                    </div>
                </div>

                <!-- Stepper Dots & Labels (Hidden on mobile) -->
                <div class="hidden sm:grid grid-cols-6 gap-1 text-center">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step >= 1 ? 'text-[#2B82C9] font-black' : 'text-slate-400'">IDENTITAS</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step >= 2 ? 'text-[#2B82C9] font-black' : 'text-slate-400'">VERIFIKASI</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step >= 3 ? 'text-[#2B82C9] font-black' : 'text-slate-400'">GOL. II</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step >= 4 ? 'text-[#2B82C9] font-black' : 'text-slate-400'">GOL. III</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step >= 5 ? 'text-[#2B82C9] font-black' : 'text-slate-400'">GOL. IV</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-wider transition-colors duration-300" :class="step === 6 ? 'text-[#DFA53A] font-black' : 'text-slate-400'">FINAL</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================ -->
        <!-- WIZARD CONTAINER                 -->
        <!-- ================================ -->
        <div class="max-w-5xl w-full relative z-10 min-h-[520px]">

            <!-- ================================ -->
            <!-- STEP 1: Identitas / Pilih Nama   -->
            <!-- ================================ -->
            <div x-show="step === 1" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-12 border border-white relative overflow-hidden">
                    
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Pilih Identitas Pemilih</h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-1.5 font-medium">Tentukan jenis kepegawaian dan pilih nama Anda pada daftar berikut.</p>
                    </div>
                    
                    <div class="space-y-6 sm:space-y-8 max-w-xl mx-auto">
                        <div>
                            <label class="block text-xs font-black text-slate-500 mb-3 uppercase tracking-wider">
                                <i class="fas fa-layer-group mr-1.5 text-[#4CA3E6]"></i> Kategori Pegawai
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="voter_type" value="perwakilan" x-model="voterType" @change="fetchVoters" class="peer sr-only">
                                    <div class="text-center p-5 sm:p-6 rounded-2xl border-2 border-slate-200/90 peer-checked:border-[#4CA3E6] peer-checked:bg-gradient-to-br peer-checked:from-[#4CA3E6] peer-checked:to-[#2B82C9] peer-checked:text-white peer-checked:shadow-xl peer-checked:shadow-[#4CA3E6]/25 text-slate-600 bg-white/70 hover:bg-white hover:border-slate-300 transition-all duration-300 relative overflow-hidden">
                                        <i class="fas fa-building text-3xl sm:text-4xl mb-2.5 transition-transform group-hover:scale-110 peer-checked:text-white"></i>
                                        <p class="font-black text-base sm:text-lg">Perwakilan</p>
                                        <p class="text-[11px] opacity-80 mt-0.5 peer-checked:text-white/90">Pegawai Kantor Perwakilan</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="voter_type" value="pkb" x-model="voterType" @change="fetchVoters" class="peer sr-only">
                                    <div class="text-center p-5 sm:p-6 rounded-2xl border-2 border-slate-200/90 peer-checked:border-[#4CA3E6] peer-checked:bg-gradient-to-br peer-checked:from-[#4CA3E6] peer-checked:to-[#2B82C9] peer-checked:text-white peer-checked:shadow-xl peer-checked:shadow-[#4CA3E6]/25 text-slate-600 bg-white/70 hover:bg-white hover:border-slate-300 transition-all duration-300 relative overflow-hidden">
                                        <i class="fas fa-users text-3xl sm:text-4xl mb-2.5 transition-transform group-hover:scale-110 peer-checked:text-white"></i>
                                        <p class="font-black text-base sm:text-lg">PKB / PLKB</p>
                                        <p class="text-[11px] opacity-80 mt-0.5 peer-checked:text-white/90">Penyuluh KB Lapangan</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div x-show="voterType" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4">
                            <label class="block text-xs font-black text-slate-500 mb-2.5 uppercase tracking-wider">
                                <i class="fas fa-user-tag mr-1.5 text-[#4CA3E6]"></i> Cari & Pilih Nama Anda
                            </label>
                            <select id="voter-select" class="w-full text-base">
                                <option value="">Ketik untuk mencari nama...</option>
                            </select>
                        </div>
                        
                        <div x-show="checkError" class="text-rose-600 text-sm font-bold bg-rose-50 border border-rose-200 p-4 rounded-2xl text-center flex items-center justify-center gap-2" x-text="checkError" x-transition></div>

                        <button @click="verifyAndNext" :disabled="!voterId || isLoading" class="w-full py-4 sm:py-5 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black text-base sm:text-lg rounded-2xl shadow-xl shadow-[#4CA3E6]/30 hover:shadow-2xl hover:shadow-[#4CA3E6]/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:transform-none disabled:hover:shadow-none disabled:cursor-not-allowed mt-4 flex items-center justify-center gap-2">
                            <span x-show="!isLoading" class="inline-flex items-center gap-2">
                                <span>Lanjutkan ke Otentikasi</span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span x-show="isLoading" class="inline-flex items-center gap-2">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                <span>Memeriksa Hak Suara...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STEP 2: Verifikasi NIP           -->
            <!-- ================================ -->
            <div x-show="step === 2" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full" style="display:none;">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-12 border border-white">
                    <div class="max-w-xl mx-auto text-center">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-tr from-[#4CA3E6]/15 via-[#4CA3E6]/25 to-[#DFA53A]/25 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-[#4CA3E6]/10 border-2 border-white ring-4 ring-[#4CA3E6]/10 text-[#2B82C9]">
                            <i class="fas fa-shield-halved text-3xl sm:text-4xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2 tracking-tight">Otentikasi Keamanan</h2>
                        <p class="text-slate-500 text-sm sm:text-base mb-8 font-medium">Masukkan <strong>NIP</strong> Anda untuk memverifikasi hak suara. Data terlindungi dan tersimpan secara rahasia.</p>

                        <div class="mb-8 text-left">
                            <div class="bg-gradient-to-r from-[#4CA3E6]/10 via-white to-white border border-[#4CA3E6]/25 rounded-2xl p-4 sm:p-5 mb-6 shadow-sm flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-tr from-[#4CA3E6] to-[#2B82C9] text-white rounded-2xl flex items-center justify-center font-bold shadow-md shadow-[#4CA3E6]/20 flex-shrink-0">
                                    <i class="fas fa-user-check text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] text-[#2B82C9] font-black uppercase tracking-wider mb-0.5">Voting Sebagai</p>
                                    <p class="font-black text-slate-900 text-base sm:text-lg leading-tight truncate" x-text="voterName"></p>
                                    <p class="text-xs text-slate-500 capitalize mt-0.5" x-text="'Kategori: ' + voterType"></p>
                                </div>
                            </div>

                            <label class="block text-xs font-black text-slate-500 mb-2.5 uppercase tracking-wider">
                                <i class="fas fa-lock mr-1.5 text-[#4CA3E6]"></i> NIP (Nomor Induk Pegawai)
                            </label>
                            <input 
                                type="text" 
                                x-model="nipInput" 
                                @input="nipError = ''"
                                @keydown.enter="verifyNipAndNext"
                                maxlength="30"
                                placeholder="Masukkan NIP Anda"
                                class="glass-input w-full px-4 sm:px-6 py-4 sm:py-5 rounded-2xl text-center text-slate-900 font-bold font-mono text-base sm:text-xl lg:text-2xl tracking-[0.1em] sm:tracking-[0.25em] focus:border-[#4CA3E6] focus:ring-4 focus:ring-[#4CA3E6]/15 transition-all duration-300"
                            >
                            <p class="text-[11px] text-slate-400 mt-2 text-center">Pastikan digit NIP sesuai dengan database kepegawaian BKKBN.</p>
                        </div>

                        <div x-show="nipError" class="text-rose-600 text-sm font-bold bg-rose-50 border border-rose-200 p-4 rounded-2xl mb-6 flex items-center justify-center gap-2" x-text="nipError" x-transition></div>

                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">
                            <button @click="step = 1; nipInput = ''; nipError = ''" class="flex-none px-6 py-4 text-slate-600 font-bold hover:bg-slate-200 hover:text-slate-800 rounded-2xl transition-all duration-300 bg-slate-100 flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-left"></i>
                                <span class="sm:hidden">Kembali</span>
                            </button>
                            <button @click="verifyNipAndNext" :disabled="!nipInput || isVerifyingNip" class="flex-1 py-4 sm:py-5 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black text-base sm:text-lg rounded-2xl shadow-xl shadow-[#4CA3E6]/30 hover:shadow-2xl hover:shadow-[#4CA3E6]/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:transform-none disabled:hover:shadow-none flex items-center justify-center gap-2">
                                <span x-show="!isVerifyingNip" class="inline-flex items-center gap-2">
                                    <span>Verifikasi & Buka Surat Suara</span>
                                    <i class="fas fa-unlock-keyhole"></i>
                                </span>
                                <span x-show="isVerifyingNip" class="inline-flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                    <span>Memverifikasi NIP...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STEP 3: Golongan 1              -->
            <!-- ================================ -->
            <div x-show="step === 3" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full" style="display: none;">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-10 border border-white">
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DFA53A]/15 text-[#B8811C] border border-[#DFA53A]/30 mb-2.5">
                            <i class="fas fa-medal"></i> Tahap 1 dari 3 (Golongan II)
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ $setting->golongan_1_name }}</h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pilih salah satu kandidat terbaik menurut Anda di kategori ini.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        <template x-for="c in candidates.golongan_1" :key="c.id">
                            <div @click="selection.gol1 = c.id; selection.gol1Name = c.nama"
                                :class="selection.gol1 === c.id 
                                    ? 'ring-4 ring-[#DFA53A] shadow-[0_15px_35px_rgba(223,165,58,0.25)] border-[#DFA53A]/60 bg-white scale-[1.02] sm:scale-105 z-10' 
                                    : 'bg-white/80 hover:bg-white hover:shadow-xl hover:border-slate-200 border-white/80'"
                                class="p-5 rounded-[2rem] border-2 transition-all duration-300 cursor-pointer group flex flex-col h-full relative">
                                
                                <!-- Selected Check Badge -->
                                <div x-show="selection.gol1 === c.id" x-transition.scale.origin.bottom.right 
                                     class="absolute -top-3 -right-3 bg-gradient-to-tr from-[#DFA53A] to-[#F3C76A] text-white w-9 h-9 rounded-full flex items-center justify-center shadow-lg z-20 border-2 border-white ring-2 ring-[#DFA53A]/30">
                                    <i class="fas fa-check text-base"></i>
                                </div>

                                <!-- Candidate Image -->
                                <div class="w-full bg-slate-100 rounded-2xl mb-4 overflow-hidden relative border border-slate-200 shadow-inner group/image aspect-[4/5] flex items-center justify-center">
                                    <img x-show="c.foto" :src="'{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" x-on:error="c.foto = null" class="absolute inset-0 w-full h-full object-contain p-2 sm:p-3 transition-transform duration-700 group-hover:scale-105">
                                    <div x-show="!c.foto" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 text-slate-300">
                                        <i class="fas fa-user-tie text-5xl sm:text-6xl text-slate-300/80 mb-1"></i>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kandidat ASN</span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    
                                    <!-- Zoom Button (Pill) -->
                                    <button type="button" x-show="c.foto" @click.stop="zoomedImage = '{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 bg-slate-900/80 hover:bg-slate-900 backdrop-blur-md text-white text-xs sm:text-sm font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-[0_8px_16px_rgba(0,0,0,0.25)] flex items-center gap-2 transition-all duration-300 transform hover:scale-105 hover:-translate-y-0.5 z-30 border border-white/10" title="Perbesar Gambar">
                                        <i class="fas fa-expand pointer-events-none"></i>
                                        <span class="pointer-events-none">Perbesar</span>
                                    </button>
                                </div>

                                <!-- Candidate Name & Details -->
                                <h3 class="font-black text-slate-900 text-center text-base leading-snug mb-1 group-hover:text-[#2B82C9] transition-colors line-clamp-2" x-text="c.nama"></h3>
                                <p class="text-xs font-semibold text-slate-500 text-center line-clamp-2 mt-auto" x-text="c.unsur"></p>

                                <!-- Bottom Status Button -->
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <div x-show="selection.gol1 === c.id" class="py-2 px-3 rounded-xl bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white text-xs font-black text-center flex items-center justify-center gap-1.5 shadow-sm">
                                        <i class="fas fa-check-circle"></i>
                                        <span>TERPILIH</span>
                                    </div>
                                    <div x-show="selection.gol1 !== c.id" class="py-2 px-3 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold text-center group-hover:bg-[#4CA3E6]/10 group-hover:text-[#2B82C9] transition-colors flex items-center justify-center gap-1">
                                        <span>Pilih Kandidat</span>
                                        <i class="fas fa-arrow-right text-[10px] opacity-60"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="mt-10 sm:mt-12 flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 pt-6 border-t border-slate-200/60">
                        <button @click="step--" class="px-6 py-4 text-slate-600 font-bold hover:bg-slate-200 hover:text-slate-900 rounded-2xl transition-all duration-300 bg-slate-100 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        <button @click="step++" :disabled="!selection.gol1" class="px-8 py-4 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black rounded-2xl shadow-xl shadow-[#4CA3E6]/25 hover:shadow-2xl hover:shadow-[#4CA3E6]/35 transition-all disabled:opacity-50 disabled:hover:shadow-none disabled:transform-none flex items-center justify-center gap-2">
                            <span>Lanjut ke Tahap 2</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STEP 4: Golongan 2              -->
            <!-- ================================ -->
            <div x-show="step === 4" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full" style="display: none;">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-10 border border-white">
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DFA53A]/15 text-[#B8811C] border border-[#DFA53A]/30 mb-2.5">
                            <i class="fas fa-medal"></i> Tahap 2 dari 3 (Golongan III)
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ $setting->golongan_2_name }}</h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pilih salah satu kandidat terbaik menurut Anda di kategori ini.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        <template x-for="c in candidates.golongan_2" :key="c.id">
                            <div @click="selection.gol2 = c.id; selection.gol2Name = c.nama"
                                :class="selection.gol2 === c.id 
                                    ? 'ring-4 ring-[#DFA53A] shadow-[0_15px_35px_rgba(223,165,58,0.25)] border-[#DFA53A]/60 bg-white scale-[1.02] sm:scale-105 z-10' 
                                    : 'bg-white/80 hover:bg-white hover:shadow-xl hover:border-slate-200 border-white/80'"
                                class="p-5 rounded-[2rem] border-2 transition-all duration-300 cursor-pointer group flex flex-col h-full relative">
                                
                                <!-- Selected Check Badge -->
                                <div x-show="selection.gol2 === c.id" x-transition.scale.origin.bottom.right 
                                     class="absolute -top-3 -right-3 bg-gradient-to-tr from-[#DFA53A] to-[#F3C76A] text-white w-9 h-9 rounded-full flex items-center justify-center shadow-lg z-20 border-2 border-white ring-2 ring-[#DFA53A]/30">
                                    <i class="fas fa-check text-base"></i>
                                </div>

                                <!-- Candidate Image -->
                                <div class="w-full bg-slate-100 rounded-2xl mb-4 overflow-hidden relative border border-slate-200 shadow-inner group/image aspect-[4/5] flex items-center justify-center">
                                    <img x-show="c.foto" :src="'{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" x-on:error="c.foto = null" class="absolute inset-0 w-full h-full object-contain p-2 sm:p-3 transition-transform duration-700 group-hover:scale-105">
                                    <div x-show="!c.foto" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 text-slate-300">
                                        <i class="fas fa-user-tie text-5xl sm:text-6xl text-slate-300/80 mb-1"></i>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kandidat ASN</span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    
                                    <!-- Zoom Button (Pill) -->
                                    <button type="button" x-show="c.foto" @click.stop="zoomedImage = '{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 bg-slate-900/80 hover:bg-slate-900 backdrop-blur-md text-white text-xs sm:text-sm font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-[0_8px_16px_rgba(0,0,0,0.25)] flex items-center gap-2 transition-all duration-300 transform hover:scale-105 hover:-translate-y-0.5 z-30 border border-white/10" title="Perbesar Gambar">
                                        <i class="fas fa-expand pointer-events-none"></i>
                                        <span class="pointer-events-none">Perbesar</span>
                                    </button>
                                </div>

                                <!-- Candidate Name & Details -->
                                <h3 class="font-black text-slate-900 text-center text-base leading-snug mb-1 group-hover:text-[#2B82C9] transition-colors line-clamp-2" x-text="c.nama"></h3>
                                <p class="text-xs font-semibold text-slate-500 text-center line-clamp-2 mt-auto" x-text="c.unsur"></p>

                                <!-- Bottom Status Button -->
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <div x-show="selection.gol2 === c.id" class="py-2 px-3 rounded-xl bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white text-xs font-black text-center flex items-center justify-center gap-1.5 shadow-sm">
                                        <i class="fas fa-check-circle"></i>
                                        <span>TERPILIH</span>
                                    </div>
                                    <div x-show="selection.gol2 !== c.id" class="py-2 px-3 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold text-center group-hover:bg-[#4CA3E6]/10 group-hover:text-[#2B82C9] transition-colors flex items-center justify-center gap-1">
                                        <span>Pilih Kandidat</span>
                                        <i class="fas fa-arrow-right text-[10px] opacity-60"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="mt-10 sm:mt-12 flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 pt-6 border-t border-slate-200/60">
                        <button @click="step--" class="px-6 py-4 text-slate-600 font-bold hover:bg-slate-200 hover:text-slate-900 rounded-2xl transition-all duration-300 bg-slate-100 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        <button @click="step++" :disabled="!selection.gol2" class="px-8 py-4 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black rounded-2xl shadow-xl shadow-[#4CA3E6]/25 hover:shadow-2xl hover:shadow-[#4CA3E6]/35 transition-all disabled:opacity-50 disabled:hover:shadow-none disabled:transform-none flex items-center justify-center gap-2">
                            <span>Lanjut ke Tahap 3</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STEP 5: Golongan 3               -->
            <!-- ================================ -->
            <div x-show="step === 5" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full" style="display: none;">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-10 border border-white">
                    <div class="text-center mb-8">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DFA53A]/15 text-[#B8811C] border border-[#DFA53A]/30 mb-2.5">
                            <i class="fas fa-medal"></i> Tahap 3 dari 3 (Golongan IV)
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ $setting->golongan_3_name }}</h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pilih salah satu kandidat terbaik menurut Anda di kategori ini.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        <template x-for="c in candidates.golongan_3" :key="c.id">
                            <div @click="selection.gol3 = c.id; selection.gol3Name = c.nama"
                                :class="selection.gol3 === c.id 
                                    ? 'ring-4 ring-[#DFA53A] shadow-[0_15px_35px_rgba(223,165,58,0.25)] border-[#DFA53A]/60 bg-white scale-[1.02] sm:scale-105 z-10' 
                                    : 'bg-white/80 hover:bg-white hover:shadow-xl hover:border-slate-200 border-white/80'"
                                class="p-5 rounded-[2rem] border-2 transition-all duration-300 cursor-pointer group flex flex-col h-full relative">
                                
                                <!-- Selected Check Badge -->
                                <div x-show="selection.gol3 === c.id" x-transition.scale.origin.bottom.right 
                                     class="absolute -top-3 -right-3 bg-gradient-to-tr from-[#DFA53A] to-[#F3C76A] text-white w-9 h-9 rounded-full flex items-center justify-center shadow-lg z-20 border-2 border-white ring-2 ring-[#DFA53A]/30">
                                    <i class="fas fa-check text-base"></i>
                                </div>

                                <!-- Candidate Image -->
                                <div class="w-full bg-slate-100 rounded-2xl mb-4 overflow-hidden relative border border-slate-200 shadow-inner group/image aspect-[4/5] flex items-center justify-center">
                                    <img x-show="c.foto" :src="'{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" x-on:error="c.foto = null" class="absolute inset-0 w-full h-full object-contain p-2 sm:p-3 transition-transform duration-700 group-hover:scale-105">
                                    <div x-show="!c.foto" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 text-slate-300">
                                        <i class="fas fa-user-tie text-5xl sm:text-6xl text-slate-300/80 mb-1"></i>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kandidat ASN</span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    
                                    <!-- Zoom Button (Pill) -->
                                    <button type="button" x-show="c.foto" @click.stop="zoomedImage = '{{ asset('laporan-pkl/storage/app/public') }}/' + c.foto" class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 bg-slate-900/80 hover:bg-slate-900 backdrop-blur-md text-white text-xs sm:text-sm font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-[0_8px_16px_rgba(0,0,0,0.25)] flex items-center gap-2 transition-all duration-300 transform hover:scale-105 hover:-translate-y-0.5 z-30 border border-white/10" title="Perbesar Gambar">
                                        <i class="fas fa-expand pointer-events-none"></i>
                                        <span class="pointer-events-none">Perbesar</span>
                                    </button>
                                </div>

                                <!-- Candidate Name & Details -->
                                <h3 class="font-black text-slate-900 text-center text-base leading-snug mb-1 group-hover:text-[#2B82C9] transition-colors line-clamp-2" x-text="c.nama"></h3>
                                <p class="text-xs font-semibold text-slate-500 text-center line-clamp-2 mt-auto" x-text="c.unsur"></p>

                                <!-- Bottom Status Button -->
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <div x-show="selection.gol3 === c.id" class="py-2 px-3 rounded-xl bg-gradient-to-r from-[#DFA53A] to-[#F3C76A] text-white text-xs font-black text-center flex items-center justify-center gap-1.5 shadow-sm">
                                        <i class="fas fa-check-circle"></i>
                                        <span>TERPILIH</span>
                                    </div>
                                    <div x-show="selection.gol3 !== c.id" class="py-2 px-3 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold text-center group-hover:bg-[#4CA3E6]/10 group-hover:text-[#2B82C9] transition-colors flex items-center justify-center gap-1">
                                        <span>Pilih Kandidat</span>
                                        <i class="fas fa-arrow-right text-[10px] opacity-60"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="mt-10 sm:mt-12 flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 pt-6 border-t border-slate-200/60">
                        <button @click="step--" class="px-6 py-4 text-slate-600 font-bold hover:bg-slate-200 hover:text-slate-900 rounded-2xl transition-all duration-300 bg-slate-100 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        <button @click="step++" :disabled="!selection.gol3" class="px-8 py-4 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black rounded-2xl shadow-xl shadow-[#4CA3E6]/25 hover:shadow-2xl hover:shadow-[#4CA3E6]/35 transition-all disabled:opacity-50 disabled:hover:shadow-none disabled:transform-none flex items-center justify-center gap-2">
                            <span>Tinjau Ringkasan Pilihan</span>
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================================ -->
            <!-- STEP 6: Konfirmasi & Submit      -->
            <!-- ================================ -->
            <div x-show="step === 6" 
                 x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" 
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 class="w-full" style="display: none;">
                 
                <div class="bg-white/80 backdrop-blur-2xl rounded-[2.25rem] shadow-[0_15px_40px_rgba(76,163,230,0.08)] p-6 sm:p-12 border border-white">
                    <div class="max-w-xl mx-auto">
                        
                        <!-- Glowing Header Icon -->
                        <div class="text-center mb-6 sm:mb-8">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 flex items-center justify-center">
                                <div class="absolute inset-0 bg-gradient-to-tr from-[#4CA3E6] to-[#DFA53A] rounded-2xl sm:rounded-3xl rotate-6 opacity-30 blur-md"></div>
                                <div class="relative w-full h-full bg-gradient-to-tr from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] rounded-2xl sm:rounded-3xl flex items-center justify-center shadow-xl shadow-[#4CA3E6]/30 border-2 border-white text-white">
                                    <i class="fas fa-clipboard-check text-2xl sm:text-3xl"></i>
                                </div>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Finalisasi Voting</h2>
                            <p class="text-slate-500 font-medium text-sm sm:text-base mt-1">Pastikan pilihan Anda sudah tepat sebelum disimpan secara permanen.</p>
                        </div>

                        <!-- Ringkasan Pemilih -->
                        <div class="bg-gradient-to-br from-white via-white to-blue-50/40 border border-slate-200/90 rounded-2xl p-5 sm:p-6 mb-6 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-gradient-to-b from-[#4CA3E6] to-[#DFA53A]"></div>
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-black uppercase tracking-wider bg-[#4CA3E6]/10 text-[#2B82C9] mb-1.5">
                                        <i class="fas fa-user-check"></i> DATA PEMILIH
                                    </span>
                                    <p class="font-black text-slate-900 text-lg sm:text-xl tracking-tight" x-text="voterName"></p>
                                    <p class="text-xs font-semibold text-slate-500 mt-1 capitalize flex items-center gap-2">
                                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span x-text="'Tipe Akun: ' + voterType"></span>
                                    </p>
                                </div>
                                <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#4CA3E6]/15 to-[#DFA53A]/15 border border-[#4CA3E6]/25 items-center justify-center text-[#2B82C9]">
                                    <i class="fas fa-id-card text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan Pilihan Kandidat -->
                        <div class="space-y-3.5 mb-8">
                            <!-- Golongan 1 -->
                            <div class="flex items-center gap-4 bg-white/95 backdrop-blur-sm p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#4CA3E6]/15 to-[#4CA3E6]/5 border border-[#4CA3E6]/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#2B82C9] font-black text-base">I</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $setting->golongan_1_name }}</p>
                                    <p class="font-black text-slate-800 text-base sm:text-lg leading-tight truncate mt-0.5" x-text="selection.gol1Name || 'Belum dipilih'"></p>
                                </div>
                                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check text-sm font-bold"></i>
                                </div>
                            </div>

                            <!-- Golongan 2 -->
                            <div class="flex items-center gap-4 bg-white/95 backdrop-blur-sm p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#4CA3E6]/15 to-[#4CA3E6]/5 border border-[#4CA3E6]/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#2B82C9] font-black text-base">II</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $setting->golongan_2_name }}</p>
                                    <p class="font-black text-slate-800 text-base sm:text-lg leading-tight truncate mt-0.5" x-text="selection.gol2Name || 'Belum dipilih'"></p>
                                </div>
                                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check text-sm font-bold"></i>
                                </div>
                            </div>

                            <!-- Golongan 3 -->
                            <div class="flex items-center gap-4 bg-white/95 backdrop-blur-sm p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#4CA3E6]/15 to-[#4CA3E6]/5 border border-[#4CA3E6]/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#2B82C9] font-black text-base">III</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $setting->golongan_3_name }}</p>
                                    <p class="font-black text-slate-800 text-base sm:text-lg leading-tight truncate mt-0.5" x-text="selection.gol3Name || 'Belum dipilih'"></p>
                                </div>
                                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check text-sm font-bold"></i>
                                </div>
                            </div>
                        </div>

                        <div x-show="submitError" class="text-rose-600 text-sm font-bold bg-rose-50 border border-rose-200 p-4 rounded-2xl mb-6 text-center" x-text="submitError" x-transition></div>

                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">
                            <button @click="step--" class="flex-none px-6 py-4 text-slate-600 font-bold hover:bg-slate-200 hover:text-slate-900 rounded-2xl transition-all duration-300 bg-slate-100 flex items-center justify-center gap-2" :disabled="isSubmitting">
                                <i class="fas fa-arrow-left"></i>
                                <span class="sm:hidden">Ubah Pilihan</span>
                            </button>
                            <button @click="submitVote" :disabled="isSubmitting" class="flex-1 py-4 sm:py-5 px-6 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-black text-base sm:text-lg rounded-2xl shadow-xl shadow-[#4CA3E6]/30 hover:shadow-2xl hover:shadow-[#4CA3E6]/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:transform-none disabled:hover:shadow-none flex items-center justify-center gap-2">
                                <span x-show="!isSubmitting" class="inline-flex items-center gap-2">
                                    <span>Konfirmasi & Kirim Suara</span>
                                    <i class="fas fa-paper-plane ml-1"></i>
                                </span>
                                <span x-show="isSubmitting" class="inline-flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                    <span>Menyimpan Suara ke Sistem...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Zoom Image -->
        <div x-show="zoomedImage" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="zoomedImage = null"></div>
            
            <!-- Image Container -->
            <div class="relative w-full max-w-4xl max-h-[90vh] flex flex-col items-center justify-center z-10" @click.stop>
                <button @click="zoomedImage = null" type="button" class="absolute -top-12 right-0 sm:-right-12 sm:top-0 text-white hover:text-rose-400 transition-colors w-10 h-10 flex items-center justify-center text-3xl">
                    <i class="fas fa-times"></i>
                </button>
                <img :src="zoomedImage" class="w-full h-full max-h-[85vh] object-contain rounded-xl shadow-2xl bg-black/50" alt="Kandidat Zoom">
            </div>
        </div>

    </div>

    <!-- Logic Controller Script -->
    <script>
        let tomSelectInstance = null;

        function votingWizard() {
            return {
                step: 1,
                voterType: '',
                voters: [],
                voterId: '',
                voterName: '',
                nipInput: '',
                candidates: { golongan_1: [], golongan_2: [], golongan_3: [] },
                selection: { gol1: null, gol1Name: '', gol2: null, gol2Name: '', gol3: null, gol3Name: '' },
                isLoading: false,
                isVerifyingNip: false,
                isSubmitting: false,
                checkError: '',
                nipError: '',
                submitError: '',
                zoomedImage: null,
                
                init() {
                    fetch('{{ url('/api/voting/candidates') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.candidates = data;
                        });
                        
                    this.$watch('voterId', (val) => {
                        if (val) {
                            const v = this.voters.find(x => x.id == val);
                            if (v) this.voterName = v.nama;
                        }
                    });
                },
                
                fetchVoters() {
                    this.voterId = '';
                    this.checkError = '';
                    
                    fetch(`{{ url('/api/voting/voters') }}?type=${this.voterType}`)
                        .then(res => res.json())
                        .then(data => {
                            this.voters = data;
                            this.$nextTick(() => {
                                if (tomSelectInstance) {
                                    tomSelectInstance.destroy();
                                }
                                
                                const selectEl = document.getElementById('voter-select');
                                selectEl.innerHTML = '<option value="">Ketik untuk mencari nama...</option>';
                                
                                this.voters.forEach(v => {
                                    const text = v.unsur ? `${v.nama} (${v.unsur})` : v.nama;
                                    selectEl.add(new Option(text, v.id));
                                });
                                
                                tomSelectInstance = new TomSelect('#voter-select', {
                                    create: false,
                                    sortField: { field: "text", direction: "asc" }
                                });
                                tomSelectInstance.on('change', (val) => {
                                    this.voterId = val;
                                });
                            });
                        });
                },
                
                verifyAndNext() {
                    this.isLoading = true;
                    this.checkError = '';
                    
                    fetch('{{ url('/api/voting/check-voted') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ type: this.voterType, id: this.voterId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isLoading = false;
                        if (data.voted) {
                            this.checkError = 'Akses ditolak: Data Anda sudah tercatat melakukan voting sebelumnya.';
                        } else {
                            this.step = 2;
                            this.nipInput = '';
                            this.nipError = '';
                        }
                    })
                    .catch(() => {
                        this.isLoading = false;
                        this.checkError = 'Koneksi terputus. Silakan coba beberapa saat lagi.';
                    });
                },

                verifyNipAndNext() {
                    if (!this.nipInput) return;
                    this.isVerifyingNip = true;
                    this.nipError = '';

                    fetch('{{ url('/api/voting/verify-nip') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            type: this.voterType,
                            id: this.voterId,
                            nip: this.nipInput
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isVerifyingNip = false;
                        if (data.valid) {
                            this.step = 3;
                        } else {
                            this.nipError = data.message || 'Kredensial tidak valid. Silakan periksa kembali NIP Anda.';
                        }
                    })
                    .catch(() => {
                        this.isVerifyingNip = false;
                        this.nipError = 'Koneksi terputus. Silakan coba beberapa saat lagi.';
                    });
                },
                
                submitVote() {
                    this.isSubmitting = true;
                    this.submitError = '';
                    
                    fetch('{{ route('voting.submit') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            voter_type: this.voterType,
                            voter_id: this.voterId,
                            voter_name: this.voterName,
                            nip: this.nipInput,
                            candidate_golongan_1: this.selection.gol1,
                            candidate_golongan_2: this.selection.gol2,
                            candidate_golongan_3: this.selection.gol3,
                        })
                    })
                    .then(res => {
                        // Tangani HTTP error secara eksplisit (419 CSRF, 500 Server Error, dll)
                        if (res.status === 419) {
                            throw new Error('Sesi Anda telah habis. Silakan refresh halaman dan coba lagi.');
                        }
                        if (res.status === 500) {
                            throw new Error('Terjadi kesalahan di server (500). Silakan hubungi admin.');
                        }
                        if (!res.ok) {
                            throw new Error(`Server menolak permintaan (kode: ${res.status}). Silakan coba lagi.`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        this.isSubmitting = false;
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            this.submitError = data.message || 'Gagal menyimpan data voting.';
                        }
                    })
                    .catch((err) => {
                        this.isSubmitting = false;
                        // Tampilkan pesan spesifik jika ada, atau pesan koneksi generik
                        this.submitError = err.message || 'Koneksi terputus saat menyimpan. Silakan coba lagi.';
                    });
                }

            }
        }
    </script>
</x-layout>
