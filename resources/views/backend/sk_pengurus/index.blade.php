@extends('layouts.backend.layout')

@section('title', 'Surat Keputusan (SK) Pengurus')
@section('page_title', 'Surat Keputusan (SK) Pengurus')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Pengelolaan Surat Keputusan (SK) Kepengurusan
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Alur verifikasi pengesahan susunan pengurus cabang, daerah, dan pusat secara berjenjang hingga penerbitan SK Ketua Umum resmi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('struktur_organisasi.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sitemap mr-1"></i>
                <span>Kelola Struktur Organisasi</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card p-3 sm:p-4 border-l-4 border-l-sky-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Pengajuan SK</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">{{ $stats['total'] }}</span>
                <i class="fas fa-file-alt text-sky-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3 sm:p-4 border-l-4 {{ $pendingActionCount > 0 ? 'border-l-amber-500 bg-amber-50/20' : 'border-l-slate-400' }} shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Perlu Tindakan Saya</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black {{ $pendingActionCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-white' }}">
                    {{ $pendingActionCount }}
                </span>
                <i class="fas fa-exclamation-circle {{ $pendingActionCount > 0 ? 'text-amber-500 animate-pulse' : 'text-slate-400' }} text-lg"></i>
            </div>
        </div>

        <div class="card p-3 sm:p-4 border-l-4 border-l-indigo-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Menunggu Review PP</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">{{ $stats['menunggu_sekjen'] + $stats['menunggu_ketum'] }}</span>
                <i class="fas fa-user-clock text-indigo-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3 sm:p-4 border-l-4 border-l-emerald-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">SK Terbit & Sah</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $stats['terbit'] }}</span>
                <i class="fas fa-stamp text-emerald-500 text-lg opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Table Card with Filter Tabs -->
    <div class="card shadow-sm">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-primary tab-filter active" data-tab="semua">
                    Semua SK ({{ $stats['total'] }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning tab-filter" data-tab="menunggu_saya">
                    <i class="fas fa-bell mr-1"></i> Perlu Tindakan Saya ({{ $pendingActionCount }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-success tab-filter" data-tab="terbit">
                    <i class="fas fa-check-double mr-1"></i> SK Terbit ({{ $stats['terbit'] }})
                </button>
            </div>

            <div class="w-full sm:w-48">
                <select id="filter_tingkatan" class="form-control text-xs w-full">
                    <option value="">Semua Tingkatan</option>
                    <option value="cabang">Pengurus Cabang</option>
                    <option value="daerah">Pengurus Daerah</option>
                    <option value="pusat">Pengurus Pusat</option>
                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped w-full text-xs" id="table-sk">
                    <thead>
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th>Nomor SK Resmi</th>
                            <th>Struktur Kepengurusan</th>
                            <th>Cakupan Wilayah</th>
                            <th width="90" class="text-center">Tingkat</th>
                            <th width="140" class="text-center">Status Alur</th>
                            <th width="140" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    var activeTab = 'semua';

    var table = $('#table-sk').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('sk_pengurus.list') }}",
            data: function(d) {
                d.tab = activeTab;
                d.tingkatan = $('#filter_tingkatan').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'no_sk_display', name: 'no_sk', className: 'font-bold' },
            { data: 'nama_struktur', name: 'strukturOrganisasi.nama_struktur', className: 'font-semibold' },
            { data: 'wilayah', name: 'wilayah' },
            { data: 'tingkatan_badge', name: 'tingkatan', className: 'text-center' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Tab Filter click
    $('.tab-filter').click(function() {
        $('.tab-filter').removeClass('btn-primary btn-warning btn-success active').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');
        activeTab = $(this).data('tab');
        table.ajax.reload();
    });

    // Tingkatan dropdown change
    $('#filter_tingkatan').change(function() {
        table.ajax.reload();
    });
});
</script>
@endpush
