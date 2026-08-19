<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Rekapitulasi Cicilan Unit {{ $unit->code }} - {{ $installment->buyer_name }}</title>
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

    <div class="max-w-2xl w-full glass-card rounded-3xl p-6 sm:p-8 shadow-2xl z-10 relative">
        <!-- Header status badge -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs sm:text-sm font-bold tracking-wide uppercase mb-3">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Rekapitulasi Cicilan Terverifikasi Resmi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Kartu Kontrol Cicilan Konsumen</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Sistem Manajemen Informasi Properti & Kavling Terpadu</p>
        </div>

        <!-- Meta info -->
        <div class="bg-slate-900/60 rounded-2xl p-4 mb-5 border border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Status Dokumen</span>
                <span class="text-sm font-extrabold {{ $installment->status === 'lunas' || $installment->status === 'konversi_cash' ? 'text-emerald-400' : 'text-amber-400' }}">
                    {{ $installment->status === 'lunas' ? 'LUNAS TUNTAS' : ($installment->status === 'konversi_cash' ? 'LUNAS CASH' : 'CICILAN BERJALAN') }}
                </span>
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Terakhir Diperbarui</span>
                <span class="text-xs font-mono font-semibold text-slate-200">{{ format_id_full_date(now()) }}</span>
            </div>
        </div>

        <!-- Unit & Buyer details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Identitas Unit & Proyek</span>
                <p class="text-lg font-black text-white">UNIT {{ $unit->code }}</p>
                <p class="text-xs text-emerald-400 font-semibold mt-0.5">{{ $project->name }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Luas: {{ number_format($unit->land_area, 0, ',', '.') }} m² ({{ $unit->land_length }}m &times; {{ $unit->land_width }}m)</p>
            </div>

            <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Identitas Konsumen / Pembeli</span>
                <p class="text-base font-bold text-white">{{ $installment->buyer_name }}</p>
                <p class="text-xs text-blue-400 font-mono mt-0.5">{{ $installment->buyer_phone ?? '-' }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Tenor: {{ $installment->installment_count }} Bulan (Rp {{ number_format($installment->installment_amount, 0, ',', '.') }}/bln)</p>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-slate-900/80 rounded-2xl p-5 border border-slate-800 mb-5 space-y-2.5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ringkasan Finansial Kesepakatan</h3>

            <div class="flex justify-between items-center text-xs sm:text-sm py-1 border-b border-slate-800">
                <span class="text-slate-400">Total Harga Kesepakatan</span>
                <span class="font-mono font-bold text-slate-200">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-xs sm:text-sm py-1 border-b border-slate-800">
                <span class="text-slate-400">Uang Muka (DP)</span>
                <span class="font-mono font-bold text-blue-400">Rp {{ number_format($installment->down_payment, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-sm sm:text-base py-1.5 font-bold text-emerald-400">
                <span>Total Akumulasi Terbayar (DP + Cicilan)</span>
                <span class="font-mono text-lg">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-xs sm:text-sm py-1 border-t border-slate-800">
                <span class="text-slate-400">Sisa Saldo Tagihan</span>
                <span class="font-extrabold font-mono text-sm sm:text-base {{ $remainingBalance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                    Rp {{ number_format($remainingBalance, 0, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between items-center text-xs pt-1">
                <span class="text-slate-500">Progress Pelunasan</span>
                <span class="font-mono font-bold text-emerald-400">{{ $installment->progress_percentage }}%</span>
            </div>
        </div>

        <!-- Payments Table Snippet -->
        <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-800 mb-6">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">Histori Pembayaran Tercatat ({{ $payments->count() }} Setoran):</h4>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @if($installment->down_payment > 0)
                    <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-slate-800/70 border border-slate-700/60">
                        <div>
                            <span class="font-bold text-white block">Uang Muka (DP)</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $installment->start_date ? format_id_date($installment->start_date) : '-' }}</span>
                        </div>
                        <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($installment->down_payment, 0, ',', '.') }}</span>
                    </div>
                @endif

                @foreach($payments as $idx => $pay)
                    <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-slate-800/40 border border-slate-700/40">
                        <div>
                            <span class="font-bold text-slate-200 block">Setoran Cicilan #{{ $idx + 1 }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ format_id_date($pay->payment_date) }} | {{ $pay->payment_method }}</span>
                        </div>
                        <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-slate-500 pt-3 border-t border-slate-800">
            <p>Halaman verifikasi kartu kontrol cicilan ini diterbitkan secara sah oleh Sistem Manajemen Keuangan Properti & Kavling.</p>
            <p class="mt-1">&copy; {{ date('Y') }} Official Document Verification</p>
        </div>
    </div>
</body>
</html>
