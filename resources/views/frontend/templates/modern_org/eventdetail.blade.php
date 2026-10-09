@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('event_asklin') }}">Agenda &amp; Pelatihan</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Detail Agenda</span>
            </div>
            <h1 class="mod-inner-title" style="font-size: 30px; max-width: 900px; line-height: 1.3;">{{ $event->judul }}</h1>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 14px; font-size: 13px; color: #cbd5e1; flex-wrap: wrap;">
                <span><i class="far fa-calendar-alt text-red-400 mr-1.5"></i> Mulai: {{ \Carbon\Carbon::parse($event->mulai)->translatedFormat('d F Y') }}</span>
                @if($event->selesai)
                    <span><i class="far fa-calendar-check text-red-400 mr-1.5"></i> Selesai: {{ \Carbon\Carbon::parse($event->selesai)->translatedFormat('d F Y') }}</span>
                @endif
                <span><i class="fas fa-tag text-red-400 mr-1.5"></i> {{ $event->kategori->nama ?? 'Event ASKLIN' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">
            
            <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 40px; align-items: start;">
                
                <!-- Main Content -->
                <article class="mod-card">
                    @if($event->thumbnail)
                        <div style="border-radius: 16px; overflow: hidden; margin-bottom: 28px; max-height: 460px;">
                            <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->judul }}" style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    @endif

                    <div style="font-size: 15.5px; line-height: 1.9; color: var(--mod-text-main); text-align: justify;">
                        {!! $event->processed_content ?? $event->konten !!}
                    </div>

                    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--mod-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <a href="{{ route('event_asklin') }}" class="mod-btn mod-btn-glass" style="color: var(--mod-text-main) !important; border-color: var(--mod-border); background: #f8fafc; font-size: 12.5px; padding: 8px 18px;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Jadwal Agenda</span>
                        </a>
                        <a href="{{ route('pendaftaran_event') }}" class="mod-btn mod-btn-primary" style="font-size: 13px; padding: 10px 24px;">
                            <i class="fas fa-file-signature text-xs"></i>
                            <span>Formulir Registrasi Event</span>
                        </a>
                    </div>
                </article>

                <!-- Sidebar Info -->
                <aside>
                    <div class="mod-card" style="padding: 26px; margin-bottom: 24px;">
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 16px 0; border-bottom: 1px solid var(--mod-border); padding-bottom: 12px;">
                            Ringkasan Kegiatan
                        </h4>

                        <div style="space-y: 14px; font-size: 13px;">
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">Jadwal Pelaksanaan</span>
                                <div style="font-weight: 700; color: var(--mod-primary-dark); margin-top: 3px;">
                                    {{ \Carbon\Carbon::parse($event->mulai)->translatedFormat('d F Y') }}
                                </div>
                            </div>

                            @if($event->lokasi)
                                <div style="margin-bottom: 14px;">
                                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">Lokasi / Tempat</span>
                                    <div style="font-weight: 600; color: var(--mod-text-main); margin-top: 3px;">
                                        {{ $event->lokasi }}
                                    </div>
                                </div>
                            @endif

                            <div style="margin-bottom: 20px;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">Penyelenggara</span>
                                <div style="font-weight: 600; color: var(--mod-text-main); margin-top: 3px;">
                                    Pengurus Pusat ASKLIN
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pendaftaran_event') }}" class="mod-btn mod-btn-primary" style="width: 100%; font-size: 13px; padding: 12px;">
                            <i class="fas fa-edit mr-1"></i> Registrasi Delegasi
                        </a>
                    </div>

                    @if(isset($relatedEvents) && count($relatedEvents) > 0)
                        <div class="mod-card" style="padding: 24px;">
                            <h4 style="font-size: 15px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 14px 0;">Kegiatan Lainnya</h4>
                            <div>
                                @foreach($relatedEvents as $rel)
                                    <div style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                                        <a href="{{ route('event_asklin.detail', $rel->path) }}" style="font-size: 13px; font-weight: 700; color: var(--mod-text-main); line-height: 1.4; display: block;">
                                            {{ $rel->judul }}
                                        </a>
                                        <span style="font-size: 11.5px; color: var(--mod-text-muted); display: block; margin-top: 4px;">
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
