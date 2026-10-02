@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white pt-10 pb-16 lg:pt-16 lg:pb-24">
    <!-- Background Decorative Blobs -->
    <div class="absolute top-0 right-0 -z-10 translate-x-24 -translate-y-12 transform opacity-20 filter blur-3xl lg:translate-x-32 lg:-translate-y-20">
        <div class="aspect-[1000/1000] w-[60rem] bg-gradient-to-tr from-primary to-info"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
            <!-- Left Text Content -->
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary uppercase tracking-wide mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    Sistem Ijazah Terpadu
                </span>
                
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight sm:text-4xl lg:text-5xl leading-tight">
                    SIJITU <br/>
                    <span class="text-primary bg-gradient-to-r from-primary to-info bg-clip-text text-transparent">Sistem Ijazah Terpadu Jurusan Keperawatan</span>
                </h1>
                
                <p class="mt-4 text-sm text-slate-500 leading-relaxed">
                    Sistem resmi pengelolaan ijazah dan dokumen kelulusan terpadu Jurusan Keperawatan Poltekkes Kemenkes Denpasar. Memfasilitasi pendataan alumni, verifikasi berkas, pemantauan toga, dan pengambilan dokumen kelulusan secara terintegrasi dan transparan.
                </p>
                
                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 justify-center lg:justify-start">
                    <a href="{{ route('public.register') }}" class="inline-flex items-center justify-center px-6 py-3.5 text-xs font-bold rounded-2xl text-white bg-primary hover:bg-primary/90 shadow-md shadow-primary/20 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Pendataan Alumni
                    </a>
                    <a href="{{ route('public.pengembalian') }}" class="inline-flex items-center justify-center px-6 py-3.5 text-xs font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cek Status &amp; Pengambilan Dokumen
                    </a>
                </div>
            </div>
            
            <!-- Right Illustration -->
            <div class="mt-12 sm:mt-16 lg:mt-0 lg:col-span-6 flex justify-center">
                <div class="relative w-full max-w-md">
                    <!-- Outer glows -->
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-info/10 rounded-3xl transform rotate-3 scale-105 filter blur-sm"></div>
                    
                    <!-- Main illustration card wrapper -->
                    <div class="relative bg-white border border-slate-100 rounded-3xl p-8 shadow-2xl flex items-center justify-center min-h-[350px]">
                        <!-- SVG Graduation Student Vector -->
                        <svg class="w-72 h-72 text-slate-800" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background Circles -->
                            <circle cx="100" cy="100" r="80" fill="url(#heroGrad)" fill-opacity="0.08" />
                            <circle cx="100" cy="100" r="60" fill="url(#heroGrad)" fill-opacity="0.12" />
                            
                            <!-- Graduation Cap Top -->
                            <path d="M100 45L160 70L100 95L40 70L100 45Z" fill="#0066B3" />
                            <path d="M100 45L150 66L100 87L50 66L100 45Z" fill="#0077D3" />
                            
                            <!-- Cap Under part -->
                            <path d="M72 84V105C72 113.8 84.5 121 100 121C115.5 121 128 113.8 128 105V84L100 95L72 84Z" fill="#004D8C" />
                            
                            <!-- Tassel -->
                            <path d="M100 70L148 76V98" stroke="#FACC15" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="145" y="98" width="6" height="12" rx="2" fill="#FACC15" />
                            
                            <!-- Certificate Scroll -->
                            <path d="M85 145C85 136.7 91.7 130 100 130C108.3 130 115 136.7 115 145V170C115 172.8 112.8 175 110 175H90C87.2 175 85 172.8 85 170V145Z" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="2"/>
                            <rect x="85" y="148" width="30" height="8" fill="#EF4444" />
                            
                            <!-- Student Silhouette -->
                            <path d="M70 185C70 160 85 145 100 145C115 145 130 160 130 185" fill="#E2E8F0" />
                            <circle cx="100" cy="115" r="15" fill="#E2E8F0" />

                            <defs>
                                <linearGradient id="heroGrad" x1="40" y1="40" x2="160" y2="160" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#0066B3" />
                                    <stop offset="1" stop-color="#3B82F6" />
                                </linearGradient>
                            </defs>
                        </svg>
                        
                        <!-- Floating Badge 1 -->
                        <div class="absolute -top-4 -left-4 bg-white border border-slate-100 shadow-lg rounded-2xl p-3 flex items-center gap-2 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-800 leading-tight">Terverifikasi</span>
                                <span class="text-[8px] text-slate-400 font-semibold uppercase leading-tight">Sistem Alumni</span>
                            </div>
                        </div>
                        
                        <!-- Floating Badge 2 -->
                        <div class="absolute -bottom-4 -right-4 bg-white border border-slate-100 shadow-lg rounded-2xl p-3 flex items-center gap-2 animate-bounce" style="animation-duration: 4s;">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-info flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-800 leading-tight">Verifikasi Dokumen</span>
                                <span class="text-[8px] text-slate-400 font-semibold uppercase leading-tight">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Layanan Utama Section -->
