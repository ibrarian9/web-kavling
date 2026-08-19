<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keabsahan SPJB - Unit {{ $unit->code }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="max-w-lg w-full bg-slate-800 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <!-- Status Verification Badge Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-xl font-extrabold text-white tracking-tight">SURAT PERJANJIAN JUAL BELI (SPJB) SAH</h1>
            <p class="text-xs text-emerald-400 font-semibold bg-emerald-950/60 py-1 px-3 rounded-full border border-emerald-800/60 inline-block">
                ✓ Terverifikasi Asli & Terdaftar Resmi di Sistem
            </p>
        </div>

        <!-- Document Metadata Card -->
        <div class="bg-slate-900/90 border border-slate-700/60 rounded-2xl p-4 sm:p-5 space-y-3.5 text-xs">
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <span class="text-slate-400 font-medium">Nomor Dokumen SPJB:</span>
                <span class="font-mono font-bold text-amber-400 text-sm">{{ $spjbNumber }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <span class="text-slate-400 font-medium">Tanggal Diterbitkan:</span>
                <span class="font-bold text-slate-200">{{ format_id_full_date($doc->issued_at ?? $doc->created_at) }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-slate-800 pb-2.5">
                <span class="text-slate-400 font-medium">Proyek & Unit:</span>
                <span class="font-bold text-slate-200">{{ $project->name }} — Unit {{ $unit->code }}</span>
            </div>

            <!-- Identity Parties Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/50 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-emerald-400 block tracking-wider">Pihak Pertama (Penjual)</span>
                    <p class="font-bold text-white text-xs">{{ $doc->effective_seller_name }}</p>
                    <p class="text-[10px] text-slate-400 font-mono">NIK: {{ $doc->effective_seller_nik }}</p>
                    <p class="text-[10px] text-slate-400">{{ $doc->effective_seller_position }}</p>
                </div>
                <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/50 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-sky-400 block tracking-wider">Pihak Kedua (Pembeli)</span>
                    <p class="font-bold text-white text-xs">{{ $doc->buyer_name }}</p>
                    <p class="text-[10px] text-slate-400 font-mono">NIK: {{ $doc->effective_buyer_nik }}</p>
                    <p class="text-[10px] text-slate-400">Telp: {{ $doc->buyer_contact ?: '-' }}</p>
                </div>
            </div>

            <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/50 flex justify-between items-center">
                <span class="text-slate-400 font-medium">Total Harga Jual Deal:</span>
                <span class="font-mono font-extrabold text-emerald-400 text-sm">Rp {{ number_format($agreedPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="text-center pt-2 border-t border-slate-800 text-[11px] text-slate-500">
            <p>© {{ date('Y') }} PT. Atlantik Perkasa Abadi. Sistem Manajemen Kavling & Properti Resmi.</p>
        </div>
    </div>
</body>
</html>
