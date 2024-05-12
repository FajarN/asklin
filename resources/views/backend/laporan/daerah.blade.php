@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Data Anggota ASKLIN</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan Daerah</div>
            </div>
        </div>
    </section>
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
            {data: 'nama_klinik', name: 'nama_klinik'},
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