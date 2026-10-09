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
                <span style="color: #ffffff; font-weight: 700;">Sejarah ASKLIN</span>
            </div>
            <h1 class="org-page-title">Sejarah Singkat ASKLIN</h1>
            <p class="org-page-subtitle">A Brief History of Asosiasi Klinik Indonesia — Sejak 01 Agustus 2011</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.custom_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">
            <div class="org-content-card">

                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 26px; padding-bottom: 20px; border-bottom: 1px solid var(--org-border-light);">
                    <div style="width: 54px; height: 54px; border-radius: 12px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--org-primary);">Kilas Balik Pendirian</span>
                        <h2 style="font-size: 24px; font-weight: 800; color: var(--org-secondary); margin: 0;">Lahirnya Asosiasi Klinik Indonesia</h2>
                    </div>
                </div>

                <div style="font-size: 15px; line-height: 1.85; color: var(--org-text-main); text-align: justify; space-y: 20px;">
                    <p>
                        Pada awal pembentukan <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>, dimulai dari inisiasi oleh <strong>dr. Eddi Junaidi, SpOG., SH., M.Kes.</strong> yang saat itu menjabat sebagai Ketua Ikatan Dokter Indonesia (IDI) Cabang Jakarta Timur periode 2009–2012 sekaligus sebagai Koordinator Program Pengurus Besar Ikatan Dokter Indonesia (PB IDI) periode 2009–2012.
                    </p>

                    <p>
                        Pemikiran pembentukan wadah klinik ini muncul sejak <strong>Maret 2011</strong> seiring terbitnya regulasi pemerintah yaitu Peraturan Menteri Kesehatan tentang Klinik Nomor <code>028/MENKES/PER/I/2011</code> tanggal 4 Januari 2011, serta berlakunya Undang-Undang Sistem Jaminan Sosial Nasional (SJSN) Nomor 40 Tahun 2004.
                    </p>

                    <div style="margin: 28px 0; padding: 24px; background-color: var(--org-primary-soft); border-left: 4px solid var(--org-primary); border-radius: 0 12px 12px 0;">
                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <i class="fas fa-calendar-star text-2xl text-red-700 mt-1"></i>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 800; color: var(--org-primary-dark); margin: 0 0 6px 0;">
                                    Momentum Penting: 01 Agustus 2011
                                </h4>
                                <p style="margin: 0; font-size: 14px; color: var(--org-secondary); line-height: 1.7;">
                                    Bulan Agustus, tepatnya tanggal <strong>01 Agustus 2011</strong>, menjadi momentum bersejarah dideklarasikannya Asosiasi Klinik Indonesia oleh para pendiri. Kelahiran ASKLIN menjadi wujud aktualisasi regulasi Kementerian Kesehatan dan amanat Undang-Undang BPJS (Pasal 11 huruf d) tentang peran strategis asosiasi fasilitas pelayanan kesehatan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p>
                        Embrio kelahiran ASKLIN berangkat dari kesadaran murni para pemilik dan penanggung jawab klinik di tanah air untuk bersatu padu membantu pemerintah dalam membina, mengawasi, serta mengangkat standar pelayanan fasilitas kesehatan klinik agar mampu memenuhi ekspektasi masyarakat.
                    </p>

                    <p>
                        Sejak didirikan, para inisiator menyusun Anggaran Dasar (AD) dan Anggaran Rumah Tangga (ART) secara demokratis dan inklusif. Proses legal formal kelembagaan ditempuh secara saksama hingga akhirnya memperoleh Surat Keputusan Pengesahan Badan Hukum resmi dari <strong>Kementerian Hukum dan HAM Republik Indonesia pada tanggal 04 Mei 2012</strong>.
                    </p>
                </div>

                <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--org-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--org-text-muted);">
                        <i class="fas fa-certificate text-red-600"></i>
                        <span>Status: Organisasi Berbadan Hukum Resmi RI</span>
                    </div>
                    <div>
                        <a href="{{ route('visimisi') }}" class="org-btn org-btn-primary" style="padding: 10px 20px;">
                            <span>Lanjut ke Visi &amp; Misi</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

