@extends('layouts.backend.layout')

@section('title', 'Pembayaran Pusat')
@section('page_title', 'Pembayaran Pusat')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Pembayaran Pusat
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Rekapitulasi dan verifikasi pembayaran iuran & uang pangkal tingkat pusat (nasional).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="javascript:void(0)" onclick="add()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Pembayaran</span>
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
                <i class="fas fa-landmark text-emerald-600 mr-1.5"></i> Daftar Rekap Pembayaran Pusat
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari kota, provinsi..." id="search">
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