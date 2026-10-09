@extends('layouts.backend.layout')

@section('title', 'Data Penerbangan KONAS')
@section('page_title', 'Data Penerbangan')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Data Kedatangan & Penerbangan (KONAS)
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Jadwal kedatangan delegasi, rute maskapai, dan titik jemput bandara.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-plane-arrival text-emerald-600 mr-1.5"></i> Jadwal Tiket & Penerbangan
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari nama, bandara..." id="search">
                    <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Anggota</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Email</th>
                            <th>Klinik</th>
                            <th>Tanggal</th>
                            <th>Dari Bandara</th>
                            <th>Tujuan Bandara</th>
                            <th>Pesawat</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
            url: "{{ route('konas_penerbangan.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'no_anggota', name: 'no_anggota'},
            {data: "nama_peserta" , "render": function (  data, type, row, meta ){
                return '<img src="{{ asset('images/konas') }}/'+row.foto+'" width="60"><span>'+row.nama_peserta+'</span>';
            }},
            {data: 'no_hp', name: 'no_hp'},
            {data: 'email', name: 'email'},
            {data: 'nama_klinik', name: 'nama_klinik'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'dari', name: 'dari'},
            {data: 'tujuan', name: 'tujuan'},
            {data: 'pesawat', name: 'pesawat'},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});
</script>
@endpush