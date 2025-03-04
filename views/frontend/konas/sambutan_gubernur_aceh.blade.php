@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu2')
    
    <section id="content">
			<div class="content-wrap pt-0 bg-light">
				<div class="container clearfix">

					<!-- Entry Image
					============================================= -->
					<div class="entry-image align-wide-full min-vh-50 d-flex align-items-center justify-content-left px-5" style="background: url('{{ asset("assets/images/wall-1.jpg") }}') no-repeat center center / cover;">

						<div class="p-4 bg-white position-relative rounded text-sm-center mw-xs d-none d-md-flex">

							<!-- Entry Title
							============================================= -->
							<h4 class="mb-3">Sambutan PJ Gubernur Aceh</h4>

							<!-- Entry Meta
							============================================= -->
							<div class="d-flex justify-content-center">
								<div class="entry-meta mb-0 pb-3">
									<ul>
                                        <li><a href="#"><i class="icon-user"></i>Achmad Marzuki</a></li>
										<li><i class="icon-calendar3"></i> 01 April 2023</li>
									</ul>
								</div><!-- .entry-meta end -->
							</div>
						</div>
                        
                        <div class="col-lg-6 align-self-end mt-10 mt-lg-0"">
						<img src="{{ asset('assets/images/gubernur.png') }}" alt="">
					</div>
				</div>

					<class class="single-post mw-xs mx-auto">

						<!-- Single Post
						============================================= -->
						<div class="entry clearfix">

							<div class="clear"></div>

							<!-- Entry Content
							============================================= -->
							<div class="entry-content mt-10 "style="text-align: justify;">
                                <h4><i>Assalamu’alaikum Wr.Wb</i></h4>
                                <h4> Puji serta syukur kita panjatkan kehadirat Allah SWT atas segala rahmat dan hidayahNya kita masih dapat bekerja dan berkarya dengan baik. Teriring salam dan Doa kita sanjungkan kepada Nabi Rahmatan lil alamin Rasulullah Muhammad SAW yang telah membawa kita dari alam kegelapan menuju alam yang penuh dengan ilmu pengetahuan. </h4>

                                <h4>Pemerintah Aceh menyadari bahwa Klinik tidak hanya berperan dalam proses perawatan pasien semata, namun kehadiran klinik turut menjadi mitra pemerintah dalam upaya percepatan pembangunan suatu daerah khususnya Aceh. Melalui Asosiasi Klinik Indonesia, para pemegang klinik dari Sabang sampai Merauke diharapkan mampu dapat terus meningkatkan kapasitas diri serta memperkuat peran dan fungsinya dalam transformasi sistem kesehatan yang lebih baik di masa yang akan datang.</h4>

                                <h4>Memuliakan tamu “pemulia jame” adalah adat kami masyarakat Aceh. Suatu kebanggan dan kehormatan bagi kami untuk dapat menyambut anda semua “para klinik-klinik terbaik di seluruh Indonesia” beserta seluruh tamu undangan dan mitra Asosiasi Klinik Indonesia untuk dapat melaksanakan dan merumuskan berbagai kajian penting dalam Kongres Nasional Asosiasi Klinik Indonesia di bulan Juni 2023 mendatang di Koeta Radja – Banda Aceh. </h4>

                                <h4><i>Wassalamu’alaikum wr. Wb</i></h4>
								
								<div class="clear"></div>

                                <h4>Achmad Marzuki</h4>
                                <h4>PJ Gubernur Aceh</h4>

                                </div>
                        </div>
                        </class>
                        </div>
                        </div>
                        </section>
    
@endsection

@push('css')

@endpush

@push('js')

@endpush