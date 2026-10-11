@extends('frontend.templates.modern_org.layouts.app')

@push('meta')
    <!-- SEO Optimization -->
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="Konas ASKLIN, Kongres Nasional ASKLIN, {{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }}, {{ $selectedPeriode->periode ?? '2023-2028' }}, Asosiasi Klinik Indonesia, Wisata Medis, Pendaftaran Konas, Simposium Medis">
    <meta name="author" content="Pengurus Pusat ASKLIN">
    <link rel="canonical" href="{{ route('konas') }}{{ request()->filled('periode') ? '?periode=' . request('periode') : '' }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="event">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="ASKLIN - Asosiasi Klinik Indonesia">
    <meta property="og:image" content="{{ asset('assets/images/logo_asklin.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ asset('assets/images/logo_asklin.png') }}">

    <!-- JSON-LD Structured Data Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "{{ $selectedPeriode->nama ?? 'Kongres Nasional ASKLIN' }}",
      "description": "{{ $selectedPeriode->tema ?? 'Kongres Nasional 5 Tahunan Asosiasi Klinik Indonesia' }}",
      "startDate": "{{ $selectedPeriode && $selectedPeriode->tanggal_mulai ? $selectedPeriode->tanggal_mulai->format('Y-m-d') : '2023-06-14' }}",
      "endDate": "{{ $selectedPeriode && $selectedPeriode->tanggal_selesai ? $selectedPeriode->tanggal_selesai->format('Y-m-d') : '2023-06-17' }}",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "location": {
        "@type": "Place",
        "name": "{{ $selectedPeriode->tempat ?? 'Lut Tawar Grand Ballroom, Takengon' }}",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Takengon, Aceh Tengah",
          "addressRegion": "Aceh",
          "addressCountry": "ID"
        }
      },
      "organizer": {
        "@type": "Organization",
        "name": "Asosiasi Klinik Indonesia (ASKLIN)",
        "url": "{{ url('/') }}"
      },
      "offers": {
        "@type": "Offer",
        "price": "{{ (int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000') }}",
        "priceCurrency": "IDR",
        "availability": "{{ $isPeriodeActive ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut' }}",
        "url": "{{ route('konas') }}"
      }
    }
    </script>
@endpush

