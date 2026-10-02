@props(['id', 'title' => null])

<div
    x-data="{ show: false }"
    x-show="show"
    @open-modal.window="if ($event.detail.id === '{{ $id }}') show = true"
    @close-modal.window="if ($event.detail.id === '{{ $id }}') show = false"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Backdrop with blur -->
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
        @click="show = false"
    ></div>

    <!-- Modal position wrapper -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Modal panel -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 z-10"
        >
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h3 class="text-sm font-bold text-slate-800 tracking-tight">{{ $title ?? 'Modal Title' }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 focus:outline-none rounded-lg p-1.5 hover:bg-slate-100 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            
            <!-- Body slot -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
