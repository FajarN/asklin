@extends('layouts.frontend.layout')
@section('content')
    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-8 align-self-center p-static order-2 order-md-1">
                    <h1 class="text-dark text-uppercase"><strong>Berita</strong></h1>
                </div>
                <div class="col-md-4 align-self-center order-1 order-md-2">
                    <ul class="breadcrumb d-block text-md-end">
                        <li><a href="#">Home</a></li>
                        <li class="active">Berita</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row">
            <div class="col">
                <div class="blog-posts">

                    <div class="row">
                        @forelse ($data as $berita)
                            <div class="col-md-4">
                                <article class="post post-medium border-0 pb-0 mb-5">
                                    <div class="post-image">
                                        <a href="{{ route('berita.detail', ['path' => $berita->path]) }}">
                                            <img src="{{ asset('assets/images/berita/thumbnails/' . $berita->thumb) }}"
                                                class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0"
                                                alt="{{ $berita->judul }}" />
                                        </a>
                                    </div>
                                    <div class="post-content">

                                        <h3 class="font-weight-semibold text-5 line-height-6 mt-3 mb-2"><a
                                                href="">{{ $berita->judul }}</a></h3>
                                        <p>{{ Str::limit(strip_tags($berita->konten), 150) }}</p>

                                        <div class="post-meta">
                                            <span><i class="far fa-user"></i> By Admin</span>
                                            <span><i
                                                    class="far fa-calendar"></i>{{ date('Y-m-d', strtotime($berita->tanggal)) }}</span>
                                            <span><i class="fas fa-map-marked-alt"></i>Lokasi : {{ $berita->lokasi }}</span>
                                            <span class="d-block mt-2"><a
                                                    href="{{ route('berita.detail', ['path' => $berita->path]) }}"
                                                    class="btn btn-danger">Selengkapnya</a></span>
                                        </div>
                                    </div>

                                </article>
                            </div>

                        @empty
                            <div class="col-12">
                                <p class="text-center">Tidak ada info berita yang tersedia.</p>
                            </div>
                        @endforelse


                    </div>

                    <div class="row">
                        <div class="col">
                            <ul class="pagination float-end">
                                {!! $data->links('pagination::bootstrap-5') !!}
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
