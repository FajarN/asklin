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
                <span style="color: #fff; font-weight: 700;">Sejarah ASKLIN</span>
            </div>
            <h1 class="mod-inner-title">Sejarah Pendirian ASKLIN</h1>
            <p class="mod-inner-sub">Perjalanan Panjang Menyatukan Fasilitas Kesehatan Klinik di Indonesia Sejak 2011</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.modern_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">
            <div class="mod-card">

                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--mod-border);">
                    <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, var(--mod-primary), var(--mod-primary-dark)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 1px;">Kilas Sejarah</span>
                        <h2 style="font-size: 24px; font-weight: 800; color: var(--mod-text-main); margin: 0;">Kelahiran Asosiasi Klinik Indonesia</h2>
                    </div>
                </div>

                <div style="font-size: 15px; line-height: 1.9; color: var(--mod-text-main); text-align: justify; space-y: 20px;">
                    <p>
                        Pada awal pembentukan <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>, dimulai dari inisiasi visioner oleh <strong>dr. Eddi Junaidi, SpOG., SH., M.Kes.</strong> yang saat itu menjabat sebagai Ketua Ikatan Dokter Indonesia (IDI) Cabang Jakarta Timur periode 2009–2012 dan Koordinator Program Pengurus Besar IDI (PB IDI) periode 2009–2012.
                    </p>

                    <p>
                        Pemikiran pembentukan organisasi faskes ini muncul sejak <strong>Maret 2011</strong> seiring terbitnya regulasi Kementerian Kesehatan Republik Indonesia tentang Klinik Nomor <code>028/MENKES/PER/I/2011</code> serta pengesahan Undang-Undang Sistem Jaminan Sosial Nasional (SJSN) Nomor 40 Tahun 2004.
                    </p>

                    <div style="margin: 28px 0; padding: 24px; background: linear-gradient(135deg, #020617, #0f172a); border-left: 4px solid var(--mod-primary-light); border-radius: 0 16px 16px 0; color: #ffffff;">
                        <div style="display: flex; align-items: flex-start; gap: 16px;">
                            <i class="fas fa-flag text-2xl text-red-400 mt-1"></i>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 800; color: #fca5a5; margin: 0 0 6px 0;">
                                    Momentum Deklarasi: 01 Agustus 2011
                                </h4>
                                <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.7;">
                                    Bulan Agustus, tepatnya tanggal <strong>01 Agustus 2011</strong>, menjadi titik tolak historis berdirinya ASKLIN. Pada saat itu, UU BPJS masih dalam pembahasan di parlemen, dan ASKLIN hadir mengisi peran strategis asosiasi fasilitas pelayanan kesehatan sebagaimana diamanatkan dalam penjelasan Pasal 11 huruf d UU BPJS.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p>
                        Embrio kelahiran ASKLIN merupakan wujud kepedulian bersama pimpinan klinik untuk membantu pemerintah dalam membina, mengawasi, serta mengangkat standar pelayanan fasilitas kesehatan klinik demi menjamin mutu serta keselamatan pasien di tanah air.
                    </p>

                    <p>
                        Sejak didirikan, para inisiator menyusun aturan dasar organisasi (AD/ART) dan menempuh proses pengakuan hukum resmi hingga terbitnya Surat Keputusan Pengesahan Badan Hukum dari <strong>Kementerian Hukum dan HAM Republik Indonesia pada tanggal 04 Mei 2012</strong>.
                    </p>
                </div>

                <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--mod-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--mod-text-muted);">
                        <i class="fas fa-check-circle text-red-600"></i>
                        <span>Legalitas Kemenkumham RI Terverifikasi</span>
                    </div>
                    <div>
                        <a href="{{ route('visimisi') }}" class="mod-btn mod-btn-primary" style="padding: 10px 22px;">
                            <span>Lanjut ke Visi &amp; Misi</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

