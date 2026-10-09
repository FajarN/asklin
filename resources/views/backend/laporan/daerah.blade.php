@extends('layouts.backend.layout')

@section('title', 'Laporan Data Anggota (Daerah)')
@section('page_title', 'Laporan Daerah')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('dashboard.index') }}" class="hover:underline">Dashboard</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Laporan Wilayah</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Laporan Data Anggota ASKLIN Daerah
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Rekapitulasi status keanggotaan dan pembayaran klinik di seluruh kabupaten/kota daerah.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-form">
                        <input type="text" class="form-control" placeholder="Search" id="search">
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Anggota</th>
                                    <th>Kab/Kota</th>
                                    <th>Nama Klinik</th>
                                    <th>Jenis Klinik</th>
                                    <th>Kriteria Klinik</th>
                                    <th>Status Verifikasi</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
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
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {  
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('laporan_daerah.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'no_anggota', name: 'no_anggota'},
            {data: 'name', name: 'name'},
            {
                data: 'nama_klinik',
                name: 'nama_klinik',
                render: function (data) {
                    return data ? '<span class="nama-klinik-highlight font-bold text-red-600 dark:text-red-400">' + data + '</span>' : '<span class="text-slate-400">-</span>';
                }
            },
            {data: 'jenis_klinik', name: 'jenis_klinik'},
            {data: 'kriteria', name: 'kriteria'},
            {data: 'status', name: 'status'},
            {data: 'status_pembayaran', 
                "render": function (  data, type, row, meta ) {
                    if(row.status == "0"){
                        return "Belum Lunas";
                    }else{
                        return "Lunas";
                    }
                }
            }
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});
</script>
@endpush