@extends('layouts.backend.layout')

@section('title', 'Pembayaran Iuran')
@section('page_title', 'Pembayaran Iuran')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('dashboard.index') }}" class="hover:underline">Dashboard</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Keuangan Organisasi</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Pembayaran Iuran Anggota Klinik
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola setoran uang iuran berkala anggota, konfirmasi status pelunasan, dan verifikasi bukti transfer.
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
                <i class="fas fa-receipt text-emerald-600 mr-1.5"></i> Riwayat Pembayaran Iuran
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari klinik, kategori..." id="search">
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
                            <th>Anggota</th>
                            <th>Kategori</th>
                            <th>Tanggal Bayar</th>
                            <th>Periode</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah/Edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="tambah" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-bold text-slate-800 dark:text-white"></h5>
                    <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form" class="form" action="javascript:void(0)">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Klinik Anggota</label>
                            <select class="form-control" id="id_anggota" name="id_anggota" style="width:100%">
                                @foreach($anggota as $i)
                                    <option value="{{ $i->id }}">{{ $i->nama_klinik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" id="id_kategori" name="id_kategori">
                                    <option value="1">Uang Iuran</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">Aktif / Lunas</option>
                                    <option value="0">Menunggu Verifikasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Dari Periode</label>
                                <input type="date" class="form-control" id="dari" name="dari">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sampai Periode</label>
                                <input type="date" class="form-control" id="sampai" name="sampai">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Pembayaran (Rp)</label>
                            <input type="number" class="form-control font-semibold" id="total" name="total" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan / Catatan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unggah Bukti Transfer</label>
                            <input type="file" class="form-control" id="bukti" name="bukti">
                        </div>
                    </div>
                    <div class="modal-footer flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" id="btn-save" class="btn btn-primary btn-sm">Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Status Update -->
    <div class="modal fade" tabindex="-1" role="dialog" id="update" data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-bold text-slate-800 dark:text-white"></h5>
                    <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_status" class="form" action="javascript:void(0)">
                    <input type="hidden" name="id" id="status_id">
                    <div class="modal-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-control" id="status_val" name="status">
                                <option value="1">Lunas / Selesai</option>
                                <option value="0">Menunggu Verifikasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" id="btn-save-status" onclick="submitStatusUpdate()" class="btn btn-primary btn-sm">Simpan Status</button>
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
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ajax: { 
            url: "{{ route('pembayaran.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {
                data: 'nama_klinik',
                name: 'nama_klinik',
                render: function (data) {
                    return data ? '<span class="nama-klinik-highlight font-bold text-red-600 dark:text-red-400">' + data + '</span>' : '<span class="text-slate-400">-</span>';
                }
            },
            {data: 'nama', name: 'nama'},
            {data: 'tanggal_bayar', name: 'tanggal_bayar'},
            {data: "dari" ,
                "render": function ( data, type, row, meta ) {
                    return (row.dari || '-') + ' s/d ' + (row.sampai || '-');
                }
            },
            {data: 'total', name: 'total'},
            {data: 'keterangan', name: 'keterangan'},
            {data: "bukti" ,
                "render": function ( data, type, row, meta ) {
                    if(!row.bukti) return '<span class="text-slate-400 text-xs">Tidak ada</span>';
                    return '<a href="{{ asset("images/file") }}/' + row.bukti +'" target="_blank" class="inline-block p-1 rounded-lg bg-slate-100 hover:bg-emerald-50 border border-slate-200"><img alt="bukti" src="{{ asset("images/file") }}/' + row.bukti + '" width="36" height="36" class="rounded object-cover"></a>';
                }
            },
            {data: "status" ,
                "render": function ( data, type, row, meta ) {
                    if(row.status == "0"){
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30">Menunggu</span>';
                    }else{
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30">Selesai</span>';
                    }
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    var searchTimer = null;
    $("#search").on('keyup input', function(){
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            table.draw();
        }, 300);
    });
});

function add(){
    $('#form').trigger("reset");
    $('#tambah .modal-title').html("Tambah Pembayaran Iuran");
    $('#tambah').modal('show');
    $('#id').val('');
    $('#id_anggota').select2({ dropdownParent: $('#tambah'), width: '100%', placeholder: "Pilih klinik anggota"});
}

function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('pembayaran.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('#tambah .modal-title').html("Edit Pembayaran Iuran");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $("#id_anggota").select2({ dropdownParent: $('#tambah'), width: '100%', placeholder: "Pilih klinik anggota"}).val(data['id_anggota']).trigger("change");
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
            $('#update .modal-title').html("Update Status Pembayaran");
            $('#update').modal('show');
            $('#status_id').val(data['id']);
            $('#status_val').val(data['status']);
        }
    });
}

function submitStatusUpdate() {
    let id = $('#status_id').val();
    let status = $('#status_val').val();
    $('#btn-save-status').attr("disabled", true).text('Menyimpan...');

    $.ajax({
        type: 'POST',
        url: '{{ route('pembayaran.updateStatus') }}',
        data: { id: id, status: status, _token: '{{ csrf_token() }}' },
        success: function() {
            $('#update').modal('hide');
            $('#table').DataTable().ajax.reload(null, false);
            $('#btn-save-status').attr("disabled", false).text('Simpan Status');
        },
        error: function() {
            $('#btn-save-status').attr("disabled", false).text('Simpan Status');
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save").attr("disabled", true).text('Menyimpan...');
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
            $('#table').DataTable().ajax.reload(null, false);
            $("#btn-save").html('Simpan Pembayaran').attr("disabled", false);
        },
        error: function(data){
            $("#btn-save").html('Simpan Pembayaran').attr("disabled", false);
        }
    });
});

function deleteu(id){
    if (confirm("Hapus pembayaran ini?") == true) {
        $.ajax({
            type:"POST",
            url: "{{ route('pembayaran.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table').DataTable().ajax.reload(null, false);
            }
        });
    }
}
</script>
@endpush