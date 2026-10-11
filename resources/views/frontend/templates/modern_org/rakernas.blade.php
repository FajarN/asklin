@extends('frontend.templates.modern_org.layouts.app')

@push('css')
<style>
    /* Modern Org Rakernas - Sleek Grey Accents */
    .mod-rkn-hero {
        padding: 130px 0 50px;
        background: radial-gradient(circle at 85% 15%, rgba(71, 85, 105, 0.06) 0%, transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(100, 116, 139, 0.05) 0%, transparent 45%),
                    var(--mod-bg);
        border-bottom: 1px solid var(--mod-border);
        position: relative;
        overflow: hidden;
    }
    html.dark .mod-rkn-hero,
    body.dark .mod-rkn-hero {
        background: radial-gradient(circle at 85% 15%, rgba(51, 65, 85, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 15% 85%, rgba(30, 41, 59, 0.3) 0%, transparent 50%),
                    #0f172a;
    }
    .mod-rkn-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(148, 163, 184, 0.18) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.6;
        pointer-events: none;
    }
    html.dark .mod-rkn-hero::before,
    body.dark .mod-rkn-hero::before {
        background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
    }

    .mod-rkn-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    html.dark .mod-rkn-pill-badge,
    body.dark .mod-rkn-pill-badge {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: #cbd5e1;
    }

    .mod-rkn-sticky-bar {
        background: var(--mod-header-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--mod-border);
        position: sticky;
        top: 0;
        z-index: 40;
        box-shadow: var(--mod-shadow-sm);
    }
    .mod-rkn-pill-nav {
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
    .mod-rkn-pill-nav i {
        color: var(--mod-text-muted) !important;
    }
    .mod-rkn-pill-nav:hover {
        color: var(--mod-text-heading) !important;
        background: var(--mod-surface);
        border-color: #94a3b8;
    }
    .mod-rkn-pill-nav:hover i {
        color: var(--mod-text-heading) !important;
    }
    .mod-rkn-pill-active {
        color: #ffffff !important;
        background: #334155 !important;
        border-color: #1e293b !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
    }
    .mod-rkn-pill-active i {
        color: #ffffff !important;
    }
    .mod-rkn-pill-active:hover {
        background: #1e293b !important;
        color: #ffffff !important;
    }

    .mod-rkn-card {
        background: var(--mod-surface);
        border: 1px solid var(--mod-border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: var(--mod-shadow-sm);
        margin-bottom: 40px;
        scroll-margin-top: 80px;
    }
    .mod-rkn-subcard {
        background: var(--mod-bg-alt);
        border: 1px solid var(--mod-border);
        border-radius: 16px;
        padding: 22px;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .mod-rkn-subcard:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
    }

    /* Primary Grey Button (Menggantikan Warna Hijau) */
    .mod-rkn-btn-primary {
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
    .mod-rkn-btn-primary * {
        color: #ffffff !important;
    }
    .mod-rkn-btn-primary:hover {
        background: #1e293b !important;
        color: #ffffff !important;
        border-color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
    }
    html.dark .mod-rkn-btn-primary,
    body.dark .mod-rkn-btn-primary {
        background: #475569 !important;
        border-color: #334155;
    }
    html.dark .mod-rkn-btn-primary:hover,
    body.dark .mod-rkn-btn-primary:hover {
        background: #334155 !important;
    }

    .mod-rkn-btn-glass {
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
    .mod-rkn-btn-glass i,
    .mod-rkn-btn-glass span {
        color: var(--mod-text-heading) !important;
    }
    .mod-rkn-btn-glass:hover {
        background: var(--mod-bg-alt) !important;
        color: var(--mod-text-heading) !important;
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    .mod-rkn-input {
        width: 100%;
        background: var(--mod-surface);
        border: 1px solid var(--mod-border);
        border-radius: 10px;
        color: var(--mod-text-heading);
        padding: 10px 14px;
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .mod-rkn-input:focus {
        border-color: #475569;
        box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.15);
    }
    .mod-rkn-label {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--mod-text-heading);
        margin-bottom: 6px;
    }
    .mod-rkn-label span.req {
        color: #ef4444;
    }

    .mod-rkn-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #cbd5e1;
        background: var(--mod-surface);
        margin: 0 auto 12px;
        display: block;
    }
    .mod-rkn-avatar-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(71, 85, 105, 0.12);
        border: 2px solid #cbd5e1;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 12px;
    }
    .mod-rkn-gallery-img {
        width: 100%;
        height: 190px;
        border-radius: 12px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }
    .mod-rkn-gallery-item:hover .mod-rkn-gallery-img {
        transform: scale(1.03);
    }
</style>
@endpush

@section('content')

    <!-- Hero Section (Light Mode Default, Aligned with 1280px Container) -->
    <section class="mod-rkn-hero">
        <div class="modern-org-container" style="position: relative; z-index: 10;">
            
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px;">
                <div class="mod-rkn-pill-badge">
                    <i class="fas fa-users-cog"></i> Rapat Kerja Nasional Pengurus Pusat ASKLIN
                </div>

                <!-- Dropdown Riwayat Periode Rakernas -->
                <div style="background: var(--mod-surface); padding: 8px 14px; border-radius: 12px; border: 1px solid var(--mod-border); display: flex; align-items: center; gap: 10px; box-shadow: var(--mod-shadow-sm);">
                    <label for="periode_select" style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; margin: 0; white-space: nowrap;">
                        <i class="fas fa-history mr-1"></i> Penyelenggaraan:
                    </label>
                    <select id="periode_select" 
                            style="background: var(--mod-bg-alt); color: var(--mod-text-heading); border: 1px solid var(--mod-border); border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; cursor: pointer; outline: none;"
                            onchange="window.location.href = '{{ route('rakernas') }}?periode_id=' + this.value">
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->id }}" {{ ($selectedPeriode && $selectedPeriode->id == $p->id) ? 'selected' : '' }}>
                                {{ $p->nama }} (Periode {{ $p->periode_kepengurusan }}) {{ $p->status == '1' ? '★ Aktif' : '• Arsip' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 24px; align-items: center;">
                <div style="max-width: 900px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px;">
                        <span style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.35); color: #b45309; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                            <i class="fas fa-award mr-1"></i> Rakernas Ke-{{ $selectedPeriode->rakernas_ke ?? '1' }}
                        </span>
                        <span style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                            <i class="fas fa-calendar mr-1"></i> Periode {{ $selectedPeriode->periode_kepengurusan ?? '2023–2028' }}
                        </span>
                        @if($isPeriodeActive)
                            <span style="background: #334155; border: 1px solid #1e293b; color: #ffffff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                                <i class="fas fa-bolt mr-1"></i> Pendaftaran Buka
                            </span>
                        @endif
                    </div>

                    <h1 style="font-size: clamp(26px, 4vw, 42px); font-weight: 900; line-height: 1.22; color: var(--mod-text-heading); margin-bottom: 14px; letter-spacing: -0.5px;">
                        {{ $selectedPeriode->nama ?? 'Rapat Kerja Nasional (RAKERNAS) ASKLIN' }}
                    </h1>

                    <p style="font-size: clamp(14px, 1.8vw, 17px); font-weight: 500; color: var(--mod-text-muted); line-height: 1.6; margin-bottom: 26px;">
                        &ldquo;{{ $selectedPeriode->tema ?? 'Sinergi Penguatan Mutu dan Tata Kelola Fasilitas Pelayanan Kesehatan Primer Menuju Transformasi Kesehatan Nasional' }}&rdquo;
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 30px;">
                        @if($isPeriodeActive)
                            <a href="#pendaftaran" class="mod-rkn-btn-primary">
                                <i class="fas fa-user-plus"></i> Daftar Peserta RAKERNAS
                            </a>
                        @endif
                        <a href="#tentang" class="mod-rkn-btn-glass">
                            <i class="fas fa-info-circle"></i> Info Kegiatan
                        </a>
                        <a href="#tamu-undangan" class="mod-rkn-btn-glass">
                            <i class="fas fa-users"></i> Pihak Diundang
                        </a>
                        <a href="#venue-hotel" class="mod-rkn-btn-glass">
                            <i class="fas fa-hotel"></i> Venue &amp; Lokasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric Badges Strip -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-top: 10px;">
                <div style="background: var(--mod-surface); border: 1px solid var(--mod-border); border-radius: 14px; padding: 16px 20px; box-shadow: var(--mod-shadow-sm);">
                    <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-calendar-day mr-1"></i> Jadwal Acara
                    </div>
                    <div style="font-size: 14.5px; font-weight: 800; color: var(--mod-text-heading);">
                        {{ $selectedPeriode->tanggal_formatted ?? 'Akan Diumumkan' }}
                    </div>
                </div>

                <div style="background: var(--mod-surface); border: 1px solid var(--mod-border); border-radius: 14px; padding: 16px 20px; box-shadow: var(--mod-shadow-sm);">
                    <div style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-map-marker-alt mr-1"></i> Venue &amp; Kota
                    </div>
                    <div style="font-size: 14.5px; font-weight: 800; color: var(--mod-text-heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}">
                        {{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}
                    </div>
                    <div style="font-size: 12px; color: var(--mod-text-muted);">{{ $selectedPeriode->kota ?? 'Jakarta' }}</div>
                </div>

                <div style="background: var(--mod-surface); border: 1px solid var(--mod-border); border-radius: 14px; padding: 16px 20px; box-shadow: var(--mod-shadow-sm);">
                    <div style="font-size: 11px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-hourglass-half mr-1"></i> Batas Pendaftaran
                    </div>
                    <div style="font-size: 14.5px; font-weight: 800; color: var(--mod-text-heading);">
                        {{ $selectedPeriode && $selectedPeriode->tgl_tutup_daftar ? $selectedPeriode->tgl_tutup_daftar->translatedFormat('d M Y') : 'Hingga Kuota Penuh' }}
                    </div>
                </div>

                <div style="background: var(--mod-surface); border: 1px solid var(--mod-border); border-radius: 14px; padding: 16px 20px; box-shadow: var(--mod-shadow-sm);">
                    <div style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        <i class="fas fa-clipboard-check mr-1"></i> Ketentuan Form
                    </div>
                    <div style="font-size: 14.5px; font-weight: 800; color: var(--mod-text-heading);">
                        Nama Klinik Wajib Diisi
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Sticky Navigation Jump Links -->
    <div class="mod-rkn-sticky-bar">
        <div class="modern-org-container">
            <div style="display: flex; gap: 10px; overflow-x: auto; padding: 12px 0; scrollbar-width: none;">
                <a href="#tentang" class="mod-rkn-pill-nav"><i class="fas fa-info-circle text-xs"></i> Tentang RAKERNAS</a>
                <a href="#tamu-undangan" class="mod-rkn-pill-nav"><i class="fas fa-users text-xs"></i> Pihak Diundang</a>
                <a href="#venue-hotel" class="mod-rkn-pill-nav"><i class="fas fa-hotel text-xs"></i> Venue &amp; Hotel</a>
                <a href="#pendaftaran" class="mod-rkn-pill-nav mod-rkn-pill-active"><i class="fas fa-user-plus text-xs"></i> Pendaftaran Peserta</a>
                <a href="#galeri" class="mod-rkn-pill-nav"><i class="fas fa-images text-xs"></i> Galeri Foto</a>
                <a href="#kontak" class="mod-rkn-pill-nav"><i class="fas fa-phone-alt text-xs"></i> Kontak Panitia</a>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <div style="background: var(--mod-bg); color: var(--mod-text-body); padding: 40px 0 80px;">
        <div class="modern-org-container">

            @if(!$isPeriodeActive)
                <!-- Notice Status Periode Nonaktif -->
                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.35); border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 14px; margin-bottom: 36px; display: flex; align-items: flex-start; gap: 14px;">
                    <i class="fas fa-clock text-amber-500 text-xl mt-1"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #b45309; margin: 0 0 4px;">Pemberitahuan Status Arsip RAKERNAS</h4>
                        <p style="font-size: 13px; color: var(--mod-text-body); margin: 0; line-height: 1.5;">
                            Informasi RAKERNAS yang sedang Anda lihat berstatus arsip atau pendaftaran publik belum dibuka. Formulir pendaftaran delegasi akan otomatis terbuka saat kepanitiaan mengaktifkan periode acara berjalan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 1. SECTION TENTANG RAKERNAS -->
            <section id="tentang" class="mod-rkn-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(71, 85, 105, 0.12); color: #334155; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: var(--mod-text-heading); margin: 0;">Tentang Rapat Kerja Nasional (RAKERNAS) ASKLIN</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Musyawarah Kerja Pengurus Pusat Maksimal 2 Kali dalam Satu Periode Kepengurusan</p>
                    </div>
                </div>

                <div style="font-size: 14px; line-height: 1.8; color: var(--mod-text-body); text-align: justify; margin-bottom: 26px;">
                    <p style="margin-bottom: 12px;">
                        <strong>Rapat Kerja Nasional (RAKERNAS) ASKLIN</strong> dilaksanakan oleh Pengurus Pusat maksimal 2 (dua) kali dalam satu periode kepengurusan (misalnya periode 2023–2028 memiliki Rakernas ke-1 dan Rakernas ke-2). 
                    </p>
                    <p style="margin-bottom: 12px;">
                        RAKERNAS berfokus pada evaluasi program kerja nasional, perumusan rekomendasi advokasi tarif dan regulasi klinik, serta sinergi erat dengan Kementerian Kesehatan RI, BPJS Kesehatan, dan Pemerintah Daerah dalam transformasi sistem kesehatan nasional.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                    <div class="mod-rkn-subcard">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #334155; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px;">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">Konsolidasi Klinik Indonesia</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Menghubungkan suara pengelola klinik primer dari seluruh Komda dan Komcab demi penguatan posisi tawar fasilitas kesehatan swasta.</p>
                    </div>

                    <div class="mod-rkn-subcard">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #475569; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">Audiensi Pemangku Kebijakan</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Dialog langsung bersama Kementerian Kesehatan, Direksi BPJS Kesehatan, dan Kepala Daerah mengenai kerja sama kapitasi dan akreditasi.</p>
                    </div>

                    <div class="mod-rkn-subcard">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #64748b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px;">
                            <i class="fas fa-digital-tachograph"></i>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">Standardisasi &amp; Digitalisasi</h3>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin: 0;">Workshop percepatan kepatuhan integrasi RME SatuSehat Kemenkes serta persiapan survei akreditasi klinik paripurna.</p>
                    </div>
                </div>
            </section>

            <!-- 2. SECTION PIHAK YANG DIUNDANG -->
            <section id="tamu-undangan" class="mod-rkn-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(71, 85, 105, 0.12); color: #334155; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: var(--mod-text-heading); margin: 0;">Pihak yang Diundang &amp; Sponsor Resmi</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Tokoh Kehormatan Kementerian Kesehatan, BPJS Kesehatan, Kepala Daerah, dan Mitra Industri</p>
                    </div>
                </div>

                @if($undangan->isNotEmpty())
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                        @foreach($undangan as $u)
                            <div class="mod-rkn-subcard" style="text-align: center;">
                                @if($u->foto)
                                    <img src="{{ asset('storage/' . $u->foto) }}" alt="{{ $u->nama }}" class="mod-rkn-avatar" onerror="this.outerHTML='<div class=\'mod-rkn-avatar-icon\'><i class=\'fas fa-user\'></i></div>'">
                                @else
                                    <div class="mod-rkn-avatar-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                @endif

                                <span style="display: inline-block; background: var(--mod-surface); border: 1px solid var(--mod-border); color: #0284c7; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase; margin-bottom: 8px;">
                                    {{ $u->kategori }}
                                </span>

                                <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px; line-height: 1.4;">
                                    {{ $u->nama }}
                                </h4>

                                <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0 0 6px;">
                                    {{ $u->jabatan }}
                                </p>

                                @if($u->instansi)
                                    <div style="font-size: 11.5px; font-weight: 700; color: #475569;">
                                        {{ $u->instansi }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Default Institutional Dignitaries -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                        <div class="mod-rkn-subcard" style="text-align: center;">
                            <div class="mod-rkn-avatar-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <span style="background: rgba(239, 68, 68, 0.15); color: #dc2626; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Kementerian</span>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 8px 0 4px;">Kementerian Kesehatan RI</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Menteri Kesehatan &amp; Dirjen Pelayanan Kesehatan</p>
                        </div>

                        <div class="mod-rkn-subcard" style="text-align: center;">
                            <div class="mod-rkn-avatar-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7; border-color: rgba(14, 165, 233, 0.3);">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <span style="background: rgba(14, 165, 233, 0.15); color: #0369a1; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Jaminan Kesehatan</span>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 8px 0 4px;">Direksi BPJS Kesehatan</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Direktur Jaminan Pelayanan Kesehatan</p>
                        </div>

                        <div class="mod-rkn-subcard" style="text-align: center;">
                            <div class="mod-rkn-avatar-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706; border-color: rgba(245, 158, 11, 0.3);">
                                <i class="fas fa-award"></i>
                            </div>
                            <span style="background: rgba(245, 158, 11, 0.15); color: #b45309; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Pemerintah Daerah</span>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 8px 0 4px;">Gubernur &amp; Bupati/Walikota</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Pimpinan Wilayah Tuan Rumah Acara</p>
                        </div>

                        <div class="mod-rkn-subcard" style="text-align: center;">
                            <div class="mod-rkn-avatar-icon" style="background: rgba(71, 85, 105, 0.12); color: #334155; border-color: #94a3b8;">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <span style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Mitra &amp; Sponsor</span>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 8px 0 4px;">Industri Farmasi &amp; Alkes</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Mitra Resmi Pelayanan Klinik Nasional</p>
                        </div>
                    </div>
                @endif
            </section>

            <!-- 3. SECTION VENUE & HOTEL REKANAN -->
            <section id="venue-hotel" class="mod-rkn-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: var(--mod-text-heading); margin: 0;">Tempat Acara &amp; Akomodasi Hotel Rekanan</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Informasi Grand Ballroom, Panduan Rute, dan Penginapan Khusus Peserta</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <div class="mod-rkn-subcard">
                        <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">
                            <i class="fas fa-map-marked-alt mr-1"></i> Venue Utama Penyelenggaraan
                        </div>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 8px;">
                            {{ $selectedPeriode->tempat ?? 'Grand Ballroom Penyelenggaraan' }}
                        </h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); line-height: 1.6; margin: 0 0 16px;">
                            {{ $selectedPeriode->alamat_lengkap ?? 'Lokasi strategis di pusat kota dengan fasilitas sidang representatif dan area pameran kesehatan.' }}
                        </p>
                        <div style="border-top: 1px solid var(--mod-border); padding-top: 12px; font-size: 12.5px; color: var(--mod-text-heading);">
                            <i class="fas fa-city mr-1" style="color: #475569;"></i> Kota: <strong>{{ $selectedPeriode->kota ?? 'Jakarta' }}</strong>
                        </div>
                    </div>

                    <div class="mod-rkn-subcard">
                        <div style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase; margin-bottom: 6px;">
                            <i class="fas fa-bed mr-1"></i> Rekomendasi Hotel &amp; Penginapan
                        </div>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 8px;">
                            Hotel Berbintang Rekanan Panitia
                        </h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); line-height: 1.6; margin: 0 0 16px;">
                            Panitia bekerja sama dengan jaringan hotel di sekitar lokasi acara untuk memberikan rate korporat khusus bagi seluruh delegasi klinik.
                        </p>
                        <div style="border-top: 1px solid var(--mod-border); padding-top: 12px;">
                            <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20RAKERNAS,%20saya%20ingin%20info%20rekomendasi%20hotel%20peserta" target="_blank" class="mod-rkn-btn-glass" style="font-size: 12px; padding: 8px 16px;">
                                <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Info Booking Hotel via Panitia
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. SECTION FORMULIR PENDAFTARAN PESERTA -->
            <section id="pendaftaran" class="mod-rkn-card" style="border: 1px solid var(--mod-border); box-shadow: var(--mod-shadow-md);">
                <div style="border-bottom: 1px solid var(--mod-border); padding-bottom: 20px; margin-bottom: 24px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
                    <div>
                        <span style="display: inline-block; background: rgba(245, 158, 11, 0.15); color: #b45309; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            <i class="fas fa-edit mr-1"></i> Form Pendaftaran Mandiri
                        </span>
                        <h2 style="font-size: 22px; font-weight: 900; color: var(--mod-text-heading); margin: 0;">
                            Formulir Pendaftaran Peserta RAKERNAS ASKLIN
                        </h2>
                        <p style="font-size: 12.5px; color: #475569; margin: 4px 0 0;">
                            Pendaftaran otomatis terhubung ke: <strong>{{ $activePeriode->nama ?? 'RAKERNAS ASKLIN' }}</strong> (Periode {{ $activePeriode->periode_kepengurusan ?? '2023–2028' }})
                        </p>
                    </div>

                    @if(!$activePeriode)
                        <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #b91c1c; font-size: 12px; font-weight: 700; padding: 8px 16px; border-radius: 8px;">
                            <i class="fas fa-lock mr-1"></i> Pendaftaran Belum Dibuka
                        </div>
                    @endif
                </div>

                @if(!$activePeriode)
                    <div style="text-align: center; padding: 40px 20px; background: var(--mod-bg-alt); border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <i class="fas fa-calendar-times text-amber-500" style="font-size: 48px; margin-bottom: 14px;"></i>
                        <h3 style="font-size: 16px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px;">Pendaftaran Sedang Ditutup</h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); max-width: 500px; margin: 0 auto;">
                            Saat ini tidak ada periode RAKERNAS yang berstatus aktif untuk pendaftaran publik. Silakan nantikan pengumuman resmi dari Pengurus Pusat ASKLIN.
                        </p>
                    </div>
                @else
                    <!-- Form Panduan -->
                    <div style="background: var(--mod-bg-alt); border: 1px solid var(--mod-border); border-radius: 12px; padding: 14px 18px; margin-bottom: 24px; font-size: 12.5px; line-height: 1.6; color: var(--mod-text-body);">
                        <i class="fas fa-info-circle mr-1" style="color: #475569;"></i>
                        <strong>Petunjuk Registrasi:</strong> Pendaftaran RAKERNAS bersifat mandiri dan ringkas (tanpa surat mandat/delegasi). 
                        <span style="color: #334155; font-weight: 700; text-decoration: underline;">Nama Klinik wajib diisi</span> sebagai data utama kepesertaan.
                    </div>

                    <form id="rakernasRegisterFormMod" action="{{ route('rakernas.daftar') }}" method="POST">
                        @csrf

                        <!-- Highlight Wajib: NAMA KLINIK -->
                        <div style="background: var(--mod-bg-alt); border: 2px solid #475569; border-radius: 14px; padding: 18px; margin-bottom: 20px;">
                            <label for="nama_klinik" class="mod-rkn-label" style="color: #1e293b; font-size: 12px; display: flex; justify-content: space-between;">
                                <span><i class="fas fa-clinic-medical mr-1"></i> Nama Klinik <span class="req">*</span></span>
                                <span style="background: #334155; color: #fff; font-size: 9.5px; padding: 2px 8px; border-radius: 9999px;">DATA UTAMA WAJIB</span>
                            </label>
                            <input type="text" name="nama_klinik" id="nama_klinik" class="mod-rkn-input" style="font-size: 14px; font-weight: 700;" required placeholder="Contoh: Klinik Pratama Sehat Mandiri / Klinik Utama Permata">
                            <span style="font-size: 11.5px; color: var(--mod-text-muted); margin-top: 4px; display: block;">Nama klinik ini akan tertera pada kartu pendaftaran, ID card, dan sertifikat resmi.</span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 20px;">
                            <div>
                                <label for="nama_peserta" class="mod-rkn-label">Nama Lengkap Peserta <span class="req">*</span></label>
                                <input type="text" name="nama_peserta" id="nama_peserta" class="mod-rkn-input" required placeholder="Nama lengkap &amp; gelar">
                            </div>

                            <div>
                                <label for="jabatan" class="mod-rkn-label">Jabatan di Klinik</label>
                                <input type="text" name="jabatan" id="jabatan" class="mod-rkn-input" placeholder="Pimpinan Klinik / Dokter / Penanggung Jawab">
                            </div>

                            <div>
                                <label for="no_hp" class="mod-rkn-label">No. WhatsApp / HP <span class="req">*</span></label>
                                <input type="text" name="no_hp" id="no_hp" class="mod-rkn-input" required placeholder="08123456789">
                            </div>

                            <div>
                                <label for="email" class="mod-rkn-label">Email Aktif <span class="req">*</span></label>
                                <input type="email" name="email" id="email" class="mod-rkn-input" required placeholder="nama@klinik.com">
                            </div>

                            <div>
                                <label for="id_provinsi" class="mod-rkn-label">Provinsi Asal</label>
                                <select name="id_provinsi" id="id_provinsi" class="mod-rkn-input">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsi as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="cabang" class="mod-rkn-label">Kabupaten / Kota / Cabang</label>
                                <input type="text" name="cabang" id="cabang" class="mod-rkn-input" placeholder="Kota / Cabang ASKLIN">
                            </div>

                            <div>
                                <label for="status_kehadiran" class="mod-rkn-label">Rencana Kehadiran</label>
                                <select name="status_kehadiran" id="status_kehadiran" class="mod-rkn-input">
                                    <option value="Hadir">Hadir Langsung di Lokasi (Fisik)</option>
                                    <option value="Online">Mengikuti via Zoom (Online)</option>
                                </select>
                            </div>

                            <div>
                                <label for="alamat_klinik" class="mod-rkn-label">Alamat Lengkap Klinik</label>
                                <input type="text" name="alamat_klinik" id="alamat_klinik" class="mod-rkn-input" placeholder="Alamat operasional fasilitas klinik">
                            </div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label for="kebutuhan_khusus" class="mod-rkn-label">Catatan / Kebutuhan Khusus (Opsional)</label>
                            <textarea name="kebutuhan_khusus" id="kebutuhan_khusus" rows="2" class="mod-rkn-input" placeholder="Catatan akomodasi, konsumsi, atau pertanyaan ke panitia"></textarea>
                        </div>

                        <div style="border-top: 1px solid var(--mod-border); padding-top: 18px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
                            <div style="font-size: 11.5px; color: var(--mod-text-muted);">
                                <i class="fas fa-shield-alt text-slate-500 mr-1"></i> Data Anda tersimpan aman dalam sistem pendaftaran resmi ASKLIN.
                            </div>
                            <button type="submit" id="btnSubmitDaftarMod" class="mod-rkn-btn-primary" style="padding: 13px 32px; font-size: 14px;">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Pendaftaran RAKERNAS
                            </button>
                        </div>
                    </form>
                @endif
            </section>

            <!-- 5. SECTION GALERI FOTO & VENUE -->
            <section id="galeri" class="mod-rkn-card">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(56, 189, 248, 0.15); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: var(--mod-text-heading); margin: 0;">Galeri &amp; Dokumentasi Lokasi RAKERNAS</h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 2px 0 0;">Dokumentasi Venue, Hotel Rekanan, dan Suasana Penyelenggaraan</p>
                    </div>
                </div>

                @if($galeriVenue->isNotEmpty())
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                        @foreach($galeriVenue as $g)
                            <div class="mod-rkn-gallery-item mod-rkn-subcard" style="padding: 12px; overflow: hidden;">
                                <div style="overflow: hidden; border-radius: 10px; margin-bottom: 10px;">
                                    <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->judul }}" class="mod-rkn-gallery-img" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">
                                </div>
                                <div style="font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase;">{{ $g->kategori }}</div>
                                <h4 style="font-size: 13px; font-weight: 700; color: var(--mod-text-heading); margin: 3px 0 0;">{{ $g->judul }}</h4>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback Gallery Items -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                        <div class="mod-rkn-subcard" style="text-align: center; padding: 30px;">
                            <i class="fas fa-landmark text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px;">Grand Ballroom RAKERNAS</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Ruang sidang utama kapasitas hingga 1.000 delegasi</p>
                        </div>
                        <div class="mod-rkn-subcard" style="text-align: center; padding: 30px;">
                            <i class="fas fa-hotel text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px;">Hotel Rekanan Resmi</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Akomodasi penginapan nyaman dekat lokasi konvensi</p>
                        </div>
                        <div class="mod-rkn-subcard" style="text-align: center; padding: 30px;">
                            <i class="fas fa-clinic-medical text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px;">Expo &amp; Pameran Kesehatan</h4>
                            <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0;">Booth pameran industri farmasi, alkes, dan RME klinik</p>
                        </div>
                    </div>
                @endif
            </section>

            <!-- 6. SECTION KONTAK & HELPDESK -->
            <section id="kontak" class="mod-rkn-card" style="margin-bottom: 0;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                    <div>
                        <span style="color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            <i class="fas fa-headset mr-1"></i> Layanan Bantuan Panitia
                        </span>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-heading); margin: 6px 0;">
                            Butuh Informasi Tambahan Terkait RAKERNAS ASKLIN?
                        </h3>
                        <p style="font-size: 13px; color: var(--mod-text-muted); margin: 0;">
                            Sekretariat Panitia Pelaksana siap membantu pertanyaan pendaftaran, akomodasi, dan panduan perjalanan.
                        </p>
                    </div>
                    <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Sekretariat%20RAKERNAS%20ASKLIN,%20saya%20ingin%20bertanya%20mengenai%20acara%20ini" target="_blank" class="mod-rkn-btn-primary" style="background: #25d366 !important; border-color: #22c55e !important; box-shadow: 0 4px 15px rgba(37,211,102,0.35);">
                        <i class="fab fa-whatsapp" style="font-size: 17px; color: #fff !important;"></i> Chat WhatsApp Panitia
                    </a>
                </div>
            </section>

        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rakernasRegisterFormMod');
    const submitBtn = document.getElementById('btnSubmitDaftarMod');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate nama_klinik (Wajib Utama)
            const inputKlinik = document.getElementById('nama_klinik');
            if (!inputKlinik || !inputKlinik.value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Klinik Wajib Diisi',
                    text: 'Mohon isi nama klinik Anda sebagai salah satu data utama pendaftaran RAKERNAS.',
                    confirmButtonColor: '#334155'
                });
                if (inputKlinik) inputKlinik.focus();
                return;
            }

            const formData = new FormData(form);
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Pendaftaran...';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil!',
                        html: `
                            <div style="text-align: left; font-size: 13px; line-height: 1.6; padding: 10px 0;">
                                <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; margin-bottom: 12px; text-align: center;">
                                    <div style="font-size: 11px; text-transform: uppercase; color: #475569; font-weight: bold;">Nomor Registrasi Anda</div>
                                    <div style="font-size: 20px; font-weight: 800; color: #1e293b; letter-spacing: 1px; font-family: monospace;">${data.no_registrasi}</div>
                                </div>
                                <div><strong>Nama Peserta:</strong> ${data.data.nama_peserta}</div>
                                <div><strong>Nama Klinik:</strong> ${data.data.nama_klinik}</div>
                                <div><strong>Status:</strong> Terdaftar Resmi</div>
                            </div>
                        `,
                        confirmButtonText: '<i class="fas fa-print mr-1"></i> Cetak Tanda Registrasi',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#334155',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        form.reset();
                        if (result.isConfirmed && data.redirect) {
                            window.open(data.redirect, '_blank');
                        }
                    });
                } else {
                    let errMsg = data.message || 'Terjadi kesalahan saat memproses pendaftaran.';
                    if (data.errors) {
                        errMsg = Object.values(data.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Pendaftaran Gagal',
                        html: errMsg,
                        confirmButtonColor: '#334155'
                    });
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Tidak dapat menghubungi server. Silakan coba kembali.',
                    confirmButtonColor: '#334155'
                });
            });
        });
    }
});
</script>
@endpush
