@extends('layouts.backend.layout')

@section('title', 'Manajemen Permission')
@section('page_title', 'Manajemen Permission')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Manajemen Sistem'],
        ['label' => 'Hak Akses (Permissions)'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Manajemen Hak Akses (Permissions)
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola daftar izin aksi spesifik (view, create, edit, delete, verify) dalam sistem.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="add()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Permission</span>
            </button>
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
                <i class="fas fa-key text-emerald-600 mr-1.5"></i> Daftar Hak Akses (Permission)
            </h4>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-48">
                    <select id="role" class="form-control w-full text-xs">
                        <option value="">Semua Peran / Role</option>
                        @foreach($role as $i)
                            <option value="{{ $i->name }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-64">
                    <div class="relative">
                        <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari permission..." id="search">
                        <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Permission</th>
                            <th>Group / Peran</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="tambah" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" class="form" action="javascript:void(0)">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Permission</label>
                        <input type="text" id="name" name="name" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
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
            url: "{{ route('permissions.list') }}",
            data: function (d) {
                d.search = $('#search').val(),
                d.role_names = $('#role').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'role_names', name: 'roles'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });

    $('#role').on('change', function(e) {
        table.draw();
    });
});

function add(){
    $('#form').trigger("reset");
    $('.modal-title').html("Tambah Permission");
    $('#tambah').modal('show');
    $('#id').val('');
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('permissions.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Permission");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#name').val(data['name']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('permissions.store') }}',
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
    if (confirm("Hapus permission ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('permissions.delete') }}",
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