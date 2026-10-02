@extends('layouts.admin')

@section('title', 'Perbarui Pengambilan Ijazah')
@section('page_title', 'Perbarui Kesiapan Ijazah')

@section('admin_content')
<!-- Back button -->
<div class="mb-6">
    <a href="{{ route('admin.ijazah.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar
    </a>
</div>

<!-- Main Edit Form Card -->
<div class="max-w-2xl bg-white rounded-3xl border border-slate-100 p-8 shadow-xl">
    <div class="border-b border-slate-100 pb-4 mb-6">
        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Penerima Ijazah</span>
        <h3 class="text-base font-bold text-slate-800 tracking-tight">{{ $ijazah->alumni->nama ?? 'N/A' }} ({{ $ijazah->alumni->nim ?? 'N/A' }})</h3>
        <p class="text-[11px] text-slate-400 mt-1">Program Studi: {{ $ijazah->alumni->program_studi ?? 'N/A' }} | Tahun Lulus: {{ $ijazah->alumni->tahun_lulus ?? 'N/A' }}</p>
    </div>

    <form method="POST" action="{{ route('admin.ijazah.update', $ijazah->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <x-alert type="danger">
                <ul class="list-disc pl-4 space-y-0.5 text-[10px]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Status Ijazah -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Ijazah</label>
                <select name="status" id="status" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
                    <option value="Belum Siap" {{ $ijazah->status === 'Belum Siap' ? 'selected' : '' }}>Belum Siap</option>
                    <option value="Siap Diambil" {{ $ijazah->status === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="Sudah Diambil" {{ $ijazah->status === 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
            </div>

            <!-- Tanggal Siap -->
            <div>
                <label for="tanggal_siap" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Siap Diambil</label>
                <input type="date" name="tanggal_siap" id="tanggal_siap" 
                       value="{{ $ijazah->tanggal_siap ? $ijazah->tanggal_siap->format('Y-m-d') : '' }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
            </div>

            <!-- Tanggal Diambil -->
            <div>
                <label for="tanggal_diambil" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Penyerahan (Diambil)</label>
                <input type="date" name="tanggal_diambil" id="tanggal_diambil" 
                       value="{{ $ijazah->tanggal_diambil ? $ijazah->tanggal_diambil->format('Y-m-d') : '' }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
            </div>

            <!-- Lokasi Pengambilan -->
            <div>
                <label for="lokasi_pengambilan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lokasi Pengambilan</label>
                <input type="text" name="lokasi_pengambilan" id="lokasi_pengambilan" 
                       value="{{ $ijazah->lokasi_pengambilan ?? 'Gedung Rektorat Poltekkes Denpasar Lt. 2 (Bagian Akademik)' }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                       placeholder="Contoh: Loket BAAK">
            </div>

            <!-- Jam Operasional -->
            <div class="sm:col-span-2">
                <label for="jam_operasional" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Operasional Pelayanan</label>
                <input type="text" name="jam_operasional" id="jam_operasional" 
                       value="{{ $ijazah->jam_operasional ?? 'Senin - Kamis: 09:00 - 15:00 WITA | Jumat: 09:00 - 14:30 WITA' }}"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                       placeholder="Contoh: Senin - Jumat, 08:00 - 15:00 WITA">
            </div>
        </div>

        <!-- Keterangan -->
        <div>
            <label for="keterangan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan / Persyaratan Tambahan</label>
            <textarea name="keterangan" id="keterangan" rows="4" 
                      class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                      placeholder="Masukkan catatan pendukung (misal: membawa materai, bebas pustaka fisik, dll...)">{{ $ijazah->keterangan }}</textarea>
        </div>

        <!-- Submit actions -->
        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2.5">
            <a href="{{ route('admin.ijazah.index') }}" class="px-4 py-2.5 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-primary hover:bg-primary/95 rounded-xl shadow-lg shadow-primary/20 transition-all duration-200">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
