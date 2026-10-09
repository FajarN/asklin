@extends('layouts.backend.layout')

@section('title', 'Booking Hotel KONAS')
@section('page_title', 'Booking Hotel')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Pemesanan Akomodasi Hotel (KONAS)
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Data reservasi hotel kontingen, tipe kamar, extrabed, dan status pembayaran.
            </p>
        </div>
        <div class="flex items-center gap-2">
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
                <i class="fas fa-hotel text-emerald-600 mr-1.5"></i> Daftar Reservasi Hotel
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari pemesan, hotel..." id="search">
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
                            <th>Tanggal</th>
                            <th>Pemesan</th>
                            <th>Hotel</th>
                            <th>Harga Kamar</th>
                            <th>Jml Kamar</th>
                            <th>Jml Extrabed</th>
                            <th>Harga Extrabed</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Link Bayar</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
        	dom : 'Bfrtip',
			buttons : [ 'copy', 'csv', 'pdf', 'print',{
	            extend: 'excel',
	            text: 'Export To Excel'
	        } ],
             pageLength: 100,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('konas_booking.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'name', name: 'name'},
            {data: 'hotel', name: 'hotel'},
            {data: 'harga_kamar', name: 'harga_kamar'},
            {data: 'jumlah', name: 'jumlah'},
            {data: 'extrabed', name: 'extrabed'},
            {data: 'total_harga_extrabed', name: 'total_harga_extrabed'},
            {data: 'total', name: 'total'},
             {data: 'status_pembayaran', name: 'status_pembayaran'},
            {data: "link_pembayaran" , "render": function (  data, type, row, meta ){
                return '<a class="myButton" href="'+row.link_pembayaran+'" target="_blank">Bayar</a>';
            }},
            {data: 'action', name: 'action'},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
});
</script>
@endpush