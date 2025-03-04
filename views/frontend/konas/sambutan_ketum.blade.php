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
							<h3 class="mb-3">Sambutan Ketua Umum</h3>

							<!-- Entry Meta
							============================================= -->
							<div class="d-flex justify-content-center">
								<div class="entry-meta mb-0 pb-3">
									<ul>
                                        <li><a href="#"><i class="icon-user"></i>dr. Eddi Junaidi, SpOG.,SH.,M.Kes</a></li>
										<li><i class="icon-calendar3"></i> 01 April 2023</li>
									</ul>
								</div><!-- .entry-meta end -->
							</div>
						</div>
                        
                        <div class="col-lg-6 align-self-end mt-10 mt-lg-0">
						<img src="{{ asset('assets/images/ketua-umum.png') }}" alt="">
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
                               <h4>Puji syukur kita panjatkan kehadirat Tuhan Yang Maha Esa yang senantiasa  melimpahkan rahmat dan kasih sayang Nya kepada kita semua dalam menjalankan tugas keprofesian kita. </h4>
                               <h4>Telah menjadi suatu budaya apabila ingin mengambil keputusan terhadap suatu hal, dilakukan melalui mekanisme demokrasi yang bijaksana dan hikmat, yakni musyawarah untuk mencapai mufakat. Salah satu wahana dalam bermusyawarah adalah diwujudkan dalam bentuk Kongres Nasional . </h4>
                               <h4>Kongres Nasional ASKLIN Indonesia merupakan Musyawarah Nasional para Klinik diseluruh Indonesia, sebagaimana yang tercantum di dalam Anggaran Rumah Tangga (ART) ASKLIN adalah rapat yang dihadiri oleh segenap perangkat organisasi  dari tingkat pusat, wilayah dan cabang. Kongres Nasional ASKLIN Indonesia diadakan sekali dalam 5 (lima) tahun untuk menetapkan Anggaran Dasar dan Anggaran Rumah Tangga, pedoman pokok tata laksana organisasi dan kebijakan strategis nasional </h4>
                               <h4>Kongres Nasional ASKLIN Indonesia kali ini akan diselenggarakan di Takengon, Aceh yang merupakan Provinsi paling barat dari Tanah Air Indonesia, diadakan pada tanggal 14-17 Juni 2023 dengan tema “Peran ASKLIN dalam Era Transformasi Industri Wisata Medis" </h4>
                               <h4>Kepada ASKLIN Cabang Aceh selaku Panitia Pelaksana kami mengucapkan terima kasih yang sebesar-besarnya atas kesediaannya menjadi tuan rumah Kongres Nasional ASKLIN Indonesia. Mari kita sukseskan Kongres Nasional ASKLIN Indonesia. </h4>

								
								<div class="clear"></div>

                                <h4>dr. Eddi Junaidi, SpOG.,SH.,M.Kes</h4>
                                <h4>Ketua Umum Pengurus Pusat</h4>

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