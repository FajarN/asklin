@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Galery</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Galery</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah Galery
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
                                    <th>Judul</th>
                                    <th>Keterangan</th>
                                    <th>Foto</th>
                                    <th>Cabang</th>
                                    <th>Daerah</th>
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
        <div class="modal-dialog" role="document">
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
                            <label>Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" id="foto" name="foto" class="form-control form-control-sm">
                            <span id="gambar"></span>
                        </div>
                        <div class="form-group">
                            <label>Propinsi</label>
                            <select class="form-control" name="id_provinsi" id="id_provinsi" style="width: 100%">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kab/Kota</label>
                            <select class="form-control" name="id_kota" id="id_kota" style="width: 100%"></select>
                        </div>
                        @hasanyrole('Superadmin|Admin Pusat')
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control form-control-sm" id="status" name="status">
                                    <option value="1">Live</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        @endhasanyrole
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            url: "{{ route('galery.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'judul', name: 'judul'},
            {data: 'keterangan', name: 'keterangan'},
            {data: "foto" ,
                "render": function (  data, type, row, meta ) {
                return '<img src="{{ asset("assets/images/galery") }}/' + row.foto + '" width="150px">';}
            },
            {data: 'kota', name: 'kota'},
            {data: 'provinsi', name: 'provinsi'},
            {data: "status" ,
                "render": function (  data, type, row, meta ) {
                    if(row.status == "0"){
                        return "Draft";
                    }else{
                        return "Live";
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
    $('.modal-title').html("Tambah Galery");
    $('#tambah').modal('show');
    $('#poto').remove();
    $('#id').val('');
    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#id_kota').html(data);
            }
        })
    });
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('galery.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Galery");
            $('#poto').remove();
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#judul').val(data['judul']);
            $('#keterangan').val(data['keterangan']);
            $('#gambar').append('<img width="250" id="poto" src="{{ asset("assets/images/galery") }}/' + data['foto'] + '" />');
            $("#id_provinsi").val(data['id_provinsi']).trigger("change");
            $('#id_provinsi').on('change', function() {
                let id_provinsi = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('getKota') }}',
                    data: { id_provinsi: id_provinsi },
                    cache: false,
                    success: function(data) {
                        $('#id_kota').html(data);
                        $("#id_kota").val(data.id_kota).trigger('change');
                    }
                });
            });
            $("#id_kota").val(data['id_kota']);
            $('#status').val(data['status']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('galery.store') }}',
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
    if (confirm("Hapus foto ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('galery.delete') }}",
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