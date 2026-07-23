@props(['model', 'placeholder' => '', 'class' => 'input-clean w-full font-bold font-mono'])

<div x-data="{
    val: @entangle($model),
    get formatted() {
        if (this.val === null || this.val === undefined || this.val === '') return '';
        return new Intl.NumberFormat('id-ID').format(this.val);
    },
    set formatted(newVal) {
        let raw = String(newVal).replace(/\D/g, '');
        this.val = raw ? parseInt(raw, 10) : 0;
    }
}" class="w-full">
    <input type="text" 
           x-model="formatted" 
           placeholder="{{ $placeholder }}" 
           {{ $attributes->merge(['class' => $class]) }}>
</div>
