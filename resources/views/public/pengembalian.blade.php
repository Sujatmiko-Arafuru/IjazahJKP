@extends('layouts.public')

@section('title', 'Cek Status & Pengambilan Dokumen')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-primary/95 to-info/95 text-white py-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-md mb-4 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Portal Alumni &bull; Poltekkes Denpasar
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                Cek Status &amp; Pengambilan Dokumen
            </h1>
            <p class="mt-3 text-sm text-blue-50 leading-relaxed">
                Pantau progres verifikasi berkas persyaratan alumni yang telah Anda unggah dan cek kesiapan pengambilan dokumen kelulusan Anda di kampus.
            </p>
        </div>
    </div>
    <!-- Background Blob -->
    <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
</div>

<!-- Main Content Area -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Form Pencarian Dual Method -->
    <div 
        x-data="{ searchTab: '{{ request('nomor_registrasi') ? 'reg' : 'nim' }}' }" 
        class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-100 max-w-3xl mx-auto -mt-16 relative z-20"
    >
        <!-- Tab Switcher -->
        <div class="flex items-center gap-2 p-1 bg-slate-100/80 rounded-2xl mb-6 max-w-md mx-auto">
            <button 
                type="button" 
                @click="searchTab = 'reg'" 
                :class="searchTab === 'reg' ? 'bg-white text-primary shadow-md shadow-slate-200/50 font-extrabold' : 'text-slate-500 font-semibold hover:text-slate-700'"
                class="flex-1 py-2.5 text-xs rounded-xl transition-all duration-200 text-center cursor-pointer"
            >
                Cari via No. Registrasi
            </button>
            <button 
                type="button" 
                @click="searchTab = 'nim'" 
                :class="searchTab === 'nim' ? 'bg-white text-primary shadow-md shadow-slate-200/50 font-extrabold' : 'text-slate-500 font-semibold hover:text-slate-700'"
                class="flex-1 py-2.5 text-xs rounded-xl transition-all duration-200 text-center cursor-pointer"
            >
                Cari via NIM &amp; Tgl Lahir
            </button>
        </div>

        <!-- Form 1: Search via Nomor Registrasi -->
        <form x-show="searchTab === 'reg'" method="GET" action="{{ route('public.pengembalian') }}" class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:items-end">
            <div class="flex-1">
                <label for="nomor_registrasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor Registrasi Alumni <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-8 5h8M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <input type="text" id="nomor_registrasi" name="nomor_registrasi" value="{{ request('nomor_registrasi') }}" placeholder="Contoh: ALM-2026-000001" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                </div>
            </div>

            <div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-primary hover:bg-primary/95 text-white font-bold text-sm rounded-2xl shadow-lg shadow-primary/25 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cek Status
                </button>
            </div>
        </form>

        <!-- Form 2: Search via NIM & Tanggal Lahir -->
        <form x-show="searchTab === 'nim'" method="GET" action="{{ route('public.pengembalian') }}" class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:items-end" style="display: none;">
            <div class="flex-1">
                <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    NIM Mahasiswa <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
                        </svg>
                    </div>
                    <input type="text" id="nim" name="nim" value="{{ request('nim') }}" placeholder="Contoh: P07124219001" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" />
                </div>
            </div>

            <div class="flex-1">
                <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tanggal Lahir (dd/mm/yyyy) <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="tanggal_lahir" 
                        name="tanggal_lahir" 
                        value="{{ request('tanggal_lahir') }}" 
                        placeholder="dd/mm/yyyy (Contoh: 17/08/2001)"
                        class="datepicker-dmy w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                    />
                </div>
            </div>

            <div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-primary hover:bg-primary/95 text-white font-bold text-sm rounded-2xl shadow-lg shadow-primary/25 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cek Status
                </button>
            </div>
        </form>
    </div>

    @if($searched)
        @if(!$alumni)
            <!-- Data Tidak Ditemukan -->
            <div class="max-w-3xl mx-auto mt-8 bg-amber-50 border border-amber-200 rounded-3xl p-8 text-center">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-amber-900">Data Mahasiswa Tidak Ditemukan</h3>
                <p class="mt-2 text-xs text-amber-700 leading-relaxed">
                    Nomor Registrasi atau NIM &amp; Tanggal Lahir yang Anda masukkan tidak cocok dengan data pendaftaran alumni. Silakan pastikan Anda telah melakukan <strong>Pendataan Alumni</strong> terlebih dahulu.
                </p>
                <div class="mt-5">
                    <a href="{{ route('public.register') }}" class="inline-flex items-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition-colors">
                        Isi Form Pendataan Alumni &rarr;
                    </a>
                </div>
            </div>
        @else
            <!-- Profile Alumni Banner -->
            <div class="mt-10 bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xl border border-primary/10 shadow-inner">
                        {{ strtoupper(substr($alumni->nama, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800">{{ $alumni->nama }}</h2>
                        <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500 font-medium">
                            <span>NIM: <strong class="text-slate-700">{{ $alumni->nim }}</strong></span>
                            <span>&bull;</span>
                            <span>Prodi: <strong class="text-slate-700">{{ $alumni->program_studi }}</strong></span>
                            <span>&bull;</span>
                            <span>No. Reg: <strong class="text-slate-700">{{ $alumni->nomor_registrasi }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col text-right">
                        <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Status Verifikasi</span>
                        @if($alumni->status_verifikasi === 'Sudah Diverifikasi')
                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 justify-end">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Seluruh Berkas Terverifikasi
                            </span>
                        @elseif($alumni->status_verifikasi === 'Ditolak')
                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1 justify-end">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Perlu Perbaikan Berkas
                            </span>
                        @else
                            <span class="text-xs font-bold text-amber-600 flex items-center gap-1 justify-end">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Menunggu Verifikasi Admin
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if($alumni->status_verifikasi === 'Ditolak' && $alumni->catatan_admin)
                <div class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-start gap-3 shadow-xs">
                    <div class="w-7 h-7 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-black flex-shrink-0">
                        !
                    </div>
                    <div>
                        <strong class="font-bold text-xs block text-rose-900">Catatan Perbaikan oleh Admin:</strong>
                        <p class="mt-0.5 text-xs leading-relaxed text-rose-700">{{ $alumni->catatan_admin }}</p>
                    </div>
                </div>
            @endif

            <!-- ================= BAGIAN A: VERIFIKASI BERKAS PERSYARATAN ================= -->
            <div class="mt-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Bagian A</span>
                        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Berkas Persyaratan Alumni (Pas Foto &amp; Link Google Drive 5 Berkas)</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Status verifikasi berkas yang Anda unggah saat pendataan. Berkas dengan status <em>Menunggu Review</em> atau <em>Diverifikasi</em> tidak dapat diedit. Jika status <em>Ditolak</em>, Anda dapat memperbarui berkas tersebut.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($berkasConfig as $key => $item)
                        @php
                            $statusField = $item['status_field'];
                            $fileField = $item['file_field'];
                            $catatanField = $item['catatan_field'];

                            $status = $dokumen ? $dokumen->$statusField : 'Belum Upload';
                            $fileVal = $dokumen ? $dokumen->$fileField : null;
                            $catatan = $dokumen ? $dokumen->$catatanField : null;
                        @endphp

                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:border-slate-200 transition-all">
                            <div>
                                <!-- Header Item -->
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                        @if($key === 'pas_foto')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        @else
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/></svg>
                                        @endif
                                    </div>

                                    <!-- Status Badge -->
                                    @if($status === 'Diverifikasi')
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Diverifikasi
                                        </span>
                                    @elseif($status === 'Ditolak')
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200 flex items-center gap-1 animate-pulse">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Menunggu Review
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $item['title'] }}</h3>
                                    <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded {{ $key === 'pas_foto' ? 'bg-indigo-50 text-indigo-600 border border-indigo-200' : 'bg-blue-50 text-blue-600 border border-blue-200' }} uppercase">{{ $item['format'] }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">{{ $item['description'] }}</p>

                                @if($key === 'drive_link')
                                    <div class="mt-3 bg-slate-50 p-3 rounded-2xl border border-slate-100 space-y-1 text-xs">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">5 Berkas yang ada di dalam Drive:</span>
                                        <ul class="text-[11px] text-slate-600 space-y-1 list-disc pl-4">
                                            <li>Screenshot Tracer Study</li>
                                            <li>Surat Bebas Pustaka</li>
                                            <li>Surat Pernyataan Keabsahan Data Ijazah &amp; PDDIKTI</li>
                                            <li>Bukti Pengembalian Toga Bersama Petugas</li>
                                            <li>Bukti Screenshot Pengisian Bank Ijazah</li>
                                        </ul>
                                    </div>
                                @endif

                                <!-- Catatan Admin jika ditolak -->
                                @if($status === 'Ditolak' && $catatan)
                                    <div class="mt-3 p-3 bg-rose-50 rounded-2xl border border-rose-200 text-xs text-rose-700">
                                        <div class="font-bold flex items-center gap-1 mb-1 text-rose-800">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Alasan Penolakan:
                                        </div>
                                        <p class="text-[11px] leading-relaxed">{{ $catatan }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 space-y-3">
                                @if($fileVal)
                                    @if($key === 'pas_foto')
                                        <div class="flex items-center justify-between bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                                            <span class="text-[11px] font-medium text-slate-600 truncate max-w-[150px]">
                                                {{ basename($fileVal) }}
                                            </span>
                                            <a href="{{ route('pengembalian.view', ['alumniId' => $alumni->id, 'itemKey' => $key]) }}" target="_blank" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                                Lihat Foto
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between bg-blue-50/60 px-3 py-2 rounded-xl border border-blue-100">
                                            <span class="text-[11px] font-medium text-blue-900 truncate max-w-[200px]">
                                                {{ $fileVal }}
                                            </span>
                                            <a href="{{ $fileVal }}" target="_blank" class="text-xs font-extrabold text-blue-600 hover:underline flex items-center gap-1">
                                                Buka Link Drive
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    @endif
                                @endif

                                @if($status === 'Ditolak')
                                    <!-- Mahasiswa BISA upload ulang / update link hanya jika status DITOLAK -->
                                    <form method="POST" action="{{ route('public.pengembalian.upload') }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="alumni_id" value="{{ $alumni->id }}" />
                                        @if(request('nomor_registrasi'))
                                            <input type="hidden" name="nomor_registrasi" value="{{ request('nomor_registrasi') }}" />
                                        @else
                                            <input type="hidden" name="nim" value="{{ $alumni->nim }}" />
                                            <input type="hidden" name="tanggal_lahir" value="{{ $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('Y-m-d') : '' }}" />
                                        @endif
                                        <input type="hidden" name="item_key" value="{{ $key }}" />

                                        @if($key === 'pas_foto')
                                            <div>
                                                <input type="file" name="file" required accept="image/*,.pdf" class="block w-full text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition-all cursor-pointer" />
                                            </div>
                                        @else
                                            <div>
                                                <input type="url" name="drive_link" required placeholder="https://drive.google.com/drive/folders/..." value="{{ $fileVal }}" class="block w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-rose-500 bg-white" />
                                            </div>
                                        @endif

                                        <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Simpan Perbaikan {{ $key === 'pas_foto' ? 'Foto' : 'Link Drive' }}
                                        </button>
                                    </form>
                                @elseif($status === 'Diverifikasi')
                                    <div class="py-2.5 text-center text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-xl flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Berkas Disetujui (Terkunci)
                                    </div>
                                @else
                                    <div class="py-2.5 text-center text-xs font-semibold text-amber-700 bg-amber-50 rounded-xl flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Sedang Ditinjau Admin
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ================= BAGIAN B: PENGAMBILAN DOKUMEN KELULUSAN (ADMIN CHECKLIST) ================= -->
            <div class="mt-14">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold text-info uppercase tracking-widest block mb-1">Bagian B</span>
                        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pengambilan Dokumen Kelulusan</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Status verifikasi dan kesiapan pengambilan fisik 6 dokumen kelulusan oleh admin kampus. Anda tidak perlu mengunggah berkas pada bagian ini.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documentConfig as $key => $item)
                        @php
                            $statusField = $item['status_field'];
                            $catatanField = $item['catatan_field'];

                            $status = $pengembalian ? $pengembalian->$statusField : 'Belum Siap';
                            $catatan = $pengembalian ? $pengembalian->$catatanField : null;
                            $isReady = ($status === 'Diverifikasi');
                        @endphp

                        <div class="bg-white rounded-3xl border {{ $isReady ? 'border-emerald-200 ring-2 ring-emerald-500/10 shadow-emerald-50/50' : 'border-slate-100' }} p-6 shadow-sm flex flex-col justify-between relative overflow-hidden transition-all">
                            <div>
                                <!-- Header Item -->
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="w-11 h-11 rounded-2xl {{ $isReady ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center font-bold">
                                        @if($isReady)
                                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @endif
                                    </div>

                                    <!-- Status Badge -->
                                    @if($isReady)
                                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1 shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Sudah Diambil
                                        </span>
                                    @elseif($status === 'Ditolak')
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Belum Diambil
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Belum Diambil
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $item['title'] }}</h3>
                                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">{{ $item['description'] }}</p>

                                @if($catatan)
                                    <div class="mt-3 p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-600">
                                        <span class="font-bold block text-[10px] text-slate-400 uppercase tracking-wider mb-0.5">Catatan Petugas:</span>
                                        <p class="text-[11px] leading-relaxed">{{ $catatan }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100">
                                @if($isReady)
                                    <div class="py-2.5 px-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-[11px] font-semibold text-emerald-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Dokumen fisik telah siap diambil di Bagian Akademik.</span>
                                    </div>
                                @else
                                    <div class="py-2.5 px-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] font-medium text-slate-500 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Menunggu verifikasi centang oleh petugas admin.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
