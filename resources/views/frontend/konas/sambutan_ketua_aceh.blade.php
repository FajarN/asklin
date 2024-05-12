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
							<h4 class="mb-3">Sambutan Ketua ASKLIN Wilayah Aceh</h4>

							<!-- Entry Meta
							============================================= -->
							<div class="d-flex justify-content-center">
								<div class="entry-meta mb-0 pb-3">
									<ul>
                                        <li><a href="#"><i class="icon-user"></i>Dr.Teuku Yusriadi, Sp.BA</a></li>
										<li><i class="icon-calendar3"></i> 01 April 2023</li>
									</ul>
								</div><!-- .entry-meta end -->
							</div>
						</div>
                        
                        <div class="col-lg-6 align-self-end mt-10 mt-lg-0">
						<img src="{{ asset('assets/images/ketua-aceh.png') }}" alt="">
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
                               
                                <h4>Puji syukur kita panjatkan kehadirat Tuhan Yang Maha Esa yang senantiasa melimpahkan rahmat dan kasih sayang Nya kepada kita semua dalam menjalankan tugas keprofesian kita.</h4>
                                <h4>Suatu kehiormatan bagi kami saat Aceh terpilih menjadi tuan rumah untuk acara Kongres ASKLIN Nasional di tahun ini. Aceh tidak hanya dikenal dengan negeri Serambi Mekkah dan pintu masuk jalur rempah, namun juga menjadi gerbang akan ilmu pengetahuan temasuk Kesehatan. Tahun ini menjadi tahun terbaik bagi Aceh akan menerima tamu dari berbagai klinik terkemuka di seluruh Indonesia untuk berbaur dan berbagi pengetahuan untuk memajukan Klinik Indonesia yang lebih baik .</h4>
								<h4>Kongres Nasional ASKLIN Indonesia kali ini akan diselenggarakan di Tanah Rencong, Banda Aceh, Aceh yang merupakan Provinsi paling barat dari Tanah Air Indonesia, diadakan pada tanggal 14-17 Juni 2023 dengan tema “Transformasi Industri Wisata Medis di Era Digital: Peran ASKLIN dalam Menghadapi Tantangan dan Meningkatkan Keunggulan Bersaing"</h4>
                                <h4>Saya selaku ketua pimpinan ASKLIN wilayah Aceh mengucapkan terima kasih sebesar-besarnya terhadap seluruh pihak yang membantu dan mendukung Aceh sebagai tuan rumah kali ini. Kami dari pihak Aceh siap menyambut Kongres ini dengan tangan terbuka dan penuh terhormat.</h4>
								<h4>Mari kita sukseskan Kongres Nasional ASKLIN Indonesia.</h4>
                                <div class="clear"></div>

                                <h4>Dr.Teuku Yusriadi, Sp.BA</h4>
                                <h4>Ketua Umum ASKLIN Wilayah Aceh</h4>

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