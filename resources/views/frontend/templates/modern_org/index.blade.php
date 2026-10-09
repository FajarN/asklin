@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Modern Dynamic Hero Section -->
    <section class="mod-hero">
        <div class="mod-hero-glow-1"></div>
        <div class="mod-hero-glow-2"></div>
        <div class="modern-org-container">
            <div class="mod-hero-grid">

                <!-- Left: Modern Headlines & Value Proposition -->
                <div>
                    <div class="mod-pill-badge">
                        <span class="mod-pill-badge-pulse"></span>
                        <span>ASOSIASI KLINIK INDONESIA</span>
                        <span style="opacity: 0.5;">•</span>
                        <span>PORTAL RESMI</span>
                    </div>

                    <h1 class="mod-hero-title">
                        Akselerasi Mutu &amp; Kemandirian <span class="mod-hero-title-gradient">Klinik Indonesia</span>
                    </h1>

                    <p class="mod-hero-desc">
                        Bersatu padu memajukan fasilitas pelayanan kesehatan klinik di era transformasi digital. Wadah kolaborasi, pendampingan akreditasi, pembinaan tata kelola, dan perlindungan hukum klinik se-Indonesia.
                    </p>

                    <div class="mod-hero-actions">
                        <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="font-size: 14px; padding: 14px 28px;">
                            <i class="fas fa-rocket text-xs"></i>
                            <span>Daftar Anggota Klinik</span>
                        </a>
                        <a href="{{ route('sejarah') }}" class="mod-btn mod-btn-glass" style="font-size: 14px; padding: 14px 26px;">
                            <i class="fas fa-compass text-xs text-red-400"></i>
                            <span>Jelajahi Profil ASKLIN</span>
                        </a>
                    </div>
                </div>

                <!-- Right: High-Tech Glassmorphism Metric Card -->
                <div>
                    <div class="mod-glass-card">
                        <div class="mod-glass-card-header">
                            <div>
                                <span class="mod-glass-card-title">Cakupan Keanggotaan Nasional</span>
                                <p style="font-size: 11.5px; color: #94a3b8; margin: 4px 0 0 0;">Jaringan faskes klinik terverifikasi di seluruh Indonesia</p>
                            </div>
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 12px #ef4444;"></span>
                        </div>

                        <div class="mod-glass-card-stats">
                            <div class="mod-glass-stat">
                                <div class="mod-glass-stat-num">1.460+</div>
                                <div class="mod-glass-stat-label">Klinik Terdaftar</div>
                            </div>
                            <div class="mod-glass-stat">
                                <div class="mod-glass-stat-num">34</div>
                                <div class="mod-glass-stat-label">Pengurus Daerah</div>
                            </div>
                            <div class="mod-glass-stat">
                                <div class="mod-glass-stat-num">500+</div>
                                <div class="mod-glass-stat-label">Cabang Wilayah</div>
                            </div>
                            <div class="mod-glass-stat">
                                <div class="mod-glass-stat-num">100%</div>
                                <div class="mod-glass-stat-label">Kemandirian Mutu</div>
                            </div>
                        </div>

                        <div class="mod-glass-badge">
                            <i class="fas fa-check-circle text-red-400 text-sm"></i>
                            <span>Organisasi Resmi Diakui Kemenkes &amp; Kemenkumham RI</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Slider Showcase (if available) -->
    @if(isset($sliders) && $sliders->count() > 0)
        <section style="background-color: #020617; padding: 24px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="modern-org-container">
                <div style="display: flex; gap: 18px; overflow-x: auto; padding-bottom: 10px;">
                    @foreach($sliders as $slider)
                        <div style="min-width: 340px; max-width: 480px; flex: 1; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); position: relative; background: #0f172a;">
                            <img src="{{ asset($slider->foto_slider) }}" alt="{{ $slider->judul ?? 'Slider' }}" style="width: 100%; height: 210px; object-fit: cover; display: block;">
                            @if(!empty($slider->judul))
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(2, 6, 23, 0.95)); padding: 14px 18px; color: #fff; font-size: 13.5px; font-weight: 700;">
                                    {{ $slider->judul }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 4 Pillars of Transformation -->
    <section class="mod-section">
        <div class="modern-org-container">
            <div class="mod-section-header">
                <span class="mod-tag-pill">Pilar Transformasi</span>
                <h2 class="mod-section-title">Solusi Menyeluruh untuk Ekosistem Klinik</h2>
                <p class="mod-section-desc">
                    Memberikan nilai tambah berkelanjutan bagi klinik anggota melalui program terarah, pendampingan regulasi, dan kolaborasi modern.
                </p>
            </div>

            <div class="mod-features-grid">
                <div class="mod-feature-card">
                    <div class="mod-feature-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3 class="mod-feature-title">Pemberdayaan &amp; Mutu</h3>
                    <p class="mod-feature-text">
                        Mendukung klinik pratama dan utama agar beroperasi secara profesional, mengutamakan keselamatan pasien, dan mandiri secara finansial.
                    </p>
                </div>

                <div class="mod-feature-card">
                    <div class="mod-feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="mod-feature-title">Advokasi &amp; Legalitas</h3>
                    <p class="mod-feature-text">
                        Pendampingan hukum fasilitas kesehatan, mediasi perselisihan, serta menjembatani perumusan kebijakan bersama Kemenkes dan BPJS Kesehatan.
                    </p>
                </div>

                <div class="mod-feature-card">
                    <div class="mod-feature-icon">
                        <i class="fas fa-laptop-medical"></i>
                    </div>
                    <h3 class="mod-feature-title">Transformasi Digital</h3>
                    <p class="mod-feature-text">
                        Membantu kesiapan integrasi Rekam Medis Elektronik (RME) dan SatuSehat, serta standarisasi tata kelola klinik modern.
                    </p>
                </div>

                <div class="mod-feature-card">
                    <div class="mod-feature-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3 class="mod-feature-title">Jejaring Kolaboratif</h3>
                    <p class="mod-feature-text">
                        Kerjasama kemitraan strategis dengan institusi pembiayaan, vendor farmasi, laboratorium rujukan, dan asuransi kesehatan nasional.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section Modern Split Layout -->
    <section class="mod-section mod-section-dark">
        <div class="modern-org-container">
            <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 50px; align-items: center;">
                <div>
                    <span class="mod-tag-pill" style="background: rgba(20, 184, 166, 0.15); color: #5eead4;">Identitas Organisasi</span>
                    <h2 class="mod-section-title" style="color: #ffffff; font-size: 34px;">
                        Wadah Pemersatu Fasilitas Kesehatan Klinik Se-Indonesia
                    </h2>
                    <p style="font-size: 15px; color: #94a3b8; line-height: 1.8; text-align: justify; margin-bottom: 20px;">
                        Lahir pada <strong>01 Agustus 2011</strong> dan disahkan secara resmi oleh Kementerian Hukum dan HAM Republik Indonesia pada <strong>04 Mei 2012</strong>, ASKLIN merupakan wujud nyata kepedulian para pengelola dan dokter pimpinan klinik di seluruh nusantara.
                    </p>
                    <p style="font-size: 15px; color: #94a3b8; line-height: 1.8; text-align: justify; margin-bottom: 28px;">
                        Kami berkomitmen mengawal pelaksanaan Jaminan Kesehatan Nasional, menjaga standar pelayanan fasilitas kesehatan tingkat pertama, serta memfasilitasi peningkatan mutu berkelanjutan bagi segenap anggota.
                    </p>

                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <a href="{{ route('sejarah') }}" class="mod-btn mod-btn-primary">
                            <span>Pelajari Sejarah Lengkap</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                        <a href="{{ route('visimisi') }}" class="mod-btn mod-btn-glass">
                            <span>Visi &amp; Misi</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--mod-radius-lg); padding: 36px; backdrop-filter: blur(10px);">
                        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 22px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, var(--mod-primary), var(--mod-primary-dark)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 800; color: #fff; margin: 0;">Visi Utama ASKLIN</h3>
                                <span style="font-size: 12px; color: #fca5a5;">Arah Panduan Asosiasi</span>
                            </div>
                        </div>

                        <blockquote style="margin: 0 0 24px 0; padding: 18px 20px; background: rgba(2, 6, 23, 0.5); border-left: 3px solid var(--mod-primary); border-radius: 0 12px 12px 0; font-size: 15px; font-style: italic; color: #e2e8f0; line-height: 1.7;">
                            &ldquo;Menjadikan Klinik sebagai sarana pelayanan kesehatan terdepan dalam melayani kesehatan masyarakat dengan sistem rujukan yang terarah dan terpadu.&rdquo;
                        </blockquote>

                        <div style="space-y: 10px; font-size: 13.5px; color: #94a3b8;">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-check-circle text-red-400"></i> Membantu fasilitasi penyelesaian masalah klinik</div>
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-check-circle text-red-400"></i> Menjaga standar mutu pelayanan klinik nasional</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-red-400"></i> Melakukan advokasi pembelaan bagi klinik anggota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Editorial Section -->
    <section class="mod-section">
        <div class="modern-org-container">
            <div class="mod-section-header">
                <span class="mod-tag-pill">Warta Terbaru</span>
                <h2 class="mod-section-title">Kabar &amp; Publikasi Kebijakan</h2>
                <p class="mod-section-desc">
                    Berita terkini seputar dunia kesehatan, kegiatan pengurus daerah, serta regulasi fasilitas pelayanan klinik.
                </p>
            </div>

            <div class="mod-news-grid">
                @forelse($data as $berita)
                    <article class="mod-news-card">
                        <div class="mod-news-cover">
                            @if($berita->thumbnail)
                                <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}">
                            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="{{ $berita->judul }}" style="object-fit: contain; padding: 30px;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                    <i class="fas fa-newspaper text-3xl"></i>
                                </div>
                            @endif
                            <span class="mod-news-tag">
                                {{ $berita->kategori->nama ?? 'ASKLIN News' }}
                            </span>
                        </div>

                        <div class="mod-news-content">
                            <div class="mod-news-date">
                                <i class="far fa-calendar-alt text-red-600"></i>
                                <span>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}</span>
                            </div>

                            <h3 class="mod-news-title">
                                <a href="{{ route('berita.detail', $berita->path) }}">
                                    {{ $berita->judul }}
                                </a>
                            </h3>

                            <div class="mod-news-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($berita->konten), 125) }}
                            </div>

                            <div class="mod-news-bottom">
                                <a href="{{ route('berita.detail', $berita->path) }}" class="mod-news-link">
                                    <span>Baca Berita</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: #fff; border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <i class="fas fa-newspaper text-4xl text-slate-300 mb-2"></i>
                        <p style="color: var(--mod-text-muted); font-size: 14px;">Belum ada berita yang dipublikasikan saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 45px;">
                <a href="{{ route('berita') }}" class="mod-btn mod-btn-glass" style="background: #ffffff; border-color: var(--mod-border); color: var(--mod-text-main) !important; padding: 12px 30px;">
                    <i class="fas fa-newspaper text-xs text-red-600"></i>
                    <span>Buka Arsip Berita Selengkapnya</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Documentation (Dynamic) -->
    @if(isset($galery) && $galery->count() > 0)
        <section class="mod-section" style="background-color: #f1f5f9; padding-top: 70px;">
            <div class="modern-org-container">
                <div class="mod-section-header">
                    <span class="mod-tag-pill">Dokumentasi</span>
                    <h2 class="mod-section-title">Galeri Kegiatan &amp; Pertemuan</h2>
                    <p class="mod-section-desc">
                        Dokumentasi visual kongres nasional, lokakarya, dan kunjungan pengurus ke fasilitas klinik di berbagai daerah.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px;">
                    @foreach($galery->take(8) as $item)
                        <div style="position: relative; aspect-ratio: 4 / 3; border-radius: 16px; overflow: hidden; box-shadow: var(--mod-shadow-sm); border: 1px solid rgba(0,0,0,0.06);">
                            @if(file_exists(public_path($item->foto)))
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                            @elseif(file_exists(public_path('storage/' . $item->foto)))
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(2, 6, 23, 0.85)); padding: 12px 16px; color: #fff; font-size: 12px; font-weight: 600;">
                                {{ $item->judul ?? 'Dokumentasi ASKLIN' }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <a href="{{ route('galery') }}" class="mod-btn mod-btn-glass" style="background: #ffffff; border-color: var(--mod-border); color: var(--mod-text-main) !important;">
                        <i class="fas fa-images text-xs text-red-600"></i>
                        <span>Lihat Semua Dokumentasi</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Modern Dynamic Membership CTA Banner -->
    <section class="mod-section">
        <div class="modern-org-container">
            <div style="background: linear-gradient(135deg, #020617 0%, #0f172a 40%, #450a0a 100%); border-radius: var(--mod-radius-lg); padding: 55px; border: 1px solid rgba(220, 38, 38, 0.2); box-shadow: var(--mod-shadow-md), var(--mod-shadow-glow); display: flex; align-items: center; justify-content: space-between; gap: 36px; flex-wrap: wrap;">
                <div>
                    <span style="display: inline-block; padding: 4px 14px; border-radius: 9999px; background: rgba(220, 38, 38, 0.12); border: 1px solid rgba(220, 38, 38, 0.3); font-size: 11px; font-weight: 800; color: #fca5a5; text-transform: uppercase; margin-bottom: 14px;">
                        Bergabung Bersama Kami
                    </span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #ffffff; margin: 0 0 10px 0; letter-spacing: -0.4px;">
                        Jadikan Klinik Anda Bagian dari Jaringan Resmi ASKLIN
                    </h3>
                    <p style="font-size: 15px; color: #94a3b8; margin: 0; max-width: 640px; line-height: 1.7;">
                        Dapatkan fasilitas advokasi hukum, pembinaan kendali mutu, jejaring kemitraan, serta bimbingan perizinan klinik berjenjang di seluruh Indonesia.
                    </p>
                </div>
                <div style="display: flex; gap: 14px; shrink-0; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="font-size: 14px; padding: 14px 28px;">
                        <i class="fas fa-file-signature text-xs"></i>
                        <span>Daftar Calon Anggota</span>
                    </a>
                    <a href="{{ route('kontak') }}" class="mod-btn mod-btn-glass" style="font-size: 14px; padding: 14px 24px;">
                        <i class="fas fa-comment-dots text-xs text-red-400"></i>
                        <span>Hubungi Sekretariat</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

