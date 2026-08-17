<div class="space-y-6">
    <!-- Header Banner -->
    <div class="card-clean p-6 bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 text-white rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-extrabold text-xl text-emerald-100 shadow-inner">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-xl font-extrabold tracking-tight flex items-center gap-2">
                    <span>Profil Founder</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/30 border border-emerald-400/40 text-emerald-200">
                        {{ auth()->user()->role }}
                    </span>
                </h1>
                <p class="text-emerald-100/80 text-xs mt-1">Kelola NIK KTP, Jabatan, Alamat Penjual, dan pengaturan kata sandi untuk otomatisasi berkas SPJB & SPP PDF.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Update Data Profil & Legalitas Founder (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-clean p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Data Informasi Akun & Legalitas SPJB</span>
                    </h2>
                    <span class="text-[11px] text-slate-400">Otomatis Terhubung ke SPJB & SPP PDF</span>
                </div>

                <form wire:submit.prevent="updateProfile" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Penjual / Founder <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" class="input-clean w-full font-bold" required>
                            @error('name') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Akun <span class="text-rose-500">*</span></label>
                            <input type="email" wire:model="email" class="input-clean w-full" required>
                            @error('email') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">NIK KTP Penjual / Founder (16 Digit)</label>
                            <input type="text" wire:model="nik" placeholder="1471012304850001" class="input-clean w-full font-mono font-bold text-emerald-800">
                            <p class="text-[10px] text-slate-400 mt-1">Digunakan sebagai No. KTP Pihak Pertama di Surat Perjanjian Jual Beli (SPJB).</p>
                            @error('nik') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jabatan / Posisi Penjual</label>
                            <input type="text" wire:model="position" placeholder="Direktur Utama PT. Atlantik Perkasa Abadi" class="input-clean w-full">
                            <p class="text-[10px] text-slate-400 mt-1">Dicetak di Pasal 1 SPJB & Kolom Tanda Tangan.</p>
                            @error('position') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">No Telp / WhatsApp</label>
                            <input type="text" wire:model="phone" placeholder="081234567890" class="input-clean w-full font-mono">
                            @error('phone') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap Penjual / Kantor</label>
                            <textarea wire:model="address" rows="2" placeholder="Jl. Utama Properti No. 88, Pekanbaru, Riau" class="input-clean w-full"></textarea>
                            @error('address') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <x-button variant="emerald" size="md" type="submit">
                            Simpan Perubahan Profil
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Ubah Password (1 Col) -->
        <div class="space-y-6">
            <div class="card-clean p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Ubah Kata Sandi</span>
                    </h2>
                </div>

                @if(session()->has('password_success'))
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center gap-2">
                        <span>{{ session('password_success') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword" class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Password Saat Ini</label>
                        <input type="password" wire:model="current_password" class="input-clean w-full" required>
                        @error('current_password') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" wire:model="new_password" class="input-clean w-full" required>
                        @error('new_password') <span class="text-rose-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" wire:model="new_password_confirmation" class="input-clean w-full" required>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end">
                        <x-button variant="amber" size="md" type="submit">
                            Update Password
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
