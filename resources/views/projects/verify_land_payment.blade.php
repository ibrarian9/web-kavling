<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Resi Pembayaran Lahan - PT. Atlantik Perkasa Abadi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 font-sans text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="max-w-md w-full bg-slate-800 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 font-extrabold text-2xl flex items-center justify-center mx-auto shadow-inner">
                A
            </div>
            <h1 class="font-extrabold text-white text-lg tracking-wide">PT. ATLANTIK PERKASA ABADI</h1>
            <p class="text-xs text-emerald-400 font-bold uppercase tracking-wider">Sistem Verifikasi Keabsahan Kuitansi Lahan</p>
        </div>

        <!-- Verification Status Badge -->
        <div class="bg-emerald-950/60 border border-emerald-500/50 rounded-2xl p-4 text-center space-y-1">
            <div class="w-10 h-10 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center mx-auto shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-base font-extrabold text-emerald-300">KUITANSI TERVERIFIKASI ASLI & SAH</h2>
            <p class="text-[11px] text-emerald-200/80">Dokumen pembayaran lahan ini tercatat secara resmi di database keuangan PT. Atlantik Perkasa Abadi.</p>
        </div>

        <!-- Details List -->
        <div class="bg-slate-900/80 border border-slate-700/60 rounded-2xl p-4 space-y-3 text-xs font-mono">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nomor Resi:</span>
                <span class="font-bold text-purple-400">RESI-LAHAN-{{ strtoupper(substr($payment->uuid, 0, 8)) }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nama Proyek:</span>
                <span class="font-bold text-white text-right">{{ $project->name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Tanggal Bayar:</span>
                <span class="font-bold text-slate-200">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Metode Bayar:</span>
                <span class="font-bold text-slate-200">{{ $payment->payment_method }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Dicatat Oleh:</span>
                <span class="font-bold text-slate-200">{{ $payment->creator->name ?? 'System' }}</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-purple-300 font-bold">Total Terbayar:</span>
                <span class="font-extrabold text-emerald-400 text-sm">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-2">
            <a href="{{ route('land-payment.receipt', $payment->uuid) }}" target="_blank" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-extrabold text-xs py-3 px-4 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh / Cetak Resi Kuitansi PDF</span>
            </a>

            @auth
                <a href="{{ route('projects.show', $project->id) }}" class="w-full bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold text-xs py-2.5 px-4 rounded-xl transition block text-center">
                    Kembali ke Detail Proyek
                </a>
            @endauth
        </div>

        <div class="text-center text-[10px] text-slate-500">
            &copy; {{ date('Y') }} PT. Atlantik Perkasa Abadi. All rights reserved.
        </div>
    </div>

</body>
</html>
