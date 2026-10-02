<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-slate-900 border-r border-slate-800">
        <!-- Logo Header -->
        <div class="flex items-center h-16 px-6 bg-slate-950/40 border-b border-slate-800/60">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-md shadow-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white tracking-tight leading-tight">SIJITU KEPERAWATAN</span>
                    <span class="text-[9px] font-semibold text-slate-500 tracking-wider uppercase leading-tight">ADMIN PANEL</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="flex flex-col flex-1 h-0 overflow-y-auto py-5 px-4 space-y-7">
            <div>
                <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Main Menu</span>
                <nav class="mt-2 space-y-1.5">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all duration-200 {{ Route::is('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ Route::is('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <!-- 1. Verifikasi Berkas Alumni -->
                    <a href="{{ route('admin.alumni.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all duration-200 {{ Route::is('admin.alumni.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ Route::is('admin.alumni.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        Verifikasi Berkas Alumni
                    </a>

                    <!-- 2. Pengambilan Dokumen Kelulusan -->
                    <a href="{{ route('admin.pengembalian.dokumen') }}" class="group flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all duration-200 {{ Route::is('admin.pengembalian.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ Route::is('admin.pengembalian.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pengambilan Dokumen
                    </a>
                </nav>
            </div>
            
            <!-- Quick Actions -->
            <div class="pt-6 border-t border-slate-800">
                <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Aksi</span>
                <div class="mt-2">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full group flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl text-slate-400 hover:bg-red-950/20 hover:text-danger transition-all duration-200">
                            <svg class="mr-3 h-5 w-5 text-slate-500 group-hover:text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
