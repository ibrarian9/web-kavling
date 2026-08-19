<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Invoice Setoran - {{ $unit->code }}</title>
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
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-2xl w-full glass-card rounded-2xl p-6 sm:p-8 shadow-2xl z-10 relative">
        <!-- Header status badge -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold tracking-wide uppercase mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Invoice Setoran Terverifikasi Resmi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Kuitansi / Invoice Setoran Cicilan</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Sistem Keuangan Properti & Kavling Real Estate</p>
        </div>

        <!-- Meta info -->
        <div class="bg-slate-900/60 rounded-xl p-4 mb-6 border border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <span class="text-xs text-slate-400 block">Nomor Referensi UUID</span>
                <span class="font-mono text-sm font-bold text-blue-400">{{ $payment->uuid }}</span>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-slate-400 block">Tanggal Setoran</span>
                <span class="text-sm font-semibold text-slate-200">{{ format_id_full_date($payment->payment_date) }}</span>
            </div>
        </div>

        <!-- Unit & Project details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Identitas Unit & Proyek</span>
                <p class="text-lg font-bold text-white">Unit {{ $unit->code }}</p>
                <p class="text-xs text-blue-400 font-medium mt-0.5">{{ $project->name }} ({{ ucfirst($unit->category) }} - {{ $unit->type }})</p>
            </div>

            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Pembeli & Metode Bayar</span>
                <p class="text-base font-bold text-white">{{ $installment->buyer_name }}</p>
                <p class="text-xs text-amber-400 font-semibold mt-0.5">
                    Metode: {{ $payment->payment_method }}
                </p>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-slate-900/80 rounded-xl p-5 border border-slate-800 mb-6 space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ringkasan Nominal Setoran</h3>

            <div class="flex justify-between items-center text-sm py-1 border-b border-slate-800">
                <span class="text-slate-400">Total Harga Deal Unit</span>
                <span class="font-semibold text-slate-200">Rp {{ number_format($installment->total_price, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-base py-2 font-bold text-emerald-400">
                <span>Jumlah Setoran Dibayarkan</span>
                <span class="text-xl">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-sm py-1 border-t border-slate-800">
                <span class="text-slate-400">Total Akumulasi Terbayar</span>
                <span class="font-semibold text-emerald-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-sm py-1">
                <span class="text-slate-400">Sisa Uang Belum Terbayar</span>
                <span class="font-extrabold text-amber-400 font-mono">Rp {{ number_format($remainingUnpaid, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-800">
            <p>Halaman verifikasi kuitansi/invoice ini diterbitkan secara resmi oleh Sistem Manajemen Keuangan Kavling.</p>
            <p class="mt-1">&copy; {{ date('Y') }} Official System Verification Portal</p>
        </div>
    </div>
</body>
</html>
