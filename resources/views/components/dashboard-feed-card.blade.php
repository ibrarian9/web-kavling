@props([
    'title' => null,
    'subtitle' => null,
    'actionHref' => null,
    'actionLabel' => 'Lihat Semua',
    'spanClass' => '',
    'table' => true,
])

<div {{ $attributes->merge(['class' => "card-clean rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col justify-between {$spanClass}"]) }}>
    <div>
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 uppercase tracking-wider">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-[11px] text-slate-500 mt-0.5 hidden sm:block">{{ $subtitle }}</p>
                @endif
            </div>

            @if($actionHref)
                <x-button variant="soft-emerald" size="sm" :href="$actionHref" wire:navigate.hover class="min-h-[40px] sm:min-h-[36px]">
                    <span>{{ $actionLabel }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </x-button>
            @endif
        </div>

        @if($table)
            <div class="p-3 bg-slate-50/50 sm:p-0 sm:bg-white">
                <div class="table-responsive-container">
                    <table class="table-responsive-card w-full text-left text-xs">
                        {{ $slot }}
                    </table>
                </div>
            </div>
        @else
            <div class="p-4 sm:p-5">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

