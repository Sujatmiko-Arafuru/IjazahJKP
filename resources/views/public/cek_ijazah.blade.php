@extends('layouts.public')

@section('title', 'Cek Pengambilan Ijazah')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <!-- Search Card -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl mb-8">
        <div class="text-center max-w-sm mx-auto mb-6">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Cek Status Pengambilan Ijazah</h2>
            <p class="text-xs text-slate-400 mt-1">Masukkan NIM dan tanggal lahir Anda untuk mengecek status kesiapan ijazah.</p>
        </div>

        <form method="GET" action="{{ route('public.cek_ijazah') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- NIM -->
                <div>
                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" id="nim" required value="{{ request('nim') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Masukkan NIM">
                </div>
                
                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Lahir (dd/mm/yyyy)</label>
                    <input type="text" name="tanggal_lahir" id="tanggal_lahir" required value="{{ request('tanggal_lahir') }}" placeholder="dd/mm/yyyy (Contoh: 17/08/2001)" class="datepicker-dmy w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex w-full items-center justify-center px-4 py-3.5 text-xs font-bold text-white bg-primary hover:bg-primary/95 rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari Data
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    @if ($searched)
        @if (!$alumni)
            <!-- NOT FOUND CARD (Red Card) -->
            <x-card class="border-danger/20 bg-rose-50/10">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-danger/10 text-danger flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-danger">Data tidak ditemukan.</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Silakan periksa kembali NIM dan tanggal lahir yang Anda masukkan. Pastikan Anda sudah terdaftar di sistem pendataan alumni.
                        </p>
                    </div>
                </div>
            </x-card>
        @else
            <!-- FOUND DATA (Information Card) -->
            <x-card class="space-y-6">
                <!-- Header with Status Badges -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 gap-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Status Pengambilan</span>
                        <h3 class="text-sm font-bold text-slate-800">{{ $alumni->nama }}</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <x-badge type="{{ $alumni->status_verifikasi }}">
                            Verifikasi: {{ $alumni->status_verifikasi }}
                        </x-badge>
                        <x-badge type="{{ $alumni->ijazah->status }}">
                            Ijazah: {{ $alumni->ijazah->status }}
                        </x-badge>
                    </div>
                </div>

                <!-- Main Info Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">Nama Lengkap</span>
                        <strong class="text-slate-800">{{ $alumni->nama }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">NIM / Nomor Induk Mahasiswa</span>
                        <strong class="text-slate-800">{{ $alumni->nim }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">Program Studi</span>
                        <strong class="text-slate-800">{{ $alumni->program_studi }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold block mb-0.5">Tahun Lulus</span>
                        <strong class="text-slate-800">{{ $alumni->tahun_lulus }}</strong>
                    </div>
                </div>

                <!-- Certificate Specific Details -->
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-3.5 text-xs">
                    <h4 class="font-bold text-slate-800 text-[11px] uppercase tracking-wider border-b border-slate-200/50 pb-2">Rincian Pengambilan Ijazah</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-slate-400 font-medium block">Tanggal Kesiapan Ijazah:</span>
                            <strong class="text-slate-700">
                                {{ $alumni->ijazah->tanggal_siap ? $alumni->ijazah->tanggal_siap->format('d/m/Y') : '-' }}
                            </strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Tanggal Pengambilan Ijazah:</span>
                            <strong class="text-slate-700">
                                {{ $alumni->ijazah->tanggal_diambil ? $alumni->ijazah->tanggal_diambil->format('d/m/Y') : '-' }}
                            </strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Lokasi Pengambilan:</span>
                            <strong class="text-slate-700">{{ $alumni->ijazah->lokasi_pengambilan ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Jam Operasional Pelayanan:</span>
                            <strong class="text-slate-700">{{ $alumni->ijazah->jam_operasional ?? '-' }}</strong>
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <span class="text-slate-400 font-medium block">Keterangan Tambahan:</span>
                            <p class="text-slate-600 mt-0.5 leading-relaxed font-semibold">{{ $alumni->ijazah->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Progress Tracker -->
                <div class="pt-4 border-t border-slate-100">
                    <h4 class="font-bold text-slate-800 text-xs text-center mb-6">Timeline Status Pelayanan</h4>
                    
                    @php
                        // Check states
                        $step1 = true; // Data dikirim
                        $step2 = $alumni->status_verifikasi === 'Sudah Diverifikasi'; // Diverifikasi
                        $step3 = in_array($alumni->ijazah->status, ['Siap Diambil', 'Sudah Diambil']); // Ijazah Siap
                        $step4 = $alumni->ijazah->status === 'Sudah Diambil'; // Sudah Diambil
                    @endphp

                    <!-- Responsive Horizontal/Vertical Timeline -->
                    <div class="relative">
                        <!-- Horizontal line for desktop -->
                        <div class="absolute top-4 left-0 w-full h-0.5 bg-slate-100 -translate-y-1/2 hidden sm:block z-0"></div>
                        <!-- Completed horizontal line for desktop -->
                        <div 
                            class="absolute top-4 left-0 h-0.5 bg-success -translate-y-1/2 hidden sm:block z-0 transition-all duration-300"
                            style="width: {{ $step4 ? '100%' : ($step3 ? '66%' : ($step2 ? '33%' : '0%')) }}"
                        ></div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 relative z-10">
                            <!-- Step 1: Data Dikirim -->
                            <div class="text-center flex sm:flex-col items-center gap-3 sm:gap-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-4 bg-success text-white border-white shadow shadow-success/20">
                                    ✓
                                </div>
                                <div class="text-left sm:text-center mt-0 sm:mt-2">
                                    <span class="block text-xs font-bold text-slate-800">Data Dikirim</span>
                                    <span class="block text-[10px] text-slate-400">Berkas pendaftaran diterima</span>
                                </div>
                            </div>
                            
                            <!-- Step 2: Diverifikasi -->
                            <div class="text-center flex sm:flex-col items-center gap-3 sm:gap-0">
                                <div 
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-4 transition-colors duration-300"
                                    :class="'{{ $step2 }}' ? 'bg-success text-white border-white shadow shadow-success/20' : 'bg-slate-100 text-slate-400 border-white'"
                                >
                                    {!! $step2 ? '✓' : '2' !!}
                                </div>
                                <div class="text-left sm:text-center mt-0 sm:mt-2">
                                    <span class="block text-xs font-bold transition-colors" :class="'{{ $step2 }}' ? 'text-slate-800' : 'text-slate-400'">Diverifikasi</span>
                                    <span class="block text-[10px] text-slate-400">Verifikasi berkas oleh admin</span>
                                </div>
                            </div>

                            <!-- Step 3: Ijazah Siap -->
                            <div class="text-center flex sm:flex-col items-center gap-3 sm:gap-0">
                                <div 
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-4 transition-colors duration-300"
                                    :class="'{{ $step3 }}' ? 'bg-success text-white border-white shadow shadow-success/20' : 'bg-slate-100 text-slate-400 border-white'"
                                >
                                    {!! $step3 ? '✓' : '3' !!}
                                </div>
                                <div class="text-left sm:text-center mt-0 sm:mt-2">
                                    <span class="block text-xs font-bold transition-colors" :class="'{{ $step3 }}' ? 'text-slate-800' : 'text-slate-400'">Ijazah Siap</span>
                                    <span class="block text-[10px] text-slate-400">Kesiapan fisik dokumen ijazah</span>
                                </div>
                            </div>

                            <!-- Step 4: Sudah Diambil -->
                            <div class="text-center flex sm:flex-col items-center gap-3 sm:gap-0">
                                <div 
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs border-4 transition-colors duration-300"
                                    :class="'{{ $step4 }}' ? 'bg-success text-white border-white shadow shadow-success/20' : 'bg-slate-100 text-slate-400 border-white'"
                                >
                                    {!! $step4 ? '✓' : '4' !!}
                                </div>
                                <div class="text-left sm:text-center mt-0 sm:mt-2">
                                    <span class="block text-xs font-bold transition-colors" :class="'{{ $step4 }}' ? 'text-slate-800' : 'text-slate-400'">Sudah Diambil</span>
                                    <span class="block text-[10px] text-slate-400">Ijazah diterima oleh alumni</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endif
    @endif
</div>
@endsection
