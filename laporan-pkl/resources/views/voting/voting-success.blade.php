<x-layout>
    <x-slot:title>Terima Kasih - Voting Berhasil Disimpan</x-slot:title>

    <style>
        @keyframes popIn {
            0% { transform: scale(0.85); opacity: 0; }
            50% { transform: scale(1.03); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes floatBg {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        .pop-in {
            animation: popIn 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .bg-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.6;
            z-index: -1;
            animation: floatBg 20s ease-in-out infinite;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #E0F2FE; }
        .blob-2 { bottom: -15%; right: -10%; width: 550px; height: 550px; background: #FEF3C7; animation-delay: -5s; }
    </style>

    <div class="w-full min-h-[calc(100vh-80px)] py-12 px-4 flex items-center justify-center relative overflow-hidden bg-slate-50/60">
        
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-blob blob-1"></div>
            <div class="bg-blob blob-2"></div>
        </div>

        <div class="max-w-lg w-full bg-white/85 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(76,163,230,0.12)] p-8 sm:p-12 text-center border border-white pop-in relative z-10">
            
            <!-- Logo Header -->
            <div class="relative inline-block mb-6">
                <div class="absolute -inset-2 bg-gradient-to-r from-[#4CA3E6]/30 to-[#DFA53A]/30 rounded-3xl blur-xl opacity-75"></div>
                <div class="relative p-3 rounded-2xl bg-white shadow-md border border-slate-100 flex items-center justify-center">
                    <img src="{{ asset('image/logoBKKBN.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('public/image/logoBKKBN.png') }}';" 
                         alt="Logo Kemendukbangga" 
                         class="w-14 h-14 object-contain">
                </div>
            </div>

            <!-- Success Icon -->
            <div class="relative w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 flex items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#4CA3E6] to-[#DFA53A] rounded-full opacity-20 blur-lg animate-pulse"></div>
                <div class="relative w-full h-full bg-gradient-to-tr from-emerald-400 to-teal-500 rounded-full flex items-center justify-center shadow-xl shadow-emerald-500/20 border-4 border-white text-white">
                    <i class="fas fa-check text-4xl sm:text-5xl"></i>
                </div>
            </div>
            
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200 mb-3">
                <i class="fas fa-shield-check"></i> SUARA BERHASIL DICATAT
            </span>

            <h1 class="text-3xl sm:text-4xl font-black text-slate-800 mb-3 tracking-tight">Terima Kasih!</h1>
            <p class="text-slate-500 font-medium text-sm sm:text-base mb-8 max-w-sm mx-auto leading-relaxed">
                Partisipasi suara Anda dalam pemilihan kandidat <strong class="text-slate-700">ASN KEREN Perwakilan BKKBN Aceh</strong> telah berhasil disimpan secara aman.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('voting.dashboard') }}" class="inline-flex items-center justify-center gap-2 w-full py-4 px-6 bg-gradient-to-r from-[#4CA3E6] via-[#3596E2] to-[#2B82C9] hover:from-[#3A8CC7] hover:to-[#1E6FA8] text-white font-extrabold text-base rounded-2xl shadow-xl shadow-[#4CA3E6]/25 hover:shadow-2xl hover:shadow-[#4CA3E6]/35 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-chart-pie"></i>
                    <span>Lihat Live Hasil Voting</span>
                </a>
                
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 font-bold text-sm rounded-2xl transition-all">
                    <i class="fas fa-house"></i>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </div>
</x-layout>
