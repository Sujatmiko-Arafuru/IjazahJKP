<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - SIJITU Jurusan Keperawatan</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flatpickr (Strict dd/mm/yyyy date picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @yield('styles')
</head>
<body class="h-full font-sans antialiased text-slate-700">
    <div class="flex h-screen overflow-hidden bg-slate-50">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Panel -->
        <div class="flex flex-col flex-1 w-0 overflow-y-auto focus:outline-none">
            <!-- Top Navbar -->
            <header class="relative z-10 flex flex-shrink-0 h-16 bg-white border-b border-slate-100">
                <div class="flex justify-between flex-1 px-4 sm:px-6">
                    <div class="flex flex-1">
                        <!-- Left blank or title -->
                        <div class="flex items-center text-slate-500 font-medium">
                            @yield('page_title', 'Dashboard')
                        </div>
                    </div>
                    
                    <!-- Admin User Avatar & Profile Dropdown -->
                    <div class="flex items-center ml-4 md:ml-6">
                        <div x-data="{ open: false }" class="relative ml-3">
                            <div>
                                <button @click="open = !open" type="button" class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex flex-col text-right hidden sm:flex">
                                            <span class="text-xs font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium font-mono">{{ '@' . (Auth::user()->username ?? 'admin') }}</span>
                                        </div>
                                        <!-- Avatar with dynamic text initials or placeholder image -->
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-sm shadow-inner border border-primary/10">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-xl shadow-lg border border-slate-100 ring-1 ring-black ring-opacity-5 focus:outline-none py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu" style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-50 text-xs">
                                    <p class="font-medium text-slate-500">Masuk sebagai:</p>
                                    <p class="font-bold text-slate-800 truncate font-mono">{{ '@' . (Auth::user()->username ?? 'admin') }}</p>
                                </div>
                                <a href="{{ route('public.home') }}" class="block px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors" role="menuitem">Lihat Beranda</a>
                                
                                <form method="POST" action="{{ route('admin.logout') }}" class="block w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-danger hover:bg-red-50 transition-colors" role="menuitem">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Main Content Container -->
            <main class="flex-1 relative focus:outline-none py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    @yield('admin_content')
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    <x-toast />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof flatpickr !== 'undefined') {
                flatpickr('.datepicker-dmy, input[type="date"]', {
                    locale: 'id',
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd/m/Y',
                    allowInput: true
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
