@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Formal Organization Hero Section -->
    <section class="org-hero">
        <div class="org-hero-pattern"></div>
        <div class="custom-org-container">
            <div class="org-hero-grid">

                <!-- Left: Headline & Mandate -->
                <div>
                    <div class="org-hero-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>ORGANISASI PROFESI RESMI KLINIK SE-INDONESIA</span>
                    </div>

                    <h1 class="org-hero-title">
                        Mewujudkan Pelayanan Klinik Mandiri, Kredibel &amp; Berkualitas
                    </h1>

                    <p class="org-hero-desc">
                        Selamat datang di portal resmi <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>. Bersama ribuan fasilitas pelayanan kesehatan klinik di seluruh penjuru tanah air, kami berikhtiar memperkuat standarisasi mutu, pembinaan kelembagaan, serta advokasi perlindungan klinik Indonesia.
                    </p>

                    <div class="org-hero-cta">
                        <a href="{{ route('register') }}" class="org-btn org-btn-primary" style="background-color: #ffffff; color: var(--org-primary-dark) !important; font-size: 14px; padding: 12px 24px;">
                            <i class="fas fa-file-signature text-red-700"></i>
                            <span>Daftar Anggota Klinik</span>
                        </a>
                        <a href="{{ route('sejarah') }}" class="org-btn org-btn-outline" style="background: transparent; color: #ffffff !important; border-color: rgba(255,255,255,0.4); font-size: 14px; padding: 12px 24px;">
                            <i class="fas fa-info-circle"></i>
                            <span>Profil Organisasi</span>
                        </a>
                    </div>
                </div>

                <!-- Right: National Association Live Metric Card -->
                <div>
                    <div class="org-hero-card">
                        <div class="org-hero-card-header">
                            <div class="flex items-center justify-between">
                                <span class="org-hero-card-title">Statistik Keanggotaan</span>
                                <span class="text-[11px] px-2 py-0.5 rounded bg-red-500/20 text-red-300 font-semibold border border-red-400/30">
                                    Nasional
                                </span>
                            </div>
                            <p class="org-hero-card-subtitle">
                                Jaringan fasilitas pelayanan kesehatan klinik berjenjang (Pusat, Daerah, Cabang)
                            </p>
                        </div>

                        <div class="org-hero-card-stats">
                            <div class="org-hero-stat-box">
                                <div class="org-hero-stat-num">1.460+</div>
                                <div class="org-hero-stat-label">Klinik Terdaftar</div>
                            </div>
                            <div class="org-hero-stat-box">
                                <div class="org-hero-stat-num">34</div>
                                <div class="org-hero-stat-label">Provinsi Terhubung</div>
                            </div>
                            <div class="org-hero-stat-box">
                                <div class="org-hero-stat-num">500+</div>
                                <div class="org-hero-stat-label">Kabupaten / Kota</div>
                            </div>
                            <div class="org-hero-stat-box">
                                <div class="org-hero-stat-num">100%</div>
                                <div class="org-hero-stat-label">Advokasi Mandiri</div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 12px; color: #cbd5e1;">
                                <i class="fas fa-check-double mr-1 text-red-400"></i> Terakreditasi &amp; Legal
                            </span>
                            <a href="{{ route('visimisi') }}" style="font-size: 12px; color: #fff; font-weight: 700;">
                                Pelajari Visi-Misi &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Slider Preview if present -->
    @if(isset($sliders) && $sliders->count() > 0)
        <section style="background-color: #0f172a; padding: 20px 0;">
            <div class="custom-org-container">
                <div style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px;">
                    @foreach($sliders as $slider)
                        <div style="min-width: 320px; max-width: 480px; flex: 1; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); position: relative; background: #1e293b;">
                            <img src="{{ asset($slider->foto_slider) }}" alt="{{ $slider->judul ?? 'Slider ASKLIN' }}" style="width: 100%; height: 200px; object-fit: cover; display: block;">
                            @if(!empty($slider->judul))
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(0,0,0,0.8)); padding: 12px 16px; color: #fff; font-size: 13px; font-weight: 700;">
                                    {{ $slider->judul }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 4 Institutional Pillars -->
    <section class="org-section org-section-alt">
        <div class="custom-org-container">
            <div class="org-section-header">
                <span class="org-section-eyebrow">Fokus Kerja Organisasi</span>
                <h2 class="org-section-title">Empat Pilar Pengabdian ASKLIN</h2>
                <p class="org-section-desc">
                    Mendukung transformasi fasilitas kesehatan klinik melalui tata kelola yang profesional, pembinaan kepatuhan regulasi, serta peningkatan mutu berkelanjutan.
                </p>
            </div>

            <div class="org-pillars-grid">
                <div class="org-pillar-card">
                    <div class="org-pillar-icon">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                    <h3 class="org-pillar-title">Pemberdayaan Klinik</h3>
                    <p class="org-pillar-text">
                        Mendorong klinik agar mampu menempatkan diri secara profesional dan mandiri dalam menyelenggarakan pelayanan kesehatan terdepan di tengah masyarakat.
                    </p>
                </div>

                <div class="org-pillar-card">
                    <div class="org-pillar-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3 class="org-pillar-title">Advokasi &amp; Perlindungan</h3>
                    <p class="org-pillar-text">
                        Memberikan pendampingan hukum, perlindungan profesi, serta menjembatani aspirasi klinik anggota kepada kementerian, BPJS Kesehatan, dan dinas kesehatan.
                    </p>
                </div>

                <div class="org-pillar-card">
                    <div class="org-pillar-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="org-pillar-title">Standarisasi &amp; Akreditasi</h3>
                    <p class="org-pillar-text">
                        Membantu persiapan akreditasi klinik, peningkatan tata kelola administrasi rekam medis elektronik (RME), dan standarisasi mutu layanan fasilitas kesehatan.
                    </p>
                </div>

                <div class="org-pillar-card">
                    <div class="org-pillar-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="org-pillar-title">Kemitraan Strategis</h3>
                    <p class="org-pillar-text">
                        Membangun kolaborasi saling menguntungkan dengan mitra farmasi, laboratorium penunjang, perbankan, dan asuransi kesehatan swasta berskala nasional.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About & Institutional History Overview -->
    <section class="org-section">
        <div class="custom-org-container">
            <div class="org-about-grid">
                <div>
                    <span class="org-section-eyebrow">Mengenal ASKLIN</span>
                    <h2 style="font-size: 28px; font-weight: 800; color: var(--org-secondary); margin: 0 0 16px 0; line-height: 1.3;">
                        Asosiasi Resmi Fasilitas Pelayanan Kesehatan Klinik
                    </h2>
                    <p style="font-size: 14.5px; color: var(--org-text-main); line-height: 1.8; text-align: justify; margin-bottom: 14px;">
                        Asosiasi Klinik Indonesia (ASKLIN) lahir atas inisiasi para tokoh dokter dan pimpinan fasilitas kesehatan pada 01 Agustus 2011 sebagai respons terhadap Peraturan Menteri Kesehatan No. 028/MENKES/PER/I/2011 dan implementasi Sistem Jaminan Sosial Nasional.
                    </p>
                    <p style="font-size: 14.5px; color: var(--org-text-main); line-height: 1.8; text-align: justify; margin-bottom: 20px;">
                        ASKLIN memperoleh pengesahan badan hukum resmi dari Kementerian Hukum dan HAM Republik Indonesia pada tanggal 04 Mei 2012. Hingga saat ini, kepengurusan ASKLIN tersebar di 34 Pengurus Daerah tingkat provinsi dan ratusan Pengurus Cabang di tingkat kabupaten/kota.
                    </p>

                    <div class="org-about-badges">
                        <div class="org-about-badge">
                            <i class="fas fa-calendar-check"></i>
                            <span>Didirikan: 01 Agustus 2011</span>
                        </div>
                        <div class="org-about-badge">
                            <i class="fas fa-stamp"></i>
                            <span>Legalitas Kemenkumham RI</span>
                        </div>
                        <div class="org-about-badge">
                            <i class="fas fa-network-wired"></i>
                            <span>34 Wilayah Pengurus Daerah</span>
                        </div>
                    </div>

                    <div style="margin-top: 26px;">
                        <a href="{{ route('sejarah') }}" class="org-btn org-btn-primary">
                            <span>Baca Sejarah Selengkapnya</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <div style="background-color: #ffffff; border: 1px solid var(--org-border); border-radius: var(--org-radius-lg); padding: 32px; box-shadow: var(--org-shadow-md);">
                        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--org-border-light);">
                            <div style="width: 50px; height: 50px; border-radius: var(--org-radius-md); background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: 800; color: var(--org-secondary); margin: 0;">Visi Utama ASKLIN</h3>
                                <span style="font-size: 12px; color: var(--org-text-muted);">Pedoman Arah Organisasi</span>
                            </div>
                        </div>

                        <blockquote style="margin: 0 0 20px 0; padding: 16px; background-color: var(--org-bg-body); border-left: 4px solid var(--org-primary); font-size: 14.5px; font-style: italic; color: var(--org-secondary); line-height: 1.7;">
                            &ldquo;Menjadikan Klinik sebagai sarana pelayanan kesehatan terdepan dalam melayani kesehatan masyarakat dengan sistem rujukan yang terarah dan terpadu.&rdquo;
                        </blockquote>

                        <h4 style="font-size: 14px; font-weight: 700; color: var(--org-secondary); margin: 0 0 12px 0;">Misi Strategis:</h4>
                        <ul style="list-style: none; padding: 0; margin: 0; space-y: 10px;">
                            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--org-text-main); margin-bottom: 8px;">
                                <i class="fas fa-check-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Menjaga standar kualitas klinik dalam memberikan pelayanan kesehatan masyarakat.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--org-text-main); margin-bottom: 8px;">
                                <i class="fas fa-check-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Melakukan advokasi dan pembelaan hak-hak hukum bagi klinik anggota.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--org-text-main);">
                                <i class="fas fa-check-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Membantu terlaksananya tertib administrasi klinis yang kredibel dan akuntabel.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section (Dynamic from Database) -->
    <section class="org-section org-section-alt">
        <div class="custom-org-container">
            <div class="org-section-header">
                <span class="org-section-eyebrow">Informasi &amp; Publikasi</span>
                <h2 class="org-section-title">Berita Terbaru ASKLIN</h2>
                <p class="org-section-desc">
                    Ikuti perkembangan terkini kebijakan kesehatan, kegiatan organisasi pengurus pusat &amp; daerah, serta informasi seputar fasilitas klinik.
                </p>
            </div>

            <div class="org-news-grid">
                @forelse($data as $berita)
                    <article class="org-news-card">
                        <div class="org-news-img-wrap">
                            @if($berita->thumbnail)
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}" class="org-news-img">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $berita->judul }}" class="org-news-img" style="object-fit: contain; padding: 30px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-newspaper text-3xl"></i>
                                </div>
                            @endif
                            <span class="org-news-badge">
                                {{ $berita->kategori->nama ?? 'ASKLIN News' }}
                            </span>
                        </div>

                        <div class="org-news-body">
                            <div class="org-news-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}</span>
                                <span><i class="far fa-user"></i> {{ $berita->creator->name ?? 'Redaksi' }}</span>
                            </div>

                            <h3 class="org-news-title">
                                <a href="{{ route('berita.detail', $berita->path) }}">
                                    {{ $berita->judul }}
                                </a>
                            </h3>

                            <div class="org-news-snippet">
                                {{ \Illuminate\Support\Str::limit(strip_tags($berita->konten), 120) }}
                            </div>

                            <div class="org-news-footer">
                                <a href="{{ route('berita.detail', $berita->path) }}" class="org-news-more">
                                    <span>Baca Artikel</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 12px; border: 1px dashed var(--org-border);">
                        <i class="fas fa-newspaper text-3xl text-slate-300 mb-2"></i>
                        <p style="color: var(--org-text-muted); font-size: 14px;">Belum ada artikel berita yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('berita') }}" class="org-btn org-btn-outline" style="padding: 12px 28px;">
                    <i class="fas fa-list-alt text-xs"></i>
                    <span>Lihat Semua Berita &amp; Artikel</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Section (Dynamic from Database) -->
    @if(isset($galery) && $galery->count() > 0)
        <section class="org-section">
            <div class="custom-org-container">
                <div class="org-section-header">
                    <span class="org-section-eyebrow">Dokumentasi Resmi</span>
                    <h2 class="org-section-title">Galeri Kegiatan Organisasi</h2>
                    <p class="org-section-desc">
                        Dokumentasi musyawarah kerja nasional, pelantikan pengurus wilayah, seminar ilmiah, dan kunjungan advokasi klinik.
                    </p>
                </div>

                <div class="org-gallery-grid">
                    @foreach($galery->take(8) as $item)
                        <div class="org-gallery-item">
                            @if(file_exists(public_path($item->foto)))
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->judul ?? 'Galeri ASKLIN' }}" class="org-gallery-img">
                            @elseif(file_exists(public_path('storage/' . $item->foto)))
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul ?? 'Galeri ASKLIN' }}" class="org-gallery-img">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-image text-2xl"></i>
                                </div>
                            @endif
                            <div class="org-gallery-overlay">
                                <span class="org-gallery-caption">{{ $item->judul ?? 'Kegiatan ASKLIN' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 36px;">
                    <a href="{{ route('galery') }}" class="org-btn org-btn-outline">
                        <i class="fas fa-images text-xs"></i>
                        <span>Lihat Semua Galeri Dokumentasi</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Membership Call-to-Action Banner -->
    <section class="org-section" style="padding-top: 0;">
        <div class="custom-org-container">
            <div class="org-cta-banner">
                <div>
                    <span style="display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; color: #fff;">
                        Keanggotaan Resmi ASKLIN
                    </span>
                    <h3 class="org-cta-title">Klinik Anda Belum Bergabung Bersama ASKLIN?</h3>
                    <p class="org-cta-text">
                        Dapatkan pendampingan akreditasi, advokasi hukum fasilitas kesehatan, bimbingan rekam medis elektronik, serta akses jejaring ribuan klinik se-Indonesia.
                    </p>
                </div>
                <div style="display: flex; gap: 12px; shrink-0; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="org-btn" style="background-color: #ffffff; color: var(--org-primary-dark) !important; font-size: 14px; padding: 14px 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <i class="fas fa-user-plus text-red-700"></i>
                        <span>Daftar Sekarang</span>
                    </a>
                    <a href="{{ route('kontak') }}" class="org-btn" style="background-color: transparent; border: 1px solid rgba(255,255,255,0.5); color: #ffffff !important; font-size: 14px; padding: 14px 24px;">
                        <i class="fas fa-envelope"></i>
                        <span>Konsultasi</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

