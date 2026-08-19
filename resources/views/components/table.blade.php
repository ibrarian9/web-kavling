@props([
    'headers' => [], // array of header column names or custom th specs
    'loadingTarget' => null,
    'emptyText' => 'Tidak ada data ditemukan.',
    'emptyColspan' => null,
    'wrapperClass' => 'overflow-x-auto sm:overflow-visible relative min-h-[160px]',
])

@php
    $colspan = $emptyColspan ?? (count($headers) > 0 ? count($headers) : 1);
@endphp

<div class="relative card-clean border border-slate-200/80 rounded-3xl shadow-xs bg-white">
    @if($loadingTarget)
        <x-table-loading :target="$loadingTarget" />
    @endif

    <div class="{{ $wrapperClass }}">
        <table 
            {{ $attributes->merge(['class' => 'w-full text-left text-xs text-slate-600']) }}
            @if($loadingTarget)
                wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300"
                wire:target="{{ $loadingTarget }}"
            @endif
        >
            @if(count($headers) > 0)
                <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        @foreach($headers as $header)
                            @if(is_array($header))
                                <th class="{{ $header['class'] ?? 'p-3' }} first:rounded-tl-3xl last:rounded-tr-3xl">{{ $header['label'] ?? '' }}</th>
                            @else
                                <th class="p-3 first:rounded-tl-3xl last:rounded-tr-3xl">{{ $header }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
            @endif

            <tbody class="divide-y divide-slate-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
