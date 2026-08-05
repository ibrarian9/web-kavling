<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Perintah Kerja (SPK) - PT. Atlantik Perkasa Abadi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="max-w-md w-full bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden backdrop-blur-xl">
        <!-- Glow Effect -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header Status Badge -->
        <div class="text-center space-y-3">
            <div class="w-16 h-16 bg-emerald-500/10 border-2 border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/10">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-extrabold text-[10px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Dokumen SPK Terverifikasi Resmi
                </span>
                <h1 class="text-xl font-extrabold text-white tracking-tight mt-2">SURAT PERINTAH KERJA (SPK)</h1>
                <p class="text-xs text-slate-400 font-mono mt-1">{{ $spkNumber }}</p>
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-slate-900/80 rounded-2xl p-5 border border-slate-700/60 space-y-3 text-xs">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-800">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Perumahan / Proyek</span>
                <span class="font-bold text-white text-right">{{ $project->name }}</span>
            </div>

            <div class="flex items-center justify-between pb-2.5 border-b border-slate-800">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Kode Unit Objek</span>
                <span class="font-extrabold text-emerald-400 font-mono text-sm">UNIT {{ $unit->code }}</span>
            </div>

            <div class="flex items-center justify-between pb-2.5 border-b border-slate-800">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Nama Pekerja</span>
                <span class="font-bold text-white text-right">{{ $worker->name }} ({{ ucfirst($worker->type) }})</span>
            </div>

            <div class="flex items-center justify-between pb-2.5 border-b border-slate-800">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Total Kontrak Gaji</span>
                <span class="font-bold text-emerald-400 font-mono text-sm">Rp {{ number_format($payroll->agreed_salary, 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center justify-between pb-2.5 border-b border-slate-800">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Skema Penarikan</span>
                <span class="font-bold text-slate-200 capitalize">{{ $payroll->payment_frequency }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-slate-400 font-semibold uppercase text-[10px]">Tanggal Terbit SPK</span>
                <span class="font-mono text-slate-300">{{ date('d F Y', strtotime($payroll->created_at)) }}</span>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center pt-2 space-y-1">
            <p class="text-xs font-bold text-white">PT. ATLANTIK PERKASA ABADI</p>
            <p class="text-[10px] text-slate-500">Sistem Keuangan Manajemen Konstruksi & Properti</p>
        </div>
    </div>
</body>
</html>
