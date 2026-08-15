<!-- Filter & Search Bar -->
<div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
    <!-- Header Section for Filter Bar -->
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <div class="p-1.5 rounded-lg bg-teal-50 text-teal-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <h3 class="font-extrabold text-slate-800 text-xs sm:text-sm">Filter & Pencarian Aktivitas</h3>
        </div>

        @if($search || $filter_user_id || $filter_project_id || $filter_lead_stage || $filter_lead_source || $filter_start_date || $filter_end_date)
            <button wire:click="resetFilters" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filter</span>
            </button>
        @endif
    </div>

    <!-- Main Search Bar -->
    <x-search-input placeholder="Cari nama klien, nomor telepon, atau catatan aktivitas..." containerClass="relative w-full" />

    <!-- Dropdown Filter Controls -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs pt-1">
        <!-- Filter Sales Marketing (Founder / Supervisor Only) -->
        @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
            <div>
                <label class="block font-bold text-slate-600 mb-1 text-[11px] flex items-center gap-1">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Sales Marketing</span>
                </label>
                <select wire:model.live="filter_user_id" class="w-full px-3 py-2 bg-slate-50/80 border rounded-xl text-xs font-medium transition focus:outline-hidden focus:bg-white focus:border-teal-500 {{ $filter_user_id ? 'border-teal-500 bg-teal-50/40 font-bold text-teal-900' : 'border-slate-200 text-slate-700' }}">
                    <option value="">Semua Tim Sales</option>
                    @foreach($allMarketingUsers as $mUser)
                        <option value="{{ $mUser->id }}">{{ $mUser->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Filter Project -->
        <div>
            <label class="block font-bold text-slate-600 mb-1 text-[11px] flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Proyek Properti</span>
            </label>
            <select wire:model.live="filter_project_id" class="w-full px-3 py-2 bg-slate-50/80 border rounded-xl text-xs font-medium transition focus:outline-hidden focus:bg-white focus:border-teal-500 {{ $filter_project_id ? 'border-teal-500 bg-teal-50/40 font-bold text-teal-900' : 'border-slate-200 text-slate-700' }}">
                <option value="">Semua Proyek</option>
                @foreach($allProjects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Lead Stage -->
        <div>
            <label class="block font-bold text-slate-600 mb-1 text-[11px] flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tahap Prospek</span>
            </label>
            <select wire:model.live="filter_lead_stage" class="w-full px-3 py-2 bg-slate-50/80 border rounded-xl text-xs font-medium transition focus:outline-hidden focus:bg-white focus:border-teal-500 {{ $filter_lead_stage ? 'border-teal-500 bg-teal-50/40 font-bold text-teal-900' : 'border-slate-200 text-slate-700' }}">
                <option value="">Semua Tahap</option>
                @foreach(\App\Models\DailyActivityReport::leadStages() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Lead Source -->
        <div>
            <label class="block font-bold text-slate-600 mb-1 text-[11px] flex items-center gap-1">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span>Sumber Lead</span>
            </label>
            <select wire:model.live="filter_lead_source" class="w-full px-3 py-2 bg-slate-50/80 border rounded-xl text-xs font-medium transition focus:outline-hidden focus:bg-white focus:border-teal-500 {{ $filter_lead_source ? 'border-teal-500 bg-teal-50/40 font-bold text-teal-900' : 'border-slate-200 text-slate-700' }}">
                <option value="">Semua Sumber</option>
                @foreach(\App\Models\DailyActivityReport::leadSources() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
