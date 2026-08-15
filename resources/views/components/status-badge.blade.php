@props([
    'status' => '',
    'label' => null,
    'pulse' => true,
    'size' => 'xs',
])

@php
    $normalized = strtolower(trim((string)$status));

    // Map status key to display configuration
    $config = match($normalized) {
        'lunas', 'terbayar', 'paid', 'disetujui', 'acc', 'completed' => [
            'text' => $label ?? ($normalized === 'terbayar' ? 'TERBAYAR' : 'LUNAS'),
            'class' => 'bg-emerald-100/90 text-emerald-800 border border-emerald-200/80',
            'icon' => 'check',
            'icon_color' => 'text-emerald-600',
        ],
        'booked', 'booking' => [
            'text' => $label ?? 'BOOKED',
            'class' => 'bg-amber-100/90 text-amber-800 border border-amber-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-amber-600',
        ],
        'terjual', 'sold' => [
            'text' => $label ?? 'TERJUAL (ACC)',
            'class' => 'bg-blue-100/90 text-blue-800 border border-blue-200/80',
            'icon' => 'check',
            'icon_color' => 'text-blue-600',
        ],
        'tersedia', 'ready', 'available' => [
            'text' => $label ?? 'TERSEDIA',
            'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'icon' => 'pulse',
            'dot_color' => 'bg-emerald-500',
        ],
        'berjalan', 'dicicil', 'partial' => [
            'text' => $label ?? 'DICICIL',
            'class' => 'bg-blue-100/90 text-blue-800 border border-blue-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-blue-600',
        ],
        'belum_lunas', 'unpaid' => [
            'text' => $label ?? 'BELUM LUNAS',
            'class' => 'bg-amber-100/90 text-amber-800 border border-amber-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-amber-600',
        ],
        'hutang', 'hutang_toko' => [
            'text' => $label ?? 'HUTANG TOKO',
            'class' => 'bg-rose-100/90 text-rose-800 border border-rose-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-rose-600',
        ],
        'belum_dibayar' => [
            'text' => $label ?? 'BELUM DIBAYAR',
            'class' => 'bg-purple-100/90 text-purple-800 border border-purple-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-purple-600',
        ],
        'pending', 'menunggu', 'menunggu_persetujuan' => [
            'text' => $label ?? 'MENUNGGU APPROVAL',
            'class' => 'bg-amber-100/90 text-amber-800 border border-amber-200/80',
            'icon' => 'pulse',
            'dot_color' => 'bg-amber-600',
        ],
        'ditolak', 'rejected', 'batal', 'cancelled' => [
            'text' => $label ?? 'DITOLAK / BATAL',
            'class' => 'bg-slate-200 text-slate-700 border border-slate-300',
            'icon' => 'cross',
            'icon_color' => 'text-slate-500',
        ],
        'infrastruktur', 'fasum' => [
            'text' => $label ?? 'FASUM / INFRA',
            'class' => 'bg-indigo-100/90 text-indigo-800 border border-indigo-200/80',
            'icon' => 'dot',
            'dot_color' => 'bg-indigo-600',
        ],
        default => [
            'text' => $label ?? strtoupper(str_replace('_', ' ', $normalized)),
            'class' => 'bg-slate-100 text-slate-700 border border-slate-200',
            'icon' => 'none',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 {$config['class']}"]) }}>
    @if(($config['icon'] ?? '') === 'check')
        <svg class="w-3 h-3 {{ $config['icon_color'] ?? 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    @elseif(($config['icon'] ?? '') === 'cross')
        <svg class="w-3 h-3 {{ $config['icon_color'] ?? 'text-current' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    @elseif(($config['icon'] ?? '') === 'pulse' && $pulse)
        <span class="w-1.5 h-1.5 rounded-full {{ $config['dot_color'] ?? 'bg-current' }} animate-pulse"></span>
    @elseif(($config['icon'] ?? '') === 'dot')
        <span class="w-1.5 h-1.5 rounded-full {{ $config['dot_color'] ?? 'bg-current' }}"></span>
    @endif
    <span>{{ $config['text'] }}</span>
</span>
