@extends('layouts.backend.layout')

@section('title', 'Galeri Dokumentasi')
@section('page_title', 'Galeri')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Galeri Dokumentasi Kegiatan
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Album foto kegiatan tingkat cabang, daerah, dan pusat se-Indonesia.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="javascript:void(0)" onclick="add()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Foto Galeri</span>
            </a>
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
                <i class="fas fa-camera text-emerald-600 mr-1.5"></i> Album Foto Dokumentasi
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari album, judul..." id="search">
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