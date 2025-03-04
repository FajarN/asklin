@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu2')
    
    <section id="content">
			<div class="content-wrap pt-0 bg-light">
				<div class="container clearfix">

					<!-- Entry Image
					============================================= -->
					<div class="entry-image align-wide-full min-vh-50 d-flex align-items-center justify-content-center px-5" style="background: url('{{ asset("assets/images/wall-1.jpg") }}') no-repeat center center / cover;">

						<div class="p-4 bg-white position-relative rounded text-sm-center mw-xs">

							<!-- Entry Title
							============================================= -->
							<h2 class="mb-3">Pendahuluan</h2>

							<!-- Entry Meta
							============================================= -->
							<div class="d-flex justify-content-center">
								<div class="entry-meta mb-0 pb-3">
									<ul>
                                        <li><a href="#"><i class="icon-user"></i>Asklin</a></li>
										<li><i class="icon-calendar3"></i> 01 April 2023</li>
									</ul>
								</div><!-- .entry-meta end -->
							</div>

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
                                <h4>Asosiasi Klinik Indonesia disingkat dengan ASKLIN adalah Wadah Para Pengusaha Klinik/ Penanggung Jawab Klinik untuk berhimpun dalam rangka meningkatkan kualitas pelayanan kesehatan.</h4>

                                <h4>Didirikan di Jakarta, pada tanggal 01 Agustus 2011 oleh beberapa orang yang memang peduli terhadap perkembangan pelayanan kesehatan, dikuatkan secara hukum dengan akta pendirian No. 43 tanggal 27 Desember 2011 di Departemen Kementerian Hukum dan Hak Asasi Republik Indonesia tanggal 04 Mei 2012.</h4>

                                <h4>ASKLIN telah hadir untuk Klinik dalam rangka penelesaian masalah-masalah yang dirasakan oleh pengelola klinik baik dalam perizinan dan pembiayaan serta banyak hal lain yang telah dilakukan oleh Pengurus ASKLIN.</h4>

                                <h4>Kongres Nasional ASKLIN II merupakan acara pertemuan seluruh pengelola dan pimpinan klinik se-Indonesia untuk memilih ketua Asosiasi Klinik Indonesia periode selanjutnya. Kongres Nasional ASKLIN II akan dilaksanakan di Provinsi Aceh tepatnya di Kota Takengon. Selain memilih ketua ASKLIN, pada KONAS kali ini juga diselenggarakan lima simposium nasional yang menunjang pengetahuan dan menjadi ajang diskusi para pengelola dan pimpinan klinik se-Indonesia. Simposium akan menyajikan materi dari lintas profesi yaitu kedokteran, keperawatan, kebidanan, dan lainnya.</h4>

                                <h4>Kegiatan KONAS ASKLIN II akan ditutup dengan city tour. Para delegasi akan dibawa keliling menikmati keindahan dan kenikmatan kuliner di Kota Takengon.</h4>
								
								<div class="clear"></div>

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