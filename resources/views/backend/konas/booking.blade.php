<style>
    .myButton {
	box-shadow:inset 0px 1px 0px 0px #bbdaf7;
	background:linear-gradient(to bottom, #79bbff 5%, #378de5 100%);
	background-color:#79bbff;
	border-radius:6px;
	border:1px solid #84bbf3;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #528ecc;
}
.myButton:hover {
	background:linear-gradient(to bottom, #378de5 5%, #79bbff 100%);
	background-color:#378de5;
}
.myButton:active {
	position:relative;
	top:1px;
}

</style>

@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Booking Hotel</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Booking Hotel</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <!--<a href="javascript:void(0)" onclick="add()" class="btn btn-icon icon-left btn-danger btn-outline-secondary">-->
                        <!--    <i class="fas fa-plus"></i> Tambah-->
                        <!--</a>-->
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
                                    <th>Tanggal</th>
                                    <th>Pemesan</th>
                                    <th>Hotel</th>
                                    <th>Harga&nbsp;Kamar</th>
                                    <th>Jml&nbsp;Kamar</th>
                                    <th>Jml&nbsp;Extrabed</th>
                                    <th>Harga&nbsp;Extrabed</th>
                                    <th>Total</th>
                                    <th>Status&nbsp;Pembayaran</th>
                                    <th>Link&nbsp;Bayar</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
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