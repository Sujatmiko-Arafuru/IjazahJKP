@extends('layouts.admin')

@section('title', 'Pengambilan Dokumen Kelulusan')
@section('page_title', 'Pengambilan Dokumen Kelulusan')

@section('admin_content')
<div class="space-y-6" x-data="{ 
    modalOpen: false, 
    modalAlumniName: '', 
    modalItemTitle: '', 
    modalAlumniId: null, 
    modalItemKey: '', 
    modalCatatan: '',
    detailModalOpen: false,
    selectedAlumni: null
}">

    <!-- Header Banner -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-primary/10 text-primary uppercase tracking-wider">Percentangan Admin</span>
                <h1 class="text-base font-extrabold text-slate-800 tracking-tight">Pengambilan 6 Dokumen Kelulusan</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola kesiapan fisik 6 dokumen kelulusan: <strong>Ijazah Asli, Transkrip Nilai, Sertifikat Profesi, SKPI, Kartu Alumni (IKAPODE), &amp; Foto Wisuda</strong>.<br/>
                Klik <strong class="text-emerald-600">✓ (Centang / Sudah Diambil)</strong> atau <strong class="text-rose-600">✕ (Silang / Belum Diambil)</strong>. Mahasiswa dapat memantau status percentangan ini secara realtime.
            </p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.pengembalian.dokumen') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search Bar -->
            <div>
                <label for="search" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian Realtime</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    @input.debounce.500ms="$el.form.submit()"
                    placeholder="Cari No. Reg, Nama, NIM..." 
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                />
            </div>

            <!-- Program Studi Filter -->
            <div>
                <label for="program_studi" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Program Studi</label>
                <select 
                    id="program_studi" 
                    name="program_studi" 
                    @change="$el.form.submit()"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
                >
                    <option value="">-- Semua Prodi --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi }}" {{ request('program_studi') === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Dokumen -->
            <div>
                <label for="status_filter" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Filter Status Dokumen</label>
                <select 
                    id="status_filter" 
                    name="status_filter" 
                    @change="$el.form.submit()"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
                >
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status_filter') === 'verified' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                    <option value="rejected" {{ request('status_filter') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center px-3 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'program_studi', 'status_filter']))
                    <a href="{{ route('admin.pengembalian.dokumen') }}" class="inline-flex items-center justify-center px-3 py-2 border border-slate-200 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4 min-w-[200px]">Mahasiswa & No. Reg</th>
                        <th class="py-3 px-2 text-center w-28">1. Ijazah</th>
                        <th class="py-3 px-2 text-center w-28">2. Transkrip</th>
                        <th class="py-3 px-2 text-center w-28">3. Profesi</th>
                        <th class="py-3 px-2 text-center w-28">4. SKPI</th>
                        <th class="py-3 px-2 text-center w-28">5. Kartu Alumni</th>
                        <th class="py-3 px-2 text-center w-28">6. Foto Wisuda</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($alumniList as $alumni)
                        @php
                            $pengembalian = $alumni->pengembalian;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Mahasiswa info -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 text-xs leading-snug">{{ $alumni->nama }}</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[10px] font-extrabold text-primary tracking-tight">{{ $alumni->nomor_registrasi }}</span>
                                    <span class="text-[10px] text-slate-400">NIM: {{ $alumni->nim }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $alumni->program_studi }}</div>
                            </td>

                            <!-- 6 Dokumen Kelulusan Columns: Ijazah, Transkrip, Profesi, SKPI, Kartu Alumni, Wisuda -->
                            @foreach(['foto_ijazah', 'bukti_transkrip', 'sertifikat_profesi', 'skpi', 'kartu_ikapode', 'foto_wisuda'] as $itemKey)
                                @php
                                    $config = $documentConfig[$itemKey];
                                    $statusField = $config['status_field'];
                                    $fileField = $config['file_field'];
                                    $catatanField = $config['catatan_field'];

                                    $status = $pengembalian ? $pengembalian->$statusField : 'Belum Siap';
                                    $file = $pengembalian ? $pengembalian->$fileField : null;
                                    $catatan = $pengembalian ? $pengembalian->$catatanField : null;
                                @endphp
                                <td class="py-3.5 px-2 text-center align-middle">
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <!-- Status Badge -->
                                        @if($status === 'Diverifikasi')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                ✓ Sudah Diambil
                                            </span>
                                        @elseif($status === 'Ditolak')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-600 border border-rose-200" title="{{ $catatan }}">
                                                ✕ Belum Diambil
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-medium bg-slate-100 text-slate-400">
                                                Belum Diambil
                                            </span>
                                        @endif

                                        <!-- Percentangan Admin Actions: Centang (✓) atau Silang (✕) -->
                                        @if($status !== 'Diverifikasi')
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <!-- Direct Approve / Centang (✓) -->
                                                <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="item_key" value="{{ $itemKey }}" />
                                                    <input type="hidden" name="action" value="approve" />
                                                    <button 
                                                        type="submit" 
                                                        title="Centang (✓): Dokumen Sudah Diambil" 
                                                        class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-200 flex items-center justify-center font-bold text-xs shadow-xs transition-all cursor-pointer"
                                                    >
                                                        ✓
                                                    </button>
                                                </form>

                                                <!-- Direct Reject Trigger / Silang (✕) -->
                                                <button 
                                                    type="button" 
                                                    @click="
                                                        modalOpen = true; 
                                                        modalAlumniName = '{{ addslashes($alumni->nama) }}'; 
                                                        modalItemTitle = '{{ addslashes($config['title']) }}'; 
                                                        modalAlumniId = {{ $alumni->id }}; 
                                                        modalItemKey = '{{ $itemKey }}'; 
                                                        modalCatatan = '{{ addslashes($catatan ?? '') }}';
                                                    " 
                                                    title="Silang (✕): Belum Siap / Ditolak (Wajib Catatan)" 
                                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-200 flex items-center justify-center font-bold text-xs shadow-xs transition-all cursor-pointer"
                                                >
                                                    ✕
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach

                            <!-- Detail Link -->
                            <td class="py-3.5 px-4 text-center align-middle">
                                <a 
                                    href="{{ route('admin.pengembalian.show', $alumni->id) }}" 
                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-800 hover:text-white text-slate-700 font-bold text-[11px] rounded-xl transition-all duration-200 inline-flex items-center gap-1"
                                    title="Lihat Detail Pengambilan Dokumen"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-xs font-medium">
                                Tidak ada data dokumen kelulusan alumni ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alumniList->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <x-pagination :paginator="$alumniList" />
            </div>
        @endif
    </div>

    <!-- MODAL PENOLAKAN DOKUMEN (Wajib Catatan) -->
    <div 
        x-show="modalOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" 
        style="display: none;"
    >
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100" @click.away="modalOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    Tolak Dokumen Kelulusan
                </h3>
                <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('admin/pengembalian') }}/' + modalAlumniId + '/verify'">
                @csrf
                <input type="hidden" name="item_key" :value="modalItemKey" />
                <input type="hidden" name="action" value="reject" />

                <div class="space-y-3 mb-4">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                        <div>Mahasiswa: <strong class="text-slate-800" x-text="modalAlumniName"></strong></div>
                        <div>Dokumen: <strong class="text-rose-600 font-bold" x-text="modalItemTitle"></strong></div>
                    </div>

                    <div>
                        <label for="catatan_admin_dokumen" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Alasan / Catatan Penolakan <span class="text-rose-500">* (Wajib)</span>
                        </label>
                        <textarea 
                            id="catatan_admin_dokumen" 
                            name="catatan_admin" 
                            rows="3" 
                            required 
                            x-model="modalCatatan" 
                            placeholder="Contoh: Berkas PDF tidak dapat dibaca/kurang jelas, mohon unggah ulang dokumen asli yang jernih." 
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500"
                        ></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
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
