@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-md shadow-slate-100/50 border border-slate-100 overflow-hidden']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100/70 bg-slate-50/20">
            <h3 class="text-sm font-bold text-slate-800 tracking-tight leading-tight">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-wider">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
