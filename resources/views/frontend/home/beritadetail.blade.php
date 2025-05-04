@extends('layouts.frontend.layout')

@section('content')
    <div role="main" class="main">
        <section class="page-header bg-primary text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold display-5">{{ $berita->judul }}</h3>
                        <p class="mb-0 text-dark">
                            <i class="bi bi-calendar me-2"></i> {{ date('d F Y', strtotime($berita->tanggal)) }}
                            <i class="bi bi-geo-alt ms-3 me-2"></i> {{ $berita->lokasi }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-md-end bg-transparent">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('berita') }}">Berita</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <img src="{{ asset('assets/images/berita/thumbnails/' . $berita->thumb) }}"
                            class="img-fluid rounded-3 shadow" alt="{{ $berita->judul }}">
                    </div>

                    <article class="mb-5">
                        {!! $berita->konten !!}
                    </article>

                    @if ($images->count() > 0)
                        <div class="mb-5">
                            <h3 class="mb-4 border-bottom pb-2">Galeri Berita</h3>
                            <div class="row g-3">
                                @foreach ($images as $image)
                                    <div class="col-md-4 col-6">
                                        <a href="{{ asset('assets/images/berita/' . $image->gambar) }}"
                                            data-lightbox="gallery" data-title="{{ $berita->judul }}">
                                            <img src="{{ asset('assets/images/berita/' . $image->gambar) }}"
                                                class="img-thumbnail rounded" alt="Gallery Image">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Statistik Kunjungan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-people me-2"></i> Total Kunjungan</span>
                                    <span class="badge bg-primary rounded-pill">{{ $berita->total_visits }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-person me-2"></i> Kunjungan Unik</span>
                                    <span class="badge bg-success rounded-pill">{{ $berita->unique_visits }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</span>
                                    <span
                                        class="text-muted small">{{ $berita->visits->last()->created_at->diffForHumans() ?? '-' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-5">
                        <span class="me-3 fw-bold">Bagikan:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            class="btn btn-sm btn-outline-primary me-2" target="_blank">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}"
                            class="btn btn-sm btn-outline-info me-2" target="_blank">
                            <i class="bi bi-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}"
                            class="btn btn-sm btn-outline-success">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Detail Berita</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-calendar me-2"></i> Tanggal</span>
                                    <span class="fw-bold">{{ date('d F Y', strtotime($berita->tanggal)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-geo-alt me-2"></i> Lokasi</span>
                                    <span class="fw-bold">{{ $berita->lokasi }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-person me-2"></i> Penulis</span>
                                    <span class="fw-bold">Admin</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Latest News -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Berita Terbaru</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $latestNews = App\Models\Berita::where('status', '1')
                                    ->orderBy('id', 'DESC')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @foreach ($latestNews as $news)
                                <div class="mb-3 pb-3 border-bottom">
                                    <h6 class="mb-1">
                                        <a href="{{ route('berita.detail', ['path' => $news->path]) }}"
                                            class="text-decoration-none">{{ $news->judul }}</a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i> {{ date('d M Y', strtotime($news->tanggal)) }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .page-header {
            padding: 5rem 0;
            background: linear-gradient(135deg, #fff 0%, #c4c4c4 100%);
        }

        .article img {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
            border-radius: 0.25rem;
        }

        .gallery-img {
            transition: transform 0.3s;
        }

        .gallery-img:hover {
            transform: scale(1.03);
        }
    </style>
@endpush

@push('js')
    <!-- Lightbox2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endpush
