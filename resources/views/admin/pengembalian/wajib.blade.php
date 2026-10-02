@extends('layouts.admin')

@section('title', 'Verifikasi 3 Syarat Utama Wajib')
@section('page_title', 'Menu 3 Syarat Utama Wajib (Pengembalian Toga & Ijazah)')

@section('admin_content')
<div class="space-y-6" x-data="{ modalOpen: false, modalAlumniName: '', modalItemTitle: '', modalAlumniId: null, modalItemKey: '', modalCatatan: '' }">
    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Verifikasi 3 Syarat Utama Wajib</h1>
            <p class="text-xs text-slate-500 mt-1">
                Verifikasi Pengambilan Ijazah, Foto Pengembalian Toga bersama Petugas, dan Screenshot Bank Ijazah. Gunakan tombol centang <strong class="text-emerald-600">(V)</strong> untuk memverifikasi dan silang <strong class="text-rose-600">(X)</strong> untuk menolak/meminta perbaikan.
            </p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.pengembalian.wajib') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari No. Reg, Nama, atau NIM..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                </div>
            </div>

            <!-- Program Studi Filter -->
            <div>
                <label for="program_studi" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Program Studi</label>
                <select id="program_studi" name="program_studi" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Semua Program Studi</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi }}" {{ request('program_studi') === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-primary hover:bg-primary/95 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter Data
                </button>
                @if(request()->hasAny(['search', 'program_studi']))
                    <a href="{{ route('admin.pengembalian.wajib') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Mahasiswa</th>
                        <th class="py-3 px-3 text-center">Pengambilan Ijazah</th>
                        <th class="py-3 px-3 text-center">Foto Pengembalian Toga</th>
                        <th class="py-3 px-3 text-center">Screenshot Bank Ijazah</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($alumniList as $alumni)
                        @php
                            $pengembalian = $alumni->pengembalian;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800 text-sm">{{ $alumni->nama }}</div>
                                <div class="text-[10px] font-extrabold text-primary tracking-tight mt-0.5">{{ $alumni->nomor_registrasi }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">NIM: <span class="font-semibold text-slate-600">{{ $alumni->nim }}</span> &bull; {{ $alumni->program_studi }}</div>
                            </td>

                            <!-- 1. Pengambilan Ijazah -->
                            @foreach(['pengambilan_ijazah', 'pengembalian_toga', 'bank_ijazah'] as $itemKey)
                                @php
                                    $config = $mandatoryConfig[$itemKey];
                                    $statusField = $config['status_field'];
                                    $fileField = $config['file_field'];
                                    $catatanField = $config['catatan_field'];

                                    $status = $pengembalian ? $pengembalian->$statusField : 'Belum Upload';
                                    $file = $pengembalian ? $pengembalian->$fileField : null;
                                    $catatan = $pengembalian ? $pengembalian->$catatanField : null;
                                @endphp
                                <td class="py-4 px-6 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if($status === 'Diverifikasi')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                &check; Diverifikasi
                                            </span>
                                        @elseif($status === 'Ditolak')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200" title="{{ $catatan }}">
                                                &times; Ditolak
                                            </span>
                                        @elseif($status === 'Menunggu Verifikasi')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 animate-pulse">
                                                Menunggu
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">
                                                Belum Upload
                                            </span>
                                        @endif

                                        @if($file)
                                            <a href="{{ route('pengembalian.view', ['alumniId' => $alumni->id, 'itemKey' => $itemKey]) }}" target="_blank" class="text-[10px] font-semibold text-primary hover:underline flex items-center gap-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Lihat File
                                            </a>
                                        @endif

                                        <!-- Centang & Silang Quick Buttons -->
                                        @if($file)
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <!-- Approve Form -->
                                                <form method="POST" action="{{ route('admin.pengembalian.verify', $alumni->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="item_key" value="{{ $itemKey }}" />
                                                    <input type="hidden" name="action" value="approve" />
                                                    <button type="submit" title="Setujui/Verifikasi" class="w-7 h-7 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-sm transition-transform active:scale-95">
                                                        &check;
                                                    </button>
                                                </form>

                                                <!-- Reject Trigger -->
                                                <button type="button" @click="modalOpen = true; modalAlumniName = '{{ addslashes($alumni->nama) }}'; modalItemTitle = '{{ addslashes($config['title']) }}'; modalAlumniId = {{ $alumni->id }}; modalItemKey = '{{ $itemKey }}'; modalCatatan = '{{ addslashes($catatan ?? '') }}'" title="Tolak / Minta Revisi" class="w-7 h-7 rounded-lg bg-rose-500 hover:bg-rose-600 text-white flex items-center justify-center font-bold text-xs shadow-sm transition-transform active:scale-95">
                                                    &times;
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach

                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.alumni.show', $alumni->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                                    Detail Alumni &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data alumni ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alumniList->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $alumniList->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL PENOLAKAN / CATATAN REVISI ADMIN -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-base font-extrabold text-slate-800">Tolak Dokumen &amp; Beri Catatan</h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>

                <form method="POST" :action="'{{ url('admin/pengembalian') }}/' + modalAlumniId + '/verify'">
                    @csrf
                    <input type="hidden" name="item_key" :value="modalItemKey" />
                    <input type="hidden" name="action" value="reject" />

                    <div class="space-y-3">
                        <div class="text-xs text-slate-600">
                            Mahasiswa: <strong class="text-slate-800" x-text="modalAlumniName"></strong><br/>
                            Item Dokumen: <strong class="text-primary" x-text="modalItemTitle"></strong>
                        </div>

                        <div>
                            <label for="catatan_admin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Alasan Penolakan / Catatan Perbaikan <span class="text-danger">*</span>
                            </label>
                            <textarea id="catatan_admin" name="catatan_admin" rows="4" required x-model="modalCatatan" placeholder="Contoh: Bukti foto toga buram dan kurang jelas, mohon upload foto ulang bersama petugas." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-1">
                            &times; Simpan Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
