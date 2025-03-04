@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Tagihan</h1>
        </div>
    </section>

    @include('frontend.menu')
    
    <div class="promo promo-border p-4 p-md-5 mb-5">
		<div class="row align-items-center">
			<div class="col-12 col-lg">
				<h3>Anda Dapat Mendownload SK Anggota disini</h3>
			</div>
			<div class="col-12 col-lg-auto mt-4 mt-lg-0">
				<a href="{{ route('downloadSK', $anggota->id) }}" class="button button-black button-large button-rounded m-0">Download</a>
			</div>
		</div>
	</div>

    <div class="container clearfix">
		<div class="card">
			<div class="card-header">Sertifikat</div>
			<div class="card-body">
    			<br>
    			<table class="table table-bordered" id="table" width="100%">
    				<thead>
    					<tr>
    						<th>No</th>
    						<th>Nama Klinik</th>
    						<th>No. Anggota</th>
    						<th>Status Pembayaran Iuran</th>
    						<th>Periode Pembayaran</th>
    						<th>Tanggal Verifikasi Cabang</th>
    						<th>Tanggal Verifikasi Pusat</th>
    						<th>Cetak</th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table>
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
            url: "{{ route('getSertifikat') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_klinik', name: 'nama_klinik'},
            {data: 'no_anggota', name: 'no_anggota'},
            {data: "status_pembayaran" ,
                "render": function (  data, type, row, meta ) {
                    if(row.status_pembayaran == '0'){
                        return "Belum";
                    }else{
                        return "Lunas";
                    }
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush

