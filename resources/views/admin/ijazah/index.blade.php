@extends('layouts.admin')

@section('title', 'Kelola Pengambilan Ijazah')
@section('page_title', 'Kelola Status Pengambilan Ijazah')

@section('admin_content')
<!-- Header Controls Card -->
<div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('admin.ijazah.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
        <!-- Search bar -->
        <div>
            <label for="search" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pencarian Realtime</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                   @input.debounce.500ms="$el.form.submit()"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                   placeholder="Cari Nama atau NIM...">
        </div>

        <!-- Filter Status Ijazah -->
        <div>
            <label for="status" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status Ijazah</label>
            <select name="status" id="status" @change="$el.form.submit()"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20">
                <option value="">-- Semua Status --</option>
                <option value="Belum Siap" {{ request('status') === 'Belum Siap' ? 'selected' : '' }}>Belum Siap</option>
                <option value="Siap Diambil" {{ request('status') === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="Sudah Diambil" {{ request('status') === 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200">
                Terapkan Pencarian
            </button>
        </div>
    </form>
</div>

<!-- Table Card -->
<x-table>
    <thead>
        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
            <th class="px-6 py-4">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 group">
                    Nama Alumni
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity">⇅</span>
                </a>
            </th>
            <th class="px-6 py-4">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'nim', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 group">
                    NIM
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity">⇅</span>
                </a>
            </th>
            <th class="px-6 py-4">Program Studi</th>
            <th class="px-6 py-4">Status Verifikasi</th>
            <th class="px-6 py-4">Status Ijazah</th>
            <th class="px-6 py-4">Tanggal Siap</th>
            <th class="px-6 py-4">Tanggal Diambil</th>
            <th class="px-6 py-4 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 bg-white">
        @forelse($ijazahList as $ijazah)
            <tr class="hover:bg-slate-50/40 transition-colors">
                <!-- Nama -->
                <td class="px-6 py-3.5 whitespace-nowrap font-bold text-slate-800">
                    {{ $ijazah->alumni->nama ?? 'N/A' }}
                </td>
                <!-- NIM -->
                <td class="px-6 py-3.5 whitespace-nowrap font-semibold text-slate-500">
                    {{ $ijazah->alumni->nim ?? 'N/A' }}
                </td>
                <!-- Prodi -->
                <td class="px-6 py-3.5 whitespace-nowrap text-slate-600">
                    {{ $ijazah->alumni->program_studi ?? 'N/A' }}
                </td>
                <!-- Status Verifikasi -->
                <td class="px-6 py-3.5 whitespace-nowrap">
                    <x-badge type="{{ $ijazah->alumni->status_verifikasi ?? 'Belum Diverifikasi' }}">
                        {{ $ijazah->alumni->status_verifikasi ?? 'Belum Diverifikasi' }}
                    </x-badge>
                </td>
                <!-- Status Ijazah -->
                <td class="px-6 py-3.5 whitespace-nowrap">
                    <x-badge type="{{ $ijazah->status }}">
                        {{ $ijazah->status }}
                    </x-badge>
                </td>
                <!-- Tanggal Siap -->
                <td class="px-6 py-3.5 whitespace-nowrap text-slate-500">
                    {{ $ijazah->tanggal_siap ? $ijazah->tanggal_siap->format('d/m/Y') : '-' }}
                </td>
                <!-- Tanggal Diambil -->
                <td class="px-6 py-3.5 whitespace-nowrap text-slate-500">
                    {{ $ijazah->tanggal_diambil ? $ijazah->tanggal_diambil->format('d/m/Y') : '-' }}
                </td>
                <!-- Aksi -->
                <td class="px-6 py-3.5 whitespace-nowrap text-center">
                    <a href="{{ route('admin.ijazah.edit', $ijazah->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-primary/20 text-[10px] font-bold text-primary bg-primary/5 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-10 text-center text-xs text-slate-400 font-medium">
                    Belum ada data pengambilan ijazah.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-table>

<!-- Pagination Component -->
<x-pagination :paginator="$ijazahList" />
@endsection
