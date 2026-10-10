@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Berita</span>
            </div>
            <h1 class="org-page-title">Berita ASKLIN</h1>
            <p class="org-page-subtitle">Publikasi Resmi Kegiatan Organisasi, Regulasi Pelayanan Kesehatan &amp; Kabar Fasilitas Klinik</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <div class="org-news-grid">
                @forelse($data as $berita)
                    <article class="org-news-card">
                        <div class="org-news-img-wrap">
                            @if($berita->thumbnail)
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}" class="org-news-img">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $berita->judul }}" class="org-news-img" style="object-fit: contain; padding: 30px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-newspaper text-3xl"></i>
                                </div>
                            @endif
                            <span class="org-news-badge">
                                {{ $berita->kategori->nama ?? $berita->nama ?? 'ASKLIN' }}
                            </span>
                        </div>

                        <div class="org-news-body">
                            <div class="org-news-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}</span>
                                <span><i class="far fa-user"></i> {{ $berita->creator->name ?? 'Redaksi' }}</span>
                            </div>

                            <h3 class="org-news-title">
                                <a href="{{ route('berita.detail', $berita->path) }}">
                                    {{ $berita->judul }}
                                </a>
                            </h3>

                            <div class="org-news-snippet">
                                {{ \Illuminate\Support\Str::limit(strip_tags($berita->konten), 130) }}
                            </div>

                            <div class="org-news-footer">
                                <a href="{{ route('berita.detail', $berita->path) }}" class="org-news-more">
                                    <span>Baca Selengkapnya</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed var(--org-border);">
                        <i class="fas fa-newspaper text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--org-secondary); font-size: 16px; font-weight: 700;">Belum Ada Berita</h4>
                        <p style="color: var(--org-text-muted); font-size: 13px;">Belum ada artikel warta yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($data, 'links'))
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $data->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection

