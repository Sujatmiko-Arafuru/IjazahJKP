@props(['type' => 'info'])

@php
    $classes = match($type) {
        'success' => 'bg-success/5 text-success border-success/20',
        'danger' => 'bg-danger/5 text-danger border-danger/20',
        'warning' => 'bg-warning/5 text-amber-700 border-warning/20',
        default => 'bg-info/5 text-info border-info/20',
    };
@endphp

<div {{ $attributes->merge(['class' => 'p-4 border rounded-2xl flex items-start gap-3 ' . $classes]) }} role="alert">
    <!-- Icon representation based on alert type -->
    <div class="flex-shrink-0">
        @if($type === 'success')
            <svg class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @elseif($type === 'danger')
            <svg class="h-5 w-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        @elseif($type === 'warning')
            <svg class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        @else
            <svg class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @endif
    </div>
    
    <div class="text-xs font-semibold leading-relaxed">
        {{ $slot }}
    </div>
</div>
