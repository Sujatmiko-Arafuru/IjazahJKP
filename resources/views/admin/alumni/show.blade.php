@extends('layouts.admin')

@section('title', 'Detail & Verifikasi Berkas Alumni')
@section('page_title', 'Verifikasi Berkas Alumni')

@section('admin_content')
<div x-data="{ 
    activeTab: 'pas_foto', 
    itemRejectModal: false, 
    activeItemKey: '', 
    activeItemTitle: '',
    catatanItem: '',
    alumniRejectModal: false,
    catatanAlumni: '{{ $alumni->catatan_admin }}'
}">
    <!-- Back button & Global verification action bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <a href="{{ route('admin.alumni.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200 w-fit">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>

        <div class="flex items-center gap-2">
            @if($alumni->status_verifikasi !== 'Sudah Diverifikasi')
                <!-- Setujui Seluruh Berkas -->
                <form method="POST" action="{{ route('admin.alumni.verify', $alumni->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_verifikasi" value="Sudah Diverifikasi" />
                    <button 
                        type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin menyetujui seluruh berkas alumni ini?')"
                        class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 hover:shadow-lg rounded-xl transition-all duration-200 cursor-pointer"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Setujui Semua Berkas
                    </button>
                </form>

                <!-- Tolak Verifikasi Alumni -->
                <button 
                    type="button"
                    @click="alumniRejectModal = true" 
                    class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-600/20 hover:shadow-lg rounded-xl transition-all duration-200 cursor-pointer"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak Verifikasi
                </button>
            @endif
        </div>
    </div>

    <!-- Main Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Biodata Summary (Col span 5) -->
        <div class="lg:col-span-5 space-y-6">
            <x-card title="Informasi Alumni" subtitle="Biodata &amp; Status Pendaftaran">
                <!-- Profile Thumbnail Center Box -->
                <div class="flex flex-col items-center pb-6 border-b border-slate-100 mb-6">
                    @if($alumni->dokumen && $alumni->dokumen->pas_foto)
                        <img src="{{ route('admin.dokumen.view', [$alumni->id, 'pas_foto']) }}" 
                             class="w-24 h-32 object-cover rounded-2xl border border-slate-200 shadow-md mb-3"
                             alt="Pas Foto">
                    @else
                        <div class="w-24 h-32 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] text-slate-400 font-bold uppercase mb-3">
                            No Foto
                        </div>
                    @endif
                    <h3 class="font-bold text-slate-800 text-sm tracking-tight">{{ $alumni->nama }}</h3>
                    <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase mt-0.5">NIM: {{ $alumni->nim }}</span>
                    <span class="text-[11px] text-primary font-black tracking-tight mt-1 px-2.5 py-0.5 rounded-full bg-primary/10 border border-primary/20">{{ $alumni->nomor_registrasi }}</span>
                    
                    <div class="mt-3 flex gap-2">
                        <x-badge type="{{ $alumni->status_verifikasi }}">
                            {{ $alumni->status_verifikasi }}
                        </x-badge>
                    </div>
                </div>

                <!-- Fields -->
                <div class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 font-semibold block">Nomor Registrasi</span>
                            <strong class="text-primary font-bold">{{ $alumni->nomor_registrasi }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">NIK (KTP)</span>
                            <strong class="text-slate-700">{{ $alumni->nik }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Tempat, Tanggal Lahir</span>
                            <strong class="text-slate-700">{{ $alumni->tempat_lahir }}, {{ $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('d/m/Y') : '' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Jenis Kelamin</span>
                            <strong class="text-slate-700">{{ $alumni->jenis_kelamin }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Program Studi</span>
                            <strong class="text-slate-700">{{ $alumni->program_studi }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Jurusan</span>
                            <strong class="text-slate-700">{{ $alumni->jurusan }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Tahun Masuk / Lulus</span>
                            <strong class="text-slate-700">{{ $alumni->tahun_masuk }} / {{ $alumni->tahun_lulus }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Email</span>
                            <strong class="text-slate-700">{{ $alumni->email }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Nomor HP / WA</span>
                            <strong class="text-slate-700">{{ $alumni->no_hp }}</strong>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-4">
                        <span class="text-slate-400 font-semibold block">Alamat Lengkap</span>
                        <p class="text-slate-700 mt-1 leading-relaxed">{{ $alumni->alamat }}</p>
                    </div>

                    @if($alumni->catatan_admin)
                        <div class="border-t border-red-100 pt-4 mt-4 bg-rose-50/40 p-3 rounded-2xl border border-rose-200">
                            <span class="text-rose-700 font-bold block text-xs">Catatan Verifikasi Admin:</span>
                            <p class="text-rose-800 mt-1 leading-relaxed text-[11px]">{{ $alumni->catatan_admin }}</p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <!-- Right Column: Interactive Document PDF Viewer (Col span 7) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden min-h-[580px]">
                <!-- Tab Headers (6 Berkas Persyaratan) -->
                <div class="flex border-b border-slate-100 bg-slate-50/70 p-2 gap-1.5 overflow-x-auto">
                    @foreach($berkasConfig as $key => $item)
                        @php
                            $stField = $item['status_field'];
                            $st = $alumni->dokumen ? $alumni->dokumen->$stField : 'Belum Upload';
                        @endphp
                        <button 
                            type="button"
                            @click="activeTab = '{{ $key }}'"
                            :class="activeTab === '{{ $key }}' ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-200/60 bg-white border border-slate-200/50'"
                            class="flex-1 text-center py-2 px-2.5 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all duration-200 whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5"
                        >
                            <span>{{ $item['title'] }}</span>
                            @if($st === 'Diverifikasi')
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            @elseif($st === 'Ditolak')
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- Tab Body Contents -->
                <div class="p-6">
                    @foreach($berkasConfig as $key => $item)
                        @php
                            $fileField = $item['file_field'];
                            $statusField = $item['status_field'];
                            $catatanField = $item['catatan_field'];

                            $file = $alumni->dokumen ? $alumni->dokumen->$fileField : null;
                            $status = $alumni->dokumen ? $alumni->dokumen->$statusField : 'Belum Upload';
                            $catatan = $alumni->dokumen ? $alumni->dokumen->$catatanField : null;
                        @endphp

                        <div x-show="activeTab === '{{ $key }}'" class="space-y-4" style="{{ $key === 'pas_foto' ? '' : 'display: none;' }}">
                            <!-- Top Document Toolbar: Status & Actions -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-slate-800">{{ $item['title'] }}</h4>
                                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-rose-50 text-rose-600 border border-rose-200 uppercase">{{ $item['format'] }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $item['description'] }}</p>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Current Status Badge -->
                                    @if($status === 'Diverifikasi')
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Diverifikasi
                                        </span>
                                    @elseif($status === 'Ditolak')
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-200 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-amber-50 text-amber-600 border border-amber-200 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @endif

                                    @if($status !== 'Diverifikasi')
                                        <!-- Quick 1-Click Approve Document -->
                                        <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $key }}" />
                                            <input type="hidden" name="action" value="approve" />
                                            <button 
                                                type="submit" 
                                                title="Verifikasi / Setujui Dokumen Ini"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-lg shadow-sm transition-all cursor-pointer"
                                            >
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Quick Reject Document Modal Trigger -->
                                        <button 
                                            type="button" 
                                            @click="
                                                activeItemKey = '{{ $key }}';
                                                activeItemTitle = '{{ $item['title'] }}';
                                                catatanItem = '{{ $catatan ?: '' }}';
                                                itemRejectModal = true;
                                            "
                                            title="Tolak Dokumen Ini dengan Catatan"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-[10px] rounded-lg shadow-sm transition-all cursor-pointer"
                                        >
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Tolak
                                        </button>
                                    @endif

                                    @if($file)
                                        <a href="{{ route('pengembalian.view', [$alumni->id, $key]) }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 text-[10px] font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-lg transition-colors shadow-xs">
                                            <svg class="w-3 h-3 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Buka Tab Baru
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($status === 'Ditolak' && $catatan)
                                <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 text-xs text-rose-800">
                                    <strong class="font-bold block text-[10px] uppercase tracking-wider text-rose-700">Catatan Penolakan Admin:</strong>
                                    <p class="text-[11px] leading-relaxed mt-0.5">{{ $catatan }}</p>
                                </div>
                            @endif

                            <!-- Viewer Body -->
                            <div class="relative w-full min-h-[380px] bg-slate-100 rounded-2xl overflow-hidden border border-slate-200">
                                @if($key === 'pas_foto')
                                    @if($file)
                                        <div class="flex flex-col items-center justify-center h-full p-6 bg-slate-50 min-h-[380px]">
                                            <img src="{{ route('pengembalian.view', [$alumni->id, $key]) }}" class="max-h-[320px] rounded-xl shadow-lg border border-white object-contain mb-3" alt="Pas Foto">
                                            <a href="{{ route('pengembalian.view', [$alumni->id, $key]) }}" target="_blank" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                Buka Pas Foto Ukuran Penuh
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full text-xs text-slate-400 font-medium min-h-[380px]">
                                            Pas Foto belum diunggah.
                                        </div>
                                    @endif
                                @elseif($key === 'berkas_persyaratan')
                                    @if($file)
                                        <div class="p-6 bg-white min-h-[380px] flex flex-col justify-between space-y-4">
                                            <!-- File Info & Action Bar -->
                                            <div class="p-4 rounded-2xl bg-gradient-to-br from-rose-50 to-red-50/40 border border-rose-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                                    <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md shadow-rose-600/20">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <span class="text-[10px] font-extrabold text-rose-800 uppercase tracking-wider block">File PDF 5 Berkas Persyaratan Mahasiswa (Maks. 1 MB)</span>
                                                        <span class="text-xs font-bold text-slate-800 truncate block mt-0.5">
                                                            {{ basename($file) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <a href="{{ route('pengembalian.view', [$alumni->id, $key]) }}" target="_blank" class="flex-shrink-0 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-rose-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    <span>📄 Buka PDF di Tab Baru</span>
                                                </a>
                                            </div>

                                            <!-- PDF Inline Iframe Viewer -->
                                            <div class="relative w-full h-[520px] bg-slate-100 rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                                                <iframe src="{{ route('pengembalian.view', [$alumni->id, $key]) }}" class="w-full h-full rounded-2xl">
                                                    <p class="p-6 text-center text-xs text-slate-500">
                                                        Browser Anda tidak mendukung preview PDF. 
                                                        <a href="{{ route('pengembalian.view', [$alumni->id, $key]) }}" target="_blank" class="text-primary font-bold underline">Klik di sini untuk mengunduh berkas PDF</a>
                                                    </p>
                                                </iframe>
                                            </div>

                                            <!-- Checklist items to verify in the PDF -->
                                            <div class="border border-slate-200/80 rounded-2xl p-4 bg-slate-50/50 space-y-2">
                                                <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider block mb-1">
                                                    Checklist 5 Berkas yang Harus Ada di Dalam File PDF:
                                                </span>
                                                <ul class="text-xs text-slate-600 grid grid-cols-1 md:grid-cols-2 gap-2">
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">1</span>
                                                        <span><strong>Screenshot Tracer Study</strong> (Kemenkes/Poltekkes)</span>
                                                    </li>
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">2</span>
                                                        <span><strong>Surat Bebas Pustaka</strong> (Perpustakaan)</span>
                                                    </li>
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">3</span>
                                                        <span><strong>Surat Keabsahan Data Ijazah</strong> (Bermaterai)</span>
                                                    </li>
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">4</span>
                                                        <span><strong>Bukti Pengembalian Toga</strong> (Berita Acara)</span>
                                                    </li>
                                                    <li class="flex items-center gap-2 col-span-1 md:col-span-2">
                                                        <span class="w-4 h-4 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">5</span>
                                                        <span><strong>Screenshot Pengisian Bank Ijazah</strong></span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800">
                                                💡 <strong>Petunjuk Admin:</strong> Periksa kelengkapan isi ke-5 berkas di dalam dokumen PDF pada preview di atas (atau klik <strong>"📄 Buka PDF di Tab Baru"</strong>). Jika seluruh berkas valid dan jelas, klik tombol <strong class="text-emerald-700">Setujui</strong>. Jika berkas buram atau tidak lengkap, klik tombol <strong class="text-rose-700">Tolak</strong> dan tuliskan catatan penolakan.
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full text-xs text-slate-400 font-medium min-h-[380px]">
                                            Berkas persyaratan PDF belum diunggah oleh mahasiswa.
                                        </div>
                                    @endif
                                @elseif($key === 'drive_link')
                                    @php
                                        $driveUrl = $alumni->dokumen ? $alumni->dokumen->drive_link : null;
                                    @endphp
                                    <div class="p-6 bg-white min-h-[380px] flex flex-col justify-between">
                                        <div class="space-y-4">
                                            <!-- Drive Link Box -->
                                            <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50/90 to-indigo-50/40 border border-blue-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md shadow-blue-600/20">
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <span class="text-[10px] font-extrabold text-blue-800 uppercase tracking-wider block">Link Google Drive Berkas Mahasiswa (Arsip Lama)</span>
                                                        @if($driveUrl)
                                                            <a href="{{ $driveUrl }}" target="_blank" class="text-xs font-bold text-primary hover:underline truncate block max-w-full font-mono mt-0.5" title="{{ $driveUrl }}">
                                                                {{ $driveUrl }}
                                                            </a>
                                                        @else
                                                            <span class="text-xs text-slate-400 font-medium block mt-0.5">Link belum dimasukkan</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($driveUrl)
                                                    <a href="{{ $driveUrl }}" target="_blank" class="flex-shrink-0 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-blue-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        <span>📁 Buka Folder Drive Mahasiswa</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: Tolak Dokumen Satuan -->
    <div 
        x-show="itemRejectModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
        style="display: none;"
    >
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100" @click.away="itemRejectModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Tolak Dokumen: <span x-text="activeItemTitle" class="text-primary"></span>
                </h3>
                <button type="button" @click="itemRejectModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Silakan isi catatan atau alasan mengapa dokumen ini ditolak. Mahasiswa akan dapat melihat catatan ini dan mengunggah ulang perbaikan file PDF.
            </p>

            <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}">
                @csrf
                <input type="hidden" name="item_key" :value="activeItemKey" />
                <input type="hidden" name="action" value="reject" />

                <div class="mb-4">
                    <label for="catatan_admin_item" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan / Alasan Penolakan <span class="text-rose-500">* (Wajib)</span></label>
                    <textarea 
                        name="catatan_admin" 
                        id="catatan_admin_item" 
                        x-model="catatanItem" 
                        rows="3" 
                        required
                        class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/20"
                        placeholder="Contoh: Dokumen PDF terpotong atau buram, mohon unggah ulang dengan jelas."
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="itemRejectModal = false" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors cursor-pointer">
                        Simpan Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Tolak Verifikasi Alumni Keseluruhan -->
    <div 
        x-show="alumniRejectModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
        style="display: none;"
    >
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100" @click.away="alumniRejectModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Tolak Verifikasi Alumni
                </h3>
                <button type="button" @click="alumniRejectModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Anda akan menolak verifikasi berkas untuk alumni <strong class="text-slate-900">{{ $alumni->nama }}</strong>. Silakan isi alasan penolakan di bawah ini.
            </p>

            <form method="POST" action="{{ route('admin.alumni.verify', $alumni->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_verifikasi" value="Ditolak" />

                <div class="mb-4">
                    <label for="catatan_admin_alumni" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan / Alasan Penolakan <span class="text-rose-500">* (Wajib)</span></label>
                    <textarea 
                        name="catatan_admin" 
                        id="catatan_admin_alumni" 
                        x-model="catatanAlumni" 
                        rows="4" 
                        required
                        class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/20"
                        placeholder="Contoh: Berkas persyaratan belum lengkap atau tidak valid."
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="alumniRejectModal = false" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
