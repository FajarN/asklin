@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Event</span>
            </div>
            <h1 class="org-page-title">Event ASKLIN</h1>
            <p class="org-page-subtitle">Jadwal Seminar Ilmiah, Lokakarya Akreditasi, Musyawarah Kerja &amp; Pelatihan Manajemen Klinik</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">
            
            <!-- Filters -->
            <div class="org-content-card" style="padding: 20px; margin-bottom: 30px;">
                <form action="{{ route('event_asklin') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                    <div style="flex: 1; min-width: 240px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari agenda atau kegiatan..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13px;">
                    </div>
                    @if(isset($categories) && count($categories) > 0)
                        <div style="min-width: 180px;">
                            <select name="kategori" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13px;" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <button type="submit" class="org-btn org-btn-primary" style="padding: 10px 20px;">
                            <i class="fas fa-search text-xs"></i>
                            <span>Cari Event</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Event Cards Grid -->
            <div class="org-news-grid">
                @forelse($event as $ev)
                    <article class="org-news-card">
                        <div class="org-news-img-wrap">
                            @if($ev->thumbnail)
                                <img src="{{ asset('storage/' . $ev->thumbnail) }}" alt="{{ $ev->judul }}" class="org-news-img">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $ev->judul }}" class="org-news-img" style="object-fit: contain; padding: 24px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-calendar-alt text-3xl"></i>
                                </div>
                            @endif
                            <span class="org-news-badge" style="background-color: var(--org-primary);">
                                {{ $ev->kategori->nama ?? 'Event ASKLIN' }}
                            </span>
                        </div>

                        <div class="org-news-body">
                            <div class="org-news-meta">
                                <span><i class="far fa-calendar-alt text-red-600"></i> {{ \Carbon\Carbon::parse($ev->mulai)->translatedFormat('d M Y') }}</span>
                                @if($ev->lokasi)
                                    <span><i class="fas fa-map-marker-alt text-red-600"></i> {{ \Illuminate\Support\Str::limit($ev->lokasi, 16) }}</span>
                                @endif
                            </div>

                            <h3 class="org-news-title">
                                <a href="{{ route('event_asklin.detail', $ev->path) }}">
                                    {{ $ev->judul }}
                                </a>
                            </h3>

                            <div class="org-news-snippet">
                                {{ $ev->clean_excerpt ?? \Illuminate\Support\Str::limit(strip_tags($ev->konten), 120) }}
                            </div>

                            <div class="org-news-footer">
                                <a href="{{ route('event_asklin.detail', $ev->path) }}" class="org-news-more">
                                    <span>Lihat Informasi</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed var(--org-border);">
                        <i class="fas fa-calendar-times text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--org-secondary); font-size: 16px; font-weight: 700;">Belum Ada Agenda Terjadwal</h4>
                        <p style="color: var(--org-text-muted); font-size: 13px;">Saat ini belum ada kegiatan yang sedang berlangsung atau terjadwal.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($event, 'links'))
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $event->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection
