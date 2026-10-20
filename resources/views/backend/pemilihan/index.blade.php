@extends('layouts.backend.layout')

@section('title', 'Agenda Pemilihan Ketua ASKLIN')
@section('page_title', 'Pemilihan Ketua')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-rose-500 to-red-600 flex items-center justify-center text-white text-xl shadow-md shadow-red-500/20">
                <i class="fas fa-vote-yea"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                    Sistem Pemilihan Ketua Cabang & Daerah
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Manajemen agenda musyawarah dan pemilihan ketua 5 tahunan ASKLIN (LUBER JURDIL).
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('pemilihan-dashboard')
                <a href="{{ route('pemilihan.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-xl">
                    <i class="fas fa-chart-pie mr-1"></i> Dashboard Hasil
                </a>
            @endcan
            @can('pemilihan-create')
                <a href="{{ route('pemilihan.create') }}" class="btn btn-primary btn-sm rounded-xl">
                    <i class="fas fa-plus mr-1"></i> Buat Agenda Pemilihan
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
        </div>
    @endif

    <!-- Table Card -->
    <div class="card bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="card-header p-4 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between border-b border-slate-100 dark:border-slate-800">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                <i class="fas fa-list-ul text-red-600"></i> Daftar Agenda Pemilihan
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs rounded-xl" placeholder="Cari judul..." id="searchBox">
                    <i class="fas fa-search absolute left-3 top-2.5 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-full" id="pemilihanTable">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 border-b border-slate-200 dark:border-slate-700">
                            <th style="width: 50px;">No</th>
                            <th>Judul Pemilihan</th>
                            <th>Tingkat / Wilayah</th>
                            <th>Periode</th>
                            <th>Jadwal Pelaksanaan</th>
                            <th>Kandidat</th>
                            <th>Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#pemilihanTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "{{ route('pemilihan.list') }}",
            data: function(d) {
                d.search = $('#searchBox').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'judul', name: 'judul'},
            {data: 'wilayah', name: 'wilayah'},
            {data: 'periode', name: 'periode'},
            {data: 'jadwal', name: 'jadwal'},
            {data: 'kandidat_count', name: 'kandidat_count'},
            {data: 'status_badge', name: 'status_badge'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        language: {
            paginate: {
                previous: "<i class='fas fa-angle-left'></i>",
                next: "<i class='fas fa-angle-right'></i>"
            }
        }
    });

    $('#searchBox').on('keyup', function() {
        table.draw();
    });

    window.deletePemilihan = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus agenda pemilihan ini? Data kandidat dan riwayat suara akan terhapus permanen!')) {
            $.ajax({
                type: 'POST',
                url: "{{ url('pemilihan/delete') }}/" + id,
                dataType: 'json',
                success: function(res) {
                    alert(res.message);
                    table.draw(false);
                },
                error: function(xhr) {
                    alert('Gagal menghapus pemilihan. ' + (xhr.responseJSON?.message || ''));
                }
            });
        }
    };
});
</script>
@endpush
