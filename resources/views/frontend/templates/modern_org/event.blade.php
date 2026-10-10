@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Event</span>
            </div>
            <h1 class="mod-inner-title">Event ASKLIN</h1>
            <p class="mod-inner-sub">Jadwal Kongres, Seminar Ilmiah Kedokteran, Workshop Tata Kelola &amp; Pelatihan Akreditasi Klinik</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">
            
            <!-- Filters -->
            <div class="mod-card" style="padding: 20px; margin-bottom: 30px;">
                <form action="{{ route('event_asklin') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                    <div style="flex: 1; min-width: 240px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama agenda atau materi workshop..." style="width: 100%; padding: 10px 16px; border: 1px solid var(--mod-border); border-radius: 9999px; font-size: 13.5px;">
                    </div>
                    @if(isset($categories) && count($categories) > 0)
                        <div style="min-width: 180px;">
                            <select name="kategori" style="width: 100%; padding: 10px 16px; border: 1px solid var(--mod-border); border-radius: 9999px; font-size: 13.5px;" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <button type="submit" class="mod-btn mod-btn-primary" style="padding: 10px 22px;">
                            <i class="fas fa-search text-xs"></i>
                            <span>Cari Agenda</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Event Cards Grid -->
            <div class="mod-news-grid">
                @forelse($event as $ev)
                    <article class="mod-news-card">
                        <div class="mod-news-cover">
                            @if($ev->thumbnail)
                                <img src="{{ asset('storage/' . $ev->thumbnail) }}" alt="{{ $ev->judul }}">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $ev->judul }}" style="object-fit: contain; padding: 24px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                    <i class="fas fa-calendar-alt text-3xl"></i>
                                </div>
                            @endif
                            <span class="mod-news-tag">
                                {{ $ev->kategori->nama ?? 'Event' }}
                            </span>
                        </div>

                        <div class="mod-news-content">
                            <div class="mod-news-date">
                                <i class="far fa-calendar-alt text-red-600"></i>
                                <span>{{ \Carbon\Carbon::parse($ev->mulai)->translatedFormat('d M Y') }}</span>
                                @if($ev->lokasi)
                                    <span style="opacity: 0.5;">•</span>
                                    <span><i class="fas fa-map-marker-alt text-red-600"></i> {{ \Illuminate\Support\Str::limit($ev->lokasi, 16) }}</span>
                                @endif
                            </div>

                            <h3 class="mod-news-title">
                                <a href="{{ route('event_asklin.detail', $ev->path) }}">
                                    {{ $ev->judul }}
                                </a>
                            </h3>

                            <div class="mod-news-excerpt">
                                {{ $ev->clean_excerpt ?? \Illuminate\Support\Str::limit(strip_tags($ev->konten), 120) }}
                            </div>

                            <div class="mod-news-bottom">
                                <a href="{{ route('event_asklin.detail', $ev->path) }}" class="mod-news-link">
                                    <span>Detail Event</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <i class="fas fa-calendar-times text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--mod-text-main); font-size: 16px; font-weight: 700;">Belum Ada Event</h4>
                        <p style="color: var(--mod-text-muted); font-size: 13px;">Saat ini belum ada jadwal agenda pelatihan yang dibuka.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($event, 'links'))
                <div style="margin-top: 45px; display: flex; justify-content: center;">
                    {{ $event->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection
