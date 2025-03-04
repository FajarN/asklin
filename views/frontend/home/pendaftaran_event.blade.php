@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Pendaftaran Event</h1>
            <span>Pendaftaran Event KONGRES ASKLIN II</span>
        </div>
    </section>

    <section id="content">
		<div class="content-wrap pt-0">
			<section id="content">
    			<div class="content-wrap">
    				<div class="container clearfix">
    					<div class="form-widget">
    						<div class="form-result"></div>
    						<div class="row">
    							<div class="col-lg-6">
        								<form class="row" action="{{ route('pendaftaran_event.daftar') }}" method="POST" enctype="multipart/form-data">
        								    @CSRF
        									<div class="col-12 form-group">
        										<label>Nama Peserta:</label>
        										<input type="text" name="nama_peserta" class="form-control required" required>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Keanggotaan:</label>
        										<select class="form-select" name="keanggotaaan" required>
        											<option value="">-- Pilih --</option>
        											<option value="0">Anggota</option>
        											<option value="1">Bukan Anggota</option>
        											<option value="2">Perseorangan</option>
        										</select>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Nama Klinik:</label>
        										<input type="text" name="nama_klinik" class="form-control required" required>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Alamat Klinik:</label>
        										<textarea name="alamat" class="form-control" cols="30" rows="8" required></textarea>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Propinsi:</label>
        										<select class="form-select" name="id_provinsi" id="id_provinsi">
        											<option value="">-- Pilih --</option>
        											@foreach($provinsi as $i)
        											    <option value="{{ $i->code }}">{{ $i->name }}</option>
        											@endforeach
        										</select>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Kabupaten:</label>
        										<select class="form-select" name="id_kota" id="id_kota" required></select>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Kecamatan:</label>
        										<select class="form-select" name="id_kecamatan" id="id_kecamatan" required></select>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Kelurahan:</label>
        										<select class="form-select" name="id_kelurahan" id="id_kelurahan" required></select>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Telepon:</label><br>
        										<input type="number" name="tlf" id="tlf" class="form-control required" required>
        									</div>
        									<div class="col-12 form-group">
        										<label>Email:</label>
        										<input type="email" name="email" id="email" class="form-control required" required>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Nama Hotel:</label>
        										<select class="form-select" name="nama_hotel" required>
        											<option value="">-- Pilih --</option>
        											<option value="Hotel A">Hotel A</option>
        											<option value="Hotel B">Hotel B</option>
        											<option value="Hotel C">Hotel C</option>
        										</select>
        									</div>
                                        	<div class="col-12 form-group">
        										<label>Jumlah Kamar:</label>
        										<input type="number" name="jumlah_kamar" class="form-control required" required>
        									</div>
                                            <div class="col-12 form-group">
        										<label>Tanggal Masuk:</label>
        										<input type="date" name="tgl_masuk" class="form-control required" required>
        									</div>
                                              <div class="col-12 form-group">
        										<label>Tanggal Keluar:</label>
        										<input type="date" name="tgl_keluar" class="form-control required" required>
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
                                <td>Anggota Asklin</td>
                                <td>1000.000</td>
                                </tr>
                                <tr>
                                <td>Pembayaran Di Tempat</td>
                                <td>1500.000</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>

                      
                    <div class="feature-box fbox-border fbox-light fbox-effect">
                            <a href="#"><i class="i-circled i-light icon-ok"></i></a><h4>Konfirmasi Pembayaran Via WA Ke</h4>
                    </div>
                    <ul style="margin-left:60px;">
                        <li>Alkudri Temasmiko  HP.01298312039</li>
                        <li>Pengurus  HP.01298312039</li>
                    </ul>
                     <div class="konfir">
                    <span>Pembayaran dapat diTransfer Ke</span><br>
                    <span>BSI Cabang Jakarta</span><br>
                    <span>An Asosiasi Klinik Indonesia (ASKLIN)</span><br>
                    <span>No Rek. 012389012</span>
                  </div>

                            </div>
                        </div>

                    </div>

				</div>
			</div>
		</section>

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#id_provinsi").select2();

    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#id_kota').html(data);
                $('#id_kecamatan').html();
                $('#id_kelurahan').html();
            }
        })
    }).change();

    $('#id_kota').on('change', function(){
        let id_kota = $('#id_kota').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKecamatan') }}',
            data: {id_kota:id_kota},
            cache: false,
            success: function(data){
                $('#id_kecamatan').html(data);
                $('#id_kelurahan').html();
            }
        })
    }).change();

    $('#id_kecamatan').on('change', function(){
        let id_kecamatan = $('#id_kecamatan').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKelurahan') }}',
            data: {id_kecamatan:id_kecamatan},
            cache: false,
            success: function(data){
                $('#id_kelurahan').html(data);
            }
        })
    }).change();
});
</script>
@endpush