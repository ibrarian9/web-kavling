@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-6',
    'headerClass' => 'border-b border-slate-100 pb-4 mb-4',
    'actions' => null,
])

<div {{ $attributes->merge(['class' => "bg-white border border-slate-200/80 rounded-3xl shadow-sm transition-all duration-200 {$padding}"]) }}>
    @if($title || isset($actions))
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $headerClass }}">
            @if($title)
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base tracking-tight">{{ $title }}</h2>
                    @if($subtitle)
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            @if(isset($actions))
                <div class="flex items-center gap-2 flex-wrap">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
