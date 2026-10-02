<nav x-data="{ open: false }" class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo and Name -->
            <div class="flex items-center">
                <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white shadow-md shadow-primary/20 transform group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-slate-800 tracking-tight leading-tight group-hover:text-primary transition-colors flex items-center gap-1.5">
                            SIJITU
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-primary/10 text-primary font-bold">KEPERAWATAN</span>
                        </span>
                        <span class="text-[10px] font-semibold text-slate-500 tracking-wider uppercase leading-tight">Sistem Ijazah Terpadu</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-2 lg:gap-3">
                <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ Route::is('public.home') ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    Home
                </a>
                <a href="{{ route('public.register') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ Route::is('public.register') ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    Pendataan Alumni
                </a>
                <a href="{{ route('public.pengembalian') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ Route::is('public.pengembalian') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    Cek Status &amp; Pengambilan Dokumen
                </a>
                <a href="#kontak" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all duration-200">
                    Kontak
                </a>
                
                <div class="pl-2 border-l border-slate-200 ml-1">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-primary hover:bg-primary/90 rounded-xl shadow-md shadow-primary/20 transition-all duration-200">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-primary border border-primary/30 bg-primary/5 hover:bg-primary hover:text-white rounded-xl shadow-sm transition-all duration-200">
                            Masuk Admin
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Hamburger Button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:text-primary hover:bg-slate-100 focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden border-b border-slate-100 bg-white" style="display: none;">
        <div class="px-4 pt-3 pb-4 space-y-2">
            <a href="{{ route('public.home') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold {{ Route::is('public.home') ? 'bg-primary/10 text-primary' : 'text-slate-700 hover:bg-slate-100' }}">Home</a>
            <a href="{{ route('public.register') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold {{ Route::is('public.register') ? 'bg-primary/10 text-primary' : 'text-slate-700 hover:bg-slate-100' }}">Pendataan Alumni</a>
            <a href="{{ route('public.pengembalian') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold {{ Route::is('public.pengembalian') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' }}">Cek Status &amp; Pengambilan Dokumen</a>
            <a href="#kontak" @click="open = false" class="block px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100">Kontak</a>
            <div class="pt-3 border-t border-slate-100">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block w-full text-center px-4 py-2.5 text-xs font-bold text-white bg-primary rounded-xl shadow-md">
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 text-xs font-bold text-primary border border-primary/30 bg-primary/5 rounded-xl">
                        Masuk Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
