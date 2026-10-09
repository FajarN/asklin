@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span>Profil Organisasi</span>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Latar Belakang</span>
            </div>
            <h1 class="org-page-title">Latar Belakang Pendirian</h1>
            <p class="org-page-subtitle">Urgensi Keberadaan Asosiasi Klinik Indonesia &amp; Landasan Hukum Penyelenggaraan</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.custom_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <div class="org-content-card">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--org-border-light);">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--org-primary);">Urgensi Institusional</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--org-secondary); margin: 0;">Latar Belakang Keberadaan ASKLIN</h2>
                    </div>
                </div>

                <div style="font-size: 14.5px; line-height: 1.85; color: var(--org-text-main); text-align: justify; space-y: 18px;">
                    <p>
                        <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong> adalah wadah resmi fasilitas kesehatan klinik di seluruh wilayah Indonesia untuk berhimpun dalam rangka meningkatkan kualitas, mutu, dan keselamatan pelayanan kesehatan masyarakat.
                    </p>

                    <!-- Milestone Pelantikan -->
                    <div style="padding: 20px; background-color: var(--org-bg-body); border-radius: 10px; border: 1px solid var(--org-border); margin: 20px 0;">
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--org-primary-dark); margin: 0 0 10px 0;">
                            <i class="fas fa-certificate text-red-600 mr-1.5"></i> Pelantikan Pengurus Pusat Pertama (07 Maret 2012)
                        </h4>
                        <p style="margin: 0 0 8px 0; font-size: 13.5px;">Dihadiri secara resmi oleh jajaran pimpinan lembaga tinggi negara:</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 8px; font-size: 13px; font-weight: 600; color: var(--org-secondary);">
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> Ketua DPD Republik Indonesia</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> Ketua Komisi IX DPR RI</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> Kementerian Kesehatan RI</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> Kementerian Koordinator Kesra</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> Pimpinan BKKBN Pusat</div>
                            <div class="flex items-center gap-2"><i class="fas fa-check text-red-500"></i> +/- 200 Tokoh Kesehatan Nasional</div>
                        </div>
                    </div>

                    <p>
                        Melalui Rapat Dengar Pendapat dengan <strong>Komisi IX DPR RI tanggal 21 Maret 2012</strong>, parlemen memberikan dukungan penuh atas eksistensi ASKLIN untuk bermitra dengan pemerintah dalam mengawal tata kelola kendali mutu, kendali biaya, dan pembinaan klinik menuju penerapan Jaminan Kesehatan Nasional (JKN/BPJS Kesehatan).
                    </p>

                    <!-- Legal Framework -->
                    <div style="margin-top: 30px; padding-top: 24px; border-top: 1px solid var(--org-border);">
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--org-secondary); margin: 0 0 16px 0;">
                            <i class="fas fa-balance-scale text-red-600 mr-2"></i> Landasan Hukum Kelembagaan
                        </h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div style="padding: 14px; background: #fff; border: 1px solid var(--org-border); border-radius: 8px;">
                                <strong style="color: var(--org-primary-dark); font-size: 13px;">1. UU No. 36 Tahun 2009</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--org-text-muted);">Tentang Penyelenggaraan Kesehatan Nasional</p>
                            </div>
                            <div style="padding: 14px; background: #fff; border: 1px solid var(--org-border); border-radius: 8px;">
                                <strong style="color: var(--org-primary-dark); font-size: 13px;">2. UU No. 40 Tahun 2004 &amp; UU No. 24 Tahun 2011</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--org-text-muted);">Sistem Jaminan Sosial Nasional &amp; BPJS Kesehatan</p>
                            </div>
                            <div style="padding: 14px; background: #fff; border: 1px solid var(--org-border); border-radius: 8px;">
                                <strong style="color: var(--org-primary-dark); font-size: 13px;">3. Permenkes No. 09 Tahun 2014</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--org-text-muted);">Perubahan atas Permenkes 028/2011 tentang Klinik (Pasal 40 Ayat 2)</p>
                            </div>
                            <div style="padding: 14px; background: #fff; border: 1px solid var(--org-border); border-radius: 8px;">
                                <strong style="color: var(--org-primary-dark); font-size: 13px;">4. UU No. 25 Tahun 2009 &amp; UU No. 8 Tahun 1999</strong>
                                <p style="margin: 4px 0 0 0; font-size: 12.5px; color: var(--org-text-muted);">Pelayanan Publik &amp; Perlindungan Pasien / Konsumen</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

