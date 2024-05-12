@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Konas Master Hotel</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Konas Master Tipe Hotel</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah
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
                                    <th>Hotel</th>
                                    <th>Tipe</th>
                                    <th>Harga</th>
                                    <th>Extrabed</th>
                                    <th>Sisa Stok Kamar</th>
                                    <th>Sisa Stok Kasur</th>
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
                            <label>Hotel</label>
                            <select class="form-control form-control-sm" id="id_hotel" name="id_hotel">
                                <option>Pilih</option>
                                @foreach($hotel as $i)
                                    <option value="{{ $i->id }}">{{ $i->hotel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipe</label>
                            <input type="text" id="tipe" name="tipe" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" id="harga" name="harga" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Harga Kasur Tambahan</label>
                            <input type="number" id="extrabed" name="extrabed" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Stok Kamar</label>
                            <input type="number" id="stok" name="stok" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Stok Kasur</label>
                            <input type="number" id="stok_extrabed" name="stok_extrabed" class="form-control form-control-sm">
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
            url: "{{ route('konas_master_tipe_hotel.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'hotel', name: 'hotel'},
            {data: 'tipe', name: 'tipe'},
            {data: 'harga', name: 'harga'},
            {data: 'extrabed', name: 'extrabed'},
            {data: 'stok', name: 'stok'},
            {data: 'stok_extrabed', name: 'stok_extrabed'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});

function add(){
    $('#form').trigger("reset");
    $('.modal-title').html("Tambah Hotel");
    $('#tambah').modal('show');
    $('#id').val('');
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('konas_master_tipe_hotel.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Hotel");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#id_hotel').val(data['id_hotel']);
            $('#tipe').val(data['tipe']);
            $('#harga').val(data['harga']);
            $('#extrabed').val(data['extrabed']);
            $('#stok').val(data['stok']);
            $('#stok_extrabed').val(data['stok_extrabed']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('konas_master_tipe_hotel.store') }}',
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
    if (confirm("Hapus hotel ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('konas_master_tipe_hotel.delete') }}",
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