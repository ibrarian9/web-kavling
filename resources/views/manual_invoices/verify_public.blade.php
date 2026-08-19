<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan Invoice - {{ $invoice->invoice_number }}</title>
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
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-2xl w-full glass-card rounded-2xl p-6 sm:p-8 shadow-2xl z-10 relative">
        <!-- Header status badge -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold tracking-wide uppercase mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Invoice Manual Terverifikasi Resmi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Verifikasi Keabsahan Invoice</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Sistem Keuangan Properti & Kavling Real Estate</p>
        </div>

        <!-- Meta info -->
        <div class="bg-slate-900/60 rounded-xl p-4 mb-6 border border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Nomor Invoice</span>
                <span class="font-mono text-sm font-bold text-teal-400">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Tanggal Invoice</span>
                <span class="text-sm font-semibold text-slate-200">{{ format_id_full_date($invoice->invoice_date) }}</span>
            </div>
        </div>

        <!-- Recipient & Project details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Identitas Penerima / Klien</span>
                <p class="text-lg font-bold text-white">{{ $invoice->recipient_name }}</p>
                @if($invoice->recipient_phone)
                    <p class="text-xs text-slate-400 mt-0.5">Telp: {{ $invoice->recipient_phone }}</p>
                @endif
            </div>

            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Cakupan Proyek & Unit</span>
                <p class="text-base font-bold text-white">{{ $invoice->project->name ?? 'Konsolidasi Global' }}</p>
                <p class="text-xs text-teal-400 font-semibold mt-0.5">
                    {{ $invoice->unit ? 'Unit ' . $invoice->unit->code : 'Transaksi Keuangan Umum' }}
                </p>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-slate-900/80 rounded-xl p-5 border border-slate-800 mb-6 space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rincian Nominal & Mutasi</h3>

            <div class="flex justify-between items-center text-sm py-1 border-b border-slate-800">
                <span class="text-slate-400">Tipe Mutasi</span>
                <span class="font-bold uppercase {{ $invoice->type === 'masuk' ? 'text-emerald-400' : 'text-rose-400' }}">
                    {{ $invoice->type === 'masuk' ? 'Kas Masuk (Pemasukan)' : 'Kas Keluar (Pengeluaran)' }}
                </span>
            </div>

            <div class="flex justify-between items-center text-base py-2 font-bold {{ $invoice->type === 'masuk' ? 'text-emerald-400' : 'text-rose-400' }}">
                <span>Nominal Invoice</span>
                <span class="text-xl font-mono">{{ $invoice->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-xs py-1 border-t border-slate-800 text-slate-400">
                <span>Metode Pembayaran</span>
                <span class="font-semibold text-slate-200">{{ $invoice->payment_method }}</span>
            </div>

            @if($invoice->description)
                <div class="pt-2 border-t border-slate-800 text-xs">
                    <span class="text-slate-400 block mb-0.5">Keterangan Tagihan:</span>
                    <p class="text-slate-200 italic">{{ $invoice->description }}</p>
                </div>
            @endif
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-800 space-y-1">
            <p>Halaman verifikasi ini diterbitkan secara otomatis dan resmi oleh Sistem Manajemen Keuangan Kavling.</p>
            <p>&copy; {{ date('Y') }} Official System Verification Portal</p>
        </div>
    </div>
</body>
</html>
