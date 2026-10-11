@extends('frontend.templates.custom_org.layouts.app')

@push('css')
<style>
    /* Scope styles for Rakernas Page in Custom Org Template - Grey Gradient Theme */
    .rkn-org-page-header {
        background: linear-gradient(135deg, #94a3b8 0%, #475569 45%, #1e293b 100%);
        padding: 44px 0 38px;
        color: #ffffff;
        border-bottom: 3px solid #334155;
        position: relative;
    }
    .rkn-org-page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.55;
        pointer-events: none;
    }
    .rkn-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .rkn-badge-gold {
        background: rgba(251, 191, 36, 0.2);
        color: #fde68a;
        border: 1px solid rgba(251, 191, 36, 0.4);
    }
    .rkn-badge-white {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }
    .rkn-badge-grey {
        background: #334155;
        color: #ffffff;
        border: 1px solid #64748b;
    }
    .rkn-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        padding: 28px;
        margin-bottom: 36px;
    }
    .dark .rkn-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.3);
    }
    .rkn-subcard {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .dark .rkn-subcard {
        background: #0f172a;
        border-color: #334155;
    }
    .rkn-subcard:hover {
        transform: translateY(-3px);
        border-color: #64748b;
    }
    .rkn-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 26px;
        border-radius: 10px;
        background: linear-gradient(135deg, #475569, #1e293b);
        color: #ffffff !important;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid #334155;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30, 41, 59, 0.25);
        transition: all 0.25s ease;
    }
    .rkn-btn-primary * {
        color: #ffffff !important;
    }
    .rkn-btn-primary:hover {
        background: linear-gradient(135deg, #334155, #0f172a);
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(30, 41, 59, 0.35);
    }
    .rkn-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 10px;
        background: transparent;
        color: #334155 !important;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none !important;
        border: 2px solid #475569;
        transition: all 0.25s ease;
    }
    .dark .rkn-btn-outline {
        color: #cbd5e1 !important;
        border-color: #64748b;
    }
    .rkn-btn-outline:hover {
        background: #334155;
        color: #ffffff !important;
        border-color: #334155;
    }
    .dark .rkn-btn-outline:hover {
        background: #475569;
        color: #ffffff !important;
    }
    .rkn-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #1e293b;
        padding: 10px 14px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .dark .rkn-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    .rkn-input:focus {
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51, 65, 85, 0.15);
    }
    .rkn-label {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 6px;
    }
    .dark .rkn-label {
        color: #cbd5e1;
    }
    .rkn-label span.req {
        color: #dc2626;
    }
    .rkn-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #cbd5e1;
        background: #e2e8f0;
        margin: 0 auto 12px;
        display: block;
    }
    .rkn-avatar-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 2px solid #cbd5e1;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 12px;
    }
    .rkn-subnav-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569 !important;
        text-decoration: none !important;
        white-space: nowrap;
        background: #f1f5f9;
        transition: all 0.2s ease;
    }
    .rkn-subnav-link:hover {
        background: #e2e8f0;
        color: #0f172a !important;
    }
    .rkn-subnav-link.active-pill {
        background: #334155 !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    .rkn-subnav-link.active-pill i {
        color: #ffffff !important;
    }
    .rkn-gallery-img {
        width: 100%;
        height: 190px;
        border-radius: 10px;
        object-fit: cover;
        display: block;
    }
</style>
@endpush

