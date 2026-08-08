<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900 text-white p-6 rounded-3xl border border-slate-800 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-extrabold uppercase tracking-wider">
                    Founder Exclusive Security
                </span>
                <span class="text-xs text-slate-400 font-mono">• Audit Trail System</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Log Aktivitas & Audit Sistem</h1>
            <p class="text-xs text-slate-400">Pemantauan aktivitas pengguna, otentikasi, pergeseran peran, dan log transaksi real-time.</p>
        </div>

        <div class="flex items-center gap-2">
            @if($activeTab === 'database')
                <button type="button" @click="confirmModalAction({
                    title: 'Bersihkan Database Logs',
                    message: 'Yakin ingin membersihkan seluruh data log aktivitas database?',
                    confirmText: 'Clear DB Logs',
                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                    onConfirm: () => $wire.clearDatabaseLogs()
                })" class="px-3.5 py-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Clear DB Logs</span>
                </button>
            @else
                <button type="button" @click="confirmModalAction({
                    title: 'Kosongkan File Log',
                    message: 'Yakin ingin mengosongkan file storage/logs/laravel.log?',
                    confirmText: 'Clear File Log',
                    btnClass: 'px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                    onConfirm: () => $wire.clearFileLog()
                })" class="px-3.5 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Clear File Log</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Control & Filter Bar -->
    <div class="card-clean p-4 space-y-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Tabs Switcher -->
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-2xl w-full md:w-auto">
                <button wire:click="$set('activeTab', 'database')" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'database' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    <span>Audit Log Database</span>
                </button>
                <button wire:click="$set('activeTab', 'file')" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'file' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>File Log (`laravel.log`)</span>
                </button>
            </div>

            <!-- Search & Filters -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari user, aksi, IP..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                @if($activeTab === 'database')
                    <select wire:model.live="actionFilter" class="w-full sm:w-48 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Semua Aksi</option>
                        @foreach($availableActions as $act)
                            <option value="{{ $act }}">{{ $act }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

        </div>
    </div>

    <!-- TAB 1: Database Audit Logs -->
    @if($activeTab === 'database')
        <div class="card-clean overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5">Waktu</th>
                            <th class="px-4 py-3.5">Pengguna / Peran</th>
                            <th class="px-4 py-3.5">Aksi / Event</th>
                            <th class="px-4 py-3.5">Detail Aktivitas</th>
                            <th class="px-4 py-3.5">Alamat IP & Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($databaseLogs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 font-extrabold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $log->user_name }}</div>
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-[9px] font-semibold text-slate-600 uppercase">
                                                {{ $log->user_role }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @php
                                        $badgeClass = match(true) {
                                            str_contains($log->action, 'LOGIN') || str_contains($log->action, 'AUTH') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            str_contains($log->action, 'CLEAR') || str_contains($log->action, 'DELETE') => 'bg-rose-50 text-rose-700 border-rose-200',
                                            str_contains($log->action, 'CREATE') || str_contains($log->action, 'ADD') => 'bg-sky-50 text-sky-700 border-sky-200',
                                            default => 'bg-purple-50 text-purple-700 border-purple-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg border text-[10px] font-extrabold font-mono uppercase tracking-wider {{ $badgeClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-700 font-medium max-w-md">
                                    {{ $log->description }}
                                </td>
                                <td class="px-4 py-3.5 text-[11px] font-mono text-slate-500 whitespace-nowrap">
                                    <div>{{ $log->ip_address ?: '127.0.0.1' }}</div>
                                    <div class="text-[9px] text-slate-400 max-w-xs truncate" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ? Str::limit($log->user_agent, 30) : '-' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-semibold text-slate-600">Belum ada riwayat aktivitas sistem tercatat</p>
                                    <p class="text-xs text-slate-400 mt-1">Seluruh log transaksi, login, dan aksi user akan ditampilkan di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $databaseLogs->links() }}
            </div>
        </div>

    <!-- TAB 2: File Log (`laravel.log`) -->
    @else
        <div class="bg-slate-950 text-slate-200 rounded-3xl p-5 border border-slate-800 shadow-2xl font-mono text-xs space-y-3 overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 text-slate-400 text-[11px]">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="font-bold text-slate-300 ml-2">storage/logs/laravel.log (Baris Terbaru)</span>
                </div>
                <div>Total Baris Ditemukan: {{ count($rawLogLines) }}</div>
            </div>

            <div class="max-h-[550px] overflow-y-auto space-y-1 pr-2 font-mono leading-relaxed text-[11px]">
                @forelse($rawLogLines as $line)
                    @php
                        $isError = str_contains($line, '.ERROR') || str_contains($line, 'Exception') || str_contains($line, 'Error:');
                        $isWarning = str_contains($line, '.WARNING');
                        $isInfo = str_contains($line, '.INFO');
                        $colorClass = $isError ? 'text-rose-400 bg-rose-950/30' : ($isWarning ? 'text-amber-400' : ($isInfo ? 'text-emerald-300' : 'text-slate-300'));
                    @endphp
                    <div class="p-1.5 rounded hover:bg-slate-900/90 transition break-all {{ $colorClass }}">
                        {{ $line }}
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500">
                        File `storage/logs/laravel.log` saat ini kosong atau tidak ditemukan entri matching.
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
