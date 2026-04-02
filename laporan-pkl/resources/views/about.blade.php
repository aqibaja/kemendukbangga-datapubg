<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="w-full px-4 sm:px-6 lg:px-12 py-10 space-y-8">

        {{-- Contact Card --}}
        <section class="max-w-7xl mx-auto space-y-6">

            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m4 4h.01M12 20h.01M8 12h.01" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Informasi BKKBN</h2>
                        <p class="text-sm text-slate-500">Kontak resmi dan jam operasional.</p>
                    </div>
                </div>

                {{-- Grid kontak --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Alamat --}}
                    <div class="bg-slate-50 p-4 rounded-lg flex gap-3">
                        <div class="text-slate-500 mt-1">📍</div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Alamat</p>
                            <p class="text-sm text-slate-600 mt-1">
                                H89R+XQQ, Jl. T. Nyak Arief Lampineung,<br>
                                Banda Aceh City, Aceh 23115
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="bg-slate-50 p-4 rounded-lg flex gap-3">
                        <div class="text-slate-500 mt-1">✉️</div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Email</p>
                            <a href="mailto:aceh@bkkbn.go.id"
                                class="text-sm text-blue-600 mt-1 inline-block hover:underline">
                                aceh@bkkbn.go.id
                            </a>
                        </div>
                    </div>

                    {{-- Telepon --}}
                    <div class="bg-slate-50 p-4 rounded-lg flex gap-3">
                        <div class="text-slate-500 mt-1">📞</div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Telepon</p>
                            <a href="tel:+62811689537"
                                class="text-sm text-slate-600 mt-1 inline-block hover:underline">
                                +62 811-689-537
                            </a>
                        </div>
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="bg-slate-50 p-4 rounded-lg flex gap-3">
                        <div class="text-slate-500 mt-1">⏰</div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Jam Operasional</p>
                            <p class="text-sm text-slate-600 mt-1">
                                <b>Minggu Tutup</b> <br>
                                Senin 08.00–16.00 <br>
                                Selasa 08.00–16.00 <br>
                                Rabu 08.00–16.00 <br>
                                Kamis 08.00–16.00 <br>
                                Jumat 08.00–16.30 <br>
                                <b>Sabtu Tutup</b>
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Sosial media --}}
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <p class="text-sm font-medium text-slate-700 mb-2">Temukan Kami di Media Sosial</p>

                    <div class="flex items-center gap-3 flex-wrap">

                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/kemendukbangga_bkkbnaceh/" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border hover:shadow-sm">

                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <defs>
                                    <linearGradient id="igGrad" x1="0%" y1="0%" x2="100%"
                                        y2="100%">
                                        <stop offset="0%" stop-color="#f58529" />
                                        <stop offset="40%" stop-color="#dd2a7b" />
                                        <stop offset="70%" stop-color="#8134af" />
                                        <stop offset="100%" stop-color="#515bd4" />
                                    </linearGradient>
                                </defs>
                                <rect x="3" y="3" width="18" height="18" rx="5" fill="url(#igGrad)" />
                                <path d="M12 8.3a3.7 3.7 0 100 7.4 3.7 3.7 0 000-7.4z" fill="#fff" />
                                <circle cx="17.5" cy="6.5" r="0.9" fill="#fff" />
                            </svg>

                            <span class="text-sm text-slate-800">Instagram</span>
                        </a>

                        {{-- YouTube --}}
                        <a href="https://www.youtube.com/@bkkbnprovinsiaceh6614" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border hover:shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M23.5 6.2s-.2-1.6-.9-2.3c-.9-.9-1.9-.9-2.4-1C16.8 2.5 12 2.5 12 2.5s-4.8 0-8.2.4c-.5.1-1.6.1-2.4 1C.7 4.6.5 6.2.5 6.2S0 8.1 0 10v1.9c0 1.9.5 3.8.5 3.8s.2 1.6.9 2.3c.9.9 2.1.9 2.6 1.1 1.9.2 8 .4 8 .4s4.8 0 8.2-.4c.5-.1 1.6-.1 2.4-1 .7-.7.9-2.3.9-2.3s.5-1.9.5-3.8V10c0-1.9-.5-3.8-.5-3.8zM9.7 14.3V8.6l5.8 2.9-5.8 2.8z" />
                            </svg>
                            <span class="text-sm text-slate-800">YouTube</span>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/perwakilan.bkkbn.aceh" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border hover:shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07c0 4.99 3.66 9.12 8.44 9.94v-7.03H8.07v-2.91h2.37V9.41c0-2.35 1.4-3.64 3.55-3.64 1.03 0 2.1.18 2.1.18v2.31h-1.18c-1.16 0-1.52.72-1.52 1.46v1.75h2.59l-.41 2.91h-2.18v7.03C18.34 21.19 22 17.06 22 12.07z" />
                            </svg>
                            <span class="text-sm text-slate-800">Facebook</span>
                        </a>

                        {{-- Twitter --}}
                        <a href="https://x.com/bkkbn_aceh?lang=id" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border hover:shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-500" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0012 7.48v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                            </svg>
                            <span class="text-sm text-slate-800">Twitter</span>
                        </a>

                        {{-- TikTok --}}
                        <a href="https://www.tiktok.com/@kemendukbangga_bkkbnaceh" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border hover:shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12.5 2c.7 3.2 2.8 5.2 6 5.4v3.3c-2.3.2-4.3-.7-6-2.2v6.7a6.8 6.8 0 11-6.8-6.8c.4 0 .8 0 1.2.1v3.5a3.3 3.3 0 103.3 3.3V2h2.3z" />
                            </svg>
                            <span class="text-sm text-slate-800">TikTok</span>
                        </a>

                    </div>
                </div>
            </div>

            {{-- Map Card --}}
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">Lokasi Kami</h3>
                <p class="text-sm text-slate-500 mb-4">Klik dan geser peta untuk melihat area sekitar kantor kami.</p>

                <div class="rounded-xl overflow-hidden border">
                    <iframe class="w-full h-[420px] border-0"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1334.4885233598113!2d95.341942070579!3d5.569302034526662!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304037054f9ff285%3A0x64452fbded263f69!2sKantor%20BKKBN%20Aceh!5e1!3m2!1sen!2sid!4v1765084755051!5m2!1sen!2sid"
                        allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

        </section>

    </div>

</x-layout>