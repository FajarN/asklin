@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- ==========================================================================
         HERO SECTION - NEXT-GEN AMBIENT BENTO HEALTHCARE HUB
         ========================================================================== -->
    <section class="mod-hero-v3">
        <div class="mod-hero-glow-blob mod-hero-glow-blob-1"></div>
        <div class="mod-hero-glow-blob mod-hero-glow-blob-2"></div>

        <div class="modern-org-container">
            <!-- Center Futuristic Headline -->
            <div class="mod-hero-center-content">
                <div class="mod-hero-pill">
                    <span class="mod-pulse-dot"></span>
                    <span>JARINGAN FASILITAS PELAYANAN KESEHATAN KLINIK INDONESIA</span>
                </div>

                <h1 class="mod-hero-headline">
                    Akselerasi Digital &amp; Kemandirian <span class="mod-gradient-text">Klinik Indonesia</span>
                </h1>

                <p class="mod-hero-description">
                    Wadah pemersatu, pendampingan akreditasi paripurna, percepatan Rekam Medis Elektronik SATUSEHAT, serta advokasi hukum fasilitas pelayanan kesehatan klinik di seluruh penjuru tanah air.
                </p>

                <div class="mod-hero-cta-group">
                    <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="font-size: 14px; padding: 14px 30px;">
                        <i class="fas fa-file-medical"></i>
                        <span>Daftar Anggota Klinik</span>
                    </a>
                    <a href="{{ route('sejarah') }}" class="mod-btn mod-btn-glass" style="font-size: 14px; padding: 14px 28px;">
                        <i class="fas fa-compass text-red-500"></i>
                        <span>Jelajahi Profil Organisasi</span>
                    </a>
                </div>
            </div>

            <!-- Bento Hero Interactive 3-Card Showcase -->
            <div class="mod-bento-hero-grid">

                <!-- Card 1: Association Live Matrix -->
                <div class="mod-bento-card">
                    <div class="mod-bento-matrix-header">
                        <div class="mod-bento-matrix-title">
                            <i class="fas fa-chart-line text-red-500"></i>
                            <span>Cakupan Nasional</span>
                        </div>
                        <span class="mod-live-chip">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            LIVE MATRIX
                        </span>
                    </div>

                    <div class="mod-kpi-grid">
                        <div class="mod-kpi-box">
                            <div class="mod-kpi-val">1.460+</div>
                            <div class="mod-kpi-lbl">Klinik Terdaftar</div>
                        </div>
                        <div class="mod-kpi-box">
                            <div class="mod-kpi-val">34</div>
                            <div class="mod-kpi-lbl">Pengurus Daerah</div>
                        </div>
                        <div class="mod-kpi-box">
                            <div class="mod-kpi-val">500+</div>
                            <div class="mod-kpi-lbl">Cabang Wilayah</div>
                        </div>
                        <div class="mod-kpi-box">
                            <div class="mod-kpi-val">100%</div>
                            <div class="mod-kpi-lbl">Kemandirian Mutu</div>
                        </div>
                    </div>

                    <div class="mod-bento-chip-footer">
                        <i class="fas fa-certificate text-red-500"></i>
                        <span>Diakui Resmi Kemenkes RI &amp; Kemenkumham RI</span>
                    </div>
                </div>

                <!-- Card 2: Surat Edaran / Pengumuman Terkini -->
                <div class="mod-bento-card">
                    <span class="mod-notice-badge">
                        <i class="fas fa-bullhorn text-xs"></i> Warta Edaran Pusat
                    </span>
                    <h3 class="mod-notice-title">
                        Akselerasi Integrasi RME SATUSEHAT &amp; Akreditasi Paripurna
                    </h3>
                    <p class="mod-notice-desc">
                        Dihimbau kepada seluruh pimpinan klinik anggota untuk memvalidasi kesiapan integrasi rekam medis elektronik dan pembaruan berkas perizinan operasional berjenjang.
                    </p>
                    <div class="mod-notice-meta">
                        <span><i class="far fa-clock mr-1"></i> Update Resmi ASKLIN</span>
                        <a href="{{ route('berita') }}" class="font-bold text-red-500 hover:underline">
                            Baca Rincian <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Quick Fast-Track Services -->
                <div class="mod-bento-card">
                    <div style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                        <span>Layanan Mandiri</span>
                        <i class="fas fa-bolt text-amber-500"></i>
                    </div>

                    <div class="mod-shortcut-list">
                        <a href="{{ route('register') }}" class="mod-shortcut-item">
                            <div class="mod-shortcut-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; line-height: 1.2;">Registrasi Faskes</div>
                                <span style="font-size: 11px; color: var(--mod-text-muted);">Pendaftaran klinik baru</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </a>

                        <a href="{{ route('konas') }}" class="mod-shortcut-item">
                            <div class="mod-shortcut-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; line-height: 1.2;">Kongres Nasional</div>
                                <span style="font-size: 11px; color: var(--mod-text-muted);">Informasi KONAS ASKLIN</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </a>

                        <a href="{{ route('kontak') }}" class="mod-shortcut-item">
                            <div class="mod-shortcut-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; line-height: 1.2;">Advokasi &amp; Konsultasi</div>
                                <span style="font-size: 11px; color: var(--mod-text-muted);">Bantuan sekretariat</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==========================================================================
         LIVE RUNNING MARQUEE / TICKER
         ========================================================================== -->
    <section class="mod-marquee-section">
        <div class="mod-marquee-track">
            <div class="mod-marquee-item"><i class="fas fa-hospital-alt"></i> ASOSIASI KLINIK INDONESIA</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-check-double"></i> 1.460+ Faskes Anggota Terverifikasi</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-laptop-medical"></i> Standarisasi RME &amp; SATUSEHAT Kemenkes</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-shield-alt"></i> Pendampingan Hukum &amp; Advokasi Faskes</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-award"></i> Pendampingan Akreditasi Paripurna Klinik</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-handshake"></i> Kemitraan Strategis BPJS Kesehatan</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-map-marker-alt"></i> 34 Dewan Pengurus Daerah Seluruh Indonesia</div>
            <div class="mod-marquee-item">•</div>
            <!-- Repeat for seamless looping -->
            <div class="mod-marquee-item"><i class="fas fa-hospital-alt"></i> ASOSIASI KLINIK INDONESIA</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-check-double"></i> 1.460+ Faskes Anggota Terverifikasi</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-laptop-medical"></i> Standarisasi RME &amp; SATUSEHAT Kemenkes</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-shield-alt"></i> Pendampingan Hukum &amp; Advokasi Faskes</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-award"></i> Pendampingan Akreditasi Paripurna Klinik</div>
            <div class="mod-marquee-item">•</div>
            <div class="mod-marquee-item"><i class="fas fa-handshake"></i> Kemitraan Strategis BPJS Kesehatan</div>
        </div>
    </section>

    <!-- Sliders Showcase if Available -->
    @if(isset($sliders) && $sliders->count() > 0)
        <section style="padding: 30px 0; background: var(--mod-bg-alt); border-bottom: 1px solid var(--mod-border);">
            <div class="modern-org-container">
                <div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 12px;">
                    @foreach($sliders as $slider)
                        <div style="min-width: 320px; max-width: 460px; flex: 1; border-radius: var(--mod-radius-lg); overflow: hidden; border: 1px solid var(--mod-border); position: relative; background: var(--mod-surface); box-shadow: var(--mod-shadow-sm);">
                            <img src="{{ asset($slider->foto_slider) }}" alt="{{ $slider->judul ?? 'Slider' }}" style="width: 100%; height: 200px; object-fit: cover; display: block;">
                            @if(!empty($slider->judul))
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(3, 7, 18, 0.9)); padding: 14px 18px; color: #fff; font-size: 13.5px; font-weight: 700;">
                                    {{ $slider->judul }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ==========================================================================
         ASYMMETRIC BENTO PILLARS (STRATEGIC TRANSFORMATION)
         ========================================================================== -->
    <section style="padding: 75px 0;">
        <div class="modern-org-container">
            <div class="mod-section-title-wrap">
                <span class="mod-section-badge">Pilar Strategis</span>
                <h2 class="mod-section-title">Ekosistem Penguatan Klinik Indonesia</h2>
                <p class="mod-section-sub">
                    Inisiatif komprehensif ASKLIN untuk memberdayakan fasilitas pelayanan kesehatan menghadapi era JKN dan transformasi kesehatan digital.
                </p>
            </div>

            <div class="mod-bento-pillars-grid">

                <!-- Large Pillar 1 (Spans 2 columns) -->
                <div class="mod-bento-pillar-large">
                    <div class="mod-pillar-card-v3">
                        <div>
                            <div class="mod-pillar-icon-box">
                                <i class="fas fa-laptop-medical"></i>
                            </div>
                            <h3 class="mod-pillar-title">Akselerasi RME &amp; Integrasi SATUSEHAT Kemenkes</h3>
                            <p class="mod-pillar-desc">
                                Memfasilitasi seluruh klinik pratama dan utama anggota untuk terhubung secara seamless dengan platform SATUSEHAT Kementerian Kesehatan RI. Menyediakan bimbingan teknis arsitektur rekam medis elektronik terstandarisasi, interoperabilitas data kesehatan, serta kepatuhan perlindungan data medis pasien sesuai UU Kesehatan.
                            </p>
                        </div>
                        <div class="mod-pillar-tags">
                            <span class="mod-pillar-tag"><i class="fas fa-check text-red-500 mr-1"></i> Standar FHIR HL7</span>
                            <span class="mod-pillar-tag"><i class="fas fa-check text-red-500 mr-1"></i> SIMKlinik Terintegrasi</span>
                            <span class="mod-pillar-tag"><i class="fas fa-check text-red-500 mr-1"></i> Kepatuhan UU No. 17/2023</span>
                        </div>
                    </div>
                </div>

                <!-- Pillar 2 -->
                <div>
                    <div class="mod-pillar-card-v3">
                        <div>
                            <div class="mod-pillar-icon-box" style="background: linear-gradient(135deg, #e11d48, #be123c);">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <h3 class="mod-pillar-title">Advokasi &amp; Perlindungan Hukum</h3>
                            <p class="mod-pillar-desc">
                                Memberikan pendampingan hukum operasional faskes, bantuan telaah kontrak kerjasama asuransi, serta mediasi perselisihan mediko-legal dengan mengedepankan etika profesi dan keselamatan pasien.
                            </p>
                        </div>
                        <div class="mod-pillar-tags">
                            <span class="mod-pillar-tag">Konsultasi Hukum</span>
                            <span class="mod-pillar-tag">Mediasi Medis</span>
                        </div>
                    </div>
                </div>

                <!-- Pillar 3 -->
                <div>
                    <div class="mod-pillar-card-v3">
                        <div>
                            <div class="mod-pillar-icon-box" style="background: linear-gradient(135deg, #10b981, #047857);">
                                <i class="fas fa-award"></i>
                            </div>
                            <h3 class="mod-pillar-title">Akreditasi Mutu Berkelanjutan</h3>
                            <p class="mod-pillar-desc">
                                Workshop berkala dan simulasi penilaian akreditasi klinik bersama lembaga independen terakreditasi untuk memastikan standar keselamatan pasien (patient safety) dan tata kelola klinis paripurna.
                            </p>
                        </div>
                        <div class="mod-pillar-tags">
                            <span class="mod-pillar-tag">Simulasi Akreditasi</span>
                            <span class="mod-pillar-tag">Tata Kelola Klinis</span>
                        </div>
                    </div>
                </div>

                <!-- Pillar 4 (Spans 2 columns) -->
                <div class="mod-bento-pillar-large">
                    <div class="mod-pillar-card-v3">
                        <div>
                            <div class="mod-pillar-icon-box" style="background: linear-gradient(135deg, #0284c7, #075985);">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <h3 class="mod-pillar-title">Kemitraan Strategis BPJS Kesehatan &amp; Asuransi Nasional</h3>
                            <p class="mod-pillar-desc">
                                Menjadi jembatan negosiasi dan harmonisasi tarif kapitasi/non-kapitasi yang berkeadilan, menyempurnakan alur rujukan berjenjang yang terarah, serta membuka jejaring konsolidasi rantai pasok farmasi dan laboratorium penunjang.
                            </p>
                        </div>
                        <div class="mod-pillar-tags">
                            <span class="mod-pillar-tag"><i class="fas fa-check text-sky-500 mr-1"></i> Perjanjian Kerjasama Adil</span>
                            <span class="mod-pillar-tag"><i class="fas fa-check text-sky-500 mr-1"></i> Rujukan Terpadu</span>
                            <span class="mod-pillar-tag"><i class="fas fa-check text-sky-500 mr-1"></i> Konsolidasi Logistik</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==========================================================================
         MAGAZINE STYLE NEWS & EVENT BENTO
         ========================================================================== -->
    <section style="padding: 75px 0; background: var(--mod-bg-alt); border-top: 1px solid var(--mod-border); border-bottom: 1px solid var(--mod-border);">
        <div class="modern-org-container">
            <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
                <div>
                    <span class="mod-section-badge">Warta &amp; Publikasi</span>
                    <h2 class="mod-section-title" style="margin: 0;">Kabar Terkini &amp; Agenda Organisasi</h2>
                </div>
                <a href="{{ route('berita') }}" class="mod-btn mod-btn-glass" style="font-size: 13px;">
                    <span>Lihat Semua Berita</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if(isset($data) && $data->count() > 0)
                <div class="mod-magazine-grid">

                    <!-- Featured Big News Item -->
                    @php $featured = $data->first(); @endphp
                    <div>
                        <article class="mod-featured-news-card">
                            @if($featured->thumbnail)
                                <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->judul }}" class="mod-featured-news-img">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $featured->judul }}" class="mod-featured-news-img" style="object-fit: contain; padding: 60px; background: #0f172a;">
                            @else
                                <div style="position: absolute; inset: 0; background: #0f172a;"></div>
                            @endif
                            <div class="mod-featured-news-overlay"></div>

                            <div class="mod-featured-news-content">
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; background: var(--mod-primary); color: #fff; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 12px;">
                                    {{ $featured->kategori->nama ?? 'Headline Utama' }}
                                </span>

                                <div style="font-size: 12px; color: #94a3b8; margin-bottom: 8px;">
                                    <i class="far fa-calendar-alt text-red-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($featured->created_at)->translatedFormat('d F Y') }}
                                </div>

                                <h3 style="font-size: 24px; font-weight: 800; line-height: 1.3; color: #ffffff; margin: 0 0 12px 0;">
                                    <a href="{{ route('berita.detail', $featured->path) }}" style="color: #ffffff !important;">
                                        {{ $featured->judul }}
                                    </a>
                                </h3>

                                <p style="font-size: 14px; color: #cbd5e1; line-height: 1.6; margin: 0 0 18px 0;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($featured->konten), 140) }}
                                </p>

                                <a href="{{ route('berita.detail', $featured->path) }}" class="mod-btn mod-btn-primary" style="font-size: 12.5px; padding: 8px 18px;">
                                    <span>Baca Berita Selengkapnya</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Right Compact Stack -->
                    <div class="mod-compact-stack">
                        @foreach($data->skip(1)->take(2) as $item)
                            <article class="mod-compact-news-card">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->judul }}" class="mod-compact-news-thumb">
                                @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $item->judul }}" class="mod-compact-news-thumb" style="object-fit: contain; padding: 12px; background: var(--mod-bg-alt);">
                                @else
                                    <div class="mod-compact-news-thumb" style="background: var(--mod-bg-alt); display: flex; align-items: center; justify-content: center; color: var(--mod-text-dim);">
                                        <i class="fas fa-newspaper text-xl"></i>
                                    </div>
                                @endif

                                <div style="flex: 1;">
                                    <div style="font-size: 11.5px; color: var(--mod-primary-light); font-weight: 700; margin-bottom: 4px;">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                    </div>
                                    <h4 style="font-size: 14.5px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px 0; line-height: 1.35;">
                                        <a href="{{ route('berita.detail', $item->path) }}" style="color: inherit !important;">
                                            {{ \Illuminate\Support\Str::limit($item->judul, 65) }}
                                        </a>
                                    </h4>
                                    <p style="font-size: 12px; color: var(--mod-text-muted); margin: 0; line-height: 1.5;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 75) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach

                        <!-- Event Spotlight Box -->
                        <div class="mod-event-spotlight">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary-light); letter-spacing: 0.5px;">
                                    <i class="fas fa-calendar-star mr-1"></i> Event Mendatang
                                </span>
                                <span style="font-size: 11px; font-weight: 700; color: #10b981; background: rgba(16,185,129,0.1); padding: 2px 8px; border-radius: 9999px;">Terbuka</span>
                            </div>
                            <h4 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 6px 0;">
                                Lokakarya Nasional &amp; Konsolidasi Pengurus Daerah
                            </h4>
                            <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0 0 14px 0; line-height: 1.5;">
                                Ikuti pembahasan regulasi terkini penyelenggaraan fasilitas klinik bersama Kementerian Kesehatan &amp; PB IDI.
                            </p>
                            <a href="{{ route('event_asklin') }}" class="mod-btn mod-btn-subtle" style="width: 100%; justify-content: center; font-size: 12px; padding: 8px 14px;">
                                <span>Lihat Jadwal Workshop</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @else
                <div style="text-align: center; padding: 50px; background: var(--mod-card-bg); border-radius: var(--mod-radius-lg); border: 1px dashed var(--mod-border);">
                    <i class="fas fa-newspaper text-4xl text-slate-400 mb-2"></i>
                    <p style="color: var(--mod-text-muted); font-size: 14px; margin: 0;">Belum ada artikel berita yang dipublikasikan saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- ==========================================================================
         GALLERY / DOKUMENTASI KEGIATAN
         ========================================================================== -->
    @if(isset($galery) && $galery->count() > 0)
        <section style="padding: 75px 0;">
            <div class="modern-org-container">
                <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 35px; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span class="mod-section-badge">Dokumentasi</span>
                        <h2 class="mod-section-title" style="margin: 0;">Galeri Kegiatan Nasional</h2>
                    </div>
                    <a href="{{ route('galery') }}" class="mod-btn mod-btn-glass" style="font-size: 13px;">
                        <span>Buka Galeri Foto</span>
                        <i class="fas fa-images text-xs text-red-500"></i>
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px;">
                    @foreach($galery->take(8) as $item)
                        <div style="position: relative; aspect-ratio: 4 / 3; border-radius: var(--mod-radius-md); overflow: hidden; box-shadow: var(--mod-shadow-sm); border: 1px solid var(--mod-border); background: var(--mod-surface);">
                            @if(file_exists(public_path($item->foto)))
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                            @elseif(file_exists(public_path('storage/' . $item->foto)))
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--mod-bg-alt); color: var(--mod-text-dim);">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(3, 7, 18, 0.9)); padding: 12px 16px; color: #fff; font-size: 12px; font-weight: 700;">
                                {{ $item->judul ?? 'Dokumentasi ASKLIN' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ==========================================================================
         HIGH-IMPACT DIGITAL MEMBERSHIP BANNER
         ========================================================================== -->
    <section style="padding: 0 0 80px 0;">
        <div class="modern-org-container">
            <div class="mod-cta-v3">
                <div class="mod-cta-glow"></div>
                <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; gap: 36px; flex-wrap: wrap;">
                    <div style="max-width: 680px;">
                        <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.35); font-size: 11px; font-weight: 800; color: #fca5a5; text-transform: uppercase; margin-bottom: 14px;">
                            Portal Resmi Keanggotaan
                        </span>
                        <h3 style="font-size: 32px; font-weight: 800; color: #ffffff; margin: 0 0 12px 0; letter-spacing: -0.6px; line-height: 1.25;">
                            Perkuat Posisi &amp; Tata Kelola Klinik Anda Bersama ASKLIN
                        </h3>
                        <p style="font-size: 15px; color: #cbd5e1; margin: 0; line-height: 1.7;">
                            Dapatkan hak perlindungan advokasi hukum, bimbingan akreditasi, konsultasi perizinan faskes berjenjang, dan jaringan rujukan terarah dengan menjadi anggota resmi.
                        </p>
                    </div>

                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="font-size: 14px; padding: 14px 28px;">
                            <i class="fas fa-file-signature text-xs"></i>
                            <span>Daftar Calon Anggota</span>
                        </a>
                        <a href="{{ route('kontak') }}" class="mod-btn mod-btn-glass" style="font-size: 14px; padding: 14px 24px; color: #ffffff !important; border-color: rgba(255,255,255,0.2);">
                            <i class="fas fa-comment-dots text-xs text-red-400"></i>
                            <span>Hubungi Sekretariat</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
