@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Berita</span>
            </div>
            <h1 class="mod-inner-title">Berita ASKLIN</h1>
            <p class="mod-inner-sub">Kanal Informasi Resmi Terkait Kebijakan Pelayanan, Kegiatan Organisasi &amp; Transformasi Fasilitas Klinik</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div class="mod-news-grid">
                @forelse($data as $berita)
                    <article class="mod-news-card">
                        <div class="mod-news-cover">
                            @if($berita->thumbnail)
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $berita->judul }}" style="object-fit: contain; padding: 30px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                    <i class="fas fa-newspaper text-3xl"></i>
                                </div>
                            @endif
                            <span class="mod-news-tag">
                                {{ $berita->kategori->nama ?? $berita->nama ?? 'ASKLIN News' }}
                            </span>
                        </div>

                        <div class="mod-news-content">
                            <div class="mod-news-date">
                                <i class="far fa-calendar-alt text-red-600"></i>
                                <span>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}</span>
                            </div>

                            <h3 class="mod-news-title">
                                <a href="{{ route('berita.detail', $berita->path) }}">
                                    {{ $berita->judul }}
                                </a>
                            </h3>

                            <div class="mod-news-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($berita->konten), 130) }}
                            </div>

                            <div class="mod-news-bottom">
                                <a href="{{ route('berita.detail', $berita->path) }}" class="mod-news-link">
                                    <span>Baca Artikel</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <i class="fas fa-newspaper text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--mod-text-main); font-size: 16px; font-weight: 700;">Belum Ada Berita</h4>
                        <p style="color: var(--mod-text-muted); font-size: 13px;">Belum ada artikel warta yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($data, 'links'))
                <div style="margin-top: 45px; display: flex; justify-content: center;">
                    {{ $data->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection

