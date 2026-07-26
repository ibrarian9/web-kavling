<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Resi Gaji Pekerja - {{ $worker->name }}</title>
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
<div class="min-h-screen py-10 px-4 flex flex-col justify-center items-center relative overflow-hidden">
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
                Resi Gaji Terverifikasi Resmi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Bukti Pembayaran Gaji Worker</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Sistem Manajemen Proyek Kavling Real Estate</p>
        </div>

        <!-- Meta info -->
        <div class="bg-slate-900/60 rounded-xl p-4 mb-6 border border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <span class="text-xs text-slate-400 block">Nomor Referensi Resi (UUID)</span>
                <span class="font-mono text-sm font-bold text-amber-400">{{ $payment->uuid }}</span>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-slate-400 block">Tanggal Pembayaran</span>
                <span class="text-sm font-semibold text-slate-200">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</span>
            </div>
        </div>

        <!-- Worker & Unit details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Identitas Pekerja</span>
                <p class="text-lg font-bold text-white">{{ $worker->name }}</p>
                <p class="text-xs text-emerald-400 font-medium mt-0.5">{{ ucfirst($worker->type) }} {{ $worker->specialty ? '('.$worker->specialty.')' : '' }}</p>
                @if($worker->phone)
                    <p class="text-xs text-slate-400 mt-1">Telp: {{ $worker->phone }}</p>
                @endif
            </div>

            <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50">
                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider block mb-1">Lokasi Kerja & Unit</span>
                <p class="text-base font-bold text-white">{{ $project->name }}</p>
                <p class="text-sm font-semibold text-amber-400 mt-0.5">
                    {{ $unit ? 'Unit '.$unit->code.' ('.$unit->type.')' : 'Pekerjaan Area Umum Proyek' }}
                </p>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-slate-900/80 rounded-xl p-5 border border-slate-800 mb-6 space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Rincian Pembayaran</h3>

            <div class="flex justify-between items-center text-sm py-1 border-b border-slate-800">
                <span class="text-slate-400">Total Kesepakatan Gaji Unit</span>
                <span class="font-semibold text-slate-200">Rp {{ number_format($payroll->agreed_salary, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between items-center text-base py-2 font-bold text-emerald-400">
                <span>Gaji Dibayarkan ke Pekerja</span>
                <span class="text-xl">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</span>
            </div>

            <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                <span>Metode Pembayaran</span>
                <span class="px-2.5 py-1 rounded bg-slate-800 text-slate-200 font-semibold uppercase">
                    {{ str_replace('_', ' ', $payment->payment_method) }}
                </span>
            </div>
        </div>

        <!-- Struk Transfer Bank (Bukti Transfer) -->
        <div class="mb-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Foto Struk Transfer / Bukti Pembayaran</h3>
            @if($payment->receipt_photo_path)
                <div class="bg-slate-900/90 rounded-xl p-3 border border-slate-800 text-center">
                    <a href="{{ asset('storage/' . $payment->receipt_photo_path) }}" target="_blank" class="inline-block group relative overflow-hidden rounded-lg">
                        <img src="{{ asset('storage/' . $payment->receipt_photo_path) }}" alt="Struk Transfer Bank" class="max-h-80 w-auto mx-auto rounded-lg shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Klik untuk lihat ukuran penuh
                        </div>
                    </a>
                </div>
            @else
                <div class="bg-slate-900/40 rounded-xl p-6 border border-slate-800 text-center text-slate-500 text-sm">
                    Pembayaran diserahterimakan secara Tunai (Cash) atau tanpa lampiran dokumen digital.
                </div>
            @endif
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-slate-500 pt-4 border-t border-slate-800">
            <p>Resi verifikasi ini diterbitkan secara otomatis oleh Sistem Keuangan & Manajemen Konstruksi Kavling.</p>
            <p class="mt-1">&copy; {{ date('Y') }} Official Verified Document</p>
        </div>
    </div>
</div>
</body>
</html>
