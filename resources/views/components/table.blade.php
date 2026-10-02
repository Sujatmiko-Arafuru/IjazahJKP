<div class="overflow-x-auto w-full border border-slate-100 shadow-sm rounded-2xl bg-white">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-100 text-left text-xs text-slate-600']) }}>
        {{ $slot }}
    </table>
</div>
