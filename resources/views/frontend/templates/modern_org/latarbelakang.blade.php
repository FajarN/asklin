@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span>Tentang Kami</span>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Latar Belakang</span>
            </div>
            <h1 class="mod-inner-title">Latar Belakang &amp; Landasan Hukum</h1>
            <p class="mod-inner-sub">Dasar Historis, Mandat Parlemen, dan Payung Regulasi Penyelenggaraan ASKLIN</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.modern_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div class="mod-card">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--mod-border);">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 1px;">Regulasi &amp; Legitimasi</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--mod-text-main); margin: 0;">Latar Belakang Berdirinya ASKLIN</h2>
                    </div>
                </div>

                <div style="font-size: 15px; line-height: 1.85; color: var(--mod-text-main); text-align: justify; space-y: 18px;">
                    <p>
                        Asosiasi Klinik Indonesia (<strong>ASKLIN</strong>) adalah wadah resmi fasilitas kesehatan klinik untuk berhimpun dalam rangka meningkatkan kualitas pelayanan kesehatan serta kepatuhan regulasi di seluruh nusantara.
                    </p>

                    <div style="padding: 22px; background: #0f172a; border-radius: 14px; color: #fff; margin: 24px 0;">
                        <h4 style="font-size: 15px; font-weight: 800; color: #fca5a5; margin: 0 0 10px 0;">
                            <i class="fas fa-certificate mr-1.5"></i> Pelantikan Pengurus Pusat Pertama (07 Maret 2012)
                        </h4>
                        <p style="font-size: 13.5px; color: #cbd5e1; margin-bottom: 12px;">Dihadiri pimpinan lembaga negara dan kementerian terkait:</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px; font-size: 13px; color: #94a3b8;">
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> Ketua DPD RI</div>
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> Ketua Komisi IX DPR RI</div>
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> Kementerian Kesehatan RI</div>
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> Kementerian Kesra</div>
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> Kepala BKKBN Pusat</div>
                            <div><i class="fas fa-check text-red-400 mr-1.5"></i> +/- 200 Undangan Tokoh Kesehatan</div>
                        </div>
                    </div>

                    <p>
                        Melalui Rapat Dengar Pendapat dengan <strong>Komisi IX DPR RI tanggal 21 Maret 2012</strong>, parlemen menegaskan dukungan penuh terhadap ASKLIN untuk bermitra dengan pemerintah dalam mengawal implementasi BPJS Kesehatan, kendali mutu, dan kendali biaya klinik di Indonesia.
                    </p>

                    <!-- Legal Framework Grid -->
                    <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--mod-border);">
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 18px 0;">
                            <i class="fas fa-balance-scale text-red-600 mr-1.5"></i> Dasar Hukum Penyelenggaraan
                        </h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 12px;">
                                <strong style="color: var(--mod-primary-dark); font-size: 13.5px;">1. UU No. 36 Tahun 2009 tentang Kesehatan</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--mod-text-muted);">Dasar hukum utama penyelenggaraan fasilitas dan tenaga kesehatan.</p>
                            </div>
                            <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 12px;">
                                <strong style="color: var(--mod-primary-dark); font-size: 13.5px;">2. UU SJSN No. 40/2004 &amp; UU BPJS No. 24/2011</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--mod-text-muted);">Amanat keterlibatan asosiasi faskes dalam jaminan kesehatan nasional.</p>
                            </div>
                            <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 12px;">
                                <strong style="color: var(--mod-primary-dark); font-size: 13.5px;">3. Permenkes No. 09 Tahun 2014 tentang Klinik</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--mod-text-muted);">Pengawasan dan pembinaan bersama perhimpunan/asosiasi klinik.</p>
                            </div>
                            <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 12px;">
                                <strong style="color: var(--mod-primary-dark); font-size: 13.5px;">4. UU No. 25/2009 &amp; UU No. 8/1999</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--mod-text-muted);">Standar pelayanan publik serta perlindungan konsumen/pasien.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

