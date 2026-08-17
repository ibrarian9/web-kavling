<!-- Modal Tarik & Export PDF Laporan Aktivitas Harian -->
@if($showExportPdfModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-teal-50 text-teal-700 border border-teal-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Export PDF Daily Activity Report</h3>
                        <p class="text-[11px] text-slate-500">Tarik dokumen rekap aktivitas harian per hari, per minggu, atau per bulan</p>
                    </div>
                </div>
                <button wire:click="closeExportPdfModal" class="text-slate-400 hover:text-slate-600 font-bold text-sm p-1">✕</button>
            </div>

            <!-- Modal Form Body -->
            <div class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                
                <!-- Periode Selector Radios -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Pilih Opsi Periode Laporan *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center {{ $export_period === 'day' ? 'bg-teal-50 border-teal-500 text-teal-900 font-bold shadow-2xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                            <input type="radio" wire:model.live="export_period" value="day" class="sr-only">
                            <span class="text-xs">Per Hari</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Spesifik Tanggal</span>
                        </label>

                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center {{ $export_period === 'week' ? 'bg-teal-50 border-teal-500 text-teal-900 font-bold shadow-2xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                            <input type="radio" wire:model.live="export_period" value="week" class="sr-only">
                            <span class="text-xs">Per Minggu</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Rentang 7 Hari</span>
                        </label>

                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center {{ $export_period === 'month' ? 'bg-teal-50 border-teal-500 text-teal-900 font-bold shadow-2xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                            <input type="radio" wire:model.live="export_period" value="month" class="sr-only">
                            <span class="text-xs">Per Bulan</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Bulanan</span>
                        </label>

                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center {{ $export_period === 'all' ? 'bg-teal-50 border-teal-500 text-teal-900 font-bold shadow-2xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                            <input type="radio" wire:model.live="export_period" value="all" class="sr-only">
                            <span class="text-xs">Semua Data</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Filter Layar</span>
                        </label>
                    </div>
                </div>

                <!-- Conditional Dynamic Date Controls -->
                @if($export_period === 'day')
                    <div class="bg-teal-50/60 border border-teal-100 p-3.5 rounded-2xl space-y-2">
                        <label class="block font-bold text-teal-900 text-xs">Tanggal Laporan Aktivitas Harian:</label>
                        <input type="date" wire:model.live="export_date" class="input-clean w-full font-mono text-xs sm:text-sm font-bold text-slate-800">
                        <p class="text-[10px] text-slate-500">Akan menarik seluruh data laporan aktivitas marketing pada tanggal ini.</p>
                    </div>
                @elseif($export_period === 'week')
                    <div class="bg-teal-50/60 border border-teal-100 p-3.5 rounded-2xl space-y-2">
                        <label class="block font-bold text-teal-900 text-xs">Rentang Tanggal Mingguan (Senin - Minggu):</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <span class="block text-[10px] font-semibold text-slate-500 mb-0.5">Dari Tanggal:</span>
                                <input type="date" wire:model.live="export_week_start" class="input-clean w-full font-mono text-xs">
                            </div>
                            <div>
                                <span class="block text-[10px] font-semibold text-slate-500 mb-0.5">Sampai Tanggal:</span>
                                <input type="date" wire:model.live="export_week_end" class="input-clean w-full font-mono text-xs">
                            </div>
                        </div>
                    </div>
                @elseif($export_period === 'month')
                    <div class="bg-teal-50/60 border border-teal-100 p-3.5 rounded-2xl space-y-2">
                        <label class="block font-bold text-teal-900 text-xs">Pilih Bulan & Tahun Laporan:</label>
                        <input type="month" wire:model.live="export_month" class="input-clean w-full font-mono text-xs sm:text-sm font-bold text-slate-800">
                        <p class="text-[10px] text-slate-500">Akan menarik seluruh data rekap aktivitas harian dalam 1 bulan penuh.</p>
                    </div>
                @endif

                <!-- Filter Tahap Status Prospek -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tahap Status Prospek</label>
                    <select wire:model.live="export_lead_stage" class="select-clean w-full text-xs font-semibold">
                        <option value="">Semua Tahap Prospek</option>
                        @foreach(\App\Models\DailyActivityReport::leadStages() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Sumber Prospek / Lead Source -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs uppercase tracking-wider">Sumber Prospek (Lead Source)</label>
                    <select wire:model.live="export_lead_source" class="select-clean w-full text-xs font-semibold">
                        <option value="">Semua Sumber Lead</option>
                        @foreach(\App\Models\DailyActivityReport::leadSources() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Petugas Marketing (Founder/Supervisor/Admin only) -->
                @if(auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor())
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs uppercase tracking-wider">Petugas Sales Marketing</label>
                        <select wire:model.live="export_user_id" class="select-clean w-full text-xs font-semibold">
                            <option value="">Semua Petugas Marketing (Konsolidasi)</option>
                            @foreach($allMarketingUsers as $mUser)
                                <option value="{{ $mUser->id }}">{{ $mUser->name }} (No. HP: {{ $mUser->phone_number ?: 'Tanpa HP' }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Filter Proyek -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kawasan Proyek Properti</label>
                    <select wire:model.live="export_project_id" class="select-clean w-full text-xs font-semibold">
                        <option value="">Semua Kawasan Proyek</option>
                        @foreach($allProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Modal Footer Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-2.5 pt-3 border-t border-slate-100 shrink-0">
                <x-button variant="secondary" size="md" type="button" wire:click="closeExportPdfModal">Batal</x-button>
                <div class="flex items-center gap-2">
                    <x-button variant="outline" size="md" type="button" 
                              wire:click="openViewerModal('{{ $this->exportPdfUrl }}', 'Pratinjau PDF Daily Activity Report')" 
                              @click="$wire.closeExportPdfModal()">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Pratinjau PDF</span>
                    </x-button>
                    <x-button variant="primary" size="md" href="{{ $this->exportPdfUrl }}" target="_blank" icon="pdf">
                        <span>Download PDF</span>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
@endif
