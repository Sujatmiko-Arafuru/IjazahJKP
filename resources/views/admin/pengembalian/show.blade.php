@extends('layouts.admin')

@section('title', 'Detail Pengambilan Dokumen')
@section('page_title', 'Detail Pengambilan Dokumen Kelulusan')

@section('admin_content')
<div x-data="{ 
    activeTab: 'foto_ijazah',
    rejectModalOpen: false,
    rejectItemKey: '',
    rejectItemTitle: '',
    rejectCatatan: ''
}">

    <!-- Top Navigation Bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.pengembalian.dokumen') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-primary/10 text-primary border border-primary/20">
                Percentangan Dokumen Kelulusan
            </span>
        </div>
    </div>

    <!-- Main Content Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Biodata Form Summary (Col 5) -->
        <div class="lg:col-span-5 space-y-6">
            <x-card title="Informasi Alumni" subtitle="Status Biodata &amp; Pendaftaran">
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
                            Berkas: {{ $alumni->status_verifikasi }}
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
                            <span class="text-slate-400 font-semibold block">Nomor HP / WA</span>
                            <strong class="text-slate-700">{{ $alumni->no_hp }}</strong>
                        </div>
                    </div>

                    <!-- Summary Status 6 Dokumen Kelulusan -->
                    <div class="border-t border-slate-100 pt-4 mt-4 space-y-2">
                        <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block mb-2">Ringkasan Status 6 Dokumen</span>
                        
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            @foreach($documentConfig as $key => $config)
                                @php
                                    $statusField = $config['status_field'];
                                    $status = $pengembalian ? $pengembalian->$statusField : 'Belum Upload';
                                @endphp
                                <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <span class="font-medium text-slate-600 truncate">{{ $config['title'] }}</span>
                                    @if($status === 'Diverifikasi')
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">✓ OK</span>
                                    @elseif($status === 'Ditolak')
                                        <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">✕ Tolak</span>
                                    @elseif($status === 'Menunggu Verifikasi')
                                        <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">⌛ Wait</span>
                                    @else
                                        <span class="text-[9px] font-medium text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">—</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Right Column: 6 Graduation Document Viewer Tabs (Col 7) -->
        <div class="lg:col-span-7 space-y-6">
            <x-card title="Pengambilan 6 Dokumen Kelulusan" subtitle="Percentangan Kesiapan Fisik Dokumen">
                <!-- Tabs Bar -->
                <div class="flex overflow-x-auto gap-2 pb-2 mb-6 border-b border-slate-100 no-scrollbar">
                    @foreach($documentConfig as $itemKey => $config)
                        @php
                            $statusField = $config['status_field'];
                            $fileField = $config['file_field'];
                            $status = $pengembalian ? $pengembalian->$statusField : 'Belum Upload';
                            $file = $pengembalian ? $pengembalian->$fileField : null;
                        @endphp
                        <button 
                            type="button"
                            @click="activeTab = '{{ $itemKey }}'" 
                            :class="activeTab === '{{ $itemKey }}' ? 'bg-primary text-white font-bold shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold'"
                            class="px-3.5 py-2 text-xs rounded-xl transition-all duration-200 flex items-center gap-1.5 whitespace-nowrap cursor-pointer"
                        >
                            <span>{{ str_replace(['Upload ', ' (Surat Keterangan Pendamping Ijazah)', ' Official', ' Screenshot Pengisian '], '', $config['title']) }}</span>
                            @if($status === 'Diverifikasi')
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            @elseif($status === 'Ditolak')
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            @elseif($status === 'Menunggu Verifikasi')
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- Tab Panels -->
                @foreach($documentConfig as $itemKey => $config)
                    @php
                        $statusField = $config['status_field'];
                        $fileField = $config['file_field'];
                        $catatanField = $config['catatan_field'];

                        $status = $pengembalian ? $pengembalian->$statusField : 'Belum Upload';
                        $file = $pengembalian ? $pengembalian->$fileField : null;
                        $catatan = $pengembalian ? $pengembalian->$catatanField : null;
                    @endphp

                    <div x-show="activeTab === '{{ $itemKey }}'" x-transition class="space-y-4">
                        <!-- Top Header & Actions for this Document -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                            <div>
                                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">{{ $config['title'] }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-slate-500">{{ $config['description'] }}</span>
                                    <x-badge type="{{ $status === 'Diverifikasi' ? 'Sudah Diverifikasi' : ($status === 'Ditolak' ? 'Ditolak' : 'Belum Diverifikasi') }}">
                                        {{ $status }}
                                    </x-badge>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($status !== 'Diverifikasi')
                                    <!-- Direct Approve Button (✓) -->
                                    <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="item_key" value="{{ $itemKey }}" />
                                        <input type="hidden" name="action" value="approve" />
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs rounded-xl transition-all cursor-pointer"
                                        >
                                            ✓ Setujui Dokumen
                                        </button>
                                    </form>

                                    <!-- Reject Button (✕) -->
                                    <button 
                                        type="button" 
                                        @click="
                                            rejectItemKey = '{{ $itemKey }}';
                                            rejectItemTitle = '{{ addslashes($config['title']) }}';
                                            rejectCatatan = '{{ addslashes($catatan ?? '') }}';
                                            rejectModalOpen = true;
                                        "
                                        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-xs rounded-xl transition-all cursor-pointer"
                                    >
                                        ✕ Tolak Dokumen
                                    </button>
                                @endif

                                @if($file)
                                    <!-- Open in New Tab -->
                                    <a 
                                        href="{{ route('pengembalian.view', [$alumni->id, $itemKey]) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-colors"
                                    >
                                        Buka Tab Baru
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan Admin if Rejected -->
                        @if($status === 'Ditolak' && $catatan)
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-start gap-2">
                                <svg class="w-4 h-4 text-rose-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <strong class="font-bold">Catatan Penolakan Admin:</strong>
                                    <p class="mt-0.5 leading-relaxed">{{ $catatan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Document Frame Preview or Physical Readiness Status -->
                        @if($file)
                            <div class="relative w-full h-[480px] bg-slate-100 rounded-2xl overflow-hidden border border-slate-200">
                                <iframe src="{{ route('pengembalian.view', [$alumni->id, $itemKey]) }}" class="w-full h-full rounded-2xl">
                                    <p class="p-6 text-center text-xs text-slate-500">Browser Anda tidak mendukung preview. <a href="{{ route('pengembalian.view', [$alumni->id, $itemKey]) }}" target="_blank" class="text-primary font-bold underline">Klik di sini untuk mengunduh berkas</a></p>
                                </iframe>
                            </div>
                        @else
                            <div class="relative w-full p-8 bg-slate-50 rounded-2xl border border-slate-200/80 flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-2xl {{ $status === 'Diverifikasi' ? 'bg-emerald-100 text-emerald-600' : ($status === 'Ditolak' ? 'bg-rose-100 text-rose-600' : 'bg-slate-200/70 text-slate-500') }} flex items-center justify-center mb-4 shadow-sm">
                                    @if($status === 'Diverifikasi')
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @elseif($status === 'Ditolak')
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </div>

                                <h3 class="text-sm font-bold text-slate-800">
                                    Status Pengambilan Dokumen: 
                                    <span class="{{ $status === 'Diverifikasi' ? 'text-emerald-600' : ($status === 'Ditolak' ? 'text-rose-600' : 'text-slate-500') }}">
                                        {{ $status === 'Diverifikasi' ? '✓ Sudah Diambil' : ($status === 'Ditolak' ? '✕ Belum Diambil' : 'Belum Diambil') }}
                                    </span>
                                </h3>

                                <p class="text-xs text-slate-500 max-w-md mt-2 leading-relaxed">
                                    Dokumen <strong>{{ $config['title'] }}</strong> merupakan dokumen kelulusan fisik resmi. Mahasiswa tidak perlu mengunggah berkas ini. Admin hanya perlu <strong>mencentang (✓)</strong> jika dokumen fisik telah diambil oleh mahasiswa, atau <strong>menyilang (✕)</strong> jika belum diambil oleh mahasiswa dengan catatan.
                                </p>

                                <div class="mt-6 flex items-center justify-center gap-3">
                                    <!-- Centang -->
                                    <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}">
                                        @csrf
                                        <input type="hidden" name="item_key" value="{{ $itemKey }}" />
                                        <input type="hidden" name="action" value="approve" />
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Centang: Sudah Diambil
                                        </button>
                                    </form>

                                    <!-- Silang -->
                                    <button 
                                        type="button" 
                                        @click="
                                            rejectItemKey = '{{ $itemKey }}';
                                            rejectItemTitle = '{{ addslashes($config['title']) }}';
                                            rejectCatatan = '{{ addslashes($catatan ?? '') }}';
                                            rejectModalOpen = true;
                                        " 
                                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Silang: Belum Diambil
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </x-card>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div 
        x-show="rejectModalOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" 
        style="display: none;"
    >
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100" @click.away="rejectModalOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    Tolak Dokumen Kelulusan
                </h3>
                <button type="button" @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}">
                @csrf
                <input type="hidden" name="item_key" :value="rejectItemKey" />
                <input type="hidden" name="action" value="reject" />

                <div class="space-y-3 mb-4">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                        <div>Mahasiswa: <strong class="text-slate-800">{{ $alumni->nama }}</strong></div>
                        <div>Dokumen: <strong class="text-rose-600 font-bold" x-text="rejectItemTitle"></strong></div>
                    </div>

                    <div>
                        <label for="catatan_admin_show" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Alasan / Catatan Penolakan <span class="text-rose-500">* (Wajib)</span>
                        </label>
                        <textarea 
                            id="catatan_admin_show" 
                            name="catatan_admin" 
                            rows="3" 
                            required 
                            x-model="rejectCatatan" 
                            placeholder="Contoh: Berkas PDF tidak terbaca/rusak, mohon unggah ulang dokumen asli." 
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500"
                        ></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors cursor-pointer flex items-center gap-1">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
