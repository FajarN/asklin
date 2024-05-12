@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Tagihan</h1>
        </div>
    </section>

    @include('frontend.menu')

    <div class="container clearfix">
		<div class="card">
			<div class="card-header">Informasi Pembayaran</div>
			<div class="card-body">
			    <a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="add()"><span><i class="icon-plus"></i> Tambah</span></a>
    			<br>
    			<table class="table table-bordered" id="table">
    				<thead>
    					<tr>
    						<th>No</th>
    						<th>Periode Pembayaran</th>
    						<th>Tanggal Bayar</th>
    						<th>Jml Pembayaran</th>
    						<th>Periode Pembayaran</th>
    						<th>Keterangan</th>
    					</tr>
    				</thead>
    				<tbody></tbody>
    			</table>
    		</div>
    	</div>
    	
    	<!-- Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="tambah">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="form" class="form" action="javascript::void(0)">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="id_anggota" id="id_anggota" value="{{ $anggota->id }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Jenis Pembayaran</label>
                                <select class="form-control form-control-sm" id="id_kategori" name="id_kategori">
                                    @foreach($kategori as $i)
                                        <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Bayar</label>
                                <input type="number" id="total" name="total" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea id="keterangan" name="keterangan" class="form-control form-control-sm" placeholder="Ex: Januari-Februari"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Bukti Transfer</label>
                                <input type="file" id="bukti" name="bukti" class="form-control form-control-sm">
                            </div>
                            <div class="modal-footer">
        						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        						<button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
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
            url: "{{ route('getTagihan') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: "awal" ,
                "render": function (  data, type, row, meta ) {
                    return row.awal + ' - ' + row.sampai;
                }
            },
            {data: "total" ,
                "render": function (  data, type, row, meta ) {
                    return ' Rp. ' + row.total;
                }
            },
            {data: "bukti" ,
                "render": function (  data, type, row, meta ) {
                return '<img src="{{ asset("images/file") }}/' + row.bukti + '" width="90px">';}
            },
            {data: 'keterangan', name: 'keterangan'}
        ]
    });
});

function add(){
    $('#form').trigger("reset");
    $('.modal-title').html("Upload Bukti Pembayaran");
    $('#tambah').modal('show');
    $('#id').val('');
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('tagihan.store') }}',
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
</script>
@endpush