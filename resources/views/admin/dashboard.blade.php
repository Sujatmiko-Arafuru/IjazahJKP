@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Statistik &amp; Overview System')

@section('admin_content')
<!-- Statistic Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Total Alumni -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
        </div>
        <div>
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Total Alumni</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-1.5 leading-none">{{ $totalAlumni }}</span>
        </div>
    </div>

    <!-- Card 2: Belum Diverifikasi -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Berkas Pending</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-1.5 leading-none">{{ $belumDiverifikasiTahap1 }}</span>
        </div>
    </div>

    <!-- Card 3: Sudah Diverifikasi -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Berkas Diverifikasi</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-1.5 leading-none">{{ $sudahDiverifikasiTahap1 }}</span>
        </div>
    </div>

    <!-- Card 4: Dokumen Kelulusan Pending -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-info flex items-center justify-center font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Dokumen Belum Siap</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-1.5 leading-none">{{ $pengembalianPending }}</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Chart 1: Alumni per Program Studi -->
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">Grafik Alumni Per Program Studi</h3>
        <div class="relative w-full h-[280px]">
            <canvas id="prodiChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Status Verifikasi 6 Dokumen Kelulusan -->
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">Dokumen Kelulusan Sudah Diambil</h3>
        <div class="relative w-full h-[280px]">
            <canvas id="dokumenChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const prodiLabels = {!! json_encode($prodiLabels) !!};
        const prodiValues = {!! json_encode($prodiValues) !!};

        const dokumenLabels = {!! json_encode($dokumenLabels) !!};
        const dokumenValues = {!! json_encode($dokumenValues) !!};

        // 1. Alumni per Program Studi (Bar Chart)
        const ctxProdi = document.getElementById('prodiChart').getContext('2d');
        new Chart(ctxProdi, {
            type: 'bar',
            data: {
                labels: prodiLabels.length ? prodiLabels : ['Belum ada data'],
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: prodiValues.length ? prodiValues : [0],
                    backgroundColor: '#0066B3',
                    borderRadius: 8,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                    x: {
                        ticks: {
                            callback: function(val, index) {
                                let label = this.getLabelForValue(val);
                                return label.length > 20 ? label.substr(0, 18) + '...' : label;
                            }
                        }
                    }
                }
            }
        });

        // 2. Status 6 Dokumen Kelulusan (Bar Chart)
        const ctxDokumen = document.getElementById('dokumenChart').getContext('2d');
        new Chart(ctxDokumen, {
            type: 'bar',
            data: {
                labels: dokumenLabels,
                datasets: [{
                    label: 'Dokumen Diverifikasi',
                    data: dokumenValues,
                    backgroundColor: ['#10B981', '#059669', '#047857', '#065F46', '#047857', '#10B981'],
                    borderRadius: 8,
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });
    });
</script>
@endsection