@section('content')

    <!-- Institutional Page Header & Periode Switcher - Gradient Abu-Abu Muda ke Abu-Abu Tua -->
    <div class="rkn-org-page-header">
        <div class="custom-org-container" style="position: relative; z-index: 10;">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}" style="color: #f1f5f9;">Beranda</a>
                <span style="color: #cbd5e1;">/</span>
                <span style="color: #f1f5f9;">Program &amp; Acara</span>
                <span style="color: #cbd5e1;">/</span>
                <span style="color: #ffffff; font-weight: 700;">Rapat Kerja Nasional (RAKERNAS)</span>
            </div>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 20px; margin-top: 16px;">
                <div style="max-width: 780px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;">
                        <span class="rkn-badge rkn-badge-white">
                            <i class="fas fa-users-cog"></i> RAKERNAS ASKLIN
                        </span>
                        <span class="rkn-badge rkn-badge-gold">
                            <i class="fas fa-award"></i> RAKERNAS KE-{{ $selectedPeriode->rakernas_ke ?? '1' }}
                        </span>
                        @if($isPeriodeActive)
                            <span class="rkn-badge rkn-badge-grey">
                                <i class="fas fa-bolt mr-1"></i> Pendaftaran Buka
                            </span>
                        @endif
                    </div>

                    <h1 class="org-page-title" style="margin-bottom: 8px; font-size: clamp(24px, 3.5vw, 36px); font-weight: 900; color: #ffffff;">
                        {{ $selectedPeriode->nama ?? 'Rapat Kerja Nasional (RAKERNAS) ASKLIN' }}
                    </h1>
                    <p class="org-page-subtitle" style="margin-bottom: 0; font-size: 14px; color: #f1f5f9; line-height: 1.5;">
                        {{ $selectedPeriode->tema ?? 'Sinergi Penguatan Mutu dan Tata Kelola Fasilitas Pelayanan Kesehatan Primer Menuju Transformasi Kesehatan Nasional' }}
                    </p>
                </div>

                <!-- Dropdown Riwayat Periode Rakernas -->
                <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 12px 18px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.25); min-width: 260px;">
                    <label for="riwayat_rakernas_select" style="display: block; font-size: 11px; font-weight: 700; color: #f1f5f9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        <i class="fas fa-history mr-1"></i> Riwayat Penyelenggaraan:
                    </label>
                    <select id="riwayat_rakernas_select" 
                            style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); background: #1e293b; color: #ffffff; font-size: 12px; font-weight: 700; cursor: pointer; outline: none;" 
                            onchange="window.location.href = '{{ route('rakernas') }}?periode_id=' + this.value">
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->id }}" {{ ($selectedPeriode && $selectedPeriode->id == $p->id) ? 'selected' : '' }}>
                                {{ $p->nama }} (Periode {{ $p->periode_kepengurusan }}) {{ $p->status == '1' ? '★ Aktif' : '• Arsip' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Subnav Jump Links -->
    <div style="background: #ffffff; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 40; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div class="custom-org-container">
            <div style="display: flex; gap: 8px; overflow-x: auto; padding: 10px 0; scrollbar-width: none;">
                <a href="#tentang" class="rkn-subnav-link"><i class="fas fa-info-circle text-xs"></i> Tentang RAKERNAS</a>
                <a href="#tamu-undangan" class="rkn-subnav-link"><i class="fas fa-users text-xs"></i> Pihak Diundang</a>
                <a href="#venue-hotel" class="rkn-subnav-link"><i class="fas fa-hotel text-xs"></i> Venue &amp; Akomodasi</a>
                <a href="#pendaftaran" class="rkn-subnav-link active-pill"><i class="fas fa-user-plus text-xs"></i> Pendaftaran Peserta</a>
                <a href="#galeri" class="rkn-subnav-link"><i class="fas fa-images text-xs"></i> Galeri Foto</a>
                <a href="#kontak" class="rkn-subnav-link"><i class="fas fa-phone-alt text-xs"></i> Kontak Panitia</a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section" style="padding-top: 30px;">
        <div class="custom-org-container">

            @if(!$isPeriodeActive)
                <!-- Notice Status Periode Nonaktif -->
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #f59e0b; padding: 16px 20px; border-radius: 10px; margin-bottom: 30px; display: flex; align-items: flex-start; gap: 14px;">
                    <i class="fas fa-archive text-amber-600 text-xl" style="margin-top: 2px;"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #92400e; margin: 0 0 4px;">Informasi Status Periode (Arsip RAKERNAS)</h4>
                        <p style="font-size: 12.5px; color: #78350f; margin: 0; line-height: 1.5;">
                            Anda sedang melihat riwayat data untuk <strong>{{ $selectedPeriode->nama ?? 'RAKERNAS ASKLIN' }} (Periode {{ $selectedPeriode->periode_kepengurusan ?? '-' }})</strong>. Pendaftaran publik untuk periode ini dinonaktifkan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Key Fact Highlight Strip -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 36px;">
                <div class="rkn-subcard" style="display: flex; align-items: center; gap: 12px; border-left: 4px solid #475569;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #f1f5f9; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600;">Jadwal Pelaksanaan</div>
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                            {{ $selectedPeriode->tanggal_formatted ?? 'Akan Diumumkan' }}
                        </div>
                    </div>
                </div>

                <div class="rkn-subcard" style="display: flex; align-items: center; gap: 12px; border-left: 4px solid #0284c7;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600;">Tempat &amp; Kota</div>
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}">
                            {{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}
                        </div>
                    </div>
                </div>

                <div class="rkn-subcard" style="display: flex; align-items: center; gap: 12px; border-left: 4px solid #7c3aed;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600;">Batas Pendaftaran</div>
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                            {{ $selectedPeriode && $selectedPeriode->tgl_tutup_daftar ? $selectedPeriode->tgl_tutup_daftar->translatedFormat('d M Y') : 'Hingga Kuota Penuh' }}
                        </div>
                    </div>
                </div>

                <div class="rkn-subcard" style="display: flex; align-items: center; gap: 12px; border-left: 4px solid #d97706;">
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600;">Ketentuan Utama</div>
                        <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                            Nama Klinik Wajib Diisi
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: TENTANG RAKERNAS -->
            <section id="tentang" style="margin-bottom: 40px; scroll-margin-top: 80px;">
                <div class="rkn-card">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #f1f5f9; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: #0f172a;">Tentang Rapat Kerja Nasional (RAKERNAS) ASKLIN</h2>
                            <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0;">Musyawarah Pengurus Pusat Maksimal 2 Kali dalam Satu Periode Kepengurusan</p>
                        </div>
                    </div>

                    <div style="font-size: 13.5px; line-height: 1.8; color: #334155; text-align: justify; margin-bottom: 24px;">
                        <p style="margin-bottom: 12px;">
                            <strong>Rapat Kerja Nasional (RAKERNAS) ASKLIN</strong> dilaksanakan oleh Pengurus Pusat maksimal 2 (dua) kali dalam satu periode kepengurusan (misalnya periode 2023–2028 memiliki Rakernas ke-1 dan Rakernas ke-2). 
                        </p>
                        <p style="margin-bottom: 12px;">
                            Kegiatan ini berfokus pada evaluasi program kerja nasional, perumusan rekomendasi advokasi tarif dan regulasi klinik, serta sinergi erat dengan Kementerian Kesehatan RI, BPJS Kesehatan, dan Pemerintah Daerah dalam transformasi sistem kesehatan nasional.
                        </p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                        <div class="rkn-subcard">
                            <h4 style="font-size: 14px; font-weight: 800; color: #334155; margin: 0 0 6px;">Konsolidasi Klinik Indonesia</h4>
                            <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.6;">Menghubungkan suara pengelola klinik primer dari seluruh Komda dan Komcab demi penguatan fasilitas kesehatan swasta.</p>
                        </div>

                        <div class="rkn-subcard">
                            <h4 style="font-size: 14px; font-weight: 800; color: #0284c7; margin: 0 0 6px;">Sinergi Pemangku Kebijakan</h4>
                            <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.6;">Dialog langsung bersama Kementerian Kesehatan, Direksi BPJS Kesehatan, dan Kepala Daerah mengenai tarif layanan dan akreditasi.</p>
                        </div>

                        <div class="rkn-subcard">
                            <h4 style="font-size: 14px; font-weight: 800; color: #7c3aed; margin: 0 0 6px;">Transformasi Digital Klinik</h4>
                            <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.6;">Percepatan kepatuhan integrasi Rekam Medis Elektronik (RME) SatuSehat Kemenkes serta persiapan survei akreditasi klinik.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: PIHAK YANG DIUNDANG -->
            <section id="tamu-undangan" style="margin-bottom: 40px; scroll-margin-top: 80px;">
                <div class="rkn-card">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: #0f172a;">Pihak yang Diundang &amp; Sponsor Resmi</h2>
                            <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0;">Kementerian Kesehatan RI, BPJS Kesehatan, Pemerintah Daerah, dan Mitra Industri</p>
                        </div>
                    </div>

                    @if($undangan->isNotEmpty())
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                            @foreach($undangan as $u)
                                <div class="rkn-subcard" style="text-align: center;">
                                    @if($u->foto)
                                        <img src="{{ asset('storage/' . $u->foto) }}" alt="{{ $u->nama }}" class="rkn-avatar" onerror="this.outerHTML='<div class=\'rkn-avatar-icon\'><i class=\'fas fa-user\'></i></div>'">
                                    @else
                                        <div class="rkn-avatar-icon"><i class="fas fa-user-tie"></i></div>
                                    @endif

                                    <span style="display: inline-block; background: #e2e8f0; color: #334155; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 6px; text-transform: uppercase; margin-bottom: 6px;">
                                        {{ $u->kategori }}
                                    </span>

                                    <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">{{ $u->nama }}</h4>
                                    <p style="font-size: 12px; color: #64748b; margin: 0 0 4px;">{{ $u->jabatan }}</p>

                                    @if($u->instansi)
                                        <div style="font-size: 11px; font-weight: 700; color: #334155;">{{ $u->instansi }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                            <div class="rkn-subcard" style="text-align: center;">
                                <div class="rkn-avatar-icon" style="background: #fee2e2; color: #dc2626;"><i class="fas fa-landmark"></i></div>
                                <span style="background: #fee2e2; color: #991b1b; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">Kementerian</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 6px 0 2px;">Kementerian Kesehatan RI</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Menteri Kesehatan &amp; Dirjen Yankes</p>
                            </div>

                            <div class="rkn-subcard" style="text-align: center;">
                                <div class="rkn-avatar-icon" style="background: #e0f2fe; color: #0284c7;"><i class="fas fa-heartbeat"></i></div>
                                <span style="background: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">BPJS Kesehatan</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 6px 0 2px;">Direksi BPJS Kesehatan</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Direktur Jaminan Pelayanan Kesehatan</p>
                            </div>

                            <div class="rkn-subcard" style="text-align: center;">
                                <div class="rkn-avatar-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-award"></i></div>
                                <span style="background: #fef3c7; color: #b45309; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">Pemerintah Daerah</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 6px 0 2px;">Gubernur &amp; Kepala Daerah</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Pimpinan Wilayah Tuan Rumah Acara</p>
                            </div>

                            <div class="rkn-subcard" style="text-align: center;">
                                <div class="rkn-avatar-icon" style="background: #f1f5f9; color: #334155;"><i class="fas fa-handshake"></i></div>
                                <span style="background: #e2e8f0; color: #334155; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">Mitra Sponsor</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 6px 0 2px;">Industri Farmasi &amp; Alkes</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Mitra Resmi Pelayanan Klinik Nasional</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- SECTION 3: VENUE & HOTEL REKANAN -->
            <section id="venue-hotel" style="margin-bottom: 40px; scroll-margin-top: 80px;">
                <div class="rkn-card">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-hotel"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: #0f172a;">Tempat Acara &amp; Akomodasi Hotel Rekanan</h2>
                            <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0;">Informasi Grand Ballroom, Panduan Rute, dan Penginapan Khusus Peserta</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        <div class="rkn-subcard">
                            <div style="font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 6px;">
                                <i class="fas fa-map-marked-alt mr-1"></i> Venue Utama Penyelenggaraan
                            </div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 8px;">
                                {{ $selectedPeriode->tempat ?? 'Grand Ballroom Penyelenggaraan' }}
                            </h3>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin: 0 0 16px;">
                                {{ $selectedPeriode->alamat_lengkap ?? 'Lokasi strategis di pusat kota dengan fasilitas sidang representatif dan area pameran kesehatan.' }}
                            </p>
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 12px; font-size: 12px; color: #334155;">
                                <i class="fas fa-city mr-1" style="color: #475569;"></i> Kota: <strong>{{ $selectedPeriode->kota ?? 'Jakarta' }}</strong>
                            </div>
                        </div>

                        <div class="rkn-subcard">
                            <div style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase; margin-bottom: 6px;">
                                <i class="fas fa-bed mr-1"></i> Rekomendasi Hotel &amp; Penginapan
                            </div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 8px;">
                                Hotel Berbintang Rekanan Panitia
                            </h3>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin: 0 0 16px;">
                                Panitia bekerja sama dengan jaringan hotel di sekitar lokasi acara untuk memberikan rate korporat khusus bagi seluruh delegasi klinik.
                            </p>
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 12px;">
                                <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Panitia%20RAKERNAS,%20saya%20ingin%20info%20rekomendasi%20hotel%20peserta" target="_blank" class="rkn-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                                    <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> Info Booking Hotel via Panitia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: FORMULIR PENDAFTARAN PESERTA -->
            <section id="pendaftaran" style="margin-bottom: 40px; scroll-margin-top: 80px;">
                <div class="rkn-card" style="border: 2px solid #475569;">
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 18px; margin-bottom: 22px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
                        <div>
                            <span style="display: inline-block; background: #334155; color: #ffffff; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                                <i class="fas fa-edit mr-1"></i> Form Pendaftaran Mandiri
                            </span>
                            <h2 style="font-size: 22px; font-weight: 900; color: #0f172a; margin: 0;">
                                Formulir Pendaftaran Peserta RAKERNAS ASKLIN
                            </h2>
                            <p style="font-size: 12.5px; color: #475569; margin: 4px 0 0;">
                                Pendaftaran otomatis terhubung ke: <strong>{{ $activePeriode->nama ?? 'RAKERNAS ASKLIN' }}</strong> (Periode {{ $activePeriode->periode_kepengurusan ?? '2023–2028' }})
                            </p>
                        </div>

                        @if(!$activePeriode)
                            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; font-size: 12px; font-weight: 700; padding: 8px 16px; border-radius: 8px;">
                                <i class="fas fa-lock mr-1"></i> Pendaftaran Belum Dibuka
                            </div>
                        @endif
                    </div>

                    @if(!$activePeriode)
                        <div style="text-align: center; padding: 40px 20px;">
                            <i class="fas fa-calendar-times text-amber-500" style="font-size: 48px; margin-bottom: 14px;"></i>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 6px;">Pendaftaran Sedang Ditutup</h3>
                            <p style="font-size: 13px; color: #64748b; max-width: 500px; margin: 0 auto;">
                                Saat ini tidak ada periode RAKERNAS yang berstatus aktif untuk pendaftaran publik. Silakan nantikan pengumuman resmi dari Pengurus Pusat ASKLIN.
                            </p>
                        </div>
                    @else
                        <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px 18px; margin-bottom: 22px; font-size: 12.5px; line-height: 1.6; color: #334155;">
                            <i class="fas fa-info-circle mr-1" style="color: #475569;"></i>
                            <strong>Petunjuk Registrasi:</strong> Pendaftaran RAKERNAS bersifat mandiri dan ringkas (tanpa delegasi formal/surat mandat). 
                            <span style="font-weight: 700; text-decoration: underline; color: #1e293b;">Nama Klinik wajib diisi</span> sebagai salah satu data utama pendaftaran.
                        </div>

                        <form id="rakernasRegisterFormOrg" action="{{ route('rakernas.daftar') }}" method="POST">
                            @csrf

                            <!-- Highlight Wajib: NAMA KLINIK -->
                            <div style="background: #f8fafc; border: 2px solid #475569; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                                <label for="nama_klinik_org" class="rkn-label" style="color: #1e293b; font-size: 12px; display: flex; justify-content: space-between;">
                                <span><i class="fas fa-clinic-medical mr-1"></i> Nama Klinik <span class="req">*</span></span>
                                    <span style="background: #334155; color: #fff; font-size: 9.5px; padding: 2px 8px; border-radius: 9999px;">DATA UTAMA WAJIB</span>
                                </label>
                                <input type="text" name="nama_klinik" id="nama_klinik_org" class="rkn-input" style="font-size: 14px; font-weight: 700;" required placeholder="Contoh: Klinik Pratama Sehat Mandiri / Klinik Utama Permata">
                                <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Nama klinik ini akan tertera pada kartu pendaftaran, ID card, dan sertifikat resmi.</span>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 20px;">
                                <div>
                                    <label for="nama_peserta_org" class="rkn-label">Nama Lengkap Peserta <span class="req">*</span></label>
                                    <input type="text" name="nama_peserta" id="nama_peserta_org" class="rkn-input" required placeholder="Nama lengkap &amp; gelar">
                                </div>

                                <div>
                                    <label for="jabatan_org" class="rkn-label">Jabatan di Klinik</label>
                                    <input type="text" name="jabatan" id="jabatan_org" class="rkn-input" placeholder="Pimpinan Klinik / Dokter / Penanggung Jawab">
                                </div>

                                <div>
                                    <label for="no_hp_org" class="rkn-label">No. WhatsApp / HP <span class="req">*</span></label>
                                    <input type="text" name="no_hp" id="no_hp_org" class="rkn-input" required placeholder="08123456789">
                                </div>

                                <div>
                                    <label for="email_org" class="rkn-label">Email Aktif <span class="req">*</span></label>
                                    <input type="email" name="email" id="email_org" class="rkn-input" required placeholder="nama@klinik.com">
                                </div>

                                <div>
                                    <label for="id_provinsi_org" class="rkn-label">Provinsi Asal</label>
                                    <select name="id_provinsi" id="id_provinsi_org" class="rkn-input">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsi as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="cabang_org" class="rkn-label">Kabupaten / Kota / Cabang</label>
                                    <input type="text" name="cabang" id="cabang_org" class="rkn-input" placeholder="Kota / Cabang ASKLIN">
                                </div>

                                <div>
                                    <label for="status_kehadiran_org" class="rkn-label">Rencana Kehadiran</label>
                                    <select name="status_kehadiran" id="status_kehadiran_org" class="rkn-input">
                                        <option value="Hadir">Hadir Langsung di Lokasi (Fisik)</option>
                                        <option value="Online">Mengikuti via Zoom (Online)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="alamat_klinik_org" class="rkn-label">Alamat Lengkap Klinik</label>
                                    <input type="text" name="alamat_klinik" id="alamat_klinik_org" class="rkn-input" placeholder="Alamat operasional fasilitas klinik">
                                </div>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <label for="kebutuhan_khusus_org" class="rkn-label">Catatan / Kebutuhan Khusus (Opsional)</label>
                                <textarea name="kebutuhan_khusus" id="kebutuhan_khusus_org" rows="2" class="rkn-input" placeholder="Catatan akomodasi, konsumsi, atau pertanyaan ke panitia"></textarea>
                            </div>

                            <div style="border-top: 1px solid #e2e8f0; padding-top: 18px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
                                <div style="font-size: 11.5px; color: #64748b;">
                                    <i class="fas fa-shield-alt text-slate-500 mr-1"></i> Data Anda tersimpan resmi dalam sistem pendaftaran ASKLIN.
                                </div>
                                <button type="submit" id="btnSubmitDaftarOrg" class="rkn-btn-primary" style="padding: 13px 32px; font-size: 14px;">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Pendaftaran RAKERNAS
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </section>

            <!-- SECTION 5: GALERI & DOKUMENTASI -->
            <section id="galeri" style="margin-bottom: 40px; scroll-margin-top: 80px;">
                <div class="rkn-card">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: #0f172a;">Galeri &amp; Dokumentasi Lokasi RAKERNAS</h2>
                            <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0;">Dokumentasi Venue, Hotel Rekanan, dan Suasana Penyelenggaraan</p>
                        </div>
                    </div>

                    @if($galeriVenue->isNotEmpty())
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                            @foreach($galeriVenue as $g)
                                <div class="rkn-subcard" style="padding: 12px; overflow: hidden;">
                                    <div style="overflow: hidden; border-radius: 10px; margin-bottom: 10px;">
                                        <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->judul }}" class="rkn-gallery-img" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">
                                    </div>
                                    <div style="font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase;">{{ $g->kategori }}</div>
                                    <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 3px 0 0;">{{ $g->judul }}</h4>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                            <div class="rkn-subcard" style="text-align: center; padding: 30px;">
                                <i class="fas fa-landmark text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Grand Ballroom RAKERNAS</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Ruang sidang utama kapasitas hingga 1.000 delegasi</p>
                            </div>
                            <div class="rkn-subcard" style="text-align: center; padding: 30px;">
                                <i class="fas fa-hotel text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Hotel Rekanan Resmi</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Akomodasi penginapan nyaman dekat lokasi konvensi</p>
                            </div>
                            <div class="rkn-subcard" style="text-align: center; padding: 30px;">
                                <i class="fas fa-clinic-medical text-slate-500" style="font-size: 36px; margin-bottom: 10px;"></i>
                                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Expo &amp; Pameran Kesehatan</h4>
                                <p style="font-size: 12px; color: #64748b; margin: 0;">Booth pameran industri farmasi, alkes, dan RME klinik</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- SECTION 6: KONTAK & HELPDESK -->
            <section id="kontak" style="scroll-margin-top: 80px;">
                <div class="rkn-card" style="background: linear-gradient(135deg, #475569 0%, #1e293b 100%); color: #ffffff;">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                        <div>
                            <span style="color: #cbd5e1; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                <i class="fas fa-headset mr-1"></i> Layanan Bantuan Panitia
                            </span>
                            <h3 style="font-size: 18px; font-weight: 800; color: #ffffff; margin: 6px 0;">
                                Butuh Informasi Tambahan Terkait RAKERNAS ASKLIN?
                            </h3>
                            <p style="font-size: 13px; color: #e2e8f0; margin: 0;">
                                Sekretariat Panitia Pelaksana siap membantu pertanyaan pendaftaran, akomodasi, dan panduan perjalanan.
                            </p>
                        </div>
                        <a href="https://api.whatsapp.com/send/?phone=6281245999923&text=Halo%20Sekretariat%20RAKERNAS%20ASKLIN,%20saya%20ingin%20bertanya%20mengenai%20acara%20ini" target="_blank" class="rkn-btn-primary" style="background: #25d366 !important; border-color: #22c55e !important; box-shadow: 0 4px 15px rgba(37,211,102,0.35);">
                            <i class="fab fa-whatsapp" style="font-size: 17px; color: #fff !important;"></i> Chat WhatsApp Panitia
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rakernasRegisterFormOrg');
    const submitBtn = document.getElementById('btnSubmitDaftarOrg');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate nama_klinik
            const inputKlinik = document.getElementById('nama_klinik_org');
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
