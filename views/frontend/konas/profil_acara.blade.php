@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
    
    <section id="content">
			<div class="content-wrap">
				<div class="container clearfix">

					<div class="row col-mb-50 mb-0">

						<div class="col-lg-6">

							<div class="heading-block fancy-title border-bottom-0 title-bottom-border">
								<h5><span>Nama Acara</span> <br>KONGRES NASIONAL</h5>
							</div>

                             <div class="form-group">
                             <b>Tema Acara</b>
                             <p>"Peran ASKLIN dalam Era Transformasi Industri Wisata Medis"</p>
							
                            </div>
                            <div class="form-group">
                                <b>Tempat Pelaksanaan</b>
                                <p>
                               LUT TAWAR GRAND BALLROOM, Takengon - Aceh<br />
                                Takengon - Aceh
                                </p>
                            </div>

                            <div class="form-group">
                                <b>Tanggal Pelaksanaan</b>
                                <p>
                                14-17 Juni 2023<br />
                                </p>
                            </div>

                            <div class="form-group">
                                <b>Jenis Pelaksanaan</b>
                                <p>
                               <i>Offline</i> 800 orang<br />
                                </p>
                            </div>

						</div>

						<div class="col-lg-6">

						<div class="heading-block fancy-title border-bottom-0 title-bottom-border">
								<h5><span>Abstraksi Tema</span> <br>Peran ASKLIN dalam Era Transformasi Industri Wisata Medis</h5>
							</div>
							 <p>Peran ASKLIN dalam Menghadapi Tantangan dan Meningkatkan Keunggulan Bersaing</p>

							 Terms of Reference <br><a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" class="button button-border button-rounded button-teal" target="_BLANK">Download disini</a><hr>
							 Sponsorship Book <br><a href="{{ asset('assets/frontend/SPONSORSHIP_BOOK.pdf') }}" class="button button-border button-rounded button-amber"  target="_BLANK">Download disini</a><hr>
							 Virtual Background KONAS ASKLIN 2 <br><a href="https://drive.google.com/drive/folders/1_LxDefwLQmD-CVuZudMsbtU7OhTafbio" class="button button-border button-rounded button-aqua"  target="_BLANK">Download disini</a><hr>
							 Twibbon <br><a href="https://twb.nz/konas-asklin" class="button button-border button-rounded button-purple"  target="_BLANK">Download disini</a><br>

						</div>

					</div>


					

                    	<div class="fancy-title title-center title-border topmargin">
						<h3>Tempat Pelaksanaan</h3>
					</div>

				</div>

				<div id="related-portfolio" class="owl-carousel owl-carousel-full portfolio-carousel carousel-widget" data-margin="0" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="4">

					<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb1.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider1.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb2.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider2.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb3.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider3.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb4.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider4.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb5.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider5.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb6.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider6.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb7.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider7.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb8.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider8.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb9.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider9.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb10.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider10.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>

                    	<article class="portfolio-item pf-uielements pf-media">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="#">
									<img src="{{ asset('assets/images/slider/thumb11.jpg') }}" alt="Console Activity">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark flex-column" data-hover-animate="fadeIn">
										

										<div class="d-flex">
											<a href="{{ asset('assets/images/slider/slider12.jpg') }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInUpSmall" data-hover-animate-out="fadeOutDownSmall" data-hover-speed="350" data-lightbox="image" title="venue"><i class="icon-camera-retro"></i></a>
										</div>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
								</div>
							</div>
						</div>
					</article>


			        </div>
				</div>
		</section>

                
@endsection

@push('css')

@endpush

@push('js')

@endpush