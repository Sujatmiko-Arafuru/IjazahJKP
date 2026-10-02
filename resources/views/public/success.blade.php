@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <!-- Printable Card Container -->
    <div id="printable-receipt" class="bg-white rounded-3xl border border-slate-100 p-8 shadow-xl relative overflow-hidden">
        <!-- Decorative Header Background Gradient Accent -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-emerald-500 to-primary"></div>

        <!-- Animated Success Badge Icon -->
        <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-md shadow-emerald-500/10 animate-pulse">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100/60 text-emerald-700 mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                Tersimpan di Database System
            </span>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Data Alumni Berhasil Dikirim!</h2>
            <p class="text-xs text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                Data diri dan berkas persyaratan Anda telah sukses disimpan ke database dan telah diteruskan ke pihak administrator Poltekkes Kemenkes Denpasar.
            </p>
        </div>

        <!-- Official Registration Box -->
        <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-inner relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Nomor Registrasi Alumni</span>
                    <strong class="text-2xl font-black text-primary tracking-tight" id="regNum">{{ $alumni->nomor_registrasi }}</strong>
                </div>
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        onclick="navigator.clipboard.writeText('{{ $alumni->nomor_registrasi }}'); alert('Nomor registrasi berhasil disalin!');" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white rounded-xl transition-all duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Salin No. Reg
                    </button>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Nama Lengkap</span>
                    <strong class="text-slate-800 font-semibold">{{ $alumni->nama }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">NIM</span>
                    <strong class="text-slate-800 font-semibold">{{ $alumni->nim }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Program Studi</span>
                    <strong class="text-slate-800 font-semibold">{{ $alumni->program_studi }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Jurusan</span>
                    <strong class="text-slate-800 font-semibold">{{ $alumni->jurusan }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Tahun Masuk / Lulus</span>
                    <strong class="text-slate-800 font-semibold">{{ $alumni->tahun_masuk }} / {{ $alumni->tahun_lulus }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Status Verifikasi</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 mt-1">
                        {{ $alumni->status_verifikasi }}
                    </span>
                </div>
            </div>

            <!-- Uploaded Files Summary -->
            <div class="mt-6 pt-4 border-t border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-3">Dokumen Wajib Diterima System</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">Pas Foto Resmi Alumni</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">1. Screenshot Tracer Study (PDF)</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">2. Surat Bebas Pustaka (PDF)</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">3. Keabsahan Data &amp; PDDIKTI (PDF)</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">4. Bukti Pengembalian Toga (PDF)</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-700">5. Bukti SS Bank Ijazah (PDF)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Callout -->
        <div class="mt-6 p-4 rounded-2xl bg-sky-50 border border-sky-100 flex items-start gap-3 text-left">
            <svg class="w-5 h-5 text-sky-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-xs text-sky-900 leading-relaxed">
                <strong>Langkah Selanjutnya:</strong>
                <p class="mt-0.5 text-sky-700">
                    Gunakan <strong>Nomor Registrasi</strong> (<strong>{{ $alumni->nomor_registrasi }}</strong>) atau <strong>NIM &amp; Tanggal Lahir</strong> Anda untuk memantau status verifikasi berkas dan percentangan kesiapan dokumen kelulusan di menu <a href="{{ route('public.pengembalian', ['nomor_registrasi' => $alumni->nomor_registrasi]) }}" class="font-bold underline hover:text-sky-900">Cek Status &amp; Pengambilan Dokumen</a>.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3 print:hidden">
            <button 
                type="button" 
                onclick="window.print()" 
                class="flex-1 inline-flex items-center justify-center px-4 py-3 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all duration-200 cursor-pointer"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Bukti Pendaftaran
            </button>
            <a 
                href="{{ route('public.pengembalian', ['nomor_registrasi' => $alumni->nomor_registrasi]) }}" 
                class="flex-1 inline-flex items-center justify-center px-4 py-3 text-xs font-bold text-white bg-primary hover:bg-primary/95 rounded-xl shadow-lg shadow-primary/20 transition-all duration-200"
            >
                Cek Status &amp; Pengambilan Dokumen
            </a>
        </div>

        <div class="mt-4 text-center print:hidden">
            <a href="{{ route('public.home') }}" class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printable-receipt, #printable-receipt * { visibility: visible; }
    #printable-receipt { position: absolute; left: 0; top: 0; width: 100%; border: none; shadow: none; }
}
</style>
@endsection
