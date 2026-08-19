<!-- TAB 1: STANDAR GAJI KARYAWAN -->
<div class="space-y-4">
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="font-bold text-slate-900 text-sm">Daftar Standar Gaji Pokok & Tunjangan Karyawan</h2>
            <p class="text-xs text-slate-500">Master penetapan komponen gaji pokok, tunjangan, dan potongan karyawan yang dikelola Founder.</p>
        </div>

        <x-search-input placeholder="Cari nama karyawan, jabatan..." containerClass="w-full sm:w-64" />
    </div>

    <x-table :headers="['Nama Karyawan / Pekerja', 'Jabatan / Posisi', ['label' => 'Gaji Pokok', 'class' => 'p-3.5 text-right'], ['label' => 'Tunjangan', 'class' => 'p-3.5 text-right'], ['label' => 'Potongan', 'class' => 'p-3.5 text-right'], ['label' => 'Gaji Bersih (Net)', 'class' => 'p-3.5 text-right'], 'Rekening Bank', ['label' => 'Aksi Founder', 'class' => 'p-3.5 text-center']]" loadingTarget="search, gotoPage, nextPage, previousPage">
        @forelse($salaries as $sal)
            <tr class="hover:bg-slate-50 transition">
                <td class="p-3.5">
                    <div class="font-bold text-slate-900 text-sm">{{ $sal->employee_name }}</div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $sal->employee_type === 'staf' ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                        {{ $sal->employee_type === 'staf' ? 'Staf Internal' : 'Pekerja Lapangan' }}
                    </span>
                </td>
                <td class="p-3.5 font-bold text-slate-800 text-xs">{{ $sal->position ?? '-' }}</td>
                <td class="p-3.5 text-right font-mono font-bold text-xs">Rp {{ number_format($sal->basic_salary, 0, ',', '.') }}</td>
                <td class="p-3.5 text-right font-mono text-emerald-700 font-bold text-xs">+Rp {{ number_format($sal->allowance, 0, ',', '.') }}</td>
                <td class="p-3.5 text-right font-mono text-rose-600 font-bold text-xs">-Rp {{ number_format($sal->deductions, 0, ',', '.') }}</td>
                <td class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                    Rp {{ number_format($sal->net_salary, 0, ',', '.') }}
                </td>
                <td class="p-3.5">
                    @if($sal->bank_name && $sal->bank_account_number)
                        <div class="font-bold text-slate-900 text-xs">{{ $sal->bank_name }}</div>
                        <div class="font-mono text-slate-500 text-[10px]">{{ $sal->bank_account_number }}</div>
                    @else
                        <span class="text-slate-400 italic text-[10px]">Tunai / Belum diset</span>
                    @endif
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="emerald" size="xs" wire:click="openPaymentModal({{ $sal->id }})" title="Bayar Gaji Bulan Ini & Cetak Slip">
                            Bayar Gaji
                        </x-button>

                        <x-action-dropdown title="Menu Opsi Standar Gaji" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="edit" wire:click="editSalaryStandard({{ $sal->id }})">
                                    Edit Standar Gaji
                                </x-dropdown-item>
                            </div>
                            @if(auth()->user()->isSuperAdmin())
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Standar Gaji Karyawan',
                                        message: 'Yakin ingin menghapus standar gaji {{ $sal->employee_name }} dari sistem?',
                                        confirmText: 'Hapus Standar',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteSalaryStandard({{ $sal->id }})
                                    })">
                                        Hapus Standar
                                    </x-dropdown-item>
                                </div>
                            @endif
                        </x-action-dropdown>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Standar Gaji Karyawan Yang Ditetapkan</p>
                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Tetapkan Gaji Karyawan" di atas untuk mendaftarkan komponen gaji staf atau pekerja.</p>
                </td>
            </tr>
        @endforelse
    </x-table>
</div>
