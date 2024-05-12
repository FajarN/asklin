@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Penerbangan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Data Penerbangan</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <!--<a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">-->
                        <!--    <i class="fas fa-plus"></i> Tambah-->
                        <!--</a>-->
                    </h4>
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