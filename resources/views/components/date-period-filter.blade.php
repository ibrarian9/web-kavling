@props([
    'periodModel' => 'datePeriod',
    'startModel' => 'startDate',
    'endModel' => 'endDate',
    'periodValue' => 'all',
    'startDateValue' => '',
    'endDateValue' => '',
    'label' => 'Filter Periode Tanggal',
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    <div class="flex items-center gap-2 flex-wrap">
        <!-- Preset Dropdown Selector with Calendar Icon -->
        <div class="relative inline-flex items-center">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 z-10 flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <select 
                wire:model.live="{{ $periodModel }}" 
                class="select-clean text-xs font-bold bg-white text-slate-800 border border-slate-200/90 rounded-xl !pl-9 !pr-8 py-1.5 focus:border-teal-500 focus:ring-teal-500/20 shadow-2xs cursor-pointer"
            >
                <option value="all">Semua Periode Tanggal</option>
                <option value="today">Hari Ini (Today)</option>
                <option value="yesterday">Kemarin (Yesterday)</option>
                <option value="this_week">Minggu Ini (This Week)</option>
                <option value="this_month">Bulan Ini (This Month)</option>
                <option value="last_month">Bulan Lalu (Last Month)</option>
                <option value="this_year">Tahun Ini (This Year)</option>
                <option value="custom">Rentang Tanggal Custom...</option>
            </select>
        </div>

        <!-- Custom Date Range Inputs (Visible when 'custom' is selected) -->
        @if($periodValue === 'custom')
            <div class="flex items-center gap-1.5 bg-teal-50/70 p-1 rounded-xl border border-teal-200/80 text-xs shadow-2xs animate-fadeIn">
                <input 
                    type="date" 
                    wire:model.live="{{ $startModel }}" 
                    class="input-clean !py-1 !px-2.5 font-mono text-[11px] bg-white border border-slate-200 rounded-lg text-slate-800 font-bold focus:border-teal-500"
                    placeholder="Dari Tanggal"
                    title="Dari Tanggal"
                >
                <span class="text-teal-700 font-extrabold text-[10px] px-1">s/d</span>
                <input 
                    type="date" 
                    wire:model.live="{{ $endModel }}" 
                    class="input-clean !py-1 !px-2.5 font-mono text-[11px] bg-white border border-slate-200 rounded-lg text-slate-800 font-bold focus:border-teal-500"
                    placeholder="Sampai Tanggal"
                    title="Sampai Tanggal"
                >
            </div>
        @endif
    </div>
</div>
