@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pembayaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Pembayaran</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah Pembayaran
                        </a>
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
                                    <th>Kota</th>
                                    <th>Provinsi</th>
                                    <th>Status</th>
                                    <th>Jumlah Klinik</th>
                                    <th>Total</th>
                                    <th>Bukti</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="tambah">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form" class="form" action="javascript:void(0)">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>status</label>
                            <select class="form-control form-control-sm" id="status" name="status">
                                <option value="Transfer PP">Transfer PP</option>
                                <option value="Transfer PD">Transfer PD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Pembayaran</label>
                            <select class="form-control form-control-sm" id="jenis_pembayaran" name="jenis_pembayaran">
                                <option value="Uang Pangkal">Uang Pangkal</option>
                                <option value="Uang Iuran">Uang Iuran</option>
                                <option value="Uang Pangkal & Iuran">Uang Pangkal & Iuran</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="dari" name="dari">
                        </div>
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" id="sampai" name="sampai">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Klinik</label>
                            <input type="number" class="form-control form-control-sm" id="jumlah_klinik" name="jumlah_klinik">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Pembayaran</label>
                            <input type="number" class="form-control form-control-sm" id="total" name="total">
                        </div>
                        <div class="form-group">
                            <label>Bukti Transfer</label>
                            <input type="file" class="form-control form-control" id="bukti" name="bukti">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"></textarea>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
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
            url: "{{ route('pembayaran_pusat.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'kota', name: 'kota'},
            {data: 'provinsi', name: 'provinsi'},
            {data: 'status', name: 'status'},
            {data: 'jumlah_klinik', name: 'jumlah_klinik'},
            {data: 'total', name: 'total'},
            {data: "bukti" ,
                "render": function (  data, type, row, meta ) {
                return '<img src="{{ asset("images/file") }}/' + row.bukti + '" width="90px">';}
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});

function add(){
    $('#form').trigger("reset");
    $('.modal-title').html("Tambah Pembayaran");
    $('#tambah').modal('show');
    $('#id').val('');
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('pembayaran_pusat.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Pembayaran");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#status').val(data['status']);
            $('#jumlah_klinik').val(data['jumlah_klinik']);
            $('#total').val(data['total']);
            $('#jenis_pembayaran').val(data['jenis_pembayaran']);
            $('#keterangan').val(data['keterangan']);
            $('#dari').val(data['dari']);
            $('#sampai').val(data['sampai']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pembayaran_pusat.store') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#tambah").modal('hide');
            $('#table').dataTable().fnDraw(false);
            $("#btn-save").html('Submit');
            $("#btn-save"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save"). attr("disabled", false);
        }
    });
});

function deleteu(id){
    if (confirm("Hapus pembayaran ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pembayaran_pusat.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table').dataTable().fnDraw(false);
            }
        });
    }
}
</script>
@endpush