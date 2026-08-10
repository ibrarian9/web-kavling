<!-- Installment & Buyer Payments Card (Financial Data - Hidden from Pengawas Project) -->
@if(!auth()->user()->isPengawasProject() && $unit->installment)
    <div class="card-clean p-5 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span>Skema Cicilan & Pembayaran Pembeli</span>
                @if($unit->installment->status === 'lunas')
                    <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Lunas</span>
                @elseif($unit->installment->status === 'konversi_cash')
                    <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-900 border border-purple-300">Lunas Cash</span>
                @else
                    <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-800 border border-amber-200">{{ ucfirst($unit->installment->status) }}</span>
                @endif
            </h3>

            @if(auth()->user()->isFounder())
                <div class="flex items-center gap-2 flex-wrap">
                    @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                        <button wire:click="openInstallmentPaymentModal" class="btn-action-payment text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-extrabold" title="Input Setoran Cicilan Pembeli">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Input Setoran</span>
                        </button>
                    @endif
                    <button wire:click="openSetupInstallmentModal" class="btn-action-edit text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Edit Skema Cicilan & Piutang Pembeli">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Skema</span>
                    </button>
                    @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                        <button wire:click="openConvertToCashModal" class="btn-action-convert text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Batalkan skema cicilan & pelunasan Cash">
                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>Ganti Cash</span>
                        </button>
                        <button type="button" @click="confirmModalAction({
                            title: 'Hapus Skema Cicilan Pembeli',
                            message: 'Yakin ingin menghapus skema cicilan Unit {{ $unit->code }} beserta seluruh riwayat setoran terikatnya?',
                            confirmText: 'Hapus Skema',
                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                            onConfirm: () => $wire.deleteInstallmentScheme()
                        })" class="btn-action-delete text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Hapus Skema Cicilan Pembeli">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        @php
            $paidSoFar = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
            $unpaidBalance = max(0, (float)$unit->installment->total_price - $paidSoFar);
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50/90 p-4 rounded-2xl border border-slate-200/80">
            <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Total Harga Deal:</span>
                <span class="font-extrabold text-slate-900 font-mono text-xs sm:text-sm">Rp {{ number_format($unit->installment->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Sudah Terbayar:</span>
                <span class="font-extrabold text-emerald-700 font-mono text-xs sm:text-sm">Rp {{ number_format($paidSoFar, 0, ',', '.') }}</span>
            </div>
            <div class="bg-amber-50/80 p-2.5 rounded-xl border border-amber-200/80">
                <span class="text-amber-800 block text-[10px] uppercase font-bold tracking-wider">Sisa Belum Terbayar:</span>
                <span class="font-extrabold text-amber-700 font-mono text-xs sm:text-sm">Rp {{ number_format($unpaidBalance, 0, ',', '.') }}</span>
            </div>
            <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Skema Cicilan:</span>
                <span class="font-bold text-slate-800 font-mono text-xs">{{ $unit->installment->installment_count }}x @ Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payments list -->
        <div class="space-y-2.5 text-xs">
            <p class="font-extrabold text-slate-800">Setoran Cicilan Masuk:</p>
            @forelse($unit->installment->payments as $pay)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white border border-slate-200/80 rounded-2xl gap-2 transition-all hover:bg-slate-50">
                    <div>
                        <span class="font-extrabold font-mono text-emerald-700 text-sm">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                        <span class="text-slate-500 text-[10px] font-semibold ml-2">({{ $pay->payment_method }})</span>
                        <p class="text-[10px] text-slate-400 mt-0.5 italic">{{ $pay->notes ?: '-' }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                        <span class="font-mono text-slate-500 text-[11px] font-semibold">{{ $pay->payment_date ? (is_string($pay->payment_date) ? $pay->payment_date : $pay->payment_date->format('d/m/Y')) : '-' }}</span>
                        @if(auth()->user()->isFounder())
                            @if($pay->uuid)
                                <button wire:click="openViewerModal('pdf', '{{ route('installment.invoice', $pay->uuid) }}', 'Pratinjau Invoice Setoran Unit {{ $unit->code }}')" class="btn-action-pdf text-[11px]" title="Pratinjau Invoice / Kuitansi PDF (QR Verification)">
                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Invoice PDF</span>
                                </button>
                            @endif
                            <button wire:click="editInstallmentPayment({{ $pay->id }})" class="btn-action-edit text-[11px]" title="Edit Setoran">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit</span>
                            </button>
                            <button type="button" @click="confirmModalAction({
                                title: 'Hapus Setoran Cicilan',
                                message: 'Yakin ingin menghapus setoran cicilan pembeli ini?',
                                confirmText: 'Hapus Setoran',
                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                onConfirm: () => $wire.deleteInstallmentPayment({{ $pay->id }})
                            })" class="btn-action-delete text-[11px]" title="Hapus Setoran">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-slate-400 text-xs italic bg-slate-50/60 p-3 rounded-xl border border-dashed border-slate-200 text-center">Belum ada setoran cicilan pembeli.</p>
            @endforelse
        </div>
    </div>
@elseif(!auth()->user()->isPengawasProject() && auth()->user()->isFounder() && in_array($unit->status, ['booked', 'disetujui', 'terjual', 'converted']))
    <div class="card-clean p-5 flex flex-col sm:flex-row sm:items-center justify-between bg-blue-50/50 border border-blue-100 gap-3">
        <div>
            <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm">Skema Cicilan Pembeli Belum Dikonfigurasi</h4>
            <p class="text-[11px] text-slate-500 mt-0.5">Unit ini sudah terpesan/terjual. Klik tombol untuk mengonfigurasi skema harga & tenor cicilan.</p>
        </div>
        <button wire:click="openSetupInstallmentModal" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl text-xs font-extrabold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98] shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Skema Cicilan Pembeli</span>
        </button>
    </div>
@endif

@if(!auth()->user()->isPengawasProject() && isset($manualInvoices) && $manualInvoices->count() > 0)
    <div class="card-clean p-5 space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span>Invoice Manual Terkait Unit ({{ $manualInvoices->count() }})</span>
            </h3>
            <span class="text-xs font-mono font-extrabold text-emerald-700">Total Kas Masuk: Rp {{ number_format($manualInvoices->where('status', 'lunas')->where('type', 'masuk')->sum('amount'), 0, ',', '.') }}</span>
        </div>

        <div class="space-y-2 text-xs">
            @foreach($manualInvoices as $inv)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white border border-slate-200/80 rounded-2xl gap-2 hover:bg-slate-50 transition">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold font-mono text-slate-900">{{ $inv->invoice_number }}</span>
                            @if($inv->status === 'lunas')
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">LUNAS</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">{{ strtoupper($inv->status) }}</span>
                            @endif
                        </div>
                        <p class="text-slate-600 font-semibold mt-0.5">Penerima: {{ $inv->recipient_name }} | {{ str_replace('_', ' ', $inv->category) }}</p>
                        @if($inv->description)
                            <p class="text-slate-400 text-[10px] italic">{{ $inv->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="font-mono font-extrabold text-sm {{ $inv->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $inv->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($inv->amount, 0, ',', '.') }}
                        </span>
                        <button wire:click="openViewerModal('pdf', '{{ route('manual-invoices.pdf', $inv->uuid) }}', 'Pratinjau Invoice {{ $inv->invoice_number }}')" class="btn-action-pdf text-[11px]">
                            <span>PDF</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
