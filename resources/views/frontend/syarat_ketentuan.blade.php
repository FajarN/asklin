@extends('layouts.frontend.layout')
 
@section('content')
<section id="page-title" class="page-title-parallax page-title-dark" style="background-image: url('{{ asset("assets/frontend/images/baru/paralax.jpg") }}'); background-size: cover; padding: 120px 0;" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">
    <div class="container clearfix">
        <h1 class="text-center">PENDAFTARAN ANGGOTA ASKLIN</h1>    
    </div>
</section>

<div class="line d-block d-md-none"></div>

@include('frontend.step')

<div class="content-wrap py-4 overflow-visible">
    <div class="card mx-auto mw-md border-0 shadow rounded-4 card-seo-about mb-5 mb-lg-0">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="heading-block border-bottom-0 mb-0">
                        <h3 class="nott ls0 mb-3">Syarat & Ketentuan</h3>
                        <ol>
                            <li style="text-align: justify; font-size: 20px; font-weight: bold;">Registrasi online&nbsp;<em>www.asklin.org</em>&nbsp;dipergunakan untuk seluruh klinik Pratama maupun Utama di seluruh Indonesia yg tergabung menjadi anggota asklin.</li>
                            <li style="text-align: justify; font-size: 20px; font-weight: bold;">Dengan mengisi registrasi online ini calon anggota dan selanjutnya wajib memenuhi peraturan asklin sesuai AD/ART yang berlaku.</li>
                            <li style="text-align: justify; font-size: 20px; font-weight: bold;">Yang mendaftar adalah pemilik, pengelola atau penanggungjawab klinik.</li>
                            <li style="text-align: justify; font-size: 20px; font-weight: bold;">Anggota merupakan klinik kepemilikan perorangan maupun badan hukum.</li>
                            <li style="text-align: justify; font-size: 20px; font-weight: bold;">Anggota menyatakan bahwa data yang diisikan adalah benar sesuai data bukti objektif yang ada.</li>
                        </ol>
                        <a href="{{ route('pendaftaran.anggota') }}" class="button button-rounded text-capitalize m-0 ls0">SETUJU</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection