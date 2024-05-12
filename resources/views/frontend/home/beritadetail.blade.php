@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>{{ $berita->judul }}</h1>
            <span>{{ $berita->created_at->diffForHumans() }}</span>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="row">
                    <div class="entry event col-12">
                        <div class="grid-inner row align-items-center g-0 p-4">
                            <div class="col-md-4 mb-md-0">
                                <a href="#" class="entry-image">
                                    <img src="{{ asset('assets/images/berita').'/'.$berita->gambar }}" alt="{{ $berita->judul }}">
                                </a>
                            </div>
                            <div class="col-md-8 ps-md-4">
                                <div class="entry-content">
                                    {{ strip_tags($berita->konten) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection