@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
	<section id="content">
	    <div class="content-wrap">
		    <div class="container clearfix">
				<div class="row">
					<div class="col-lg-6">
						<form class="row" id="feedback-form" action="{{ route('konas.storepeserta') }}" method="POST" enctype="multipart/form-data">
						    @csrf
							<div class="col-12 form-group">
								<label>Nama Peserta <small>(Tanpa Gelar)</small>: </label>
								<input type="text" name="nama_peserta" id="nama_peserta" class="form-control required" required>
							</div>
							<div class="col-12 form-group">
								<label>Nama Sertifikat <small>(Dengan Gelar)</small>: </label>
								<input type="text" name="nama_sertifikat" id="nama_sertifikat" class="form-control required" required>
							</div>
							<div class="col-12 form-group">
								<label>NIK: </label>
								<input type="number" name="nik" id="nik" class="form-control required" required>
							</div>
							<div class="col-12 form-group">
								<label>TANGGAL LAHIR: </label>
								<input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control required" required>
							</div>
								<div class="col-12 form-group">
								<label>Telepon:</label><br>
								<input type="number" name="no_hp" id="no_hp" class="form-control required" required>
							</div>
								<div class="col-12 form-group">
								<label>Email:</label><br>
								<input type="email" name="email" id="email" class="form-control required email" required>
							</div>

							<div class="col-12 form-group ">
								<label>Upload Foto Profil:</label>
								<input id="foto" name="foto" type="file" class="form-control required" required>
							</div>
                            <div class="col-12 form-group">
								<label>Nama Klinik:</label>
								<input type="text" name="nama_klinik" id="nama_klinik" class="form-control required" required>
							</div>
                            <div class="col-12 form-group">
								<label>Alamat Klinik:</label>
								<textarea name="alamat_klinik" id="alamat_klinik" class="form-control" cols="30" rows="8" required></textarea>
							</div>
						     
							<div class="col-12 form-group">
								<label>Status:</label>
								<select class="form-select" name="status" id="status" required>
									<option value="">-- Pilih --</option>
									<option value="Peserta">Peserta</option>
									<option value="Pendamping">Pendamping</option>
									<option value="Peninjau">Peninjau</option>
								</select>
							</div>

							<div class="col-12 form-group" id="propinsi">
								<label>Propinsi:</label>
								<select class="form-select" name="id_provinsi" id="id_provinsi">
									<option value="">-- Pilih --</option>
									@foreach($provinsi as $i)
									<option value="{{ $i->code }}">{{ $i->name }}</option>
									@endforeach
								</select>
						</div>
							
							<div class="col-12 form-group" id="cabang">
										<label>Cabang:</label>
										<select class="form-select" name="id_kota" id="id_kota"></select>
									</div>

									<div class="col-12 form-group" id="mandat">
										<label>Upload Surat Mandat:</label>
										<input name="surat_mandat" type="file" class="file-loading">

									</div>

									<div class="col-12 form-group" id="komisi">
										<label>Komisi:</label>
										<select class="form-select" name="komisi">
											<option value="">-- Pilih --</option>
											<option value="Komisi AD-ART">Komisi AD-ART</option>
											<option value="Komisi ORTALA">Komisi ORTALA</option>
											<option value="Komisi Pembiayaan dan Kredensialing">Komisi Pembiayaan dan Kredensialing</option>
										</select>
									</div>
							<div class="col-12">
								<button type="submit" name="" class="btn btn-secondary">Daftar</button>
							</div>
						</form>
					</div>

					<div class="col-lg-6 ps-lg-5">
									
						<div class="fancy-title title-border">
							<h3>Informasi Pendaftaran</h3>
						</div>
					
						<div class="feature-box fbox-border fbox-light fbox-effect">
								<a href="#"><i class="i-circled i-light icon-ok"></i></a><h4>Registrasi Online</h4>
						</div>
						
						<div class="feature-box fbox-border fbox-light fbox-effect">
								<a href="#"><i class="i-circled i-light icon-ok"></i></a><h4>Pembayaran Pendaftaran</h4>
						</div>

						<div class="table-responsive" style="margin-left:50px;">
							<table class="table" widht="30%">
								<thead>
									<tr>
									<th>Pendaftar</th>
									<th>Harga</th>
									
									</tr>
								</thead>
								<tbody>
									<tr>
									<td>Konas ASKLIN Ke-2</td>
									<td>1.500.000</td>
									</tr>
								</tbody>
							</table>
						</div>

					    <div class="feature-box fbox-border fbox-light fbox-effect">
							<i class="i-circled i-light icon-ok"></i>
							<strong>Pembayaran via Website akan terkonfirmasi<br>secara otomatis sesuai payment gateway yang dipilih</strong>
						</div>

              
						<div class="feature-box fbox-border fbox-light fbox-effect">
								<i class="i-circled i-light icon-ok"></i>
								<strong>Pembayaran secara kolektif harap melakukan <br> konfirmasi terlebih dahulu kepada admin</strong>
						</div>
						<ul style="margin-left:60px;">
							<li><strong>Admin Konas</strong>  &nbsp;<a href="https://api.whatsapp.com/send/?phone=6281245999923">081245999923</a></li>
						</ul>
						
					</div>


					<a href="#myModal1" data-lightbox="inline" class="feedback-form-success-modal d-none"></a>
					<div class="modal1 mfp-hide" id="myModal1">
						<div class="block mx-auto" style="background-color: #FFF; max-width: 600px;">
							<div class="center clearfix" style="padding: 50px;">
								<img class="mb-4" src="demos/hosting/images/svg/checked.svg" alt="Image" width="60">
								<h3 class="text-uppercase">Thank You.</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perspiciatis accusantium rerum provident? Porro reiciendis iste voluptas, id sapiente impedit aperiam?</p>
								<p class="lead mb-2">Get 20% Off on Your Purchased.</p>
								<h3 class="mb-0">coupon Code: <strong class="text-success">canvas20</strong></h3>
							</div>
						</div>
					</div>

                    </div>
                </div>
			</div>
	</section>
@endsection

@push('css')

@endpush

@push('js')
<script type="text/javascript">
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

$('#propinsi').hide();
$('#cabang').hide();
$('#mandat').hide();
$('#komisi').hide();
$('#perorangan').hide();
	
$("#status").change(function() {
    if ($(this).val() == "Peserta") {
        $('#propinsi').show();
        $('#cabang').show();
        $('#mandat').show();
        $('#komisi').show();
        $('#perorangan').show();
    } else if ($(this).val() == "Pendamping") {
        $('#propinsi').show();
        $('#cabang').show();
		 $('#komisi').hide();
		 $('#mandat').hide();
	}
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
}).change();

	$('#feedback-form').on( 'formSubmitSuccess', function(){
				$('.feedback-form-success-modal').magnificPopup('open');
			});
</script>
@endpush