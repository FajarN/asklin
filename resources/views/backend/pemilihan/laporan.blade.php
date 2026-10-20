@extends('layouts.backend.layout')

@section('title', 'Laporan & Rekapitulasi Suara Pemilihan')
@section('page_title', 'Laporan Pemilihan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-rose-500 to-red-600 flex items-center justify-center text-white text-xl shadow-md shadow-red-500/20">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                    Laporan Rekapitulasi Suara Pemilihan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Unduh berita acara dan rekapitulasi perolehan suara faskes (LUBER JURDIL).
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pemilihan.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-xl">
                <i class="fas fa-chart-pie mr-1"></i> Dashboard Hasil
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-4 bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                <i class="fas fa-table text-red-600"></i> Rekapitulasi Tiap Sesi Pemilihan
            </h4>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-full">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 border-b border-slate-200 dark:border-slate-700">
                            <th>No</th>
                            <th>Agenda Pemilihan</th>
                            <th>Wilayah & Periode</th>
                            <th>Total DPT Sah</th>
                            <th>Suara Masuk</th>
                            <th>Partisipasi</th>
                            <th>Calon Terunggul</th>
                            <th>Status</th>
                            <th class="text-center">Aksi / Export</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($pemilihanList as $idx => $p)
                            <tr class="border-b border-slate-100 dark:border-slate-800">
                                <td>{{ $pemilihanList->firstItem() + $idx }}</td>
                                <td>
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $p->judul }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $p->tanggal_mulai->format('d M Y') }} s/d {{ $p->tanggal_selesai->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-info bg-cyan-600 text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold">
                                        {{ $p->tingkat }}
                                    </span>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $p->wilayah_nama }}</span>
                                    <div class="text-[11px] text-slate-400">Periode {{ $p->periode_text }}</div>
                                </td>
                                <td>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ number_format($p->total_dpt) }}</span> Klinik
                                </td>
                                <td>
                                    <span class="font-bold text-emerald-600">{{ number_format($p->total_suara) }}</span> Suara
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs">{{ $p->persen_partisipasi }}%</span>
                                        <div class="w-16 bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-red-600 h-1.5 rounded-full" style="width: {{ $p->persen_partisipasi }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($p->total_suara > 0 && $p->calon_terunggul)
                                        <div class="font-bold text-slate-800 dark:text-white text-xs">
                                            {{ $p->calon_terunggul->nama_calon }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">No. {{ $p->calon_terunggul->nomor_urut }} ({{ $p->calon_terunggul->total_suara }} suara)</div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'draft' => 'bg-slate-200 text-slate-700',
                                            'aktif' => 'bg-emerald-100 text-emerald-800',
                                            'selesai' => 'bg-blue-100 text-blue-800',
                                            'ditutup' => 'bg-rose-100 text-rose-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase {{ $badges[$p->status] ?? '' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('pemilihan.laporan.export', $p->id) }}" class="btn btn-xs btn-outline-success text-emerald-600 hover:text-white" title="Unduh Rekap Excel">
                                            <i class="fas fa-file-excel mr-1"></i> Excel
                                        </a>

                                        @can('pemilihan-audit-suara')
                                            <a href="{{ route('pemilihan.audit', $p->id) }}" class="btn btn-xs btn-outline-danger text-rose-600 hover:text-white" title="Khusus Superadmin: Audit Suara">
                                                <i class="fas fa-shield-alt"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-slate-400 text-xs">
                                    Belum ada data laporan pemilihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pemilihanList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
