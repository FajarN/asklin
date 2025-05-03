@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Penomoran Surat</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Penomoran Surat</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah Penomoran Surat
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
                                    <th>ID Jenis</th>
                                    <th>Tahun</th>
                                    <th>Nomor Terakhir</th>
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
                           <label for="id_jenis_surat" class="form-label">Jenis Surat</label>
                            <select class="form-control form-control-sm" id="id_jenis_surat" name="id_jenis_surat">
                            <option>Pilih</option>
                            @foreach ($jenis as $i)
                                <option value="{{ $i->id }}">{{ $i->nama_jenis }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" id="tahun" name="tahun" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Nomor Terakhir</label>
                            <input type="text" id="nomor_terakhir" name="nomor_terakhir" class="form-control form-control-sm">
                        </div>
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
            url: "{{ route('surat_penomoran.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'id_jenis_surat', name: 'id_jenis_surat'},
            {data: 'tahun', name: 'tahun'},
            {data: 'nomor_terakhir', name: 'nomor_terakhir'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});

function add(){
    $('#form').trigger("reset");
    $('.modal-title').html("Tambah Penomoran Surat");
    $('#tambah').modal('show');
    $('#id').val('');
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('surat_penomoran.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Penomoran Surat");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#id_jenis_surat').val(data['id_jenis_surat']);
            $('#tahun').val(data['tahun']);
            $('#nomor_terakhir').val(data['nomor_terakhir']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('surat_penomoran.store') }}',
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
    if (confirm("Hapus Penomoran Surat ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('surat_penomoran.delete') }}",
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