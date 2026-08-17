<!-- Unit Expenses & Material Purchases Combined Table Card -->
<x-card padding="p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
        <div>
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span>Rincian Biaya Pengeluaran & Belanja Unit</span>
            </h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Rekapitulasi gabungan belanja material, gaji worker terbayar, & biaya unit</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap shrink-0">
            @if(count($combinedExpenses) > 0)
                <x-button variant="outline" size="sm" wire:click="openViewerModal('pdf', '{{ route('units.expenses-pdf', $unit->id) }}', 'Pratinjau Laporan Rekapitulasi Tabel Biaya Unit {{ $unit->code }}')" icon="pdf">
                    <span>PDF Rekap</span>
                </x-button>
            @else
                <x-button variant="outline" size="sm" disabled icon="pdf" title="Belum ada data pengeluaran/belanja unit untuk digenerate PDF" class="opacity-50 cursor-not-allowed">
                    <span>PDF Rekap (Kosong)</span>
                </x-button>
            @endif

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isPengawasProject() || auth()->user()->isSupervisor())
                <x-button variant="outline" size="sm" wire:click="openMaterialModal" icon="plus">
                    <span>Catat Belanja Material</span>
                </x-button>
            @endif
        </div>
    </div>

    <!-- Responsive Scroll Table Container -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 mt-4">
        <table class="w-full text-left text-xs text-slate-600 min-w-[640px]">
            <thead class="bg-slate-100/80 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-200/80">
                <tr>
                    <th class="px-3.5 py-3">Tanggal</th>
                    <th class="px-3.5 py-3">Jenis</th>
                    <th class="px-3.5 py-3">Uraian Pengeluaran</th>
                    <th class="px-3.5 py-3 text-right">Nominal</th>
                    <th class="px-3.5 py-3 text-center">Resi</th>
                    <th class="px-3.5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($combinedExpenses as $exp)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3.5 py-3 font-mono font-semibold text-slate-600 whitespace-nowrap">
                            {{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3.5 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border shadow-2xs {{ $exp->badge_class }}">
                                {{ $exp->category_badge }}
                            </span>
                        </td>
                        <td class="px-3.5 py-3 font-semibold text-slate-800">
                            {{ $exp->description }}
                        </td>
                        <td class="px-3.5 py-3 font-mono font-extrabold text-slate-900 text-right whitespace-nowrap">
                            Rp {{ number_format($exp->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-3.5 py-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                @if($exp->receipt_photo_path)
                                    <x-button variant="outline" size="xs" wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Foto Struk Nota Belanja')" title="Pratinjau Foto Struk">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Struk</span>
                                    </x-button>
                                @endif

                                @if($exp->pdf_url)
                                    <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Resi Gaji PDF')" title="Pratinjau PDF Resi">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>PDF</span>
                                    </x-button>
                                @endif

                                @if($exp->qr_url)
                                    <x-button variant="outline" size="xs" wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Resi Gaji Publik (QR Code)')" title="Pratinjau QR Code Verifikasi">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        <span>QR</span>
                                    </x-button>
                                @endif

                                @if(!$exp->receipt_photo_path && !$exp->pdf_url && !$exp->qr_url)
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-3.5 py-3 text-center whitespace-nowrap">
                            @if(isset($exp->source_type) && $exp->source_type === 'material')
                                @if(auth()->user()->isAdminOrFounder())
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <x-action-dropdown title="Menu Opsi Material" size="xs">
                                            <div class="py-1">
                                                <button type="button" wire:click="editMaterialPurchase({{ $exp->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit Belanja Material</span>
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <button type="button" @click="confirmModalAction({
                                                    title: 'Hapus Belanja Material',
                                                    message: 'Yakin ingin menghapus data belanja material ini?',
                                                    confirmText: 'Hapus Material',
                                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                    onConfirm: () => $wire.deleteMaterialPurchase({{ $exp->id }})
                                                })" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Hapus Material</span>
                                                </button>
                                            </div>
                                        </x-action-dropdown>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[10px]">-</span>
                                @endif
                            @elseif(isset($exp->source_type) && $exp->source_type === 'salary_payment')
                                @if(auth()->user()->isAdminOrFounder())
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <x-action-dropdown title="Menu Opsi Pembayaran Gaji" size="xs">
                                            <div class="py-1">
                                                <button type="button" wire:click="editPayrollPayment({{ $exp->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit Pembayaran Gaji</span>
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <button type="button" @click="confirmModalAction({
                                                    title: 'Hapus Pembayaran Gaji',
                                                    message: 'Yakin ingin menghapus pencatatan pembayaran gaji ini?',
                                                    confirmText: 'Hapus Gaji',
                                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                    onConfirm: () => $wire.deletePayrollPayment({{ $exp->id }})
                                                })" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Hapus Pembayaran Gaji</span>
                                                </button>
                                            </div>
                                        </x-action-dropdown>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[10px]">-</span>
                                @endif
                            @elseif(isset($exp->source_type) && $exp->source_type === 'payroll_setup')
                                <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $exp->id) }}', 'Pratinjau Surat Perintah Kerja (SPK)')" title="Pratinjau SPK PDF">
                                        SPK PDF
                                    </x-button>
                                    @if(auth()->user()->isAdminOrFounder())
                                        <x-action-dropdown title="Menu Opsi Kontrak Gaji" size="xs">
                                            <div class="py-1">
                                                <button type="button" wire:click="editPayrollSetup({{ $exp->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit Kontrak</span>
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <button type="button" @click="confirmModalAction({
                                                    title: 'Hapus Kontrak Gaji',
                                                    message: 'Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?',
                                                    confirmText: 'Hapus Kontrak Gaji',
                                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                    onConfirm: () => $wire.deletePayrollSetup({{ $exp->id }})
                                                })" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Hapus Kontrak Gaji</span>
                                                </button>
                                            </div>
                                        </x-action-dropdown>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 text-[10px]">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">Belum ada rincian belanja material atau pengeluaran tercatat untuk unit ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
