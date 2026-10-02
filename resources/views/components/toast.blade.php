@props(['message' => null, 'type' => 'success'])

@php
    $success = session('toast_success') ?? (session('success') ?? ($type === 'success' ? $message : null));
    $error = session('toast_error') ?? (session('error') ?? ($type === 'error' ? $message : null));
    $warning = session('toast_warning') ?? (session('warning') ?? ($type === 'warning' ? $message : null));
    $info = session('toast_info') ?? (session('info') ?? ($type === 'info' ? $message : null));
@endphp

<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if($success)
                $nextTick(() => { this.trigger('{{ $success }}', 'success') });
            @elseif($error)
                $nextTick(() => { this.trigger('{{ $error }}', 'error') });
            @elseif($warning)
                $nextTick(() => { this.trigger('{{ $warning }}', 'warning') });
            @elseif($info)
                $nextTick(() => { this.trigger('{{ $info }}', 'info') });
            @endif
        },
        trigger(msg, type) {
            this.message = msg;
            this.type = type;
            this.show = true;
            setTimeout(() => { this.show = false; }, 4000);
        }
    }"
    @toast.window="trigger($event.detail.message, $event.detail.type || 'success')"
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-2 md:translate-y-0 md:translate-x-2"
    x-transition:enter-end="opacity-100 translate-y-0 md:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white rounded-xl shadow-xl border border-slate-100 pointer-events-auto overflow-hidden"
    style="display: none;"
>
    <div class="p-4">
        <div class="flex items-start">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <!-- Success: Green check -->
                <template x-if="type === 'success'">
                    <svg class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <!-- Error: Red cross -->
                <template x-if="type === 'error'">
                    <svg class="h-6 w-6 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <!-- Warning: Yellow warning -->
                <template x-if="type === 'warning'">
                    <svg class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <!-- Info: Blue info -->
                <template x-if="type === 'info'">
                    <svg class="h-6 w-6 text-info" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>
            
            <!-- Content -->
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-semibold text-slate-800" x-text="type.charAt(0).toUpperCase() + type.slice(1)"></p>
                <p class="mt-1 text-xs text-slate-500" x-text="message"></p>
            </div>

            <!-- Close Button -->
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
