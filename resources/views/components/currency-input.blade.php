@props([
    'label' => null,
    'model' => '',
    'value' => 0,
    'placeholder' => '0',
    'required' => false,
    'badgeColor' => 'emerald',
    'helpText' => null,
    'inputClass' => '',
    'error' => null,
])

@php
    $errorKey = $error ?? ($model ? str_replace(['.live', '.defer', '.blur'], '', $model) : null);
    $wireModel = $model ? str_replace(['.live', '.defer', '.blur'], '', $model) : '';
@endphp

<div 
    x-data="{
        displayVal: '',
        format(num) {
            if (num === null || num === undefined || num === '') return '';
            let digits = String(num).replace(/[^0-9]/g, '');
            if (!digits) return '';
            return parseInt(digits, 10).toLocaleString('id-ID');
        },
        init() {
            @if($wireModel)
                this.displayVal = this.format($wire.get('{{ $wireModel }}'));
                $wire.watch('{{ $wireModel }}', (val) => {
                    let cleanCurrent = this.displayVal.replace(/[^0-9]/g, '');
                    let cleanNew = String(val || '').replace(/[^0-9]/g, '');
                    if (cleanCurrent !== cleanNew) {
                        this.displayVal = this.format(val);
                    }
                });
            @else
                this.displayVal = this.format('{{ $value }}');
            @endif
        },
        onInput(e) {
            let input = e.target.value;
            let digits = input.replace(/[^0-9]/g, '');
            this.displayVal = digits ? parseInt(digits, 10).toLocaleString('id-ID') : '';
            @if($wireModel)
                $wire.set('{{ $wireModel }}', digits ? parseInt(digits, 10) : 0);
            @endif
        }
    }"
>
    @if($label)
        <label class="block font-bold text-slate-700 mb-1 text-xs">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="flex rounded-xl shadow-2xs border border-slate-200 overflow-hidden focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/20 bg-white transition">
        <span class="bg-slate-100 text-slate-500 font-mono font-extrabold text-xs px-3 flex items-center shrink-0 border-r border-slate-200 select-none">
            Rp
        </span>
        <input 
            type="text" 
            inputmode="numeric" 
            x-model="displayVal"
            @input="onInput($event)"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => "w-full px-3 py-2 font-mono font-bold text-xs text-slate-800 bg-transparent focus:outline-none {$inputClass}"]) }}
        >
    </div>

    @if($helpText)
        <p class="text-[11px] text-slate-400 mt-1">{{ $helpText }}</p>
    @endif

    @if($errorKey)
        @error($errorKey) 
            <span class="text-red-500 text-[11px] mt-0.5 block font-semibold">{{ $message }}</span> 
        @enderror
    @endif
</div>
