@extends('layouts.frontend.layout')

@section('content')
    {{-- slider normal --}}
    <section id="slider" class="slider-element h-auto" style="background-color: #222;">
        <div class="slider-inner">

            <div class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-pagi="false" data-loop="true"
                data-animate-in="rollIn" data-speed="450" data-animate-out="rollOut" data-autoplay="5000">

                @foreach ($sliders as $slider)
                    <a href="#">
                        <img src="{{ asset($slider->foto_slider) }}" alt="{{ $slider->judul }}">
                    </a>
                @endforeach

            </div>
        </div>
    </section>

    {{-- slider Besar --}}
    {{-- <section id="slider" class="slider-element min-vh-75">
        <div class="owl-carousel carousel-widget"
            data-margin="0"
            data-items="1"
            data-pagi="false"
            data-loop="true"
            data-animate-in="rollIn"
            data-speed="450"
            data-animate-out="rollOut"
            data-autoplay="5000">

            @foreach ($sliders as $slider)
                <div class="item">
                    <div class="slider-bg" style="background-image: url('{{ asset($slider->foto_slider) }}'); background-size: cover; background-position: center; height: 75vh;">
                        <div class="slider-caption">
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section> --}}


    <div class="section header-stick">
        <div class="container clearfix">
            <div class="row">

                <div class="col-lg-9">
                    <div class="heading-block bottommargin-sm">
                        <h4>Selamat datang di website resmi Asosiasi Klinik Indonesia (ASKLIN)</h4>
                    </div>

                    <p class="">Bersama, kita wujudkan klinik yang mandiri, berkualitas, Mari bergerak
                        bersama untuk
                        pelayanan kesehatan yang lebih baik.</p>
                    <p class="">Bergabunglah dengan ASKLIN dan jadi bagian dari perubahan positif untuk
                        klinik di
                        seluruh Indonesia</p>
                </div>

                <div class="col-lg-3">
                    <a href="{{ route('register') }}" class="button button-3d button-danger button-large w-100 center"
                        style="margin-top: 30px;">Daftar Calon Anggota</a>
                </div>

            </div>
        </div>
    </div>

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="heading-block border-bottom-0 mb-5 center">
                    <h3>BERITA</h3>
                </div>

                <div id="posts" class="post-grid row grid-container gutter-40">
                    @forelse ($data as $berita)
                        <div class="entry col-md-4 col-sm-6 col-12">
                            <div class="grid-inner">
                                <div class="entry-image">
                                    <a href="{{ route('berita.detail', ['path' => $berita->path]) }}">
                                        <img src="{{ asset('assets/images/berita/thumbnails/' . $berita->thumb) }}"
                                            class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0"
                                            alt="{{ $berita->judul }}"></a>
                                </div>
                                <div class="entry-title">
                                    <h2><a
                                            href="{{ route('berita.detail', ['path' => $berita->path]) }}">{{ $berita->judul }}</a>
                                    </h2>
                                </div>
                                <div class="entry-meta">
                                    <ul>
                                        <li><i class="icon-calendar3"></i>{{ date('Y-m-d', strtotime($berita->tanggal)) }}
                                        </li>
                                        <li><a href="#"><i class="icon-camera-retro"></i> {{ $berita->lokasi }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <p>{{ Str::limit(strip_tags($berita->konten), 150) }}</p>
                                    <a href="{{ route('berita.detail', ['path' => $berita->path]) }}"
                                        class="more-link">Read
                                        More</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">Tidak ada info berita yang tersedia.</p>
                        </div>
                    @endforelse
                </div><!-- #posts end -->
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="heading-block border-bottom-0 mb-5 center">
            <h3>Sekretariat Pengurus ASKLIN</h3>
        </div>

        <form method="GET" action="{{ url('/') }}" id="filterForm" class="row mb-4">
            <div class="col-md-4 mb-2">
                <select name="tingkatan" class="form-control selectpicker" data-live-search="true" data-style="btn-danger"
                    onchange="submitFilter()">
                    <option value="">Pilih Tingkatan</option>
                    @foreach ($tingkatan as $t)
                        <option value="{{ $t->id }}" {{ request('tingkatan') == $t->id ? 'selected' : '' }}>
                            {{ $t->nama_tingkatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <select name="provinsi" class="form-control selectpicker" data-live-search="true" data-style="btn-danger"
                    onchange="submitFilter()">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($provinsi as $id => $nama)
                        <option value="{{ $id }}" {{ request('provinsi') == $id ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <select name="kota" class="form-control selectpicker" data-live-search="true" data-style="btn-danger"
                    onchange="submitFilter()">
                    <option value="">Pilih Kota</option>
                    @foreach ($kota as $id => $nama)
                        <option value="{{ $id }}" {{ request('kota') == $id ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @foreach ($tingkatan as $t)
            @php
                $filtered = $struktur->where('id_tingkatan_pengurus', $t->id);
            @endphp
            @if ($filtered->count())
                <h4 class="mb-3">{{ $t->nama_tingkatan }}</h4>
                <div class="row mb-4">
                    @foreach ($filtered as $item)
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->nama_struktur }}</h5>
                                    <p class="card-text">
                                        {{ $item->alamat_sekretariat }}<br>
                                        {{ $item->kota ?? '-' }}, {{ $item->provinsi ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>

    <div class="container clearfix">
        <div id="oc-portfolio" class="owl-carousel portfolio-carousel carousel-widget" data-margin="1" data-pagi="false"
            data-autoplay="2000" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-xl="4">
            @foreach ($galery as $i)
                <div class="portfolio-item">
                    <div class="portfolio-image">
                        <a href="#">
                            <img src="{{ asset('/assets/images/galery') . '/' . $i->foto }}">
                        </a>
                        <div class="bg-overlay">
                            <div class="bg-overlay-content dark" data-hover-animate="fadeIn" data-hover-speed="350">
                                <a href="{{ asset('/assets/images/galery') . '/' . $i->foto }}"
                                    class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall"
                                    data-hover-animate-out="fadeInUpSmall" data-hover-speed="350"
                                    data-lightbox="image"><i class="icon-line-plus"></i></a>
                            </div>
                            <div class="bg-overlay-bg dark" data-hover-animate="fadeIn" data-hover-speed="350"></div>
                        </div>
                    </div>
                    <div class="portfolio-desc">
                        <p><a href="#">{{ $i->judul }}</a></p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/components/bs-select.css') }}" type="text/css" />
@endpush

@push('js')
    <script src="{{ asset('assets/frontend/js/components/bs-select.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/components/selectsplitter.js') }}"></script>
    <script>
        function submitFilter() {
            document.getElementById('filterForm').submit();
        }
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
    </script>
@endpush
