<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Resi Belanja Material #{{ $material->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700/80 rounded-3xl p-6 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-16 h-16 bg-amber-500/20 text-amber-400 rounded-2xl mx-auto flex items-center justify-center border border-amber-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20 tracking-wider">
                Dokumen Kuitansi Terverifikasi Valid
            </span>
            <h1 class="text-xl font-extrabold text-white">Kuitansi Belanja Material Resmi</h1>
            <p class="text-xs text-slate-400">Pembelian material ini sah tercatat dalam sistem manajemen proyek & unit.</p>
        </div>

        <div class="bg-slate-900/80 border border-slate-700/60 rounded-2xl p-4 space-y-3 text-xs">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Nama Material / Barang:</span>
                <strong class="text-amber-400 font-bold">{{ $material->item_name }}</strong>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Jumlah & Satuan:</span>
                <strong class="font-mono text-slate-200 font-bold">{{ number_format($material->quantity, 0, ',', '.') }} {{ $material->unit_measure }}</strong>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Harga Satuan:</span>
                <strong class="font-mono text-slate-200">Rp {{ number_format($material->unit_price, 0, ',', '.') }}</strong>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Proyek / Unit:</span>
                <strong class="text-slate-200 font-semibold">{{ $project->name ?? 'Global' }} (Unit {{ $unit->code ?? '-' }})</strong>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-400">Tanggal Pembelian:</span>
                <strong class="font-mono text-slate-300">{{ format_id_full_date($material->purchase_date) }}</strong>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-slate-300 font-bold">Total Pembelian:</span>
                <strong class="font-mono text-amber-400 font-extrabold text-base">Rp {{ number_format($material->total_price, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-500 space-y-1">
            <p>© {{ date('Y') }} Sistem Manajemen Kavling & Properti Resmi.</p>
            <p>Halaman ini dapat diakses oleh publik sebagai bukti keabsahan kuitansi.</p>
        </div>
    </div>
</body>
</html>
