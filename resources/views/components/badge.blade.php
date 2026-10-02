@props(['type' => 'info'])

@php
    $classes = match($type) {
        'success', 'Sudah Diverifikasi', 'Siap Diambil' => 'bg-success/10 text-success border-success/20',
        'danger', 'Belum Siap', 'Ditolak' => 'bg-danger/10 text-danger border-danger/20',
        'warning', 'Belum Diverifikasi' => 'bg-warning/10 text-amber-600 border-warning/20',
        'neutral', 'Sudah Diambil', 'grey' => 'bg-slate-100 text-slate-600 border-slate-200/60',
        default => 'bg-info/10 text-info border-info/20',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-full border leading-none tracking-wide uppercase ' . $classes]) }}>
    {{ $slot }}
</span>
