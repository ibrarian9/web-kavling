@props([
    'label' => null,
    'model' => '',
    'value' => null,
    'placeholder' => '0',
    'required' => false,
    'badgeColor' => 'slate',
    'helpText' => null,
    'inputClass' => '',
    'error' => null,
    'containerClass' => '',
])

@php
    $wireModel = $model ?: $attributes->wire('model')->value();
    $wireModelClean = $wireModel ? str_replace(['.live', '.defer', '.blur'], '', $wireModel) : '';
    $errorKey = $error ?? ($wireModelClean ?: null);
    
    $badgeClasses = match($badgeColor) {
        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
@endphp

<div 
    class="{{ $containerClass }}"
    x-data="{
        displayVal: '',
        parseValue(raw) {
            if (raw === null || raw === undefined || raw === '') return null;
            let str = String(raw).trim();
            if (!str) return null;

            // If it's already a number
            if (typeof raw === 'number') {
                return Math.round(raw);
            }

            // If it's a decimal string from database e.g. '180000000.00' (standard float/decimal format)
            if (/^\d+\.\d+$/.test(str)) {
                return Math.round(parseFloat(str));
            }

            // If it contains dots as thousands separators e.g. '180.000.000'
            let digits = str.replace(/[^0-9]/g, '');
            return digits ? parseInt(digits, 10) : 0;
        },
        format(num) {
            let val = this.parseValue(num);
            if (val === null || isNaN(val) || (val === 0 && (num === '' || num === null))) return '';
            return val.toLocaleString('id-ID');
        },
        init() {
            @if($wireModelClean)
                let initial = $wire.get('{{ $wireModelClean }}');
                this.displayVal = this.format(initial);
                $wire.watch('{{ $wireModelClean }}', (val) => {
                    let cleanCurrent = this.parseValue(this.displayVal);
                    let cleanNew = this.parseValue(val);
                    if (cleanCurrent !== cleanNew) {
                        this.displayVal = this.format(val);
                    }
                });
            @elseif(!is_null($value))
                this.displayVal = this.format(@js($value));
            @endif
        },
        onInput(e) {
            let input = e.target.value;
            let digits = input.replace(/[^0-9]/g, '');
            let val = digits ? parseInt(digits, 10) : 0;
            this.displayVal = digits ? val.toLocaleString('id-ID') : '';
            @if($wireModelClean)
                $wire.set('{{ $wireModelClean }}', val);
            @endif
        }
    }"
>
    @if($label)
        <label class="block font-bold text-slate-700 mb-1 text-xs uppercase tracking-wider">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="flex rounded-xl shadow-2xs border border-slate-200 overflow-hidden focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/20 bg-white transition">
        <span class="font-mono font-extrabold text-xs px-3 flex items-center shrink-0 border-r select-none {{ $badgeClasses }}">
            Rp
        </span>
        <input 
            type="text" 
            inputmode="numeric" 
            x-model="displayVal"
            @input="onInput($event)"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class' => "w-full px-3 py-2 font-mono font-bold text-xs text-slate-800 bg-transparent focus:outline-none {$inputClass}"]) }}
        >
    </div>

    @if($helpText)
        <p class="text-[11px] text-slate-400 mt-1">{{ $helpText }}</p>
    @endif

    @if($errorKey)
        @error($errorKey) 
            <span class="text-rose-500 text-[11px] mt-0.5 block font-semibold">{{ $message }}</span> 
        @enderror
    @endif
</div>
