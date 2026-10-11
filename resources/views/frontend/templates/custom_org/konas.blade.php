@extends('frontend.templates.custom_org.layouts.app')

@push('meta')
    <!-- SEO Optimization -->
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="Konas ASKLIN, Kongres Nasional ASKLIN, {{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }}, {{ $selectedPeriode->periode ?? '2023-2028' }}, Asosiasi Klinik Indonesia, Wisata Medis, Pendaftaran Konas, Delegasi Klinik">
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

@section('content')

    <!-- Official Institutional Page Header & Periode Switcher -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span>Program &amp; Acara</span>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Kongres Nasional (KONAS)</span>
            </div>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 20px; margin-top: 15px;">
                <div style="max-width: 780px;">
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.12); padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; color: #a7f3d0; margin-bottom: 12px; border: 1px solid rgba(255,255,255,0.2);">
                        <i class="fas fa-landmark"></i>
                        <span>FORUM MUSYAWARAH TERTINGGI 5 TAHUNAN ASKLIN</span>
                    </div>
                    <h1 class="org-page-title" style="margin-bottom: 8px;">
                        {{ $selectedPeriode->nama ?? 'Kongres Nasional ASKLIN' }}
                    </h1>
                    <p class="org-page-subtitle" style="margin-bottom: 0;">
                        {{ $selectedPeriode->tema ?? 'Peran ASKLIN dalam Era Transformasi Industri Wisata Medis' }}
                    </p>
                </div>

                <!-- Dropdown Riwayat Periode KONAS (Ketentuan 5) -->
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); padding: 12px 18px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); min-width: 260px;">
                    <label for="riwayat_periode_select" style="display: block; font-size: 11px; font-weight: 700; color: #a7f3d0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        <i class="fas fa-history mr-1"></i> Riwayat Periode KONAS:
                    </label>
                    <select id="riwayat_periode_select" 
                            style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); background: #064e3b; color: #ffffff; font-size: 12px; font-weight: 700; cursor: pointer; outline: none;" 
                            onchange="window.location.href = '{{ route('konas') }}?periode=' + this.value">
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->periode }}" {{ $selectedPeriode && $selectedPeriode->id == $p->id ? 'selected' : '' }}>
                                KONAS Periode {{ $p->periode }} {{ $p->status == '1' ? '★ Aktif' : '• Arsip' }}
                            </option>
                        @endforeach
                    </select>
                    <div style="font-size: 10px; color: rgba(255,255,255,0.7); margin-top: 4px; text-align: right;">
                        Pilih periode untuk melihat arsip kongres
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subnav Anchors (Single Landing Page Jump Links) -->
    <div style="background: #ffffff; border-bottom: 1px solid var(--org-border); position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div class="custom-org-container">
            <div style="display: flex; gap: 8px; overflow-x: auto; padding: 10px 0; scrollbar-width: none;">
                <a href="#profil" class="org-subnav-link"><i class="fas fa-info-circle text-xs"></i> Profil Kongres</a>
                <a href="#agenda" class="org-subnav-link"><i class="fas fa-calendar-alt text-xs"></i> Agenda &amp; Simposium</a>
                <a href="#akomodasi" class="org-subnav-link"><i class="fas fa-hotel text-xs"></i> Hotel &amp; Transportasi</a>
                <a href="#pendaftaran" class="org-subnav-link" style="background: var(--org-primary-soft); color: var(--org-primary); font-weight: 700;"><i class="fas fa-user-plus text-xs"></i> Pendaftaran Delegasi</a>
                <a href="#mitra" class="org-subnav-link"><i class="fas fa-handshake text-xs"></i> Sponsor Resmi</a>
                <a href="#faq" class="org-subnav-link"><i class="fas fa-question-circle text-xs"></i> Informasi &amp; Kontak</a>
            </div>
        </div>
    </div>

    <!-- Main Landing Page Content -->
    <div class="org-section" style="padding-top: 30px;">
        <div class="custom-org-container">

            <!-- Notifications / Flash Messages -->
            @if(session('success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 13px;">
                    <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 13px;">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(!$isPeriodeActive)
                <!-- Alert Periode Nonaktif / Arsip -->
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #f59e0b; padding: 16px 20px; border-radius: 10px; margin-bottom: 30px; display: flex; align-items: flex-start; gap: 14px;">
                    <i class="fas fa-archive text-amber-600 text-xl" style="margin-top: 2px;"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #92400e; margin: 0 0 4px;">Informasi Status Periode (Arsip Riwayat)</h4>
                        <p style="font-size: 12.5px; color: #78350f; margin: 0; line-height: 1.5;">
                            Anda sedang melihat riwayat data untuk <strong>{{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }} (Periode {{ $selectedPeriode->periode ?? '-' }})</strong>. Periode ini berstatus arsip / pendaftaran telah selesai, sehingga formulir registrasi publik dinonaktifkan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Key Fact Highlight Strip -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 36px;">
                <div class="org-content-card" style="padding: 16px; margin: 0; display: flex; align-items: center; gap: 12px; border-left: 4px solid var(--org-primary);">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--org-text-muted); font-weight: 600;">Jadwal Pelaksanaan</div>
                        <div style="font-size: 13px; font-weight: 800; color: var(--org-secondary);">
                            {{ $selectedPeriode && $selectedPeriode->tanggal_mulai ? $selectedPeriode->tanggal_mulai->format('d M') : '14' }} - {{ $selectedPeriode && $selectedPeriode->tanggal_selesai ? $selectedPeriode->tanggal_selesai->format('d M Y') : '17 Juni 2023' }}
                        </div>
                    </div>
                </div>

                <div class="org-content-card" style="padding: 16px; margin: 0; display: flex; align-items: center; gap: 12px; border-left: 4px solid #0284c7;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--org-text-muted); font-weight: 600;">Lokasi Kongres</div>
                        <div style="font-size: 13px; font-weight: 800; color: var(--org-secondary);">
                            {{ $selectedPeriode->tempat ?? 'Takengon, Aceh Tengah' }}
                        </div>
                    </div>
                </div>

                <div class="org-content-card" style="padding: 16px; margin: 0; display: flex; align-items: center; gap: 12px; border-left: 4px solid #7c3aed;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--org-text-muted); font-weight: 600;">Delegasi Nasional</div>
                        <div style="font-size: 13px; font-weight: 800; color: var(--org-secondary);">
                            Offline &plusmn; 800 Delegasi Klinik
                        </div>
                    </div>
                </div>

                <div class="org-content-card" style="padding: 16px; margin: 0; display: flex; align-items: center; gap: 12px; border-left: 4px solid #d97706;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: var(--org-text-muted); font-weight: 600;">Biaya Pendaftaran</div>
                        <div style="font-size: 13px; font-weight: 800; color: var(--org-secondary);">
                            Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: PROFIL KONGRES -->
            <section id="profil" style="margin-bottom: 48px; scroll-margin-top: 80px;">
                <div class="org-content-card">
                    <div class="org-card-header">
                        <div class="org-card-header-icon"><i class="fas fa-landmark"></i></div>
                        <div>
                            <h2 class="org-card-title">Profil &amp; Hakikat Kongres Nasional (KONAS) ASKLIN</h2>
                            <p class="org-card-subtitle">Landasan Organisasi, Tema Strategis, dan Pelantikan Pengurus Pusat</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                        <div style="color: var(--org-text-main); font-size: 13.5px; line-height: 1.8; text-align: justify;">
                            <p style="margin-bottom: 14px;">
                                <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong> adalah wadah perhimpunan para pengusaha dan penanggung jawab klinik di seluruh Indonesia dalam rangka meningkatkan mutu dan tata kelola pelayanan kesehatan primer. Didirikan di Jakarta pada 01 Agustus 2011 dan telah berbadan hukum resmi melalui Keputusan Kementerian Hukum dan HAM Republik Indonesia (Akta No. 43).
                            </p>
                            <p style="margin-bottom: 14px;">
                                <strong>Kongres Nasional (KONAS)</strong> merupakan permusyawaratan tertinggi organisasi ASKLIN yang diselenggarakan <strong>setiap 5 (lima) tahun sekali</strong>. Pada forum inilah seluruh pimpinan klinik perwakilan cabang dan daerah se-Indonesia berkumpul untuk menetapkan Anggaran Dasar / Anggaran Rumah Tangga (AD/ART), memilih Ketua Umum ASKLIN, menyusun arah kebijakan program nasional, serta melaksanakan <strong>Pelantikan Dewan Pengurus Pusat (DPP) ASKLIN</strong>.
                            </p>
                            @if($selectedPeriode && $selectedPeriode->subtema)
                                <div style="background: var(--org-bg-page); padding: 16px 20px; border-radius: 10px; border-left: 4px solid var(--org-primary); margin: 18px 0;">
                                    <h4 style="font-size: 13px; font-weight: 800; color: var(--org-secondary); margin: 0 0 6px;">
                                        Fokus Tema Periode {{ $selectedPeriode->periode }}:
                                    </h4>
                                    <p style="font-size: 13px; color: var(--org-text-main); margin: 0; font-style: italic;">
                                        &ldquo;{{ $selectedPeriode->subtema }}&rdquo;
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Dokumen Resmi Unduhan -->
                        <div style="background: var(--org-primary-soft); padding: 20px; border-radius: 12px; border: 1px solid rgba(4, 120, 87, 0.2);">
                            <h3 style="font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--org-primary); margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-download"></i> Unduhan Dokumen Resmi &amp; Publikasi
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                <a href="{{ asset('assets/frontend/TERMS_OF_REFERENCE.pdf') }}" target="_blank" class="org-btn org-btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-file-pdf text-red-600 mr-1"></i> Terms of Reference (TOR)
                                </a>
                                <a href="{{ asset('assets/frontend/SPONSORSHIP_BOOK.pdf') }}" target="_blank" class="org-btn org-btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-book-open text-amber-600 mr-1"></i> Sponsorship Book
                                </a>
                                <a href="https://drive.google.com/drive/folders/1_LxDefwLQmD-CVuZudMsbtU7OhTafbio" target="_blank" class="org-btn org-btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-image text-emerald-600 mr-1"></i> Virtual Background
                                </a>
                                <a href="https://twb.nz/konas-asklin" target="_blank" class="org-btn org-btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-camera text-blue-600 mr-1"></i> Twibbon KONAS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: AGENDA & SIMPOSIUM -->
            <section id="agenda" style="margin-bottom: 48px; scroll-margin-top: 80px;">
                <div class="org-content-card">
                    <div class="org-card-header">
                        <div class="org-card-header-icon" style="background: #e0f2fe; color: #0284c7;"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <h2 class="org-card-title">Rangkaian Acara, Sidang Komisi &amp; Simposium</h2>
                            <p class="org-card-subtitle">Jadwal Agenda Formal Kongres Nasional dan Kegiatan Ilmiah</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        <!-- Item 1 -->
                        <div style="background: var(--org-bg-page); padding: 18px; border-radius: 12px; border: 1px solid var(--org-border);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--org-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800;">
                                    1
                                </div>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--org-secondary); margin: 0;">Sidang Pleno &amp; Pemilihan Ketua</h4>
                            </div>
                            <p style="font-size: 12.5px; color: var(--org-text-main); margin: 0; line-height: 1.6;">
                                Musyawarah pembagian komisi sidang (Komisi AD-ART, Komisi ORTALA, Komisi Pembiayaan &amp; Kredensialing), evaluasi pertanggungjawaban, dan pemilihan Ketua Umum ASKLIN.
                            </p>
                        </div>

                        <!-- Item 2 -->
                        <div style="background: var(--org-bg-page); padding: 18px; border-radius: 12px; border: 1px solid var(--org-border);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #0284c7; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800;">
                                    2
                                </div>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--org-secondary); margin: 0;">5 Simposium Nasional Lintas Profesi</h4>
                            </div>
                            <p style="font-size: 12.5px; color: var(--org-text-main); margin: 0; line-height: 1.6;">
                                Seminar ilmiah dan workshop menunjang kompetensi manajerial klinik bersama narasumber Kementerian Kesehatan, BPJS Kesehatan, dokter, perawat, dan tenaga medis.
                            </p>
                        </div>

                        <!-- Item 3 -->
                        <div style="background: var(--org-bg-page); padding: 18px; border-radius: 12px; border: 1px solid var(--org-border);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #7c3aed; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800;">
                                    3
                                </div>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--org-secondary); margin: 0;">Pelantikan DPP &amp; Malam Keakraban</h4>
                            </div>
                            <p style="font-size: 12.5px; color: var(--org-text-main); margin: 0; line-height: 1.6;">
                                Pengukuhan resmi jajaran Dewan Pengurus Pusat ASKLIN masa bakti 5 tahun ke depan, ramah tamah antar-pengurus daerah, serta apresiasi klinik berprestasi.
                            </p>
                        </div>

                        <!-- Item 4 -->
                        <div style="background: var(--org-bg-page); padding: 18px; border-radius: 12px; border: 1px solid var(--org-border);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800;">
                                    4
                                </div>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--org-secondary); margin: 0;">City Tour &amp; Wisata Edukasi</h4>
                            </div>
                            <p style="font-size: 12.5px; color: var(--org-text-main); margin: 0; line-height: 1.6;">
                                Eksplorasi destinasi alam dan kuliner di wilayah tuan rumah, mempererat persaudaraan seluruh anggota ASKLIN dari Sabang sampai Merauke.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 3: AKOMODASI HOTEL & TRANSPORTASI -->
            <section id="akomodasi" style="margin-bottom: 48px; scroll-margin-top: 80px;">
                <div class="org-content-card">
                    <div class="org-card-header">
                        <div class="org-card-header-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-hotel"></i></div>
                        <div>
                            <h2 class="org-card-title">Akomodasi Hotel Rekanan Resmi &amp; Penerbangan</h2>
                            <p class="org-card-subtitle">Pilihan Penginapan Delegasi dan Informasi Layanan Tiket</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
                        @foreach($hotels as $h)
                            <div style="border: 1px solid var(--org-border); border-radius: 12px; padding: 18px; background: #ffffff; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                        <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700; font-size: 10px;">Hotel Mitra KONAS</span>
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                    </div>
                                    <h4 style="font-size: 15px; font-weight: 800; color: var(--org-secondary); margin: 0 0 6px;">{{ $h->hotel }}</h4>
                                    <p style="font-size: 12px; color: var(--org-text-muted); line-height: 1.5; margin: 0 0 12px;">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $h->alamat }}
                                    </p>
                                </div>
                                <div style="border-top: 1px dashed var(--org-border); padding-top: 12px; margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 11px; color: var(--org-text-muted);">Pemesanan melalui:</span>
                                    <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20KONAS,%20saya%20ingin%20info%20reservasi%20hotel%20{{ urlencode($h->hotel) }}" target="_blank" class="org-btn org-btn-secondary" style="padding: 4px 12px; font-size: 11px;">
                                        <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Hubungi Panitia
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 13px; font-weight: 800; color: var(--org-secondary); margin: 0;">Bantuan Pemesanan Tiket Penerbangan Delegasi</h4>
                                <p style="font-size: 11.5px; color: var(--org-text-muted); margin: 0;">Panitia menyediakan asistensi koordinasi jadwal kedatangan dan armada penjemputan bandara.</p>
                            </div>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Saya%20ingin%20tau%20info%20mengenai%20tiket%20penerbangan%20KONAS" target="_blank" class="org-btn org-btn-primary" style="padding: 8px 18px; font-size: 12px;">
                            <i class="fab fa-whatsapp mr-1"></i> Asistensi Penerbangan
                        </a>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: FORMULIR PENDAFTARAN DELEGASI INLINE -->
            <section id="pendaftaran" style="margin-bottom: 48px; scroll-margin-top: 80px;">
                <div class="org-content-card" style="border-top: 4px solid var(--org-primary);">
                    <div class="org-card-header">
                        <div class="org-card-header-icon" style="background: var(--org-primary-soft); color: var(--org-primary);"><i class="fas fa-user-plus"></i></div>
                        <div>
                            <h2 class="org-card-title">Formulir Pendaftaran Delegasi KONAS</h2>
                            <p class="org-card-subtitle">Pengisian Data Peserta, Mandat Organisasi, dan Penetapan Komisi</p>
                        </div>
                    </div>

                    @if($isPeriodeActive)
                        <form action="{{ route('konas.storepeserta') }}" method="POST" enctype="multipart/form-data" style="margin-top: 8px;">
                            @csrf

                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 14px 18px; border-radius: 10px; margin-bottom: 22px; font-size: 12.5px; color: #166534;">
                                <i class="fas fa-info-circle mr-1 text-emerald-600"></i>
                                Pendaftaran akan tercatat otomatis pada <strong>{{ $selectedPeriode->nama ?? 'KONAS ASKLIN' }} (Periode {{ $selectedPeriode->periode }})</strong> dengan biaya investasi registrasi sebesar <strong>Rp {{ number_format((int) preg_replace('/[^0-9]/', '', $selectedPeriode->biaya_registrasi ?? '1500000'), 0, ',', '.') }}</strong>.
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div class="org-form-group">
                                    <label class="org-form-label">Nama Lengkap Peserta (Tanpa Gelar) <span style="color: red;">*</span></label>
                                    <input type="text" name="nama_peserta" class="org-form-control" required placeholder="Contoh: Ahmad Yani">
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Nama Tercetak di Sertifikat (Dengan Gelar) <span style="color: red;">*</span></label>
                                    <input type="text" name="nama_sertifikat" class="org-form-control" required placeholder="Contoh: dr. Ahmad Yani, M.Kes">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div class="org-form-group">
                                    <label class="org-form-label">Nomor Induk Kependudukan (NIK) <span style="color: red;">*</span></label>
                                    <input type="number" name="nik" class="org-form-control" required placeholder="16 Digit NIK KTP">
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Tanggal Lahir <span style="color: red;">*</span></label>
                                    <input type="date" name="tgl_lahir" class="org-form-control" required>
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Nomor HP / WhatsApp <span style="color: red;">*</span></label>
                                    <input type="tel" name="no_hp" class="org-form-control" required placeholder="08xxxxxxxxxx">
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Alamat Email Aktif <span style="color: red;">*</span></label>
                                    <input type="email" name="email" class="org-form-control" required placeholder="email@klinik.com">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div class="org-form-group">
                                    <label class="org-form-label">Nama Klinik / Fasilitas Pelayanan Kesehatan <span style="color: red;">*</span></label>
                                    <input type="text" name="nama_klinik" class="org-form-control" required placeholder="Contoh: Klinik Pratama Sehat Utama">
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Status Delegasi <span style="color: red;">*</span></label>
                                    <select name="status" class="org-form-control" required>
                                        <option value="">-- Pilih Status Delegasi --</option>
                                        <option value="Peserta">Peserta</option>
                                        <option value="Pendamping">Pendamping</option>
                                        <option value="Peninjau">Peninjau</option>
                                    </select>
                                </div>
                            </div>

                            <div class="org-form-group" style="margin-bottom: 18px;">
                                <label class="org-form-label">Alamat Lengkap Klinik <span style="color: red;">*</span></label>
                                <textarea name="alamat_klinik" class="org-form-control" rows="2" required placeholder="Alamat lengkap lokasi klinik..."></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 18px;">
                                <div class="org-form-group">
                                    <label class="org-form-label">Provinsi Asal Delegasi <span style="color: red;">*</span></label>
                                    <select name="id_provinsi" id="daftar_id_provinsi" class="org-form-control" required onchange="loadCabangKonas(this.value)">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsi as $i)
                                            <option value="{{ $i->code }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Kabupaten / Kota (Cabang) <span style="color: red;">*</span></label>
                                    <select name="id_kota" id="daftar_id_kota" class="org-form-control" required>
                                        <option value="">-- Pilih Provinsi Dahulu --</option>
                                    </select>
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Pilihan Komisi Sidang <span style="color: red;">*</span></label>
                                    <select name="komisi" class="org-form-control" required>
                                        <option value="">-- Pilih Komisi --</option>
                                        <option value="Komisi AD-ART">Komisi AD-ART</option>
                                        <option value="Komisi ORTALA">Komisi ORTALA</option>
                                        <option value="Komisi Pembiayaan dan Kredensialing">Komisi Pembiayaan dan Kredensialing</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 24px;">
                                <div class="org-form-group">
                                    <label class="org-form-label">Upload Pas Foto Profil (JPG/PNG, Max 5MB)</label>
                                    <input type="file" name="foto" class="org-form-control" accept="image/*">
                                </div>

                                <div class="org-form-group">
                                    <label class="org-form-label">Upload Surat Mandat Delegasi (PDF/JPG/PNG, Max 5MB)</label>
                                    <input type="file" name="surat_mandat" class="org-form-control" accept=".pdf,image/*">
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--org-border); padding-top: 20px;">
                                <button type="submit" class="org-btn org-btn-primary" style="padding: 12px 28px; font-size: 13.5px; font-weight: 700;">
                                    <i class="fas fa-paper-plane mr-2"></i> Ajukan Pendaftaran &amp; Lanjut Pembayaran
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="text-align: center; padding: 40px 20px; background: var(--org-bg-page); border-radius: 12px; border: 1px dashed var(--org-border);">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #64748b; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 14px;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--org-secondary); margin: 0 0 6px;">Pendaftaran KONAS Periode Ini Belum Dibuka / Dinonaktifkan</h3>
                            <p style="font-size: 13px; color: var(--org-text-muted); max-width: 520px; margin: 0 auto 18px; line-height: 1.6;">
                                Formulir registrasi hanya dibuka ketika periode KONAS sedang aktif berjalan. Untuk pertanyaan mengenai delegasi periode mendatang, silakan hubungi Sekretariat Panitia.
                            </p>
                            @if($activePeriode && $selectedPeriode->id != $activePeriode->id)
                                <a href="{{ route('konas') }}?periode={{ $activePeriode->periode }}" class="org-btn org-btn-primary" style="font-size: 12.5px;">
                                    Beralih ke Periode Aktif: {{ $activePeriode->nama }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </section>

            <!-- SECTION 5: MITRA & SPONSOR RESMI -->
            <section id="mitra" style="margin-bottom: 48px; scroll-margin-top: 80px;">
                <div class="org-content-card">
                    <div class="org-card-header">
                        <div class="org-card-header-icon" style="background: #ede9fe; color: #7c3aed;"><i class="fas fa-handshake"></i></div>
                        <div>
                            <h2 class="org-card-title">Mitra &amp; Sponsor Resmi KONAS ASKLIN</h2>
                            <p class="org-card-subtitle">Dukungan Industri Kesehatan, Teknologi Medis, dan Perbankan Nasional</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 16px; align-items: center;">
                        @foreach($partner as $p)
                            <a href="{{ $p->link ?: 'javascript:void(0)' }}" target="{{ $p->link ? '_blank' : '_self' }}" 
                               style="background: #ffffff; border: 1px solid var(--org-border); border-radius: 10px; padding: 14px; display: flex; align-items: center; justify-content: center; min-height: 75px; transition: all 0.2s;"
                               title="{{ $p->nama }}" class="org-partner-tile">
                                <img src="{{ asset('images/partner/' . $p->photo) }}" alt="{{ $p->nama }}" style="max-height: 44px; max-width: 100%; object-fit: contain;" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- SECTION 6: FAQ & KONTAK PANITIA -->
            <section id="faq" style="scroll-margin-top: 80px;">
                <div class="org-content-card">
                    <div class="org-card-header">
                        <div class="org-card-header-icon"><i class="fas fa-question-circle"></i></div>
                        <div>
                            <h2 class="org-card-title">Informasi &amp; Kontak Sekretariat Panitia KONAS</h2>
                            <p class="org-card-subtitle">Pusat Layanan Informasi Delegasi dan Konfirmasi Pendaftaran</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        <div>
                            <h4 style="font-size: 13.5px; font-weight: 800; color: var(--org-secondary); margin: 0 0 8px;">Sekretariat Panitia Pelaksana</h4>
                            <p style="font-size: 12.5px; color: var(--org-text-main); line-height: 1.6; margin: 0 0 14px;">
                                Dewan Pengurus Pusat Asosiasi Klinik Indonesia (ASKLIN)<br>
                                Gedung Pengurus Pusat ASKLIN, Jakarta Timur, DKI Jakarta<br>
                                Email: <a href="mailto:ppasklin.indonesia@gmail.com" style="color: var(--org-primary); font-weight: 700;">ppasklin.indonesia@gmail.com</a>
                            </p>
                        </div>

                        <div>
                            <h4 style="font-size: 13.5px; font-weight: 800; color: var(--org-secondary); margin: 0 0 8px;">Hotline Layanan Delegasi</h4>
                            <p style="font-size: 12.5px; color: var(--org-text-main); line-height: 1.6; margin: 0 0 14px;">
                                WhatsApp Panitia KONAS: <strong>+62 812-4599-9923</strong><br>
                                Pelayanan Delegasi: Senin - Sabtu, 08.00 - 17.00 WIB
                            </p>
                            <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Sekretariat%20Panitia%20KONAS%20ASKLIN" target="_blank" class="org-btn org-btn-secondary" style="font-size: 12px; padding: 6px 16px;">
                                <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Hubungi WhatsApp Sekretariat
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
    function loadCabangKonas(provCode) {
        let target = $('#daftar_id_kota');
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
