<!-- TAB 1: STANDAR GAJI KARYAWAN -->
<div class="card-clean p-6 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div>
            <h2 class="font-black text-slate-900 text-base">Daftar Standar Gaji Pokok & Tunjangan Karyawan</h2>
            <p class="text-xs text-slate-500">Master penetapan komponen gaji pokok, tunjangan, dan potongan karyawan yang dikelola Founder.</p>
        </div>

        <div class="w-full sm:w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama karyawan, jabatan..." class="input-clean w-full text-xs">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
            <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="p-3.5">Nama Karyawan / Pekerja</th>
                    <th class="p-3.5">Jabatan / Posisi</th>
                    <th class="p-3.5 text-right">Gaji Pokok</th>
                    <th class="p-3.5 text-right">Tunjangan</th>
                    <th class="p-3.5 text-right">Potongan</th>
                    <th class="p-3.5 text-right">Gaji Bersih (Net)</th>
                    <th class="p-3.5">Rekening Bank</th>
                    <th class="p-3.5 text-center">Aksi Founder</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                @forelse($salaries as $sal)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5">
                            <div class="font-bold text-slate-900 text-sm">{{ $sal->employee_name }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $sal->employee_type === 'staf' ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                {{ $sal->employee_type === 'staf' ? 'Staf Internal' : 'Pekerja Lapangan' }}
                            </span>
                        </td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $sal->position ?? '-' }}</td>
                        <td class="p-3.5 text-right font-mono font-bold">Rp {{ number_format($sal->basic_salary, 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-mono text-emerald-700 font-bold">+Rp {{ number_format($sal->allowance, 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-mono text-rose-600 font-bold">-Rp {{ number_format($sal->deductions, 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                            Rp {{ number_format($sal->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="p-3.5">
                            @if($sal->bank_name && $sal->bank_account_number)
                                <div class="font-bold text-slate-900">{{ $sal->bank_name }}</div>
                                <div class="font-mono text-slate-500 text-[11px]">{{ $sal->bank_account_number }}</div>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Tunai / Belum diset</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="openPaymentModal({{ $sal->id }})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1" title="Bayar Gaji Bulan Ini & Cetak Slip">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Bayar Gaji</span>
                                </button>
                                <button wire:click="editSalaryStandard({{ $sal->id }})" class="btn-action-edit">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit</span>
                                </button>
                                <button type="button" @click="confirmModalAction({
                                    title: 'Hapus Standar Gaji Karyawan',
                                    message: 'Yakin ingin menghapus standar gaji {{ $sal->employee_name }} dari sistem?',
                                    confirmText: 'Hapus Standar',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteSalaryStandard({{ $sal->id }})
                                })" class="btn-action-delete" title="Hapus Standar Gaji">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-bold text-slate-600">Belum Ada Standar Gaji Karyawan Yang Ditetapkan</p>
                            <p class="text-xs text-slate-400 mt-1">Klik tombol "Tetapkan Gaji Karyawan" di atas untuk mendaftarkan komponen gaji staf atau pekerja.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
