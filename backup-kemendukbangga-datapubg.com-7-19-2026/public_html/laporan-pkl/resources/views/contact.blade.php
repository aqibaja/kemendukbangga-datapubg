<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full sm:px-6 lg:px-12 py-4 sm:py-8 lg:py-10">

        <div
            class="max-w-3xl mx-auto bg-white border border-slate-100 rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-6 lg:p-8">

            <!-- Header -->
            <div class="mb-3 sm:mb-6">
                <h3 class="text-base sm:text-xl lg:text-2xl font-semibold text-slate-800 mb-1 sm:mb-2">Kirim Pesan</h3>
                <p class="text-xs sm:text-sm text-slate-500">Silakan isi form di bawah ini untuk menghubungi kami</p>
            </div>

            <form class="space-y-2 sm:space-y-4 lg:space-y-5" method="POST" action="{{ route('contact.send') }}">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full px-2 sm:px-4 py-1 sm:py-2.5 text-xs sm:text-base border border-slate-300 rounded-md text-slate-800 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder:text-slate-400 transition-all"
                        placeholder="Masukkan nama lengkap" name="nama" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        class="w-full px-2 sm:px-4 py-1 sm:py-2.5 text-xs sm:text-base border border-slate-300 rounded-md text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder:text-slate-400 transition-all"
                        name="email" placeholder="email@contoh.com" required>
                </div>

                <!-- Subjek -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-1.5">
                        Subjek <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full px-2 sm:px-4 py-1 sm:py-2.5 text-xs sm:text-base border border-slate-300 rounded-md text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder:text-slate-400 transition-all"
                        name="subjek" placeholder="Subjek pesan Anda" required>
                </div>

                <!-- Pesan -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-1.5">
                        Pesan <span class="text-red-500">*</span>
                    </label>
                    <textarea rows="4"
                        class="w-full px-2 sm:px-4 py-1 sm:py-2.5 text-xs sm:text-base border border-slate-300 rounded-md text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               placeholder:text-slate-400 transition-all resize-none"
                        placeholder="Tulis pesan Anda di sini..." name="pesan" required></textarea>
                    <p class="text-xs text-slate-500 mt-1.5">Minimal 10 karakter</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-2 sm:py-3 px-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white text-xs sm:text-base font-medium rounded-md 
                               transition-colors duration-200 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                               shadow-sm hover:shadow">
                        <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Kirim Pesan
                        </span>
                    </button>
                </div>

            </form>

            <!-- Info Footer -->
            <div class="mt-3 sm:mt-6 pt-3 sm:pt-6 border-t border-slate-100">
                <p class="text-xs sm:text-sm text-slate-600 text-center">
                    <span class="inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kami akan merespons pesan Anda dalam 1-2 hari kerja
                    </span>
                </p>
            </div>

        </div>

    </div>

</x-layout>