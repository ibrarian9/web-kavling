<div class="space-y-6">
    <!-- Header Banner -->
    <div class="card-clean p-6 bg-gradient-to-r from-emerald-900 via-teal-800 to-slate-900 text-white rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-extrabold text-xl text-emerald-300 shadow-inner">
                <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-tight flex items-center gap-2">
                    <span>Penetapan & Penggajian Karyawan</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-500/30 border border-purple-400/40 text-purple-200">
                        Akses Khusus Founder
                    </span>
                </h1>
                <p class="text-emerald-100/80 text-xs mt-1">Kelola gaji pokok, tunjangan jabatan, potongan, pembayaran gaji bulanan, dan cetak Slip Gaji PDF resmi.</p>
            </div>
        </div>

        <button wire:click="openSalaryModal" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 rounded-2xl text-xs font-black inline-flex items-center gap-2 transition shadow-md shrink-0">
            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>+ Tetapkan Gaji Karyawan</span>
        </button>
    </div>

    <!-- Alert Success Flash Messages -->
    @if(session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-900 text-xs font-bold flex items-center gap-2 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Beban Gaji Terbayar (Bulan Ini)</span>
            <p class="text-2xl font-black text-emerald-700 font-mono">Rp {{ number_format($totalMonthlySalaryPaid, 0, ',', '.') }}</p>
            <p class="text-[10px] text-slate-400">Pencatatan penggajian periode {{ date('F Y') }}</p>
        </div>

        <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Jumlah Karyawan Ditetapkan</span>
            <p class="text-2xl font-black text-slate-900 font-mono">{{ $totalEmployeesCount }} Orang</p>
            <p class="text-[10px] text-slate-400">Staf kantor & pekerja proyek</p>
        </div>

        <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Transaksi Penggajian</span>
            <p class="text-2xl font-black text-slate-900 font-mono">{{ $totalPaymentsCount }} Berkas</p>
            <p class="text-[10px] text-slate-400">Slip gaji PDF yang telah diproses</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-2 flex items-center gap-2 shadow-xs">
        <button wire:click="$set('activeTab', 'salaries')" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition flex items-center justify-center gap-2 {{ $activeTab === 'salaries' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2H7a2 2 0 01-2 2v14a2 2 0 012 2z"/></svg>
            <span>Standar Gaji & Tunjangan Karyawan ({{ $salaries->count() }})</span>
        </button>

        <button wire:click="$set('activeTab', 'payments')" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition flex items-center justify-center gap-2 {{ $activeTab === 'payments' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Histori Pembayaran Gaji & Slip PDF</span>
        </button>
    </div>

    <!-- TAB 1: STANDAR GAJI KARYAWAN -->
    @if($activeTab === 'salaries')
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
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol "+ Tetapkan Gaji Karyawan" di atas untuk mendaftarkan komponen gaji staf atau pekerja.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TAB 2: HISTORI PEMBAYARAN GAJI & SLIP PDF -->
    @if($activeTab === 'payments')
        <div class="card-clean p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="font-black text-slate-900 text-base">Histori Transaksi Pembayaran Gaji Karyawan</h2>
                    <p class="text-xs text-slate-500">Riwayat penggajian bulanan yang telah diproses dan penerbitan Slip Gaji PDF resmi.</p>
                </div>

                <div class="flex items-center gap-2">
                    <select wire:model.live="selected_month" class="input-clean text-xs font-semibold">
                        <option value="">Semua Bulan</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>

                    <select wire:model.live="selected_year" class="input-clean text-xs font-semibold">
                        <option value="">Semua Tahun</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                    <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">No. Slip Gaji</th>
                            <th class="p-3.5">Nama Karyawan</th>
                            <th class="p-3.5">Periode Gaji</th>
                            <th class="p-3.5">Tgl Bayar</th>
                            <th class="p-3.5 text-right">Total Gaji Dibayar</th>
                            <th class="p-3.5">Metode Bayar</th>
                            <th class="p-3.5 text-center">Cetak Slip & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                        @forelse($payments as $pay)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3.5 font-mono font-bold text-emerald-800">
                                    SLIP/PAY/{{ strtoupper(substr($pay->uuid, 0, 8)) }}
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900 text-sm">{{ $pay->employeeSalary->employee_name ?? 'Karyawan' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $pay->employeeSalary->position ?? '-' }}</div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-800">
                                    {{ $pay->employeeSalary->getIndonesianMonth($pay->payroll_month) }} {{ $pay->payroll_year }}
                                </td>
                                <td class="p-3.5 font-mono">{{ \Carbon\Carbon::parse($pay->payment_date)->isoFormat('DD/MM/YYYY') }}</td>
                                <td class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                                    Rp {{ number_format($pay->net_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ strtoupper($pay->payment_method) }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('employee-salary.slip-pdf', $pay->uuid) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1" title="Buka / Stream Slip Gaji PDF Resmi">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span>Slip Gaji PDF</span>
                                        </a>
                                        <button type="button" @click="confirmModalAction({
                                            title: 'Hapus Histori Penggajian',
                                            message: 'Yakin ingin menghapus berkas penggajian ini dari sistem?',
                                            confirmText: 'Hapus Berkas',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deletePaymentRecord({{ $pay->id }})
                                        })" class="btn-action-delete" title="Hapus Berkas">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-bold text-slate-600">Belum Ada Histori Transaksi Pembayaran Gaji</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    @endif

    <!-- MODAL FORM PENETAPAN STANDAR GAJI -->
    @if($showSalaryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingSalaryId ? 'Edit Standar Gaji Karyawan' : 'Penetapan Standar Gaji Karyawan Baru' }}
                    </h3>
                    <button wire:click="closeSalaryModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveSalaryStandard" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Sumber Data Karyawan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" wire:click="$set('target_type', 'user')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'user' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                Staf / Akun User
                            </button>
                            <button type="button" wire:click="$set('target_type', 'worker')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'worker' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                Pekerja Lapangan
                            </button>
                            <button type="button" wire:click="$set('target_type', 'custom')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'custom' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                Custom / Manual
                            </button>
                        </div>
                    </div>

                    @if($target_type === 'user')
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Pilih Akun Staf / User *</label>
                            <select wire:model.live="user_id" class="input-clean w-full font-bold">
                                <option value="">Pilih Staf / User...</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} (Role: {{ ucfirst($u->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($target_type === 'worker')
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Pilih Pekerja Lapangan *</label>
                            <select wire:model.live="worker_id" class="input-clean w-full font-bold">
                                <option value="">Pilih Pekerja / Mandor...</option>
                                @foreach($workers as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Karyawan *</label>
                            <input type="text" wire:model="employee_name" required class="input-clean w-full font-bold">
                            @error('employee_name') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jabatan / Posisi *</label>
                            <input type="text" wire:model="position" required placeholder="misal: Supervisor / Finance / Mandor" class="input-clean w-full">
                            @error('position') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gaji Pokok (Rp) *</label>
                            <x-currency-input model="basic_salary" class="input-clean w-full font-bold font-mono text-emerald-800" />
                            @error('basic_salary') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tunjangan Jabatan/Operasional (Rp)</label>
                            <x-currency-input model="allowance" class="input-clean w-full font-bold font-mono text-emerald-700" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Bonus / Insentif (Rp)</label>
                            <x-currency-input model="bonus" class="input-clean w-full font-bold font-mono text-blue-700" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Potongan BPJS/Kasbon (Rp)</label>
                            <x-currency-input model="deductions" class="input-clean w-full font-bold font-mono text-rose-600" />
                        </div>
                    </div>

                    <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl space-y-1">
                        <div class="flex justify-between text-xs font-bold text-emerald-900">
                            <span>Estimasi Gaji Bersih (Net):</span>
                            <span class="font-mono text-sm">
                                Rp {{ number_format(max(0, (float)$basic_salary + (float)$allowance + (float)$bonus - (float)$deductions), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Bank</label>
                            <input type="text" wire:model="bank_name" placeholder="BCA / Mandiri / BRI" class="input-clean w-full font-bold">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor Rekening</label>
                            <input type="text" wire:model="bank_account_number" placeholder="1234567890" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeSalaryModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Standar Gaji</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL PEMBAYARAN GAJI BULANAN -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Pembayaran Gaji: {{ $payment_employee_name }}</h3>
                        <p class="text-xs text-slate-500">Proses pencairan gaji bulanan & terbitkan Slip Gaji PDF</p>
                    </div>
                    <button wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="processPayment" class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Bulan Gaji *</label>
                            <select wire:model="payroll_month" class="input-clean w-full font-bold">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tahun Gaji *</label>
                            <select wire:model="payroll_year" class="input-clean w-full font-bold">
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Pembayaran *</label>
                        <input type="date" wire:model="payment_date" required class="input-clean w-full font-mono">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gaji Pokok (Rp)</label>
                            <x-currency-input model="pay_basic_salary" class="input-clean w-full font-bold font-mono text-emerald-800" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tunjangan (Rp)</label>
                            <x-currency-input model="pay_allowance" class="input-clean w-full font-bold font-mono text-emerald-700" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Bonus / Insentif (Rp)</label>
                            <x-currency-input model="pay_bonus" class="input-clean w-full font-bold font-mono text-blue-700" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Potongan (Rp)</label>
                            <x-currency-input model="pay_deductions" class="input-clean w-full font-bold font-mono text-rose-600" />
                        </div>
                    </div>

                    <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl space-y-1">
                        <div class="flex justify-between text-xs font-bold text-emerald-900">
                            <span>TOTAL NET GAJI DIBAYAR:</span>
                            <span class="font-mono text-base font-black">
                                Rp {{ number_format(max(0, (float)$pay_basic_salary + (float)$pay_allowance + (float)$pay_bonus - (float)$pay_deductions), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                            <select wire:model="payment_method" class="input-clean w-full font-bold">
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai / Cash</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Bank & No. Rekening</label>
                            <input type="text" wire:model="pay_bank_name" placeholder="BCA 1234567890" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Foto Struk / Bukti Transfer (Opsional)</label>
                        <input type="file" wire:model="receipt_photo" accept="image/*,.pdf" class="w-full text-xs text-slate-600">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showPaymentModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold">Proses Bayar & Terbitkan Slip PDF</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