@push('css')
<style>
    /* Konas Modern Org - Sleek Grey Accents */
    .mod-konas-hero {
        padding: 130px 0 50px;
        background: radial-gradient(circle at 85% 15%, rgba(71, 85, 105, 0.06) 0%, transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(100, 116, 139, 0.05) 0%, transparent 45%),
                    var(--mod-bg);
        border-bottom: 1px solid var(--mod-border);
        position: relative;
        overflow: hidden;
    }
    html.dark .mod-konas-hero,
    body.dark .mod-konas-hero {
        background: radial-gradient(circle at 85% 15%, rgba(51, 65, 85, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 15% 85%, rgba(30, 41, 59, 0.3) 0%, transparent 50%),
                    #0f172a;
    }
    .mod-konas-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(148, 163, 184, 0.18) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.6;
        pointer-events: none;
    }
    html.dark .mod-konas-hero::before,
    body.dark .mod-konas-hero::before {
        background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
    }

    .mod-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .mod-badge-pill i {
        color: #64748b;
    }
    html.dark .mod-badge-pill,
    body.dark .mod-badge-pill {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: #cbd5e1;
    }
    html.dark .mod-badge-pill i,
    body.dark .mod-badge-pill i {
        color: #94a3b8;
    }

    .mod-hero-metric {
        background: var(--mod-surface);
        border: 1px solid var(--mod-border);
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: var(--mod-shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .mod-hero-metric:hover {
        transform: translateY(-2px);
        box-shadow: var(--mod-shadow-md);
    }
    .mod-hero-metric-val {
        font-size: 15px;
        font-weight: 800;
        color: var(--mod-text-heading);
    }

    /* Sticky Navigation Jump Links */
    .mod-sticky-jumpbar {
        background: var(--mod-header-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--mod-border);
        position: sticky;
        top: 0;
        z-index: 40;
        box-shadow: var(--mod-shadow-sm);
    }
    .mod-pill-nav {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 9999px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--mod-text-muted) !important;
        background: var(--mod-bg-alt);
        border: 1px solid var(--mod-border);
        text-decoration: none !important;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .mod-pill-nav i {
        color: var(--mod-text-muted) !important;
    }
    .mod-pill-nav:hover {
        color: var(--mod-text-heading) !important;
        background: var(--mod-surface);
        border-color: #94a3b8;
    }
    .mod-pill-nav:hover i {
        color: var(--mod-text-heading) !important;
    }
    .mod-pill-active {
        color: #ffffff !important;
        background: #334155 !important;
        border-color: #1e293b !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
    }
    .mod-pill-active i {
        color: #ffffff !important;
    }
    .mod-pill-active:hover {
        background: #1e293b !important;
        color: #ffffff !important;
    }

    /* Section Cards */
    .mod-section-card {
        background: var(--mod-surface);
        border: 1px solid var(--mod-border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: var(--mod-shadow-sm);
        margin-bottom: 40px;
        scroll-margin-top: 80px;
    }
    .mod-subcard {
        background: var(--mod-bg-alt);
        border: 1px solid var(--mod-border);
        border-radius: 14px;
        padding: 20px;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .mod-subcard:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
    }

    /* Primary Grey Button (Menggantikan Warna Hijau) */
    .mod-btn-grey,
    .mod-btn-teal {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 26px;
        border-radius: 12px;
        background: #334155 !important;
        color: #ffffff !important;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid #1e293b;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        transition: all 0.2s ease;
    }
    .mod-btn-grey *,
    .mod-btn-teal * {
        color: #ffffff !important;
    }
    .mod-btn-grey:hover,
    .mod-btn-teal:hover {
        background: #1e293b !important;
        color: #ffffff !important;
        border-color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
    }
    html.dark .mod-btn-grey,
    html.dark .mod-btn-teal,
    body.dark .mod-btn-grey,
    body.dark .mod-btn-teal {
        background: #475569 !important;
        border-color: #334155;
    }
    html.dark .mod-btn-grey:hover,
    html.dark .mod-btn-teal:hover,
    body.dark .mod-btn-grey:hover,
    body.dark .mod-btn-teal:hover {
        background: #334155 !important;
    }

    /* Secondary / Outline Button */
    .mod-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 12px;
        background: var(--mod-surface) !important;
        color: var(--mod-text-heading) !important;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none !important;
        border: 1px solid var(--mod-border);
        box-shadow: var(--mod-shadow-sm);
        transition: all 0.2s ease;
    }
    .mod-btn-outline i,
    .mod-btn-outline span {
        color: var(--mod-text-heading) !important;
    }
    .mod-btn-outline:hover {
        background: var(--mod-bg-alt) !important;
        color: var(--mod-text-heading) !important;
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    /* Form Inputs */
    .mod-form-input {
        width: 100%;
        padding: 11px 14px;
        background: var(--mod-surface);
        border: 1px solid var(--mod-border);
        border-radius: 10px;
        color: var(--mod-text-heading);
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .mod-form-input:focus {
        border-color: #475569;
        box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.15);
    }
    .mod-form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--mod-text-heading);
        margin-bottom: 6px;
    }
    .mod-form-label span.req {
        color: #ef4444;
    }
</style>
@endpush

@section('content')

    <!-- Hero Section (Light Mode Default, Aligned with 1280px Container) -->
    <section class="mod-konas-hero">
        <div class="modern-org-container" style="position: relative; z-index: 10;">
            
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px;">
                <div class="mod-badge-pill">
                    <i class="fas fa-landmark"></i> Kongres Nasional 5 Tahunan &amp; Pelantikan Pengurus Pusat
                </div>

                <!-- Dropdown Riwayat Periode KONAS (Ketentuan 5) -->
                <div style="background: var(--mod-surface); padding: 8px 14px; border-radius: 12px; border: 1px solid var(--mod-border); display: flex; align-items: center; gap: 10px; box-shadow: var(--mod-shadow-sm);">
                    <label for="konas_periode_select" style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; margin: 0; white-space: nowrap;">
                        <i class="fas fa-history mr-1"></i> Periode KONAS:
                    </label>
                    <select id="konas_periode_select" 
                            style="background: var(--mod-bg-alt); color: var(--mod-text-heading); border: 1px solid var(--mod-border); border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer; outline: none;"
                            onchange="window.location.href = '{{ route('konas') }}?periode=' + this.value">
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->periode }}" {{ $selectedPeriode && $selectedPeriode->id == $p->id ? 'selected' : '' }}>
                                KONAS Periode {{ $p->periode }} {{ $p->status == '1' ? '★ Aktif' : '• Arsip' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 24px; align-items: center;">
                <div style="max-width: 900px;">
                    <h1 style="font-size: clamp(26px, 4vw, 42px); font-weight: 900; line-height: 1.22; color: var(--mod-text-heading); margin-bottom: 14px; letter-spacing: -0.5px;">
                        {{ $selectedPeriode->nama ?? 'Kongres Nasional (KONAS) ASKLIN' }}
                    </h1>

                    <p style="font-size: clamp(14px, 1.8vw, 17px); font-weight: 500; color: var(--mod-text-muted); line-height: 1.6; margin-bottom: 26px;">
                        &ldquo;{{ $selectedPeriode->tema ?? 'Peran ASKLIN dalam Era Transformasi Industri Wisata Medis' }}&rdquo;
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 30px;">
                        @if($isPeriodeActive)
                            <a href="#pendaftaran" class="mod-btn-grey">
                                <i class="fas fa-user-plus mr-1"></i> Daftar Delegasi Sekarang
                            </a>
                        @endif
                        <a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" target="_blank" class="mod-btn-outline">
                            <i class="fas fa-file-download mr-1"></i> Unduh TOR Kongres
                        </a>
                        <a href="#agenda" class="mod-btn-outline">
                            <i class="fas fa-calendar-alt mr-1"></i> Jadwal Acara
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric Badges Strip -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-top: 10px;">
                <div class="mod-hero-metric">
                    <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-calendar-check mr-1"></i> Tanggal Pelaksanaan
                    </div>
                    <div class="mod-hero-metric-val">
                        {{ $selectedPeriode && $selectedPeriode->tanggal_mulai ? $selectedPeriode->tanggal_mulai->format('d M') : '14' }} - {{ $selectedPeriode && $selectedPeriode->tanggal_selesai ? $selectedPeriode->tanggal_selesai->format('d M Y') : '17 Juni 2023' }}
                    </div>
                </div>

                <div class="mod-hero-metric">
                    <div style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-map-marker-alt mr-1"></i> Lokasi Kongres
                    </div>
                    <div class="mod-hero-metric-val" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $selectedPeriode->tempat ?? 'Takengon, Aceh Tengah' }}">
                        {{ $selectedPeriode->tempat ?? 'Takengon, Aceh Tengah' }}
                    </div>
                </div>

                <div class="mod-hero-metric">
                    <div style="font-size: 11px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-users mr-1"></i> Target Partisipasi
                    </div>
                    <div class="mod-hero-metric-val">
                        Offline &plusmn; 800 Pimpinan Klinik
                    </div>
                </div>

                <div class="mod-hero-metric">
                    <div style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-ticket-alt mr-1"></i> Biaya Registrasi
                    </div>
                    <div class="mod-hero-metric-val">
                        Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Sticky Single Page Jump Navigation -->
    <div class="mod-sticky-jumpbar">
        <div class="modern-org-container">
            <div style="display: flex; gap: 10px; overflow-x: auto; padding: 12px 0; scrollbar-width: none;">
                <a href="#profil" class="mod-pill-nav"><i class="fas fa-info-circle text-xs"></i> Profil Kongres</a>
                <a href="#agenda" class="mod-pill-nav"><i class="fas fa-calendar-alt text-xs"></i> Simposium &amp; Sidang</a>
                <a href="#akomodasi" class="mod-pill-nav"><i class="fas fa-hotel text-xs"></i> Hotel &amp; Tiket</a>
                <a href="#pendaftaran" class="mod-pill-nav mod-pill-active"><i class="fas fa-user-plus text-xs"></i> Pendaftaran Delegasi</a>
                <a href="#sponsor" class="mod-pill-nav"><i class="fas fa-handshake text-xs"></i> Sponsor Resmi</a>
                <a href="#kontak" class="mod-pill-nav"><i class="fas fa-phone-alt text-xs"></i> Kontak Panitia</a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div style="background: var(--mod-bg); color: var(--mod-text-body); padding: 40px 0 80px;">
        <div class="modern-org-container">

            <!-- Flash Alert -->
            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #047857; padding: 16px 20px; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #b91c1c; padding: 16px 20px; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(!$isPeriodeActive)
                <!-- Notice Status Periode Nonaktif -->
                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.35); border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 12px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 14px;">
                    <i class="fas fa-clock text-amber-500 text-xl mt-1"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #b45309; margin: 0 0 4px;">Pemberitahuan Status Periode KONAS</h4>
                        <p style="font-size: 13px; color: var(--mod-text-body); margin: 0; line-height: 1.5;">
                            Informasi KONAS saat ini berstatus arsip / pendaftaran publik belum dibuka. Formulir pendaftaran delegasi akan otomatis terbuka saat kepanitiaan mengaktifkan periode kongres berjalan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 1. SECTION PROFIL KONGRES -->
            <section id="profil" class="mod-section-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(51, 65, 85, 0.1); color: #334155; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div>
                        <h2>Tentang Kongres Nasional (KONAS) ASKLIN</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Musyawarah Akbar Tertinggi Organisasi Asosiasi Klinik Indonesia Setiap 5 Tahun</p>
                    </div>
                </div>

                <div style="font-size: 14px; line-height: 1.8; color: var(--mod-text-body); text-align: justify; margin-bottom: 24px;">
                    <p style="margin-bottom: 14px;">
                        <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong> didirikan di Jakarta pada 01 Agustus 2011 sebagai wadah berhimpun para pengusaha dan pimpinan klinik di seluruh Nusantara demi kemajuan dan standardisasi mutu pelayanan kesehatan Indonesia.
                    </p>
                    <p style="margin-bottom: 14px;">
                        <strong>KONAS ASKLIN</strong> diselenggarakan secara berkala setiap 5 (lima) tahun sekali bersamaan dengan momentum <strong>Pelantikan Dewan Pengurus Pusat (DPP) ASKLIN</strong>. Forum ini menjadi sarana strategis pimpinan cabang dan daerah dalam menentukan AD/ART, arah kebijakan advokasi klinik, serta pemilihan pucuk kepemimpinan asosiasi.
                    </p>
                </div>

                <!-- Material Kit Unduhan -->
                <div class="mod-subcard">
                    <h4 style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px;">
                        <i class="fas fa-download mr-1"></i> Dokumen &amp; Media Publikasi Resmi:
                    </h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" target="_blank" class="mod-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-file-pdf mr-1" style="color: #ef4444 !important;"></i> Terms of Reference (TOR)
                        </a>
                        <a href="{{ asset('assets/frontend/SPONSORSHIP_BOOK.pdf') }}" target="_blank" class="mod-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-book mr-1" style="color: #f59e0b !important;"></i> Sponsorship Book
                        </a>
                        <a href="https://drive.google.com/drive/folders/1_LxDefwLQmD-CVuZudMsbtU7OhTafbio" target="_blank" class="mod-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-image mr-1" style="color: #10b981 !important;"></i> Virtual Background
                        </a>
                        <a href="https://twb.nz/konas-asklin" target="_blank" class="mod-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-camera mr-1" style="color: #3b82f6 !important;"></i> Twibbon Peserta
                        </a>
                    </div>
                </div>
            </section>

            <!-- 2. SECTION AGENDA & SIMPOSIUM -->
            <section id="agenda" class="mod-section-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(2, 132, 199, 0.15); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h2>Agenda Kongres &amp; 5 Simposium Nasional</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Sidang Komisi AD-ART, Simposium Medis Lintas Profesi, dan Pameran Expo</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    <div class="mod-subcard">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #334155; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">01</div>
                        <h3 style="font-size: 15px; margin: 0 0 6px;">Sidang Pleno &amp; AD/ART</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Musyawarah komisi organisasi, penetapan regulasi keanggotaan klinik, dan pemilihan Ketua Umum ASKLIN.</p>
                    </div>

                    <div class="mod-subcard">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #475569; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">02</div>
                        <h3 style="font-size: 15px; margin: 0 0 6px;">5 Simposium Ilmiah Nasional</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Materi multidisiplin dari Kementerian Kesehatan, BPJS Kesehatan, kedokteran klinis, dan transformasi wisata medis.</p>
                    </div>

                    <div class="mod-subcard">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #64748b; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">03</div>
                        <h3 style="font-size: 15px; margin: 0 0 6px;">Pelantikan Dewan Pengurus Pusat</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Pengukuhan kepengurusan baru ASKLIN masa bakti 5 tahun, pemberian penghargaan klinik teladan, dan gala dinner.</p>
                    </div>

                    <div class="mod-subcard">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #334155; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">04</div>
                        <h3 style="font-size: 15px; margin: 0 0 6px;">City Tour &amp; Wisata Medis</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Kunjungan keindahan destinasi wisata dan keanekaragaman kuliner khas wilayah tuan rumah penyelenggara.</p>
                    </div>
                </div>
            </section>

            <!-- 3. SECTION AKOMODASI HOTEL -->
            <section id="akomodasi" class="mod-section-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div>
                        <h2>Akomodasi Hotel &amp; Layanan Tiket Penerbangan</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Hotel Rekanan Resmi Kongres dengan Tarif Khusus Delegasi ASKLIN</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 24px;">
                    @foreach($hotels as $h)
                        <div class="mod-subcard" style="display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #b45309; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">Hotel Mitra KONAS</span>
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">{{ $h->hotel }}</h4>
                                <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.5; margin: 0 0 14px;">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $h->alamat }}
                                </p>
                            </div>
                            <div style="border-top: 1px solid var(--mod-border); padding-top: 12px;">
                                <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20KONAS,%20saya%20ingin%20info%20reservasi%20hotel%20{{ urlencode($h->hotel) }}" target="_blank" class="mod-btn-outline w-full" style="font-size: 12px; text-align: center; justify-content: center; width: 100%;">
                                    <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Konfirmasi Booking Panitia
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 14px; padding: 18px 22px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px;">Pemesanan Tiket Pesawat &amp; Penjemputan Delegasi</h4>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0;">Tim akomodasi ASKLIN siap membantu penjadwalan dan armada penjemputan dari bandara ke venue.</p>
                    </div>
                    <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Saya%20ingin%20tau%20info%20mengenai%20tiket%20penerbangan%20KONAS" target="_blank" class="mod-btn-grey" style="font-size: 12.5px;">
                        <i class="fab fa-whatsapp mr-1"></i> Konsultasi Penerbangan
                    </a>
                </div>
            </section>

            <!-- 4. SECTION PENDAFTARAN DELEGASI INLINE -->
            <section id="pendaftaran" class="mod-section-card" style="border: 1px solid var(--mod-border); box-shadow: var(--mod-shadow-md);">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(51, 65, 85, 0.1); color: #334155; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h2>Formulir Pendaftaran Delegasi KONAS</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Registrasi Online Terpadu — Masuk Otomatis ke Periode {{ $selectedPeriode->periode ?? '2023-2028' }}</p>
                    </div>
                </div>

                @if($isPeriodeActive)
                    <form action="{{ route('konas.storepeserta') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div style="background: var(--mod-bg-alt); border: 1px solid var(--mod-border); padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; color: var(--mod-text-heading);">
                            <i class="fas fa-check-circle mr-1" style="color: #475569;"></i> Pendaftaran delegasi terbuka untuk <strong>{{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }} (Periode {{ $selectedPeriode->periode }})</strong>. Biaya registrasi: <strong>Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}</strong>.
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                            <div>
                                <label class="mod-form-label">Nama Peserta (Tanpa Gelar) <span class="req">*</span></label>
                                <input type="text" name="nama_peserta" required placeholder="Ahmad Yani" class="mod-form-input">
                            </div>

                            <div>
                                <label class="mod-form-label">Nama Sertifikat (Dengan Gelar Lengkap) <span class="req">*</span></label>
                                <input type="text" name="nama_sertifikat" required placeholder="dr. Ahmad Yani, M.Kes" class="mod-form-input">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 18px;">
                            <div>
                                <label class="mod-form-label">NIK KTP <span class="req">*</span></label>
                                <input type="number" name="nik" required placeholder="16 Digit NIK" class="mod-form-input">
                            </div>

                            <div>
                                <label class="mod-form-label">Tanggal Lahir <span class="req">*</span></label>
                                <input type="date" name="tgl_lahir" required class="mod-form-input">
                            </div>

                            <div>
                                <label class="mod-form-label">No. WhatsApp <span class="req">*</span></label>
                                <input type="tel" name="no_hp" required placeholder="08xxxxxxxxxx" class="mod-form-input">
                            </div>

                            <div>
                                <label class="mod-form-label">Email Aktif <span class="req">*</span></label>
                                <input type="email" name="email" required placeholder="email@klinik.com" class="mod-form-input">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                            <div>
                                <label class="mod-form-label">Nama Klinik / Instansi <span class="req">*</span></label>
                                <input type="text" name="nama_klinik" required placeholder="Klinik Pratama..." class="mod-form-input">
                            </div>

                            <div>
                                <label class="mod-form-label">Status Delegasi <span class="req">*</span></label>
                                <select name="status" required class="mod-form-input">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Peserta">Peserta</option>
                                    <option value="Pendamping">Pendamping</option>
                                    <option value="Peninjau">Peninjau</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 18px;">
                            <label class="mod-form-label">Alamat Lengkap Klinik <span class="req">*</span></label>
                            <textarea name="alamat_klinik" rows="2" required placeholder="Alamat klinik..." class="mod-form-input"></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 18px;">
                            <div>
                                <label class="mod-form-label">Provinsi <span class="req">*</span></label>
                                <select name="id_provinsi" id="mod_id_provinsi" required onchange="loadCabangKonasMod(this.value)" class="mod-form-input">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsi as $i)
                                        <option value="{{ $i->code }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mod-form-label">Cabang (Kota/Kab) <span class="req">*</span></label>
                                <select name="id_kota" id="mod_id_kota" required class="mod-form-input">
                                    <option value="">-- Pilih Provinsi Dahulu --</option>
                                </select>
                            </div>

                            <div>
                                <label class="mod-form-label">Komisi Sidang <span class="req">*</span></label>
                                <select name="komisi" required class="mod-form-input">
                                    <option value="">-- Pilih Komisi --</option>
                                    <option value="Komisi AD-ART">Komisi AD-ART</option>
                                    <option value="Komisi ORTALA">Komisi ORTALA</option>
                                    <option value="Komisi Pembiayaan dan Kredensialing">Komisi Pembiayaan dan Kredensialing</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 24px;">
                            <div>
                                <label class="mod-form-label">Upload Foto Profil (JPG/PNG, Max 5MB)</label>
                                <input type="file" name="foto" accept="image/*" class="mod-form-input" style="padding: 8px 12px;">
                            </div>

                            <div>
                                <label class="mod-form-label">Upload Surat Mandat (PDF/JPG/PNG, Max 5MB)</label>
                                <input type="file" name="surat_mandat" accept=".pdf,image/*" class="mod-form-input" style="padding: 8px 12px;">
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--mod-border); padding-top: 20px;">
                            <button type="submit" class="mod-btn-grey" style="padding: 14px 32px; font-size: 14px; font-weight: 800;">
                                <i class="fas fa-paper-plane mr-2"></i> Konfirmasi &amp; Bayar Registrasi
                            </button>
                        </div>
                    </form>
                @else
                    <div style="text-align: center; padding: 40px 20px; background: var(--mod-bg-alt); border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--mod-surface); color: var(--mod-text-muted); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">Pendaftaran Periode Ini Sedang Dinonaktifkan</h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); max-width: 480px; margin: 0 auto; line-height: 1.6;">
                            Formulir registrasi hanya dapat diakses saat periode KONAS dinyatakan aktif oleh Dewan Pengurus Pusat ASKLIN.
                        </p>
                    </div>
                @endif
            </section>

            <!-- 5. SECTION MITRA & SPONSOR RESMI -->
            <section id="sponsor" class="mod-section-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(100, 116, 139, 0.15); color: #475569; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h2>Partner &amp; Sponsor Resmi KONAS</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Mitra Strategis Pengembangan Pelayanan Kesehatan Primer Indonesia</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 14px; align-items: center;">
                    @foreach($partner as $p)
                        <a href="{{ $p->link ?: 'javascript:void(0)' }}" target="{{ $p->link ? '_blank' : '_self' }}" 
                           style="background: #ffffff; border: 1px solid var(--mod-border); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center; min-height: 70px; transition: transform 0.2s, box-shadow 0.2s;"
                           title="{{ $p->nama }}" onmouseover="this.style.transform='scale(1.04)'; this.style.boxShadow='var(--mod-shadow-sm)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                            <img src="{{ asset('images/partner/' . $p->photo) }}" alt="{{ $p->nama }}" style="max-height: 40px; max-width: 100%; object-fit: contain;" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">
                        </a>
                    @endforeach
                </div>
            </section>

            <!-- 6. SECTION KONTAK & BANTUAN -->
            <section id="kontak" class="mod-section-card" style="margin-bottom: 0;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; align-items: center;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Hotline Panitia</div>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 8px;">Sekretariat Pelaksana KONAS ASKLIN</h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">
                            Butuh panduan mandat daerah, konfirmasi pembayaran, atau info pameran booth sponsor? Hubungi tim panitia sekarang.
                        </p>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: lg-end;">
                        <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20KONAS%20ASKLIN" target="_blank" class="mod-btn-grey" style="padding: 12px 24px;">
                            <i class="fab fa-whatsapp text-lg mr-2" style="color: #25d366 !important;"></i> WhatsApp Panitia (+62 812-4599-9923)
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>

@endsection

@push('js')
<script>
    function loadCabangKonasMod(provCode) {
        let target = $('#mod_id_kota');
        if (!provCode) {
            target.html('<option value="">-- Pilih Provinsi Dahulu --</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getKota') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_provinsi: provCode
            },
            beforeSend: function() {
                target.html('<option value="">Memuat cabang...</option>');
            },
            success: function(res) {
                let html = '<option value="">-- Pilih Cabang --</option>';
                $.each(res, function(k, v) {
                    html += `<option value="${v.code}">${v.name}</option>`;
                });
                target.html(html);
            },
            error: function() {
                target.html('<option value="">Gagal memuat cabang</option>');
            }
        });
    }
</script>
@endpush
