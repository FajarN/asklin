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
            <h1>Peserta Konas</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Peserta Konas</div>
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
                        <select id="provinsi" class="form-control form-control">
                            <option></option>
                            @foreach($provinsi as $i)
                                <option value="{{ $i->code }}">{{ $i->name }}
                            @endforeach
                        </select>
                    </div>
                    <div class="card-header-form">
                        <select id="cabang" class="form-control form-control">
                        </select>
                    </div>
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
                                    <th>No.Anggota</th>
                                    <th>Nama</th>
                                    <th>Surat Mandat</th>
                                    <th>No.HP</th>
                                    <th>Email</th>
                                    <th>Klinik</th>
                                    <th>Status</th>
                                    {{-- <th>Status&nbsp;Pembayaran</th>
                                    <th>Link&nbsp;Pembayaran</th> --}}
                                    <th>Propinsi</th>
                                    <th>Kab</th>
                                    <th>Komisi</th>
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
                                   <div class="row">
                                <div class="col-md-6">
                             <div class="form-group">
								<label>Nama Peserta <small>(Tanpa Gelar)</small>: </label>
								<input type="text" name="nama_peserta" id="nama_peserta" class="form-control required" required>
							</div>
							<div class="form-group">
								<label>Nama Sertifikat <small>(Dengan Gelar)</small>: </label>
								<input type="text" name="nama_sertifikat" id="nama_sertifikat" class="form-control required" required>
							</div>
							<div class="form-group">
								<label>NIK: </label>
								<input type="number" name="nik" id="nik" class="form-control required" required>
							</div>
							<div class="form-group">
								<label>TANGGAL LAHIR: </label>
								<input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control required" required>
							</div>
								<div class="form-group">
								<label>Telepon:</label><br>
								<input type="number" name="no_hp" id="no_hp" class="form-control required" required>
							</div>
								<div class="form-group">
								<label>Email:</label><br>
								<input type="email" name="email" id="email" class="form-control required email" required>
							</div>

						

                              </div>
                            {{-- akhir --}}

                          <div class="col-md-6">

                            <div class="form-group">
								<label>Nama Klinik:</label>
								<input type="text" name="nama_klinik" id="nama_klinik" class="form-control required" required>
							</div>
                            <div class="form-group">
								<label>Alamat Klinik:</label>
								<textarea name="alamat_klinik" id="alamat_klinik" class="form-control" cols="30" rows="8" required></textarea>
							</div>
						     
							<div class="form-group">
								<label>Status:</label>
								<select class="form-control" name="status" id="status" required>
									<option value="">-- Pilih --</option>
									<option value="Peserta">Peserta</option>
									<option value="Pendamping">Pendamping</option>
									<option value="Peninjau">Peninjau</option>
								</select>
							</div>

							<div class="form-group" id="propinsi">
								<label>Propinsi:</label>
								<select class="form-control" name="id_provinsi" id="id_provinsi">
									<option value="">-- Pilih --</option>
									@foreach($provinsi as $i)
									<option value="{{ $i->code }}">{{ $i->name }}</option>
									@endforeach
								</select>
						</div>
							
                           

                         <div class="form-group" id="mandat">
                            <label>Upload Surat Mandat:</label>
                            <input id="surat_mandat" name="surat_mandat" type="file" class="form-control">
                        </div>

                        <div class="form-group" id="komisi">
                            <label>Komisi:</label>
                            <select class="form-control" name="komisi">
                                <option value="">-- Pilih --</option>
                                <option value="Komisi AD-ART">Komisi AD-ART</option>
                                <option value="Komisi ORTALA">Komisi ORTALA</option>
                                <option value="Komisi Pembiayaan dan Kredensialing/">Komisi Mutu & Akreditasi</option>
                            </select>
                        </div>
                        </div>
                        {{-- akhir --}}

                        
                    </div>
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
 <!-- Akhir Modal -->


 <!-- Modal 2 -->
    <div class="modal fade" tabindex="-1" role="dialog" id="updateStatus">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                        <form id="form1" class="form" action="javascript:void(0)">
                            <input type="hidden" name="id_peserta" id="id_peserta">
                            <input type="hidden" name="id_booking" id="id_booking">
                            <div class="modal-body">

                            <div class="form-group">
								<label>Status Pembayaran Konas:</label>
								<select class="form-control" name="status_pembayaran" id="status_pembayaran" required>
									<option value="">-- Pilih --</option>
									<option value="PAID">PAID</option>
									<option value="PENDING">PENDING</option>
								</select>
							</div>

                             <div class="form-group">
								<label>Status Pembayaran Hotel:</label>
								<select class="form-control" name="status_pembayaran_booking" id="status_pembayaran_booking" required>
									<option value="">-- Pilih --</option>
									<option value="PAID">PAID</option>
									<option value="PENDING">PENDING</option>
								</select>
							</div>
                     
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="submit" id="btn-save-1" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 <!-- Akhir Modal -->



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
             pageLength: 15,
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('konas.list') }}",
            data: function (d) {
                d.search = $('#search').val(),
                d.provinsi = $('#provinsi').val(),
                d.cabang = $('#cabang').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'no_anggota', name: 'no_anggota'},
            {data: "nama_peserta" , "render": function (  data, type, row, meta ){
                return '<img src="{{ asset('images/konas') }}/'+row.foto+'" class="rounded-circle" width="60"><div class="d-inline-block ml-1">'+row.nama_peserta+'</div>';
            }},
            {data: "surat_mandat" , "render": function (  data, type, row, meta ){
                return '<a href="{{ asset('images/konas') }}/'+row.surat_mandat+'" target="_BLANK">'+row.surat_mandat+'</a>';
            }},
            {data: "no_hp" , "render": function (  data, type, row, meta ){
                return '<a href="https://api.whatsapp.com/send?phone=62'+row.no_hp+'" target="_BLANK">'+row.no_hp+'</a>';
            }},
             {data: "email" , "render": function (  data, type, row, meta ){
                return '<a href="mailto:'+row.email+'" target="_BLANK">'+row.email+'</a>';
            }},
            {data: 'nama_klinik', name: 'nama_klinik'},
            {data: "status" , "render": function (  data, type, row, meta ){
                if(row.status == '1') {
                    return 'Peserta';
                }else {
                    return 'Pendamping';
                }
            }},
            // {data: 'status_pembayaran', name: 'status_pembayaran'},
            // {data: "link_pembayaran" , "render": function (  data, type, row, meta ){
            //     return '<a class="myButton" href="'+row.link_pembayaran+'" target="_blank">Link&nbsp;Bayar</a>';
            // }},
            {data: 'provinsi', name: 'provinsi'},
            {data: 'name', name: 'name'},
            {data: 'komisi', name: 'komisi'},
            {data: 'action', name: 'action'},
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });
    $('#provinsi').on('change', function(e) {
        let id_provinsi = $('#provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#cabang').html(data);
            }
        })
        table.draw();
    });
    $('#cabang').on('change', function(e) {
        table.draw();
    });
});



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


