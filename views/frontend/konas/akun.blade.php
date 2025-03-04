<style>
	body {
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
		}
		
		/* Table */
		table {
			margin: auto;
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
			font-size: 12px;

		}
		.demo-table {
			border-collapse: collapse;
			font-size: 13px;
		}
		.demo-table th, 
		.demo-table td {
			border-bottom: 1px solid #e1edff;
			border-left: 1px solid #e1edff;
			padding: 7px 17px;
		}
		.demo-table th, 
		.demo-table td:last-child {
			border-right: 1px solid #e1edff;
		}
		.demo-table td:first-child {
			border-top: 1px solid #e1edff;
		}
		.demo-table td:last-child{
			border-bottom: 0;
		}
		caption {
			caption-side: top;
			margin-bottom: 10px;
		}
		
		/* Table Header */
		.demo-table thead th {
			background-color: #E0FFFF;
			color: #000;
			border-color: #6ea1cc !important;
			text-transform: capitalize;
		}
		
		/* Table Body */
		.demo-table tbody td {
			color: #353535;
		}
		
		.demo-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.demo-table tbody tr:hover th,
		.demo-table tbody tr:hover td {
			background-color: #fff;
			border-color: #fff;
			transition: all .2s;
		}

		@media screen and (max-width: 520px) {
		.demo-table table.responsive {
			width: 100%;
		}
		.demo-table thead {
			display: none;
		}
		.demo-table td {
			display: block;
			text-align: right;
			font-weight: bold;
			border-right: 1px solid #e1edff;
		}
		.demo-table td::before {
			float: left;
			text-transform: capitalize;
			font-weight: bold;
			content: attr(data-header);
		}
}
</style>
@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
	<section id="content">
		<div class="content-wrap">
			<div class="container clearfix">
				<div class="row clearfix">
					<div class="col-md-9">
						<img src="{{ asset('images/konas').'/'.$konas->foto }}" class="alignleft img-circle img-thumbnail my-0" alt="Avatar" style="max-width: 120px;">
						<div class="heading-block border-0">
							<h3>{{ $konas->nama_peserta }}</h3>
							<span>Nomor Anggota : {{ $konas->no_anggota }}</span>
							<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="button button-mini button-circle button-purple"><i class="icon-off"></i>Logout</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</div>
						<div class="clear"></div>
						<div class="row clearfix">
							<div class="col-lg-12">
								<div class="tabs tabs-alt clearfix" id="tabs-profile">
									<ul class="tab-nav clearfix">
										<li><a href="#tab-profil"><i class="icon-user2"></i> Profile</a></li>
										<li><a href="#tab-hotel"><i class="icon-cart"></i> Pembelian Hotel</a></li>
										<li><a href="#tab-tiket"><i class="icon-plane"></i> Tiket Penerbangan</a></li>
									</ul>
									<div class="tab-container">
										<div class="tab-content clearfix" id="tab-profil">
										
											<table class="table table-bordered table-striped">
												<tbody>
													<tr>
														<th>Nama</th>
														<td>{{ $konas->nama_peserta }}</td>
													</tr>
													<tr>
														<th>NIK</th>
														<td>{{ $konas->nik }}</td>
													</tr>
													<tr>
														<th>No Anggota</th>
														<td>{{ $konas->no_anggota }}</td>
													</tr>
													<tr>
														<th>Telp</th>
														<td>{{ $konas->no_hp }}</td>
													</tr>
													<tr>
														<th>Email</th>
														<td>{{ $konas->email }}</td>
													</tr>
													<tr>
														<th>Status</th>
														<td>{{ $konas->status }}</td>
													</tr>
												</tbody>
											</table>
											<p class="mb-1">Status Pembayaran:</p>
											<div class="card border-color p-4">
												<h1>{{ $konas->status_pembayaran }}</h1>
											</div>
											@if($konas->status_pembayaran == "PENDING")
											    <a href="{{ route('konas.bayar') }}" class="button button-large bg-color button-light text-dark py-2 rounded-1 fw-medium nott ls0 ms-0 mt-3">Bayar Sekarang</a>
											@else
												<a href="{{ route('cetak_kartu', $konas->id) }}" target="_BLANK" class="button button-large bg-color button-light text-dark py-2 rounded-1 fw-medium nott ls0 ms-0 mt-3">Download Sertifikat</a>
												<a href="{{ route('cetak_invoice', $konas->id) }}" target="_BLANK" class="button button-large bg-color button-light text-dark py-2 rounded-1 fw-medium nott ls0 ms-0 mt-3">Download Invoice</a>
											@endif
										</div>
									    <div class="tab-content clearfix" id="tab-hotel">
											<div class="fancy-title topmargin title-border">
												<h4>Pembelian Paket Hotel </h4>
											</div>
											<strong>Check-in 13 Juni 2023 - Checkout 17 Juni 2023 atau Check-in 14 Juni 2023 - Checkout 18 Juni 2023 (4 Malam)</strong><br>
											<strong>Penambahan Jadwal dapat dilakukan dengan menghubungi Admin Konas ASKLIN 2 di <a href="https://api.whatsapp.com/send?phone=6281245999923" target="_BLANK">081245999923</a></strong>
											<hr>
											<form action="{{ route('konas.booking_hotel') }}" method="POST">
												@CSRF
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Pilih Hotel</strong></legend>
													<div class="col-md-6 col-lg-9">
														<select class="form-select form-control h-auto" name="id_hotel" id="id_hotel" required>
															<option value="----"></option>
															@foreach($hotel as $i)
																<option value="{{ $i->id }}">{{ $i->hotel }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Pilih Kamar</strong></legend>
													<div class="col-md-6 col-lg-9">
														<select class="form-select form-control h-auto" name="hotel" id="hotel" required></select>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Harga Kamar</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2 txt" type="number" name="harga_kamar" id="harga_kamar" required readonly>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Jumlah Kamar</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2 txt" type="number" name="jumlah" id="jumlah" required>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Total Harga Kamar</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="number" name="total_harga_kamar" id="total_harga_kamar" required readonly>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Ekstra Bed</strong></legend> *<span id="hehe"></span>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2 txt1" type="number" name="extrabed" id="extrabed">
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Harga Ekstra Bed</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2 txt1" type="number" name="harga_kasur" id="harga_kasur" readonly>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Total Harga Extrabed</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="number" name="total_harga_extrabed" id="total_harga_extrabed" readonly>
													</div>
												</div>
												<button type="submit" class="btn btn-primary mt-3">Submit</button>
											</form>
											<div class="table-responsive">
											<table class="table demo-table">
												<thead>
													<tr>
														<th>Tanggal Pemesanan</th>
														<th>Hotel</th>
														<th>Tipe</th>
														<th>Harga <small>perkamar</small></th>
														<th>Jml Kamar</th>
														<th>Harga Extrabed</th>
														<th>Jml Extrabed</th>
														<th>Total</th>
														<th>Status Pembayaran</th>
													</tr>
												</thead>
												<tbody>
													@foreach($myhotel as $i)
													<tr>
														<td data-header="Tanggal Pemesanan">{{ $i->tanggal }}</td>
														<td data-header="Hotel">{{ $i->hotel }}</td>
														<td data-header="Tipe"></td>
														<td data-header="Harga Perkamar">{{ $i->harga_kamar }}</td>
														<td data-header="Jml Kamar">{{ $i->jumlah }}</td>
														<td data-header="Harga Extrabed">{{ $i->harga_kasur }}</td>
														<td data-header="Jml Extrabed">{{ $i->extrabed }}</td>
														<td data-header="Total">{{ $i->total }}</td>
														<td data-header="Status Pembayaran">
															@if($i->status_pembayaran == "PENDING")
															<a  class="button button-3d button-mini button-rounded button-red" href="{{ $i->link_pembayaran }}">Bayar Sekarang</a>
															@else
															<a  class="button button-3d button-mini button-rounded button-dirtygreen" href="#">{{ $i->status_pembayaran }}</a>
															@endif

														
														</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
										</div>
										<div class="tab-content clearfix" id="tab-tiket">
											<div class="fancy-title topmargin title-border">
												<h4>Opsional </h4>
											</div>
											<form action="{{ route('penerbanganku') }}" method="POST">
												@csrf
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Tanggal</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="datetime-local" name="tanggal" id="tanggal" required>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Dari Bandara</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="text" name="dari" id="dari" required>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>Tujuan Bandara</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="text" name="tujuan" id="tujuan" required>
													</div>
												</div>
												<div class="form-group row">
													<legend class="col-form-label col-sm-6 pt-0"><strong>No Penerbangan</strong></legend>
													<div class="col-md-6 col-lg-9">
														<input class="form-control text-3 h-auto py-2" type="text" name="pesawat" id="pesawat" required>
													</div>
												</div>
												<button type="submit" class="btn btn-primary mt-3">Submit</button>
											</form>
												<div class="table-responsive">
											<table class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Tanggal</th>
														<th>Dari Bandara</th>
														<th>Tujuan Bandara</th>
														<th>No Penerbangan</th>
													</tr>
												</thead>
												<tbody>
													@foreach($myflight as $i)
													<tr>
														<td>{{ $i->tanggal }}</td>
														<td>{{ $i->dari }}</td>
														<td>{{ $i->tujuan }}</td>
														<td>{{ $i->pesawat }}</td>
													</tr>
													@endforeach
												</tbody>
											</table>
												<div class="table-responsive"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>
						</div>
						<div class="w-100 line d-block d-md-none"></div>
						<div class="col-md-3">
							<div class="fancy-title topmargin title-border">
								<h4>Sekretariat</h4>
							</div>
							<p>Jl. Tgk. Abu Lam U no. 6, Kota Baru, Kec. Kuta Alam, Kota Banda Aceh</p>
							<div class="fancy-title topmargin title-border">
								<h4>Kontak Official</h4>
							</div>
							<ul style="margin-left:60px;">
								<li>Admin Konas  WA. <a href="https://api.whatsapp.com/send?phone=6281245999923" target="_BLANK">081245999923</a></li>
							</ul>
							<div class="fancy-title topmargin title-border">
								<h4>Social Profiles</h4>
							</div>
							<a href="https://api.whatsapp.com/send?phone=6281245999923" class="social-icon si-whatsapp si-small si-rounded si-light" title="WhatsApp">
								<i class="icon-whatsapp"></i>
								<i class="icon-whatsapp"></i>
							</a>
							<a href="#" class="social-icon si-tiktok si-small si-rounded si-light" title="Tiktok+">
								<i class="icon-tiktok"></i>
								<i class="icon-tiktok"></i>
							</a>
							<a href="https://www.instagram.com/konasasklin" class="social-icon si-instagram si-small si-rounded si-light" title="Instagram">
								<i class="icon-instagram"></i>
								<i class="icon-instagram"></i>
							</a>
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
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    $('#id_hotel').on('change', function(){
        let id_hotel = $('#id_hotel').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('konas.getTipe') }}',
            data: {id_hotel:id_hotel},
            cache: false,
            success: function(data){
                $("#hotel").empty();
                $("#harga").empty();
                $("#extrabed").empty();
                $("#harga_kasur").empty();
                $.each(data,function(key, value)
                {
                    $("#hotel").append('<option value=' + value.id + '>' + value.tipe + ' (Stok Kamar:) ' +value.stok+'</option>');
                });
            }
        })
    }).change();
    
    $('#hotel').on('change', function(){
        let id = $('#hotel').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('konas.getTipeHotel') }}',
            data: {id:id},
            cache: false,
            success: function(data){
                $("#harga_kamar").val(data.harga);
                if(data.extrabed == '0'){
                    $("#extrabed").hide();
                    $("#harga_kasur").hide();
                    $("#total_harga_extrabed").hide();
                    $("#total_harga_extrabed").empty();
                }else{
                    $("#extrabed").show();
                    $("#harga_kasur").show();
                    $("#harga_kasur").val(data.extrabed);
                    $("#total_harga_extrabed").show();
                    $("#hehe").html('Sisa extrabed: ' + data.stok_extrabed);
                    
                }
            }
        })
    }).change();
    
    calculateSum();
    calculateSum1();

    $(".txt").on("keydown keyup", function() {
        calculateSum();
    });
    
    $(".txt1").on("keydown keyup", function() {
        calculateSum1();
    });
});

function calculateSum() {
    var sum = 1;
    $(".txt").each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
            sum *= parseFloat(this.value);
            $(this).css("background-color", "white");
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "white");
        }
    });
	$("input#total_harga_kamar").val(sum.toFixed(0));
}

function calculateSum1() {
    var sum1 = 1;
    $(".txt1").each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
            sum1 *= parseFloat(this.value);
            $(this).css("background-color", "white");
        }
        else if (this.value.length != 0){
            $(this).css("background-color", "white");
        }
    });
	$("input#total_harga_extrabed").val(sum1.toFixed(0));
}
</script>
@endpush