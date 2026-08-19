@props([
    'model' => 'receipt_photo',
    'photo' => null,
    'existingPath' => null,
    'label' => 'Foto Resi / Bukti Pembayaran',
    'id' => null,
    'accept' => 'image/*,.heic,.heif,.pdf',
    'helpText' => 'Mendukung Kamera HP & Galeri Foto (JPG, PNG, WEBP, HEIC, PDF)',
])

@php
    $inputId = $id ?? 'receipt_upload_' . str_replace(['.', '$'], '_', $model) . '_' . md5($model);
    $hasPhoto = !empty($photo);
    $hasExisting = !empty($existingPath);
    
    $canPreview = false;
    $ext = '';
    $fileName = '';
    
    if ($hasPhoto) {
        try {
            $ext = strtolower($photo->getClientOriginalExtension());
            $fileName = $photo->getClientOriginalName();
            if (method_exists($photo, 'isPreviewable')) {
                $canPreview = $photo->isPreviewable();
            } else {
                $canPreview = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
            }
        } catch (\Throwable $e) {
            $fileName = 'Foto / Berkas Terpilih';
            $canPreview = false;
        }
    }
@endphp

<div class="space-y-1.5">
    @if($label)
        <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">
            {{ $label }}
        </label>
    @endif

    <div class="bg-slate-50/80 border border-slate-200/90 rounded-2xl p-3 sm:p-4 space-y-3">
        <input type="file" id="{{ $inputId }}" wire:model="{{ $model }}" accept="{{ $accept }}" class="hidden">

        @if($hasPhoto)
            <!-- Live Upload Preview Card -->
            <div class="relative bg-white border border-slate-200 rounded-2xl p-3 space-y-2.5 shadow-2xs">
                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 block truncate">{{ $fileName }}</span>
                            <span class="text-[10px] text-emerald-600 font-semibold block">✓ Berkas baru terpilih (Siap diunggah)</span>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('{{ $model }}', null)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[11px] font-bold transition shrink-0">
                        Hapus
                    </button>
                </div>

                @if($canPreview)
                    <div class="relative group rounded-xl overflow-hidden bg-slate-950/5 border border-slate-200 flex items-center justify-center max-h-52 sm:max-h-64 p-1">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Pratinjau Resi Baru" class="max-h-48 sm:max-h-56 w-auto object-contain rounded-lg shadow-xs">
                        <div class="absolute bottom-2 right-2 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-mono px-2 py-0.5 rounded-md font-semibold">
                            Pratinjau Foto Resi
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-xs font-semibold flex items-center justify-center gap-2 text-left">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Format berkas ({{ strtoupper($ext ?: 'DOKUMEN') }}) tidak mendukung pratinjau langsung di browser, namun berkas tetap siap diunggah.</span>
                    </div>
                @endif
            </div>

        @elseif($hasExisting)
            <!-- Existing Stored Photo Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-3 space-y-2.5 shadow-2xs">
                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-lg bg-blue-50 text-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Foto Resi Tersimpan</span>
                            <span class="text-[10px] text-slate-500 block">Ada foto bukti transaksi tersimpan</span>
                        </div>
                    </div>
                    <label for="{{ $inputId }}" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[11px] font-bold cursor-pointer transition shrink-0">
                        Ganti Foto
                    </label>
                </div>

                <div class="relative group rounded-xl overflow-hidden bg-slate-950/5 border border-slate-200 flex items-center justify-center max-h-52 sm:max-h-64 p-1">
                    <img src="{{ asset('storage/' . $existingPath) }}" alt="Foto Resi Saat Ini" class="max-h-48 sm:max-h-56 w-auto object-contain rounded-lg shadow-xs">
                </div>
            </div>

        @else
            <!-- Touch Upload Card (No Photo Selected Yet) -->
            <label for="{{ $inputId }}" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-white rounded-2xl p-4 sm:p-5 flex flex-col items-center justify-center text-center cursor-pointer transition active:scale-[0.99] group shadow-2xs">
                <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-300 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-700 block">Ketuk untuk Ambil Foto / Upload Resi</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $helpText }}</span>
            </label>
        @endif

        <!-- Loading Spinner (Horizontal Flex Alignment) -->
        <div wire:loading.flex wire:target="{{ $model }}" class="w-full flex-row items-center justify-center gap-2 p-2.5 bg-blue-50/90 rounded-xl border border-blue-200 text-xs text-blue-700 font-bold shadow-2xs">
            <svg class="animate-spin h-4 w-4 text-blue-600 shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="inline-block">Sedang memproses foto resi...</span>
        </div>
        @error($model) <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
    </div>
</div>
