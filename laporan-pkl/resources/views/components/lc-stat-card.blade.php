{{-- lc-stat-card: Reusable stat card for Pengendalian Lapangan --}}
@php
    $colorMap = [
        'gold'    => ['badge' => '#b45309', 'track' => '#fef3c7', 'progress' => '#f59e0b'],
        'green'   => ['badge' => '#059669', 'track' => '#d1fae5', 'progress' => '#10b981'],
        'amber'   => ['badge' => '#d97706', 'track' => '#fef3c7', 'progress' => '#f59e0b'],
        'emerald' => ['badge' => '#0d9488', 'track' => '#ccfbf1', 'progress' => '#14b8a6'],
    ];
    $c = $colorMap[$color] ?? $colorMap['gold'];
    $pct = min(abs($percent ?? 0), 100);
    $circ = 2 * 3.14159 * 28;
@endphp

<div class="stat-card animate-fade-up">
    <p class="text-xs font-extrabold uppercase mb-3 tracking-wide" style="color: {{ $c['badge'] }}; font-family: 'Poppins', sans-serif;">{{ $label }}</p>

    {{-- SVG circular percent --}}
    <div class="flex justify-center mb-3">
        <svg width="72" height="72" viewBox="0 0 72 72">
            <circle cx="36" cy="36" r="28" fill="none" stroke="{{ $c['track'] }}" stroke-width="6"/>
            <circle cx="36" cy="36" r="28" fill="none" stroke="{{ $c['progress'] }}" stroke-width="6"
                stroke-dasharray="{{ $circ }}"
                stroke-dashoffset="{{ $circ * (1 - $pct / 100) }}"
                stroke-linecap="round" transform="rotate(-90 36 36)"
                style="transition: stroke-dashoffset 1.5s ease-out;"/>
            <text x="36" y="32" text-anchor="middle" font-size="13" font-weight="800" fill="{{ $c['badge'] }}" font-family="Inter,sans-serif">
                {{ number_format($percent, 2, ',', '.') }}%
            </text>
        </svg>
    </div>

    {{-- Data rows --}}
    <div class="space-y-1 mb-3">
        @foreach($rows as $row)
            <div class="flex justify-between text-xs">
                <span class="text-slate-500">{{ $row[0] }}</span>
                <span class="font-bold text-slate-700 num-id">{{ is_numeric($row[1]) ? number_format($row[1], 0, ',', '.') : $row[1] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Progress bar --}}
    <div class="h-2 rounded-full overflow-hidden" style="background: {{ $c['track'] }};">
        <div class="h-full rounded-full" style="width: {{ $pct }}%; background: {{ $c['progress'] }}; transition: width 1s ease-out;"></div>
    </div>
</div>