<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Invoice Pembayaran - PT. Atlantik Perkasa Abadi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 font-sans text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="max-w-md w-full bg-slate-800 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden">
        <!-- Glow Effect Background -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>

        <!-- Header Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 font-extrabold text-2xl shadow-lg mb-1">
                A
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">PT. ATLANTIK PERKASA ABADI</h1>
            <p class="text-xs text-slate-400">Sistem Verifikasi Dokumen & Invoice Pembayaran Resmi</p>
        </div>

        <!-- Verification Status Badge -->
        <div class="bg-emerald-950/80 border border-emerald-500/50 rounded-2xl p-4 text-center space-y-2 shadow-inner">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-500 text-slate-950 font-bold mb-1 shadow-md">
                ✓
            </div>
            <h2 class="text-sm font-extrabold text-emerald-300 uppercase tracking-wider">INVOICE PEMBAYARAN TERVERIFIKASI RESMI & ASLI</h2>
            <p class="text-[11px] text-emerald-400/90 leading-relaxed">
                Invoice Pembayaran Tanda Jadi / Booking Fee ini terdaftar secara sah dan valid dalam database sistem PT. Atlantik Perkasa Abadi.
            </p>
        </div>

        <!-- Details Card -->
        <div class="bg-slate-900/90 border border-slate-700/60 rounded-2xl p-5 space-y-3.5 text-xs">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nomor Invoice:</span>
                <span class="font-mono font-bold text-emerald-400">INV-BKG/{{ $booking->created_at ? $booking->created_at->format('Y/m') : date('Y/m') }}/{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nama Pembeli:</span>
                <span class="font-bold text-white text-right">{{ $booking->buyer_name }}</span>
            </div>

            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">No. Handphone:</span>
                <span class="font-mono text-slate-300">{{ $booking->buyer_phone }}</span>
            </div>

            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Proyek Properti:</span>
                <span class="font-semibold text-slate-200 text-right">{{ $project->name ?? 'Proyek Properti' }}</span>
            </div>

            @if($unit)
                <div class="flex justify-between border-b border-slate-800 pb-2">
                    <span class="text-slate-400">Unit Kavling:</span>
                    <span class="font-mono font-bold text-emerald-300">Unit {{ $unit->code }} ({{ ucfirst($unit->category ?? $unit->type) }})</span>
                </div>
            @endif

            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nominal Tanda Jadi:</span>
                <span class="font-mono font-extrabold text-emerald-400 text-sm">Rp {{ number_format($booking->booking_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Tanggal Booking:</span>
                <span class="font-mono text-slate-300">{{ $booking->booking_date ? $booking->booking_date->format('d/m/Y') : '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-400">Status Verifikasi:</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                    DIVERIFIKASI / LUNAS
                </span>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="text-center pt-2 text-[10px] text-slate-500 space-y-1">
            <p>© {{ date('Y') }} PT. Atlantik Perkasa Abadi System. All rights reserved.</p>
            <p class="font-mono">Pekanbaru, Riau, Indonesia</p>
        </div>
    </div>

</body>
</html>
