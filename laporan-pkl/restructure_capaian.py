import re

with open('resources/views/laporan-capaian.blade.php', 'r') as f:
    content = f.read()

# I will find the @elseif(request('tipe') == 'capaian_program') block and replace its contents.
# But instead of regexing the whole block, I will replace specific sections.

# First, Fasyankes grid:
# from: <div class="grid grid-cols-2 md:grid-cols-5 gap-4 gap-y-10 mt-6 px-2 pb-6"> ... @endforeach </div>
fasyankes_pattern = re.compile(r'<div class="grid grid-cols-2 md:grid-cols-5 gap-4 gap-y-10 mt-6 px-2 pb-6">.*?@endforeach\s*</div>', re.DOTALL)
fasyankes_replacement = """<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6 px-2 pb-6">
                            @php
                                $faskes = [
                                    ['title' => 'Pemerintah', 'svg' => '/image/pemerintah_3d.png?v=' . time(), 'data' => $d['cakupan_fasyankes']['pemerintah'] ?? []],
                                    ['title' => 'Jaringan', 'svg' => '/image/jaringan_3d.png?v=' . time(), 'data' => $d['cakupan_fasyankes']['jaringan'] ?? []],
                                    ['title' => 'Swasta', 'svg' => '/image/swasta_3d.png?v=' . time(), 'data' => $d['cakupan_fasyankes']['swasta'] ?? []],
                                    ['title' => 'PMB Setara', 'svg' => '/image/pmb_setara_3d.png?v=' . time(), 'data' => $d['cakupan_fasyankes']['pmb_setara'] ?? []],
                                    ['title' => 'PMB Jejaring', 'svg' => '/image/pmb_jejaring_3d.png?v=' . time(), 'data' => $d['cakupan_fasyankes']['pmb_jejaring'] ?? []]
                                ];
                            @endphp
                            @foreach($faskes as $i => $f)
                            <div class="dark-green-card rounded-xl p-3 text-sm relative flex flex-col justify-center">
                                <div class="flex items-center mb-2 border-b border-teal-700 pb-2">
                                    <div class="w-10 h-10 rounded-full border-2 border-yellow-400 overflow-hidden bg-teal-900 shrink-0 mr-2 flex items-center justify-center">
                                        <img src="{{ $f['svg'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <h4 class="font-bold text-white text-xs md:text-[13px] leading-tight">{{ $f['title'] }}</h4>
                                </div>
                                <div class="text-gray-200 text-xs mt-auto">
                                    <div class="flex justify-between mb-1"><span>Ada</span> <span>= {{ number_format($f['data']['ada'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mb-1"><span>Lapor</span> <span>= {{ number_format($f['data']['lapor'] ?? 0, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between mt-2 pt-2 border-t border-teal-700 text-white font-bold"><span>Persentase</span> <span class="text-yellow-300">{{ number_format($f['data']['persentase'] ?? 0, 2, ',', '.') }}%</span></div>
                                </div>
                            </div>
                            @endforeach
                        </div>"""
content = fasyankes_pattern.sub(fasyankes_replacement, content)


with open('resources/views/laporan-capaian.blade.php', 'w') as f:
    f.write(content)

print("Fasyankes rewritten.")

