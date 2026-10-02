<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk Admin - Portal Layanan Alumni</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-slate-700">
    <div class="min-h-screen flex">
        <!-- Left Side: Campus Vibe Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary via-primary/90 to-info relative items-center justify-center p-12 overflow-hidden">
            <!-- Grid decoration overlay -->
            <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:24px_24px]"></div>
            
            <!-- Floating light circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/10 filter blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-white/10 filter blur-3xl"></div>

            <div class="relative z-10 text-center max-w-md text-white">
                <!-- Large Campus Building SVG Representation -->
                <svg class="w-48 h-48 mx-auto text-white/90 mb-8" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="30" y="60" width="140" height="110" rx="6" fill="currentColor" fill-opacity="0.1" stroke="currentColor" stroke-width="4"/>
                    <rect x="75" y="110" width="50" height="60" rx="3" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="4"/>
                    <line x1="75" y1="140" x2="125" y2="140" stroke="currentColor" stroke-width="4"/>
                    <path d="M15 65L100 15L185 65" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Windows -->
                    <rect x="50" y="80" width="20" height="20" rx="2" stroke="currentColor" stroke-width="3"/>
                    <rect x="130" y="80" width="20" height="20" rx="2" stroke="currentColor" stroke-width="3"/>
                    <!-- Pillars -->
                    <line x1="60" y1="170" x2="60" y2="190" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    <line x1="140" y1="170" x2="140" y2="190" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    <line x1="30" y1="190" x2="170" y2="190" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                </svg>
                
                <h2 class="text-2xl font-extrabold tracking-tight">Portal Layanan Alumni</h2>
                <p class="mt-4 text-sm text-white/80 leading-relaxed font-light">
                    Sistem informasi manajemen pendataan alumni dan verifikasi status kesiapan pengambilan ijazah Poltekkes Kemenkes Denpasar.
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative">
            <div class="mx-auto w-full max-w-sm">
                <!-- Branding Header for mobile -->
                <div class="lg:hidden flex flex-col items-center mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-white shadow-lg mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-none text-center">Poltekkes Kemenkes Denpasar</h1>
                    <span class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Portal Alumni</span>
                </div>

                <div class="mb-8 hidden lg:block">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Masukkan kredensial admin Anda untuk melanjutkan ke dashboard.</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <x-alert type="danger">
                            <ul class="list-disc pl-4 space-y-0.5 text-[10px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Administrator</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full rounded-xl border border-slate-200 pl-10 pr-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="admin@poltekkes-denpasar.ac.id">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required class="w-full rounded-xl border border-slate-200 pl-10 pr-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary/20 border-slate-300 rounded">
                            <label for="remember" class="ml-2 block text-xs text-slate-600 font-semibold cursor-pointer select-none">
                                Ingat Saya
                            </label>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3.5 border border-transparent text-xs font-bold rounded-xl text-white bg-primary hover:bg-primary/95 shadow-md shadow-primary/25 hover:shadow-lg hover:shadow-primary/35 transform hover:-translate-y-0.5 transition-all duration-300">
                            Masuk Ke Dashboard
                        </button>
                    </div>
                </form>

                <!-- Footer back link -->
                <div class="mt-8 text-center">
                    <a href="{{ route('public.home') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <x-toast />
</body>
</html>