<div class="bg-slate-50 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold text-primary uppercase tracking-widest">Layanan Utama</h2>
            <p class="mt-2 text-2xl font-extrabold text-slate-800 tracking-tight sm:text-3xl">Layanan SIJITU Jurusan Keperawatan</p>
            <p class="mt-3 text-sm text-slate-500 leading-relaxed">Kami menyediakan layanan digital terpadu untuk kebutuhan administratif dan pengambilan dokumen ijazah alumni secara cepat, transparan, dan terstruktur.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Card 1: Pendataan Alumni -->
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:shadow-2xl hover:border-slate-200/60 transition-all duration-300">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-primary uppercase tracking-wider block mb-1">Registrasi Awal</span>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Pendataan Alumni</h3>
                    <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                        Lengkapi biodata diri dan unggah 6 berkas persyaratan (Pas Foto &amp; 5 Dokumen wajib format PDF: Tracer Study, Bebas Pustaka, Keabsahan Data, Pengembalian Toga, dan Bank Ijazah).
                    </p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('public.register') }}" class="inline-flex w-full items-center justify-center px-4 py-3 text-xs font-bold text-white bg-primary rounded-xl hover:bg-primary/95 shadow-md shadow-primary/15 hover:shadow-lg transition-all duration-300">
                        Isi Form Pendataan Alumni
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Cek Status & Pengambilan Dokumen -->
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:shadow-2xl hover:border-slate-200/60 transition-all duration-300">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block mb-1">Monitoring &amp; Verifikasi</span>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Cek Status &amp; Pengambilan Dokumen</h3>
                    <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                        Cek status verifikasi berkas persyaratan Anda serta pantau percentangan kesiapan 6 dokumen kelulusan fisik (Ijazah, Transkrip, Sertifikat Profesi, SKPI, Kartu Alumni, Foto Wisuda).
                    </p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('public.pengembalian') }}" class="inline-flex w-full items-center justify-center px-4 py-3 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg transition-all duration-300">
                        Cek Status &amp; Pengambilan Dokumen
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alur Pelayanan Section -->
<div class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold text-primary uppercase tracking-widest">Alur Proses</h2>
            <p class="mt-2 text-2xl font-extrabold text-slate-800 tracking-tight sm:text-3xl">Alur Layanan SIJITU</p>
            <p class="mt-3 text-sm text-slate-500 leading-relaxed">Ikuti alur proses di bawah ini dari pengisian berkas hingga pengambilan dokumen kelulusan fisik di kampus.</p>
        </div>
        
        <!-- Horizontal Timeline -->
        <div class="relative max-w-5xl mx-auto mt-12">
            <!-- Background line (desktop only) -->
            <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-100 -translate-y-1/2 hidden md:block z-0"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-md text-center md:border-0 md:shadow-none md:bg-transparent">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm mx-auto shadow-md shadow-primary/20 border-4 border-white mb-4">1</div>
                    <h4 class="text-sm font-bold text-slate-800">Isi Data &amp; Berkas PDF</h4>
                    <p class="mt-2 text-[11px] text-slate-400 leading-relaxed">Mengisi biodata lengkap, mengunggah Pas Foto (maks 1 MB), &amp; 1 file PDF gabungan 5 Berkas Persyaratan (maks 1 MB).</p>
                </div>
                
                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-md text-center md:border-0 md:shadow-none md:bg-transparent">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm mx-auto shadow-md shadow-primary/20 border-4 border-white mb-4">2</div>
                    <h4 class="text-sm font-bold text-slate-800">Verifikasi oleh Admin</h4>
                    <p class="mt-2 text-[11px] text-slate-400 leading-relaxed">Admin memverifikasi Pas Foto &amp; kelengkapan isi 5 berkas di dalam file PDF. Jika ada yang ditolak, mahasiswa dapat mengunggah ulang perbaikan.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-md text-center md:border-0 md:shadow-none md:bg-transparent">
                    <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm mx-auto shadow-md shadow-emerald-600/20 border-4 border-white mb-4">3</div>
                    <h4 class="text-sm font-bold text-slate-800">Percentangan Dokumen</h4>
                    <p class="mt-2 text-[11px] text-slate-400 leading-relaxed">Admin mengelola percentangan status 6 dokumen kelulusan fisik alumni (Ijazah, Transkrip, SKPI, dll).</p>
                </div>
                
                <!-- Step 4 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-md text-center md:border-0 md:shadow-none md:bg-transparent">
                    <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm mx-auto shadow-md shadow-emerald-600/20 border-4 border-white mb-4">4</div>
                    <h4 class="text-sm font-bold text-slate-800">Pengambilan Dokumen</h4>
                    <p class="mt-2 text-[11px] text-slate-400 leading-relaxed">Mahasiswa mengambil dokumen fisik di Kampus &amp; Admin menandai status pengambilan (Sudah Diambil / Belum Diambil).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
