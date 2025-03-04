@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Berita</h1>
            <span>Berita terbaru seputar dunia kesehatan</span>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">

                <div class="row">
                    @foreach($berita as $i)
						<div class="entry col-md-4 col-sm-6 col-12">
							<div class="grid-inner">
								<div class="entry-image">
									<a href="{{ asset('assets/images/berita').'/'.$i->gambar }}"  data-lightbox="image"><img src="{{ asset('assets/images/berita').'/'.$i->gambar }}" alt="{{ $i->judul }}"></a>
								</div>
								<div class="entry-title">
									<h2><a href="{{ route('berita.detail', $i->path) }}">{{ $i->judul }}</a></h2>
								</div>
								<div class="entry-meta">
									<ul>
										<li><i class="icon-calendar3"></i>{{ $i->created_at->diffForHumans() }}</li>
										<li><a href="{{ route('berita.detail', $i->path) }}"><i class="icon-comments"></i> 6</a></li>
										<li><a href="#"><i class="icon-camera-retro"></i></a></li>
									</ul>
								</div>
								<div class="entry-content">
									{{ $i->konten }}<br>
									<a href="{{ route('berita.detail', $i->path) }}"  class="btn btn-danger">Selengkapnya</a>
								</div>
							</div>
						</div>
                    @endforeach
                    {!! $berita->links() !!}
                </div>

            </div>
        </div>
    </section>

@endsection