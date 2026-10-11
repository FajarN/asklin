@extends('frontend.templates.modern_org.layouts.app')

@push('meta')
    <!-- SEO Optimization -->
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="Konas ASKLIN, Kongres Nasional ASKLIN, {{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }}, {{ $selectedPeriode->periode ?? '2023-2028' }}, Asosiasi Klinik Indonesia, Wisata Medis, Pendaftaran Konas, Simposium Medis">
    <meta name="author" content="Pengurus Pusat ASKLIN">
    <link rel="canonical" href="{{ route('konas') }}">

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
    .mod-pill-nav {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .mod-pill-nav:hover {
        color: #5eead4;
        background: rgba(13, 148, 136, 0.15);
        border-color: rgba(45, 212, 191, 0.3);
    }
    .mod-pill-active {
        color: #ffffff;
        background: linear-gradient(135deg, #0d9488, #059669);
        border-color: transparent;
        font-weight: 700;
        box-shadow: 0 2px 10px rgba(13, 148, 136, 0.3);
    }
</style>
@endpush

@section('content')

    <!-- Modern Dark Hero Section with Glassmorphism & Teal Accents -->
    <section class="mod-hero" style="min-height: auto; padding: 130px 0 60px; background: radial-gradient(circle at top right, rgba(13, 148, 136, 0.25), transparent 50%), radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.15), transparent 50%), #0f172a; position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px); background-size: 24px 24px; opacity: 0.5; pointer-events-none;"></div>

        <div class="mod-container" style="position: relative; z-index: 10;">
            
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(13, 148, 136, 0.2); border: 1px solid rgba(45, 212, 191, 0.35); padding: 6px 16px; border-radius: 9999px; font-size: 11px; font-weight: 700; color: #5eead4; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 18px; backdrop-filter: blur(8px);">
                <i class="fas fa-landmark"></i> Kongres Nasional 5 Tahunan &amp; Pelantikan Pengurus Pusat
            </div>

            <div style="display: grid; grid-template-columns: 1fr; lg:grid-template-columns: 1.2fr 0.8fr; gap: 40px; align-items: center;">
                <div>
                    <h1 style="font-size: clamp(26px, 4vw, 42px); font-weight: 900; line-height: 1.2; color: #ffffff; margin-bottom: 14px; letter-spacing: -0.5px;">
                        {{ $selectedPeriode->nama ?? 'Kongres Nasional (KONAS) ASKLIN' }}
                    </h1>

                    <p style="font-size: clamp(14px, 1.8vw, 17px); font-weight: 600; color: #94a3b8; line-height: 1.5; margin-bottom: 24px;">
                        &ldquo;{{ $selectedPeriode->tema ?? 'Peran ASKLIN dalam Era Transformasi Industri Wisata Medis' }}&rdquo;
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 30px;">
                        @if($isPeriodeActive)
                            <a href="#pendaftaran" class="mod-btn mod-btn-primary" style="padding: 12px 26px; font-size: 13.5px; font-weight: 700;">
                                <i class="fas fa-user-plus mr-1"></i> Daftar Delegasi Sekarang
                            </a>
                        @endif
                        <a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" target="_blank" class="mod-btn mod-btn-glass" style="padding: 12px 22px; font-size: 13.5px;">
                            <i class="fas fa-file-download mr-1"></i> Unduh TOR Kongres
                        </a>
                        <a href="#agenda" class="mod-btn mod-btn-glass" style="padding: 12px 22px; font-size: 13.5px;">
                            <i class="fas fa-calendar-alt mr-1"></i> Jadwal Acara
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern Metric Badges Strip -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-top: 30px;">
                <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; padding: 16px 20px;">
                    <div style="font-size: 11px; font-weight: 700; color: #5eead4; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-calendar-check mr-1"></i> Tanggal Pelaksanaan
                    </div>
                    <div style="font-size: 15px; font-weight: 800; color: #ffffff;">
                        {{ $selectedPeriode && $selectedPeriode->tanggal_mulai ? $selectedPeriode->tanggal_mulai->format('d M') : '14' }} - {{ $selectedPeriode && $selectedPeriode->tanggal_selesai ? $selectedPeriode->tanggal_selesai->format('d M Y') : '17 Juni 2023' }}
                    </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; padding: 16px 20px;">
                    <div style="font-size: 11px; font-weight: 700; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-map-marker-alt mr-1"></i> Lokasi Kongres
                    </div>
                    <div style="font-size: 14px; font-weight: 800; color: #ffffff;">
                        {{ $selectedPeriode->tempat ?? 'Takengon, Aceh Tengah' }}
                    </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; padding: 16px 20px;">
                    <div style="font-size: 11px; font-weight: 700; color: #c084fc; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-users mr-1"></i> Target Partisipasi
                    </div>
                    <div style="font-size: 14px; font-weight: 800; color: #ffffff;">
                        Offline &plusmn; 800 Pimpinan Klinik
                    </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; padding: 16px 20px;">
                    <div style="font-size: 11px; font-weight: 700; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-ticket-alt mr-1"></i> Biaya Registrasi
                    </div>
                    <div style="font-size: 15px; font-weight: 800; color: #ffffff;">
                        Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Floating Jump Bar (Single Page Navigation) -->
    <div style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255,255,255,0.08); position: sticky; top: 0; z-index: 40;">
        <div class="mod-container">
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
    <div style="background: #0b1120; color: #cbd5e1; padding: 40px 0 80px;">
        <div class="mod-container">

            <!-- Flash Alert -->
            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #6ee7b7; padding: 16px 20px; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 16px 20px; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(!$isPeriodeActive)
                <!-- Notice Status Periode Nonaktif -->
                <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 12px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 14px;">
                    <i class="fas fa-clock text-amber-400 text-xl mt-1"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #fde68a; margin: 0 0 4px;">Pemberitahuan Status Periode KONAS</h4>
                        <p style="font-size: 13px; color: #fef3c7; margin: 0; line-height: 1.5;">
                            Informasi KONAS saat ini berstatus arsip / pendaftaran publik belum dibuka. Formulir pendaftaran delegasi akan otomatis terbuka saat kepanitiaan mengaktifkan periode kongres berjalan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 1. SECTION PROFIL KONGRES -->
            <section id="profil" style="margin-bottom: 50px; scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.2); color: #2dd4bf; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">Tentang Kongres Nasional (KONAS) ASKLIN</h2>
                            <p style="font-size: 12px; color: #94a3b8; margin: 2px 0 0;">Musyawarah Akbar Tertinggi Organisasi Asosiasi Klinik Indonesia Setiap 5 Tahun</p>
                        </div>
                    </div>

                    <div style="font-size: 14px; line-height: 1.8; color: #cbd5e1; text-align: justify; margin-bottom: 24px;">
                        <p style="margin-bottom: 14px;">
                            <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong> didirikan di Jakarta pada 01 Agustus 2011 sebagai wadah berhimpun para pengusaha dan pimpinan klinik di seluruh Nusantara demi kemajuan dan standardisasi mutu pelayanan kesehatan Indonesia.
                        </p>
                        <p style="margin-bottom: 14px;">
                            <strong>KONAS ASKLIN</strong> diselenggarakan secara berkala setiap 5 (lima) tahun sekali bersamaan dengan momentum <strong>Pelantikan Dewan Pengurus Pusat (DPP) ASKLIN</strong>. Forum ini menjadi sarana strategis pimpinan cabang dan daerah dalam menentukan AD/ART, arah kebijakan advokasi klinik, serta pemilihan pucuk kepemimpinan asosiasi.
                        </p>
                    </div>

                    <!-- Material Kit Unduhan -->
                    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 20px;">
                        <h4 style="font-size: 12px; font-weight: 800; color: #5eead4; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px;">
                            <i class="fas fa-download mr-1"></i> Dokumen &amp; Media Publikasi Resmi:
                        </h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" target="_blank" class="mod-btn mod-btn-glass" style="font-size: 12px; padding: 8px 16px;">
                                <i class="fas fa-file-pdf text-red-400 mr-1"></i> Terms of Reference (TOR)
                            </a>
                            <a href="{{ asset('assets/frontend/SPONSORSHIP_BOOK.pdf') }}" target="_blank" class="mod-btn mod-btn-glass" style="font-size: 12px; padding: 8px 16px;">
                                <i class="fas fa-book text-amber-400 mr-1"></i> Sponsorship Book
                            </a>
                            <a href="https://drive.google.com/drive/folders/1_LxDefwLQmD-CVuZudMsbtU7OhTafbio" target="_blank" class="mod-btn mod-btn-glass" style="font-size: 12px; padding: 8px 16px;">
                                <i class="fas fa-image text-emerald-400 mr-1"></i> Virtual Background
                            </a>
                            <a href="https://twb.nz/konas-asklin" target="_blank" class="mod-btn mod-btn-glass" style="font-size: 12px; padding: 8px 16px;">
                                <i class="fas fa-camera text-blue-400 mr-1"></i> Twibbon Peserta
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 2. SECTION AGENDA & SIMPOSIUM -->
            <section id="agenda" style="margin-bottom: 50px; scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px;">
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(56, 189, 248, 0.2); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">Agenda Kongres &amp; 5 Simposium Nasional</h2>
                            <p style="font-size: 12px; color: #94a3b8; margin: 2px 0 0;">Sidang Komisi AD-ART, Simposium Medis Lintas Profesi, dan Pameran Expo</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                        <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 20px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #0d9488; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">01</div>
                            <h3 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">Sidang Pleno &amp; AD/ART</h3>
                            <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin: 0;">Musyawarah komisi organisasi, penetapan regulasi keanggotaan klinik, dan pemilihan Ketua Umum ASKLIN.</p>
                        </div>

                        <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 20px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #0284c7; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">02</div>
                            <h3 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">5 Simposium Ilmiah Nasional</h3>
                            <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin: 0;">Materi multidisiplin dari Kementerian Kesehatan, BPJS Kesehatan, kedokteran klinis, dan transformasi wisata medis.</p>
                        </div>

                        <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 20px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #7c3aed; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">03</div>
                            <h3 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">Pelantikan Dewan Pengurus Pusat</h3>
                            <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin: 0;">Pengukuhan kepengurusan baru ASKLIN masa bakti 5 tahun, pemberian penghargaan klinik teladan, dan gala dinner.</p>
                        </div>

                        <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 20px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 12px;">04</div>
                            <h3 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">City Tour &amp; Wisata Medis</h3>
                            <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin: 0;">Kunjungan keindahan destinasi wisata dan keanekaragaman kuliner khas wilayah tuan rumah penyelenggara.</p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 3. SECTION AKOMODASI HOTEL -->
            <section id="akomodasi" style="margin-bottom: 50px; scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px;">
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.2); color: #fbbf24; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">Akomodasi Hotel &amp; Layanan Tiket Penerbangan</h2>
                            <p style="font-size: 12px; color: #94a3b8; margin: 2px 0 0;">Hotel Rekanan Resmi Kongres dengan Tarif Khusus Delegasi ASKLIN</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 24px;">
                        @foreach($hotels as $h)
                            <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 16px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                        <span style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #fde68a; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">Hotel Mitra KONAS</span>
                                        <i class="fas fa-check-circle text-emerald-400"></i>
                                    </div>
                                    <h4 style="font-size: 16px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">{{ $h->hotel }}</h4>
                                    <p style="font-size: 12px; color: #94a3b8; line-height: 1.5; margin: 0 0 14px;">
                                        <i class="fas fa-map-marker-alt text-red-400 mr-1"></i> {{ $h->alamat }}
                                    </p>
                                </div>
                                <div style="border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 12px;">
                                    <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20KONAS,%20saya%20ingin%20info%20reservasi%20hotel%20{{ urlencode($h->hotel) }}" target="_blank" class="mod-btn mod-btn-glass w-full" style="font-size: 12px; text-align: center; justify-content: center;">
                                        <i class="fab fa-whatsapp text-emerald-400 mr-1"></i> Konfirmasi Booking Panitia
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="background: rgba(13, 148, 136, 0.1); border: 1px solid rgba(13, 148, 136, 0.25); border-radius: 14px; padding: 18px 22px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: #ffffff; margin: 0 0 4px;">Pemesanan Tiket Pesawat &amp; Penjemputan Delegasi</h4>
                            <p style="font-size: 12px; color: #94a3b8; margin: 0;">Tim akomodasi ASKLIN siap membantu penjadwalan dan armada penjemputan dari bandara ke venue.</p>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Saya%20ingin%20tau%20info%20mengenai%20tiket%20penerbangan%20KONAS" target="_blank" class="mod-btn mod-btn-primary" style="font-size: 12.5px;">
                            <i class="fab fa-whatsapp mr-1"></i> Konsultasi Penerbangan
                        </a>
                    </div>

                </div>
            </section>

            <!-- 4. SECTION PENDAFTARAN DELEGASI INLINE -->
            <section id="pendaftaran" style="margin-bottom: 50px; scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.85); backdrop-filter: blur(16px); border: 1px solid rgba(45, 212, 191, 0.3); border-radius: 22px; padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.25); color: #2dd4bf; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">Formulir Pendaftaran Delegasi KONAS</h2>
                            <p style="font-size: 12px; color: #94a3b8; margin: 2px 0 0;">Registrasi Online Terpadu — Masuk Otomatis ke Periode {{ $selectedPeriode->periode ?? '2023-2028' }}</p>
                        </div>
                    </div>

                    @if($isPeriodeActive)
                        <form action="{{ route('konas.storepeserta') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div style="background: rgba(13, 148, 136, 0.15); border: 1px solid rgba(45, 212, 191, 0.3); padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; color: #5eead4;">
                                <i class="fas fa-check-circle mr-1"></i> Pendaftaran delegasi terbuka untuk <strong>{{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }} (Periode {{ $selectedPeriode->periode }})</strong>. Biaya registrasi: <strong>Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}</strong>.
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Nama Peserta (Tanpa Gelar) <span style="color: #f87171;">*</span></label>
                                    <input type="text" name="nama_peserta" required placeholder="Ahmad Yani" style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Nama Sertifikat (Dengan Gelar Lengkap) <span style="color: #f87171;">*</span></label>
                                    <input type="text" name="nama_sertifikat" required placeholder="dr. Ahmad Yani, M.Kes" style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">NIK KTP <span style="color: #f87171;">*</span></label>
                                    <input type="number" name="nik" required placeholder="16 Digit NIK" style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Tanggal Lahir <span style="color: #f87171;">*</span></label>
                                    <input type="date" name="tgl_lahir" required style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">No. WhatsApp <span style="color: #f87171;">*</span></label>
                                    <input type="tel" name="no_hp" required placeholder="08xxxxxxxxxx" style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Email Aktif <span style="color: #f87171;">*</span></label>
                                    <input type="email" name="email" required placeholder="email@klinik.com" style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Nama Klinik / Instansi <span style="color: #f87171;">*</span></label>
                                    <input type="text" name="nama_klinik" required placeholder="Klinik Pratama..." style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Status Delegasi <span style="color: #f87171;">*</span></label>
                                    <select name="status" required style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Peserta">Peserta</option>
                                        <option value="Pendamping">Pendamping</option>
                                        <option value="Peninjau">Peninjau</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-bottom: 18px;">
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Alamat Lengkap Klinik <span style="color: #f87171;">*</span></label>
                                <textarea name="alamat_klinik" rows="2" required placeholder="Alamat klinik..." style="width: 100%; padding: 10px 14px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Provinsi <span style="color: #f87171;">*</span></label>
                                    <select name="id_provinsi" id="mod_id_provinsi" required onchange="loadCabangKonasMod(this.value)" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsi as $i)
                                            <option value="{{ $i->code }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Cabang (Kota/Kab) <span style="color: #f87171;">*</span></label>
                                    <select name="id_kota" id="mod_id_kota" required style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                        <option value="">-- Pilih Provinsi Dahulu --</option>
                                    </select>
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Komisi Sidang <span style="color: #f87171;">*</span></label>
                                    <select name="komisi" required style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #fff; font-size: 13px;">
                                        <option value="">-- Pilih Komisi --</option>
                                        <option value="Komisi AD-ART">Komisi AD-ART</option>
                                        <option value="Komisi ORTALA">Komisi ORTALA</option>
                                        <option value="Komisi Pembiayaan dan Kredensialing">Komisi Pembiayaan dan Kredensialing</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 24px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Upload Foto Profil (JPG/PNG, Max 5MB)</label>
                                    <input type="file" name="foto" accept="image/*" style="width: 100%; padding: 8px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #94a3b8; font-size: 12px;">
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;">Upload Surat Mandat (PDF/JPG/PNG, Max 5MB)</label>
                                    <input type="file" name="surat_mandat" accept=".pdf,image/*" style="width: 100%; padding: 8px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; color: #94a3b8; font-size: 12px;">
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 20px;">
                                <button type="submit" class="mod-btn mod-btn-primary" style="padding: 14px 32px; font-size: 14px; font-weight: 800;">
                                    <i class="fas fa-paper-plane mr-2"></i> Konfirmasi &amp; Bayar Registrasi
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="text-align: center; padding: 40px 20px; background: rgba(15, 23, 42, 0.6); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.15);">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.06); color: #94a3b8; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #ffffff; margin: 0 0 6px;">Pendaftaran Periode Ini Sedang Dinonaktifkan</h3>
                            <p style="font-size: 13px; color: #94a3b8; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                                Formulir registrasi hanya dapat diakses saat periode KONAS dinyatakan aktif oleh Dewan Pengurus Pusat ASKLIN.
                            </p>
                        </div>
                    @endif

                </div>
            </section>

            <!-- 5. SECTION MITRA & SPONSOR RESMI -->
            <section id="sponsor" style="margin-bottom: 50px; scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px;">
                    
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(192, 132, 252, 0.2); color: #c084fc; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">Partner &amp; Sponsor Resmi KONAS</h2>
                            <p style="font-size: 12px; color: #94a3b8; margin: 2px 0 0;">Mitra Strategis Pengembangan Pelayanan Kesehatan Primer Indonesia</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 14px; align-items: center;">
                        @foreach($partner as $p)
                            <a href="{{ $p->link ?: 'javascript:void(0)' }}" target="{{ $p->link ? '_blank' : '_self' }}" 
                               style="background: #ffffff; border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center; min-height: 70px; transition: transform 0.2s;"
                               title="{{ $p->nama }}" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                                <img src="{{ asset('images/partner/' . $p->photo) }}" alt="{{ $p->nama }}" style="max-height: 40px; max-width: 100%; object-fit: contain;" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">
                            </a>
                        @endforeach
                    </div>

                </div>
            </section>

            <!-- 6. SECTION KONTAK & BANTUAN -->
            <section id="kontak" style="scroll-margin-top: 80px;">
                <div style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 30px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; align-items: center;">
                        <div>
                            <div style="font-size: 11px; font-weight: 700; color: #5eead4; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Hotline Panitia</div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #ffffff; margin: 0 0 8px;">Sekretariat Pelaksana KONAS ASKLIN</h3>
                            <p style="font-size: 13px; color: #94a3b8; line-height: 1.6; margin: 0;">
                                Butuh panduan mandat daerah, konfirmasi pembayaran, atau info pameran booth sponsor? Hubungi tim panitia sekarang.
                            </p>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: lg-end;">
                            <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20KONAS%20ASKLIN" target="_blank" class="mod-btn mod-btn-primary" style="padding: 12px 24px;">
                                <i class="fab fa-whatsapp text-lg mr-2"></i> WhatsApp Panitia (+62 812-4599-9923)
                            </a>
                        </div>
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
