@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pembayaran Iuran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Pembayaran Iuran</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah Pembayaran Iuran
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
                                    <th>Anggota</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Periode</th>
                                    <th>total</th>
                                    <th>Keterangan</th>
                                    <th>Bukti</th>
                                    <th>Status</th>
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
                            <label>Anggota</label>
                            <select  class="form-control" id="id_anggota" name="id_anggota" style="width:100%">
                                @foreach($anggota as $i)
                                    <option value="{{ $i->id }}">{{ $i->nama_klinik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control form-control-sm" id="id_kategori" name="id_kategori">
                                <option value="1">Uang Iuran</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control form-control-sm" id="status" name="status">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Bayar</label>
                            <input type="date" class="form-control form-control-sm" id="tanggal_bayar" name="tanggal_bayar">
                        </div>
                        <div class="form-group">
                            <label>Dari Bulan</label>
                            <input type="date" class="form-control form-control-sm" id="dari" name="dari">
                        </div>
                        <div class="form-group">
                            <label>Sampai Bulan</label>
                            <input type="date" class="form-control form-control-sm" id="sampai" name="sampai">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Pembayaran</label>
                            <input type="number" class="form-control form-control-sm" id="total" name="total">
                        </div>
                        <div class="form-group">
                            <label>Bukti Transfer</label>
                            <input type="file" class="form-control form-control" id="bukti" name="bukti">
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

    <!-- Modal 2 -->
    <div class="modal fade" tabindex="-1" role="dialog" id="update">
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
                            <label>Status</label>
                            <select class="form-control form-control-sm" id="status" name="status">
                                <option value="1">Lunas</option>
                                <option value="0">Belum Bayar</option>
                            </select>
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
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
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
            url: "{{ route('pembayaran.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_klinik', name: 'nama_klinik'},
            {data: 'nama', name: 'nama'},
            {data: 'tanggal_bayar', name: 'tanggal_bayar'},
            {data: "dari" ,
                "render": function (  data, type, row, meta ) {
                    return row.dari + ' - ' + row.sampai;
                }
            },
            {data: 'total', name: 'total'},
            {data: 'keterangan', name: 'keterangan'},
            {data: "bukti" ,
                "render": function (  data, type, row, meta ) {
                return '<a href="{{ asset("images/file") }}/' + row.bukti +'" target="_blank"  class="chocolat-image"><div data-crop-image="285"><img alt="image" src="{{ asset("images/file") }}/' + row.bukti + '" width="40" class="img-fluid"></div></a>';
                }
            },
            {data: "status" ,
                "render": function (  data, type, row, meta ) {
                    if(row.status == "0"){
                        return "Menunggu";
                    }else{
                        return "Selesai";
                    }
                }
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
    $('#id_anggota').select2({ dropdownParent: $('#tambah'), width: 'resolve', placeholder: "Pilih anggota",});
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('pembayaran.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Pembayaran");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $("#id_anggota").select2({ dropdownParent: $('#tambah'), width: 'resolve', placeholder: "Pilih anggota",}).val(data['id_anggota']).trigger("change");
            $('#id_kategori').val(data['id_kategori']);
            $('#status').val(data['status']);
            $('#tanggal_bayar').val(data['tanggal_bayar']);
            $('#dari').val(data['dari']);
            $('#sampai').val(data['sampai']);
            $('#keterangan').val(data['keterangan']);
            $('#total').val(data['total']);
        }
    });
}

function updateStatus(id){
    $.ajax({
        type:"POST",
        url: '{{ route('pembayaran.updateStatus') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Update Status Pembayaran");
            $('#update').modal('show');
            $('#id').val(data['id']);
            $('#status').val(data['status']);
             $("#btn-save").html('Submit');
            $("#btn-save"). attr("disabled", false);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pembayaran.store') }}',
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
            url: "{{ route('pembayaran.delete') }}",
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