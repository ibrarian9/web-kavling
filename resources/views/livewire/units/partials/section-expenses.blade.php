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
            <p class="text-[11px] text-slate-500 mt-0.5">Rekapitulasi gabungan kontrak gaji, belanja material, gaji terbayar, & biaya unit</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap shrink-0">
            <x-button variant="outline" size="sm" wire:click="openViewerModal('pdf', '{{ route('units.expenses-pdf', $unit->id) }}', 'Pratinjau Laporan Rekapitulasi Tabel Biaya Unit {{ $unit->code }}')" icon="pdf">
                <span>PDF Rekap</span>
            </x-button>

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isPengawasProject() || auth()->user()->isSupervisor())
                <x-button variant="outline" size="sm" wire:click="openMaterialModal" icon="plus">
                    <span>Catat Belanja Material</span>
                </x-button>
            @endif
        </div>
    </div>

    <!-- Responsive Clean Table Container -->
    <div class="rounded-2xl border border-slate-200/80 mt-4 overflow-x-auto sm:overflow-visible min-h-[140px]">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-100/80 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-200/80">
                <tr>
                    <th class="px-3.5 py-2.5 whitespace-nowrap w-28">Tanggal</th>
                    <th class="px-3 py-2.5 whitespace-nowrap w-32">Jenis</th>
                    <th class="px-3.5 py-2.5">Uraian Pengeluaran / Kontrak</th>
                    <th class="px-3.5 py-2.5 text-right whitespace-nowrap w-36">Nominal</th>
                    <th class="px-3 py-2.5 text-center whitespace-nowrap w-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($combinedExpenses as $exp)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-3.5 py-2.5 font-mono font-semibold text-slate-700 whitespace-nowrap">
                            {{ format_id_date($exp->date) }}
                        </td>
                        <td class="px-3 py-2.5 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border shadow-2xs {{ $exp->badge_class }}">
                                {{ $exp->category_badge }}
                            </span>
                        </td>
                        <td class="px-3.5 py-2.5 font-semibold text-slate-800">
                            {{ $exp->description }}
                        </td>
                        <td class="px-3.5 py-2.5 font-mono font-extrabold text-slate-900 text-right whitespace-nowrap">
                            Rp {{ number_format($exp->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-center whitespace-nowrap">
                            @if(isset($exp->source_type) && $exp->source_type === 'material')
                                <x-action-dropdown size="xs" icon="dots" title="Menu Opsi Belanja Material">
                                    @if($exp->receipt_photo_path || $exp->pdf_url || $exp->qr_url)
                                        <div class="py-1">
                                            @if($exp->receipt_photo_path)
                                                <x-dropdown-item icon="eye" wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Foto Struk Nota Belanja')">
                                                    Lihat Foto Struk
                                                </x-dropdown-item>
                                            @endif
                                            @if($exp->pdf_url)
                                                <x-dropdown-item icon="pdf" wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Nota Belanja Material PDF')">
                                                    Cetak Nota PDF
                                                </x-dropdown-item>
                                            @endif
                                            @if($exp->qr_url)
                                                <x-dropdown-item icon="check" wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Nota Material Publik (QR Code)')">
                                                    Verifikasi QR Code
                                                </x-dropdown-item>
                                            @endif
                                        </div>
                                    @endif

                                    @if(auth()->user()->isAdminOrFounder())
                                        <div class="py-1">
                                            <x-dropdown-item icon="edit" wire:click="editMaterialPurchase({{ $exp->id }})">
                                                Edit Belanja Material
                                            </x-dropdown-item>
                                        </div>
                                        <div class="py-1">
                                            <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                title: 'Hapus Belanja Material',
                                                message: 'Yakin ingin menghapus data belanja material ini?',
                                                confirmText: 'Hapus Material',
                                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                onConfirm: () => $wire.deleteMaterialPurchase({{ $exp->id }})
                                            })">
                                                Hapus Belanja Material
                                            </x-dropdown-item>
                                        </div>
                                    @endif
                                </x-action-dropdown>
                            @elseif(isset($exp->source_type) && $exp->source_type === 'salary_payment')
                                <x-action-dropdown size="xs" icon="dots" title="Menu Opsi Pembayaran Gaji">
                                    @if($exp->receipt_photo_path || $exp->pdf_url || $exp->qr_url)
                                        <div class="py-1">
                                            @if($exp->receipt_photo_path)
                                                <x-dropdown-item icon="eye" wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Bukti Pembayaran Gaji')">
                                                    Lihat Bukti Bayar
                                                </x-dropdown-item>
                                            @endif
                                            @if($exp->pdf_url)
                                                <x-dropdown-item icon="pdf" wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Resi Gaji PDF')">
                                                    Cetak Resi Gaji PDF
                                                </x-dropdown-item>
                                            @endif
                                            @if($exp->qr_url)
                                                <x-dropdown-item icon="check" wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Resi Gaji Publik (QR Code)')">
                                                    Verifikasi QR Code
                                                </x-dropdown-item>
                                            @endif
                                        </div>
                                    @endif

                                    @if(auth()->user()->isAdminOrFounder())
                                        <div class="py-1">
                                            <x-dropdown-item icon="edit" wire:click="editPayrollPayment({{ $exp->id }})">
                                                Edit Pembayaran Gaji
                                            </x-dropdown-item>
                                        </div>
                                        <div class="py-1">
                                            <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                title: 'Hapus Pembayaran Gaji',
                                                message: 'Yakin ingin menghapus pencatatan pembayaran gaji ini?',
                                                confirmText: 'Hapus Gaji',
                                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                onConfirm: () => $wire.deletePayrollPayment({{ $exp->id }})
                                            })">
                                                Hapus Pembayaran Gaji
                                            </x-dropdown-item>
                                        </div>
                                    @endif
                                </x-action-dropdown>
                            @elseif(isset($exp->source_type) && $exp->source_type === 'payroll_setup')
                                <x-action-dropdown size="xs" icon="dots" title="Menu Opsi Kontrak Gaji">
                                    <div class="py-1">
                                        <x-dropdown-item icon="pdf" wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $exp->id) }}', 'Pratinjau Surat Perintah Kerja (SPK)')">
                                            Cetak SPK PDF
                                        </x-dropdown-item>
                                    </div>
                                    @if(auth()->user()->isAdminOrFounder())
                                        <div class="py-1">
                                            <x-dropdown-item icon="edit" wire:click="editPayrollSetup({{ $exp->id }})">
                                                Edit Kontrak Gaji
                                            </x-dropdown-item>
                                        </div>
                                        <div class="py-1">
                                            <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                title: 'Hapus Kontrak Gaji',
                                                message: 'Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?',
                                                confirmText: 'Hapus Kontrak Gaji',
                                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                onConfirm: () => $wire.deletePayrollSetup({{ $exp->id }})
                                            })">
                                                Hapus Kontrak Gaji
                                            </x-dropdown-item>
                                        </div>
                                    @endif
                                </x-action-dropdown>
                            @else
                                <span class="text-slate-400 text-[10px]">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">Belum ada rincian belanja material atau pengeluaran tercatat untuk unit ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
