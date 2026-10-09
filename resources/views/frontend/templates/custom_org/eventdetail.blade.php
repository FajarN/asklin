@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('event_asklin') }}">Agenda &amp; Event</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Detail Agenda</span>
            </div>
            <h1 class="org-page-title" style="font-size: 28px; max-width: 900px;">{{ $event->judul }}</h1>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 12px; font-size: 13px; color: #cbd5e1; flex-wrap: wrap;">
                <span><i class="far fa-calendar-alt mr-1"></i> Mulai: {{ \Carbon\Carbon::parse($event->mulai)->translatedFormat('d F Y') }}</span>
                @if($event->selesai)
                    <span><i class="far fa-calendar-check mr-1"></i> Selesai: {{ \Carbon\Carbon::parse($event->selesai)->translatedFormat('d F Y') }}</span>
                @endif
                <span><i class="fas fa-tag mr-1"></i> {{ $event->kategori->nama ?? 'Umum' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">
            
            <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 40px; align-items: start;">
                
                <!-- Main Event Content -->
                <article class="org-content-card">
                    @if($event->thumbnail)
                        <div style="border-radius: 12px; overflow: hidden; margin-bottom: 24px; max-height: 440px;">
                            <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->judul }}" style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    @endif

                    <div style="font-size: 15px; line-height: 1.85; color: var(--org-text-main); text-align: justify;">
                        {!! $event->processed_content ?? $event->konten !!}
                    </div>

                    <div style="margin-top: 36px; padding-top: 20px; border-top: 1px solid var(--org-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <a href="{{ route('event_asklin') }}" class="org-btn org-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Jadwal Agenda</span>
                        </a>
                        <a href="{{ route('pendaftaran_event') }}" class="org-btn org-btn-primary" style="font-size: 13px; padding: 10px 22px;">
                            <i class="fas fa-file-signature text-xs"></i>
                            <span>Formulir Pendaftaran Event</span>
                        </a>
                    </div>
                </article>

                <!-- Sidebar Right: Event Info Card -->
                <aside>
                    <div class="org-content-card" style="padding: 24px; margin-bottom: 24px;">
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--org-secondary); margin: 0 0 16px 0; border-bottom: 1px solid var(--org-border-light); padding-bottom: 10px;">
                            Informasi Pelaksanaan
                        </h4>
                        
                        <div style="space-y: 14px; font-size: 13px; color: var(--org-text-main);">
                            <div style="margin-bottom: 12px;">
                                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--org-text-muted);">Tanggal Acara</div>
                                <div style="font-weight: 700; color: var(--org-primary-dark); margin-top: 2px;">
                                    {{ \Carbon\Carbon::parse($event->mulai)->translatedFormat('d F Y') }}
                                </div>
                            </div>

                            @if($event->lokasi)
                                <div style="margin-bottom: 12px;">
                                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--org-text-muted);">Lokasi / Venue</div>
                                    <div style="font-weight: 600; color: var(--org-secondary); margin-top: 2px;">
                                        {{ $event->lokasi }}
                                    </div>
                                </div>
                            @endif

                            <div style="margin-bottom: 18px;">
                                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--org-text-muted);">Penyelenggara</div>
                                <div style="font-weight: 600; color: var(--org-secondary); margin-top: 2px;">
                                    Pengurus Pusat ASKLIN
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pendaftaran_event') }}" class="org-btn org-btn-primary" style="width: 100%; font-size: 13px; text-align: center;">
                            <i class="fas fa-edit mr-1"></i> Daftar Sekarang
                        </a>
                    </div>

                    <!-- Related Events -->
                    @if(isset($relatedEvents) && count($relatedEvents) > 0)
                        <div class="org-content-card" style="padding: 24px;">
                            <h4 style="font-size: 15px; font-weight: 800; color: var(--org-secondary); margin: 0 0 14px 0;">Agenda Terkait</h4>
                            <div style="space-y: 12px;">
                                @foreach($relatedEvents as $rel)
                                    <div style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid var(--org-border-light);">
                                        <a href="{{ route('event_asklin.detail', $rel->path) }}" style="font-size: 13px; font-weight: 700; color: var(--org-secondary); line-height: 1.4; display: block;">
                                            {{ $rel->judul }}
                                        </a>
                                        <span style="font-size: 11px; color: var(--org-text-muted); display: block; margin-top: 4px;">
                                            <i class="far fa-calendar-alt text-red-600 mr-1"></i> {{ \Carbon\Carbon::parse($rel->mulai)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>

            </div>

        </div>
    </div>

@endsection
