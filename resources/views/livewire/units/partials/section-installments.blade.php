<!-- Installment & Buyer Payments Card (Financial Data - Hidden from Pengawas Project) -->
@if(!auth()->user()->isPengawasProject() && $unit->installment)
    <x-card padding="p-5">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between border-b border-slate-100 pb-3 gap-3">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span>Skema Cicilan & Pembayaran Pembeli</span>
                <x-status-badge :status="$unit->installment->status" />
            </h3>

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                    <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('installments.unit-statement-pdf', $unit->installment->id) }}', 'Pratinjau Rekapitulasi Cicilan Unit {{ $unit->code }} - {{ $unit->installment->buyer_name }}')" icon="pdf" title="Pratinjau Rekapitulasi & Kartu Pembayaran Cicilan PDF">
                        <span>Rekap Cicilan PDF</span>
                    </x-button>

                    @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                        <x-button variant="emerald" size="xs" wire:click="openInstallmentPaymentModal" icon="plus" title="Input Setoran Cicilan Pembeli">
                            <span>Input Setoran</span>
                        </x-button>
                    @endif

                    <!-- Dropdown Opsi Skema -->
                    <x-action-dropdown title="Kelola Skema & Status Cicilan" label="Opsi Skema" icon="gear" variant="outline" size="xs">
                        <div class="py-1">
                            @if(auth()->user()->isAdminOrFounder())
                                <x-dropdown-item icon="edit" wire:click="openSetupInstallmentModal">
                                    Edit Skema & Tenor
                                </x-dropdown-item>
                                @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                                    <x-dropdown-item icon="convert" variant="purple" wire:click="openConvertToCashModal">
                                        Dialihkan ke Cash
                                    </x-dropdown-item>
                                @endif
                            @endif
                        </div>
                        @if(auth()->user()->isAdminOrFounder() && !in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                            <div class="py-1">
                                <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                    title: 'Hapus Skema Cicilan Pembeli',
                                    message: 'Yakin ingin menghapus skema cicilan Unit {{ $unit->code }} beserta seluruh riwayat setoran terikatnya?',
                                    confirmText: 'Hapus Skema',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteInstallmentScheme()
                                })">
                                    Hapus Skema
                                </x-dropdown-item>
                            </div>
                        @endif
                    </x-action-dropdown>
                </div>
            @endif
        </div>

        @php
            $paidSoFar = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
            $unpaidBalance = max(0, (float)$unit->installment->total_price - $paidSoFar);
            $hasApprovedPrice = $unit->officialDocument || $unit->proposals->where('status', 'disetujui')->count() > 0;
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50/90 p-4 rounded-2xl border border-slate-200/80">
            <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                <div class="flex items-center justify-between gap-1 mb-0.5">
                    <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Total Harga Deal:</span>
                    @if($hasApprovedPrice)
                        <span class="text-[9px] font-extrabold text-emerald-800 bg-emerald-100 px-1.5 py-0.5 rounded border border-emerald-200">ACC</span>
                    @else
                        <span class="text-[9px] font-extrabold text-amber-800 bg-amber-100 px-1.5 py-0.5 rounded border border-amber-200">Belum ACC</span>
                    @endif
                </div>
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
                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-center whitespace-nowrap flex-nowrap">
                        <span class="font-mono text-slate-600 text-[11px] font-bold">{{ format_id_date($pay->payment_date) }}</span>
                        @if($pay->receipt_photo_path)
                            <x-button variant="outline" size="xs" wire:click="openViewerModal('image', '{{ asset('storage/' . $pay->receipt_photo_path) }}', 'Foto Resi Transfer Bank - Setoran Unit {{ $unit->code }}')" title="Buka Foto Struk / Resi Bukti Transfer Bank">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Struk TF</span>
                            </x-button>
                        @endif
                        @if($pay->uuid)
                            <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('installment.invoice', $pay->uuid) }}', 'Pratinjau Invoice Setoran Unit {{ $unit->code }}')" title="Pratinjau Invoice / Kuitansi PDF (QR Verification)">
                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Invoice PDF</span>
                            </x-button>
                        @endif
                        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                            <!-- Dropdown Aksi Setoran -->
                            <x-action-dropdown title="Opsi Aksi Setoran" variant="outline" size="xs">
                                <div class="py-1">
                                    <button wire:click="editInstallmentPayment({{ $pay->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit Setoran</span>
                                    </button>
                                </div>
                                <div class="py-1">
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Hapus Setoran Cicilan',
                                        message: 'Yakin ingin menghapus setoran cicilan pembeli ini?',
                                        confirmText: 'Hapus Setoran',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteInstallmentPayment({{ $pay->id }})
                                    })" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus Setoran</span>
                                    </button>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-slate-400 text-xs italic bg-slate-50/60 p-3 rounded-xl border border-dashed border-slate-200 text-center">Belum ada setoran cicilan pembeli.</p>
            @endforelse
        </div>
    </x-card>
@elseif(!auth()->user()->isPengawasProject() && auth()->user()->isAdminOrFounder() && in_array($unit->status, ['booked', 'disetujui', 'terjual', 'converted']))
    <x-card padding="p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm">Skema Cicilan Pembeli Belum Dikonfigurasi</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Unit ini sudah terpesan/terjual. Klik tombol untuk mengonfigurasi skema harga & tenor cicilan.</p>
            </div>
            <x-button variant="outline" size="sm" wire:click="openSetupInstallmentModal">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Skema Cicilan Pembeli</span>
            </x-button>
        </div>
    </x-card>
@endif

@if(!auth()->user()->isPengawasProject() && isset($manualInvoices) && $manualInvoices->count() > 0)
    <x-card padding="p-5" class="mt-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span>Invoice Manual Terkait Unit ({{ $manualInvoices->count() }})</span>
            </h3>
            <span class="text-xs font-mono font-extrabold text-emerald-700">Total Kas Masuk: Rp {{ number_format($manualInvoices->where('status', 'lunas')->where('type', 'masuk')->sum('amount'), 0, ',', '.') }}</span>
        </div>

        <div class="space-y-2 text-xs mt-3">
            @foreach($manualInvoices as $inv)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white border border-slate-200/80 rounded-2xl gap-2 hover:bg-slate-50 transition">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold font-mono text-slate-900">{{ $inv->invoice_number }}</span>
                            <x-status-badge :status="$inv->status" />
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
                        <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('manual-invoices.pdf', $inv->uuid) }}', 'Pratinjau Invoice {{ $inv->invoice_number }}')">
                            <span>PDF</span>
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </x-card>
@endif
