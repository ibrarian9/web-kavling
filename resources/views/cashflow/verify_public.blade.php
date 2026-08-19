<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Laporan Arus Kas (Cashflow)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass-card {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="min-h-screen py-10 px-4 flex flex-col justify-center items-center relative overflow-hidden">
    <!-- Glowing background elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-2xl w-full glass-card rounded-2xl p-6 sm:p-8 shadow-2xl z-10 relative">
        <!-- Header status badge -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold tracking-wide uppercase mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Laporan Arus Kas Terverifikasi Resmi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Verifikasi Rekapitulasi Arus Kas</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Sistem Manajemen Properti & Konstruksi Kavling</p>
        </div>

        <!-- Meta info -->
        <div class="bg-slate-900/60 rounded-xl p-4 mb-6 border border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs sm:text-sm">
            <div>
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Cakupan Laporan</span>
                <span class="font-bold text-amber-400">
                    @if($viewMode === 'global')
                        Konsolidasi Global Seluruh Proyek & Unit
                    @elseif($unit)
                        Per-Unit: {{ $unit->code }} ({{ $unit->project->name }})
                    @elseif($project)
                        Per-Proyek: {{ $project->name }}
                    @else
                        Konsolidasi Laporan Arus Kas
                    @endif
                </span>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Periode Laporan</span>
                <span class="font-bold text-slate-200">
                    @if($month)
                        {{ format_id_month_year($month) }}
                    @else
                        Semua Periode Transaksi
                    @endif
                </span>
            </div>
        </div>

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="bg-emerald-950/40 p-4 rounded-xl border border-emerald-500/30 text-center">
                <span class="text-[11px] text-emerald-400 font-bold uppercase tracking-wider block mb-1">Total Kas Masuk</span>
                <p class="text-lg font-extrabold text-emerald-300 font-mono">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            </div>

            <div class="bg-rose-950/40 p-4 rounded-xl border border-rose-500/30 text-center">
                <span class="text-[11px] text-rose-400 font-bold uppercase tracking-wider block mb-1">Total Kas Keluar</span>
                <p class="text-lg font-extrabold text-rose-300 font-mono">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            </div>

            <div class="bg-blue-950/40 p-4 rounded-xl border border-blue-500/30 text-center">
                <span class="text-[11px] text-blue-400 font-bold uppercase tracking-wider block mb-1">Saldo Kas Bersih</span>
                <p class="text-lg font-extrabold text-blue-300 font-mono">Rp {{ number_format($netCashflow, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- System Verification Note -->
        <div class="bg-slate-900/80 rounded-xl p-5 border border-slate-800 mb-6 space-y-3">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Status Keabsahan Digital</h3>
                    <p class="text-xs text-slate-300">Dokumen PDF Laporan Arus Kas ini diterbitkan secara otomatis dan resmi oleh sistem keuangan terpusat. Terverifikasi mencakup {{ $transactionCount }} transaksi mutasi kas tercatat.</p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                <span>Sinkronisasi Terakhir</span>
                <span class="font-mono text-slate-200 font-semibold">{{ format_id_datetime($lastUpdated) }}</span>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-800 space-y-1">
            <p>Halaman verifikasi ini bersifat publik dan dapat diakses oleh siapapun sebagai jaminan keabsahan laporan arus kas.</p>
            <p>&copy; {{ date('Y') }} Official System Verification Portal</p>
        </div>
    </div>
</body>
</html>
