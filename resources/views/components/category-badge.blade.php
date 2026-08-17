@props([
    'category' => '',
    'label' => null,
    'size' => 'xs',
])

@php
    $raw = strtolower(trim((string)$category));

    $config = match($raw) {
        // --- KAS MASUK ---
        'pembayaran_cicilan_pembeli', 'cicilan_pembeli', 'cicilan', 'installment' => [
            'text' => $label ?? 'Cicilan Pembeli',
            'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200/90 shadow-2xs',
            'dot' => 'bg-emerald-500',
        ],
        'booking_fee', 'booking' => [
            'text' => $label ?? 'Booking Fee',
            'class' => 'bg-purple-50 text-purple-800 border-purple-200/90 shadow-2xs',
            'dot' => 'bg-purple-500',
        ],
        'pembayaran_dp', 'dp', 'uang_muka' => [
            'text' => $label ?? 'Uang Muka (DP)',
            'class' => 'bg-blue-50 text-blue-800 border-blue-200/90 shadow-2xs',
            'dot' => 'bg-blue-500',
        ],
        'penjualan_unit', 'penjualan', 'unit_sale' => [
            'text' => $label ?? 'Penjualan Unit',
            'class' => 'bg-teal-50 text-teal-800 border-teal-200/90 shadow-2xs',
            'dot' => 'bg-teal-500',
        ],
        'pemasukan_lain', 'pemasukan_lainnya', 'income_lain' => [
            'text' => $label ?? 'Pemasukan Lain',
            'class' => 'bg-cyan-50 text-cyan-800 border-cyan-200/90 shadow-2xs',
            'dot' => 'bg-cyan-500',
        ],

        // --- KAS KELUAR ---
        'operasional', 'operasional_kantor', 'operasional_proyek' => [
            'text' => $label ?? 'Operasional',
            'class' => 'bg-amber-50 text-amber-900 border-amber-300 shadow-2xs',
            'dot' => 'bg-amber-500',
        ],
        'gaji_karyawan', 'gaji', 'salary' => [
            'text' => $label ?? 'Gaji Karyawan',
            'class' => 'bg-rose-50 text-rose-800 border-rose-200/90 shadow-2xs',
            'dot' => 'bg-rose-500',
        ],
        'upah_tukang', 'pembayaran_tukang', 'upah', 'wage' => [
            'text' => $label ?? 'Upah Tukang',
            'class' => 'bg-orange-50 text-orange-800 border-orange-200/90 shadow-2xs',
            'dot' => 'bg-orange-500',
        ],
        'material', 'belanja_material', 'bahan_bangunan' => [
            'text' => $label ?? 'Belanja Material',
            'class' => 'bg-indigo-50 text-indigo-800 border-indigo-200/90 shadow-2xs',
            'dot' => 'bg-indigo-500',
        ],
        'pembelian_lahan', 'lahan_proyek', 'lahan' => [
            'text' => $label ?? 'Pembelian Lahan',
            'class' => 'bg-violet-50 text-violet-800 border-violet-200/90 shadow-2xs',
            'dot' => 'bg-violet-500',
        ],
        'pengeluaran_lain', 'lain_lain', 'lainnya', 'other_expense' => [
            'text' => $label ?? 'Pengeluaran Lain',
            'class' => 'bg-slate-100 text-slate-700 border-slate-200 shadow-2xs',
            'dot' => 'bg-slate-400',
        ],

        // --- KATEGORI PROPERTI / UNIT ---
        'kavling' => [
            'text' => $label ?? 'Kavling',
            'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200 shadow-2xs',
            'dot' => 'bg-emerald-500',
        ],
        'rumah' => [
            'text' => $label ?? 'Rumah',
            'class' => 'bg-blue-50 text-blue-800 border-blue-200 shadow-2xs',
            'dot' => 'bg-blue-500',
        ],
        'infrastruktur' => [
            'text' => $label ?? 'Infrastruktur',
            'class' => 'bg-indigo-50 text-indigo-800 border-indigo-200 shadow-2xs',
            'dot' => 'bg-indigo-500',
        ],

        default => [
            'text' => $label ?? ucwords(str_replace('_', ' ', $raw)),
            'class' => 'bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs',
            'dot' => 'bg-slate-400',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-0.5 rounded-lg font-bold text-[10px] inline-flex items-center gap-1.5 border w-fit leading-tight tracking-wide {$config['class']}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $config['dot'] }}"></span>
    <span>{{ $config['text'] }}</span>
</span>
