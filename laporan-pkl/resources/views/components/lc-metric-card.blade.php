{{-- lc-metric-card: Program-specific metric card for Pengendalian Lapangan --}}
@php
    $colorMap = [
        'emerald' => ['bg' => 'bg-emerald-900', 'labelBg' => 'bg-emerald-800', 'text' => 'text-emerald-100', 'border' => 'border-emerald-700', 'progress' => '#fbbf24'],
        'green'   => ['bg' => 'bg-green-800',   'labelBg' => 'bg-green-700',   'text' => 'text-green-100',   'border' => 'border-green-600',   'progress' => '#fbbf24'],
        'purple'  => ['bg' => 'bg-purple-900',  'labelBg' => 'bg-purple-800',  'text' => 'text-purple-100',  'border' => 'border-purple-700',  'progress' => '#fbbf24'],
        'orange'  => ['bg' => 'bg-orange-700',  'labelBg' => 'bg-orange-600',  'text' => 'text-orange-100',  'border' => 'border-orange-500',  'progress' => '#fbbf24'],
        'blue'    => ['bg' => 'bg-blue-900',    'labelBg' => 'bg-blue-800',    'text' => 'text-blue-100',    'border' => 'border-blue-700',    'progress' => '#fbbf24'],
    ];
    $c = $colorMap[$color] ?? $colorMap['emerald'];
    $pct = min(abs($percent ?? 0), 100);
    $circ = 2 * 3.14159 * 26;
@endphp

<div class="rounded-xl {{ $c['bg'] }} border {{ $c['border'] }} overflow-hidden">
    {{-- Label Bar --}}
    <div class="{{ $c['labelBg'] }} px-4 py-2">
        <p class="text-xs font-bold uppercase text-white tracking-wide" style="font-family: 'Inter', sans-serif;">{{ $label }}</p>
    </div>

    {{-- Body --}}
    <div class="p-4 flex gap-4 items-start">
        {{-- Left: Circular gauge --}}
        <div class="flex-shrink-0 flex flex-col items-center">
            <svg width="68" height="68" viewBox="0 0 68 68">
                <circle cx="34" cy="34" r="26" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="5"/>
                <circle cx="34" cy="34" r="26" fill="none" stroke="{{ $c['progress'] }}" stroke-width="5"
                    stroke-dasharray="{{ $circ }}"
                    stroke-dashoffset="{{ $circ * (1 - $pct / 100) }}"
                    stroke-linecap="round" transform="rotate(-90 34 34)"
                    style="transition: stroke-dashoffset 1.5s ease-out;"/>
                <text x="34" y="30" text-anchor="middle" font-size="13" font-weight="800" fill="#fbbf24" font-family="Inter,sans-serif">{{ number_format($pct, 1, ',', '.') }}%</text>
            </svg>
            @if($icon)
                <i class="fa-solid {{ $icon }} text-amber-400 text-sm mt-1"></i>
            @endif
        </div>

        {{-- Right: Data rows --}}
        <div class="flex-1 space-y-1.5 pt-1">
            @foreach($rows as $row)
                <div class="flex justify-between items-baseline">
                    <span class="text-white/70 text-xs">{{ $row[0] }}</span>
                    <span class="text-white font-semibold text-sm num-id">: {{ is_numeric($row[1]) ? number_format($row[1], 0, ',', '.') : $row[1] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>