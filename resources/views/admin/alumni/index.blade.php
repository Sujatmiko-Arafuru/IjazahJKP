@extends('layouts.admin')

@section('title', 'Daftar Alumni')
@section('page_title', 'Verifikasi Berkas Alumni')

@section('admin_content')
<div x-data="{ rejectModalOpen: false, activeAlumniId: null, activeAlumniName: '', catatanAdmin: '' }">
    <!-- Header Controls Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.alumni.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search bar -->
            <div>
                <label for="search" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian Realtime</label>
                <div class="relative rounded-xl shadow-sm">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           @input.debounce.500ms="$el.form.submit()"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                           placeholder="Cari No. Reg, Nama, NIM...">
                </div>
            </div>

            <!-- Filter Prodi -->
            <div>
                <label for="program_studi" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Program Studi</label>
                <select name="program_studi" id="program_studi" @change="$el.form.submit()"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
                    <option value="">-- Semua Prodi --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi }}" {{ request('program_studi') === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status_verifikasi" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Verifikasi</label>
                <select name="status_verifikasi" id="status_verifikasi" @change="$el.form.submit()"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
                    <option value="">-- Semua Status --</option>
                    <option value="Belum Diverifikasi" {{ request('status_verifikasi') === 'Belum Diverifikasi' ? 'selected' : '' }}>Belum Diverifikasi</option>
                    <option value="Sudah Diverifikasi" {{ request('status_verifikasi') === 'Sudah Diverifikasi' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                    <option value="Ditolak" {{ request('status_verifikasi') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200 cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari Data
                </button>
                @if(request()->hasAny(['search', 'program_studi', 'status_verifikasi']))
                    <a href="{{ route('admin.alumni.index') }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition-all duration-200" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card without Horizontal Scroll -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-3 w-12 text-center">Foto</th>
                    <th class="py-3 px-4">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1 group">
                            Mahasiswa &amp; No. Reg
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">⇅</span>
                        </a>
                    </th>
                    <th class="py-3 px-3">Program Studi</th>
                    <th class="py-3 px-3">Kontak &amp; Tgl</th>
                    <th class="py-3 px-3 text-center">Status</th>
                    <th class="py-3 px-4 text-center w-44">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($alumniList as $alumni)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <!-- Thumbnail Foto -->
                        <td class="py-3 px-3 text-center align-middle">
                            @if($alumni->dokumen && $alumni->dokumen->pas_foto)
                                <img src="{{ route('admin.dokumen.view', [$alumni->id, 'pas_foto']) }}" 
                                     class="w-9 h-11 object-cover rounded-lg border border-slate-200/60 shadow-inner inline-block"
                                     alt="Pas Foto">
                            @else
                                <div class="w-9 h-11 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[9px] text-slate-400 font-bold uppercase inline-block leading-none pt-4">
                                    No Pic
                                </div>
                            @endif
                        </td>
                        <!-- Mahasiswa Info (Nama, No Reg, NIM) -->
                        <td class="py-3 px-4 align-middle">
                            <div class="font-bold text-slate-800 text-xs leading-snug">{{ $alumni->nama }}</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] font-extrabold text-primary tracking-tight">{{ $alumni->nomor_registrasi }}</span>
                                <span class="text-[10px] font-medium text-slate-400">NIM: {{ $alumni->nim }}</span>
                            </div>
                        </td>
                        <!-- Program Studi -->
                        <td class="py-3 px-3 align-middle font-medium text-slate-700">
                            {{ $alumni->program_studi }}
                        </td>
                        <!-- Kontak & Tanggal -->
                        <td class="py-3 px-3 align-middle text-slate-500">
                            <div class="truncate max-w-[140px] text-slate-700 font-medium" title="{{ $alumni->email }}">{{ $alumni->email }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $alumni->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <!-- Status -->
                        <td class="py-3 px-3 align-middle text-center">
                            <x-badge type="{{ $alumni->status_verifikasi }}">
                                {{ $alumni->status_verifikasi }}
                            </x-badge>
                        </td>
                        <!-- Direct Action Buttons: Approve (✓), Reject (✕), Detail -->
                        <td class="py-3 px-4 align-middle text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($alumni->status_verifikasi !== 'Sudah Diverifikasi')
                                    <!-- Direct Approve Button (✓) -->
                                    <form method="POST" action="{{ route('admin.alumni.verify', $alumni->id) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_verifikasi" value="Sudah Diverifikasi" />
                                        <button 
                                            type="submit" 
                                            title="Setujui Verifikasi Berkas"
                                            class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-200 flex items-center justify-center font-bold text-sm shadow-sm hover:shadow transition-all duration-200 cursor-pointer"
                                        >
                                            ✓
                                        </button>
                                    </form>

                                    <!-- Direct Reject Button (✕) -->
                                    <button 
                                        type="button" 
                                        title="Tolak Verifikasi Berkas"
                                        @click="
                                            activeAlumniId = {{ $alumni->id }};
                                            activeAlumniName = '{{ addslashes($alumni->nama) }}';
                                            catatanAdmin = '';
                                            rejectModalOpen = true;
                                        "
                                        class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-200 flex items-center justify-center font-bold text-sm shadow-sm hover:shadow transition-all duration-200 cursor-pointer"
                                    >
                                        ✕
                                    </button>
                                @endif

                                <!-- Detail Button -->
                                <a 
                                    href="{{ route('admin.alumni.show', $alumni->id) }}" 
                                    title="Lihat Detail Profil & Berkas"
                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-800 hover:text-white text-slate-700 font-bold text-[11px] rounded-xl transition-all duration-200 inline-flex items-center gap-1"
                                >
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400 font-medium">
                            Belum ada data alumni terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Reject Modal -->
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
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Tolak Verifikasi Alumni
                </h3>
                <button type="button" @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Anda akan menolak verifikasi berkas untuk alumni <strong x-text="activeAlumniName" class="text-slate-900"></strong>.
            </p>

            <form method="POST" :action="'/admin/alumni/' + activeAlumniId + '/verify'">
                @csrf
                @method('PUT')
                <input type="hidden" name="status_verifikasi" value="Ditolak" />

                <div class="mb-4">
                    <label for="catatan_admin" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan / Alasan Penolakan <span class="text-rose-500">* (Wajib)</span></label>
                    <textarea 
                        name="catatan_admin" 
                        id="catatan_admin" 
                        x-model="catatanAdmin" 
                        rows="3" 
                        required
                        class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/20"
                        placeholder="Contoh: Pas foto kurang jelas/buram, mohon perbarui foto."
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pagination Component -->
    <div class="mt-4">
        <x-pagination :paginator="$alumniList" />
    </div>
</div>
@endsection
