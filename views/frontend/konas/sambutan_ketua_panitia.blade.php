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

						<div class="p-4 bg-white position-relative rounded text-sm-center mw-xs d-none d-sm-flex">

							<!-- Entry Title
							============================================= -->
							<h4 class="mb-3">Sambutan Ketua Panitia</h4>

							<!-- Entry Meta
							============================================= -->
							<div class="d-flex justify-content-center">
								<div class="entry-meta mb-0 pb-3">
									<ul>
                                        <li><a href="#"><i class="icon-user"></i></a></li>
										<li><i class="icon-calendar3"></i> 01 April 2023</li>
									</ul>
								</div><!-- .entry-meta end -->
							</div>
						</div>
                        
                        <div class="col-lg-6 align-self-end mt-10 mt-lg-0">
							<img src="{{ asset('assets/images/ketua-panitia.png') }}" alt="">
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
                               
                               <h4>Salam pada seluruh Klinik Indonesia</h4>

                               <h4>Yang terhormat para teman sejawat di tanah air. Puji Syukur kita panjatkan kehadirat Tuhan Yang Maha Esa atas rahmat dan hidayah yang telah diberikan selama ini. Merupakan suatu kehormatan bagi kami Pengurus ASKLIN  Aceh untuk menjadi tuan rumah Pelaksanaan Kongres Nasional ASKLIN Indonesia berdasarkan ketetapan Rapat Pleno Pengurus Besar ASKLIN pada tanggal 31 Maret 2021. Pelaksanaan Kongres Nasional ASKLIN yang akan dilaksanakan pada tanggal 14 - 17 Juni 2023 akan mengusung tema “Transformasi Industri Wisata Medis di Era Digital: Peran ASKLIN dalam Menghadapi Tantangan dan Meningkatkan Keunggulan Bersaing"</h4>
                                
                                <h4>Aceh merupakan Provinsi paling Barat dari Tanah Air Indonesia. Saat ini Provinsi yang dijuluki sebagai Tanah Rencong ini sudah memiliki berbagai fasilitas dengan standar terbaik yang siap mendukung berbagai kegiatan seperti konferensi atau pertemuan baik berskala Nasional maupun Internasional. Pelaksanaan Kongres Nasional kali ini akan dikemas dengan apik bernuansa modern tanpa mengurangi nilai – nilai kebudayan Aceh yang telah dikenal hingga ke mancanegara. Kami siap memanjakan sejawat para Klinik terkemuka dari Sabang sampai Merauke dan seluruh kolega dengan destinasi pariwisata dan sajian kuliner terbaik kaliber dunia. </h4>
                               <div class="clear"></div>

                                <h4>Ketua Panitia</h4>

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