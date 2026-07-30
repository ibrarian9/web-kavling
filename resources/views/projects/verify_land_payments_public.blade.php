<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Laporan Pembayaran Lahan Proyek - {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 rounded-3xl p-6 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-purple-500/20 text-purple-400 border border-purple-500/30 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold border border-emerald-500/30 uppercase tracking-wider">
                Dokumen Terverifikasi Sah
            </span>
            <h1 class="text-xl font-extrabold text-white tracking-tight">Verifikasi Laporan Pembayaran Lahan</h1>
            <p class="text-xs text-slate-400">Hasil verifikasi resmi sistem manajemen Kavling & Properti</p>
        </div>

        <div class="bg-slate-900/80 rounded-2xl p-4 border border-slate-700 space-y-3 text-xs">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Proyek:</span>
                <span class="font-bold text-white">{{ $project->name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Lokasi:</span>
                <span class="font-semibold text-slate-300">{{ $project->location }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Harga Beli Lahan:</span>
                <span class="font-mono font-bold text-purple-400">Rp {{ number_format($project->total_project_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Total Dibayar:</span>
                <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Sisa Tagihan Lahan:</span>
                <span class="font-mono font-bold text-amber-400">Rp {{ number_format(max(0, $project->total_project_price - $totalPaid), 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="text-center text-[11px] text-slate-500 border-t border-slate-800 pt-4">
            Laporan ini secara sah diterbitkan dan dicatat dalam database terpusat aplikasi sistem manajemen Kavling & Properti.
        </div>
    </div>
</body>
</html>
