@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-6 flex items-center justify-between border-t border-slate-100/60 pt-4 px-4 bg-white/50 rounded-2xl">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-300 cursor-default">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Berikutnya</a>
            @else
                <span class="relative inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-300 cursor-default">Berikutnya</span>
            @endif
        </div>
        
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-slate-500">
                    Menampilkan
                    <span class="font-bold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-bold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>
            
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-xl shadow-sm" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center rounded-l-xl border border-slate-150 bg-white px-2.5 py-1.5 text-xs text-slate-300 cursor-default">
                            <span class="sr-only">Previous</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-xl border border-slate-150 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-50 focus:z-20 transition-colors">
                            <span class="sr-only">Previous</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($paginator->render()->elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center border border-slate-150 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative z-10 inline-flex items-center bg-primary px-3 py-1.5 text-xs font-bold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center border border-slate-150 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-50 focus:z-20 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-xl border border-slate-150 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-50 focus:z-20 transition-colors">
                            <span class="sr-only">Next</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="relative inline-flex items-center rounded-r-xl border border-slate-150 bg-white px-2.5 py-1.5 text-xs text-slate-300 cursor-default">
                            <span class="sr-only">Next</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endif
