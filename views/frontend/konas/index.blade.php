@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
    @include('frontend.konas.slider')
    
    <div class="section parallax dark mb-0" style="background-image: url('{{ asset("assets/images/parallax.jpg") }}'); background-size: cover; padding: 100px 0; margin-top:-40px;" data-bottom-top="background-position:0px 300px;" data-top-bottom="background-position:0px -300px;">
		<div class="heading-block center">
    		<h3 class="text-white">PENDAFTARAN AKAN DITUTUP PADA TANGGAL 07 JUNI 2023</h3>
    	</div>
    	<div class="text-white" id="simple-timer"></div>
    </div>

	<section id="content" style="background: white">
		<div class="content-wrap">
			<div class="container clearfix">
				<h4 class="h2 fw-semibold mb-3 text-center">Flow Registrasi</h4>
				<h4 class="h5 fw-semibold mb-3 text-center">Detail urutan registrasi peserta mandiri maupun kolektif, KLIK DISINI</h4>
				<div class="row pricing block-pricing-10 justify-content-center align-items-stretch">
					<div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
						<div class="pricing-box d-flex flex-column justify-content-between align-items-center h-100 rounded-5 pricing-simple px-5 py-0 border border-success text-center overflow-visible">
							<h4 class="pricing-title ls0 bg-white fw-normal">Registrasi Data Peserta</h4>
							<div class="pricing-features">
								<h4 class="mb-3 fw-semibold">Calon Pesrta Konas mendaftarkan diri dengan melengkapi data-data yang dibutuhkan</h4>
							</div>
							<a href="{{ route('konas.registrasi') }}" class="btn btn-action btn-outline-success bg-white h-bg-dark text-dark h-text-light h-border-transparent btn-lg px-4">Form Pendaftaran</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
						<div class="pricing-box d-flex flex-column justify-content-between align-items-center h-100 rounded-5 pricing-simple px-5 py-0 border border-danger text-center overflow-visible">
							<h4 class="pricing-title ls0 bg-white fw-normal">Pemesanan Kamar Hotel</h4>
							<div class="pricing-features">
								<h4 class="mb-3 fw-semibold">Peserta dapat melakukan pemesanan hotel dimana akan menginap</h4>
							</div>
							<a href="{{ route('konas.tour') }}" class="btn btn-action btn-outline-danger bg-white h-bg-dark text-dark h-text-light h-border-transparent btn-lg px-4">List Hotel & Tour</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
						<div class="pricing-box d-flex flex-column justify-content-between align-items-center h-100 rounded-5 pricing-simple px-5 py-0 border border border-warning text-center overflow-visible">
							<h4 class="pricing-title ls0 bg-white fw-normal">Pemesanan Tiket Pesawat</h4>
							<div class="pricing-features">
								<h4 class="mb-3 fw-semibold">Peserta juga dapat dibantu pemesanan tiket pesawat dari Jakarta Menuju Banda Aceh</h4>
							</div>
							<a href=" https://api.whatsapp.com/send/?phone=6281245999923&text=Saya%20ingin%20tau%20info%20mengenai%20tiket%20penerbangan.%20Bagaimana?" class="btn btn-action btn-outline-warning bg-white h-bg-dark text-dark h-text-light h-border-transparent btn-lg px-4">Contact Us</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	
    	<div class="container">
    		<div class="card border-0 mx-auto rounded ytb-card" style="box-shadow: 0 32px 46px 2px rgba(0,0,0,0.12); margin-top:10px">
    			<div id="ytb-video" class="yt-bg-player card-img-top rounded-top" data-mute="false" data-quality="hd1080" data-volume="80" data-autoplay="false" data-container="self" data-video="https://youtu.be/SF0DLn3hEnc">
    				<iframe width="560" height="315" src="https://www.youtube.com/embed/rieNdaumPAI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    			</div>
    			<div class="card-body p-5">
    				<div class="row justify-content-between align-items-center">
    					<div class="col-md-12">
    						<div class="heading-block border-bottom-0 mb-0">
    							<h3 class="nott fw-semibold ls0 mb-2">Tanah Luar Biasa dengan Budaya Spiritual</h3>
    							<p class="mb-1">Banda Aceh, dahulu bernama Kutaradja. Sebagai pintu gerbang pariwisata di pintu masuk paling barat Indonesia, Anda diajak untuk mempelajari sisa-sisa kemegahan zaman keemasan Aceh Darussalam yang sangat bernilai spiritual bagi bangsa.</p>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row topmargin align-items-center mx-auto bottommargin-lg" style="max-width: 840px">
    			<div class="col-md-3 mb-2 mb-md-0">
    				<h3 class="mb-0">Partners:</h3>
    			</div>
    			<div class="col-md-9">
    				<div id="oc-images" class="owl-carousel image-carousel carousel-widget" data-margin="50" data-nav="true" data-pagi="false" data-items-xs="2" data-items-md="3" data-items-xl="4" data-loop="true" data-autoplay="3500">
    					@foreach($partner as $i)
    					    <div class="oc-item">
    					       <a href="{{ $i->link }}">
    					            <img src="{{ asset('images/partner') }}/{{ $i->photo }}" alt="{{ $i->nama }}">
    					       </a>
    					    </div>
    					@endforeach
    				</div>
    			</div>
    		</div>
    	</div>
	</section>
	
	<section id="content">
		<div class="content-wrap">
			<div class="container clearfix">

				<div class="row align-items-stretch col-mb-50 mb-0">
					<!-- Contact Form
					============================================= -->
					<div class="col-lg-6">

						<div class="fancy-title title-border">
							<h3>Send us an Email</h3>
						</div>

						<div class="form-widget">

							<div class="form-result"></div>

							<form class="mb-0" id="template-contactform" name="template-contactform" action="include/form.php" method="post">

								<div class="form-process">
									<div class="css3-spinner">
										<div class="css3-spinner-scaler"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 form-group">
										<label for="template-contactform-name">Nama Lengkap <small>*</small></label>
										<input type="text" id="template-contactform-name" name="template-contactform-name" value="" class="sm-form-control required" />
									</div>

									<div class="col-md-6 -m-12">
										<label for="template-contactform-email">Email <small>*</small></label>
										<input type="email" id="template-contactform-email" name="template-contactform-email" value="" class="required email sm-form-control" />
									</div>

									<div class="col-md-6 form-group">
										<label for="template-contactform-phone">No Telp</label>
										<input type="text" id="template-contactform-phone" name="template-contactform-phone" value="" class="sm-form-control" />
									</div>

									<div class="col-md-6 form-group">
										<label for="template-contactform-subject">Subject <small>*</small></label>
										<input type="text" id="template-contactform-subject" name="subject" value="" class="required sm-form-control" />
									</div>


									<div class="w-100"></div>


									<div class="w-100"></div>

									<div class="col-12 form-group">
										<label for="template-contactform-message">Pesan <small>*</small></label>
										<textarea class="required sm-form-control" id="template-contactform-message" name="template-contactform-message" rows="6" cols="30"></textarea>
									</div>

									<div class="col-12 form-group d-none">
										<input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
									</div>

									<div class="col-12 form-group">
										<button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d m-0">Submit</button>
									</div>
								</div>

								<input type="hidden" name="prefix" value="template-contactform-">

							</form>
						</div>

					</div><!-- Contact Form End -->

					<!-- Google Map
					============================================= -->
					<div class="col-lg-6 min-vh-50">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.98246658851!2d95.3426585!3d5.5696093!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304037fc17321673%3A0xd25f25566e14f1a6!2sStem%20Cell%20Student%20Learning%20Center!5e0!3m2!1sen!2sid!4v1680714277440!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div><!-- Google Map End -->
				</div>

				<!-- Contact Info
				============================================= -->
				<div class="row col-mb-50">
					<div class="col-sm-6 col-lg-3">
						<div class="feature-box fbox-center fbox-bg fbox-plain">
							<div class="fbox-icon">
								<a href="#"><i class="icon-map-marker2"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Tempat<span class="subtitle">LUT TAWAR GRAND BALLROOM, Takengon - Aceh</span></h3>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-3">
						<div class="feature-box fbox-center fbox-bg fbox-plain">
							<div class="fbox-icon">
								<a href="#"><i class="icon-whatsapp"></i></a>
							</div>
							<div class="fbox-content">
								<h3>WhatsApp<span class="subtitle"><a href="https://api.whatsapp.com/send/?phone=6281245999923" target="_BLANK">081245999923</a></span></h3>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-3">
						<div class="feature-box fbox-center fbox-bg fbox-plain">
							<div class="fbox-icon">
								<a href="#"><i class="icon-tiktok"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Tiktok <span class="subtitle">@konasasklin</span></h3>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-lg-3">
						<div class="feature-box fbox-center fbox-bg fbox-plain">
							<div class="fbox-icon">
								<a href="#"><i class="icon-instagram"></i></a>
							</div>
							<div class="fbox-content">
								<h3>Follow on Instagram<span class="subtitle"><a href="https://www.instagram.com/konasasklin">@konasasklin</a></span></h3>
							</div>
						</div>
					</div>
				</div><!-- Contact Info End -->

			</div>
		</div>
	</section>
@endsection

@push('css')

@endpush

@push('js')

@endpush