function edit(id){
    $.ajax({
        type:"POST",
        url: '{{ route('konas.edit') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Edit Peserta Konas");
            $('#tambah').modal('show');
            $('#id').val(data['id']);
            $('#nama_peserta').val(data['nama_peserta']);
            $('#nama_sertifikat').val(data['nama_sertifikat']);
            $('#nik').val(data['nik']);
            $('#tgl_lahir').val(data['tgl_lahir']);
            $('#no_hp').val(data['no_hp']);
            $('#email').val(data['email']);
            $('#foto').append('<img id="foto" src="{{ asset("images/konas") }}/' + data['foto'] + '" />');
            $('#nama_klinik').val(data['nama_klinik']);
            $('#alamat_klinik').val(data['alamat_klinik']);
            $('#status').val(data['status']);
            $("#id_provinsi").val(data['id_provinsi']).trigger("change");
            $("#id_kota").val(data['id_kota']);
            $('#surat_mandat').append('<img id="surat_mandat" src="{{ asset("images/konas") }}/' + data['surat_mandat'] + '" />');
            $('#komisi').val(data['komisi']);
        }
    });
}

function updateStatus(id){
    $.ajax({
        type:"POST",
        url: '{{ route('konas.updateStatus') }}',
        data: { id: id },
        dataType: 'json',
        success: function(data){
            $('.modal-title').html("Update Status Pembayaran");
            $('#updateStatus').modal('show');
            $('#id_peserta').val(data[0]['id']);
            $('#status_pembayaran').val(data[0]['status_pembayaran']);
            $('#id_booking').val(data[1]['id']);
            $('#status_pembayaran_booking').val(data[1]['status_pembayaran']);
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    $("#btn-save"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('konas.store') }}',
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

$('#form1').submit(function(e) {
    e.preventDefault();
    $("#btn-save-1"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('konas.status_pembayaran') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#updateStatus").modal('hide');
            $('#table').dataTable().fnDraw(false);
            $("#btn-save-1").html('Submit');
            $("#btn-save-1"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-1"). attr("disabled", false);
        }
    });
});

function deleteu(id){
    if (confirm("Hapus peserta ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('konas.delete') }}",
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