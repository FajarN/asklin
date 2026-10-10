@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Institutional Breadcrumbs & Header -->
    <div class="org-inner-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Portal Anggota</span>
            </div>
            <h1 class="org-inner-title">Profil Fasilitas Pelayanan Kesehatan</h1>
            <p class="org-inner-subtitle">Panel Pengelolaan Informasi &amp; Status Legalitas Keanggotaan ASKLIN</p>
        </div>
    </div>

    <!-- Main Member Profile Content -->
    <section class="org-section" style="padding: 40px 0 80px 0;">
        <div class="custom-org-container">

            @if(!empty($anggota->id))

                <!-- Member Subnavigation Menu -->
                <div class="org-profile-tabs">
                    <a href="{{ route('user.profile') }}" class="org-profile-tab active">
                        <i class="fas fa-hospital text-sm"></i>
                        <span>Profil Klinik</span>
                    </a>
                    <a href="{{ route('tagihan') }}" class="org-profile-tab">
                        <i class="fas fa-file-invoice-dollar text-sm"></i>
                        <span>Iuran &amp; Pembayaran</span>
                    </a>
                    @if($anggota->status == 'approved')
                        <a href="{{ route('sertifikat') }}" class="org-profile-tab">
                            <i class="fas fa-certificate text-sm"></i>
                            <span>E-Sertifikat Anggota</span>
                        </a>
                    @endif
                </div>

                <!-- Process Alert Banners -->
                @if($anggota->status == 'proses')
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--org-radius-md); padding: 22px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #d97706; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; shrink-0;">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 800; color: #d97706; margin: 0 0 4px 0;">Pemberkasan Data Umum Belum Lengkap</h4>
                                <p style="font-size: 13px; color: var(--org-text-muted); margin: 0;">Silakan melengkapi data profil klinik dan mengunggah dokumen perizinan operasional (SIO).</p>
                            </div>
                        </div>
                        <a href="{{ route('pendaftaran.anggota') }}" class="org-btn org-btn-primary" style="padding: 10px 22px;">
                            <i class="fas fa-edit text-xs"></i>
                            <span>Lengkapi Data Umum</span>
                        </a>
                    </div>
                @elseif($anggota->status == 'create_dokter')
                    <div style="background: rgba(2, 132, 199, 0.1); border: 1px solid rgba(2, 132, 199, 0.3); border-radius: var(--org-radius-md); padding: 22px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #0284c7; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; shrink-0;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 800; color: #0284c7; margin: 0 0 4px 0;">Tahap Pengisian SDM Medis</h4>
                                <p style="font-size: 13px; color: var(--org-text-muted); margin: 0;">Lengkapi susunan dokter penanggung jawab dan tenaga medis pendukung klinik Anda.</p>
                            </div>
                        </div>
                        <a href="{{ route('pendaftaran.sdm') }}" class="org-btn org-btn-primary" style="padding: 10px 22px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Lengkapi Data SDM</span>
                        </a>
                    </div>
                @endif

                <!-- Clinic Profile Header Card -->
                <div class="org-profile-header-card">
                    <div class="org-profile-info">
                        @if($anggota->logo && file_exists(public_path('images/file/' . $anggota->logo)))
                            <img src="{{ asset('images/file/' . $anggota->logo) }}" alt="Logo Klinik" class="org-profile-logo">
                        @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                            <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="Logo Default" class="org-profile-logo" style="object-fit: contain; padding: 10px;">
                        @else
                            <div class="org-profile-logo" style="display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--org-primary); font-weight: 800;">
                                <i class="fas fa-clinic-medical"></i>
                            </div>
                        @endif

                        <div>
                            <span class="org-tag-pill" style="margin-bottom: 6px;">Fasilitas Pelayanan Kesehatan</span>
                            <h2 class="org-profile-name">{{ $anggota->nama_klinik ?? 'Nama Belum Diisi' }}</h2>
                            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <span class="org-profile-num">
                                    <i class="fas fa-id-card"></i>
                                    <span>No. Anggota: {{ $anggota->no_anggota ?? 'Menunggu Verifikasi Pusat' }}</span>
                                </span>
                                @if($anggota->status == 'approved')
                                    <span class="org-badge org-badge-approved"><i class="fas fa-check-circle"></i> Terverifikasi Nasional</span>
                                @elseif($anggota->status == 'proses')
                                    <span class="org-badge org-badge-warning"><i class="fas fa-clock"></i> Tahap Berkas</span>
                                @else
                                    <span class="org-badge org-badge-primary"><i class="fas fa-spinner fa-spin"></i> {{ ucfirst(str_replace('_', ' ', $anggota->status)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Fast Actions -->
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('pendaftaran.anggota') }}" class="org-btn org-btn-outline" style="font-size: 12.5px;">
                            <i class="fas fa-pen text-xs"></i>
                            <span>Edit Data Umum</span>
                        </a>
                        <a href="{{ route('pendaftaran.sdm') }}" class="org-btn org-btn-outline" style="font-size: 12.5px;">
                            <i class="fas fa-stethoscope text-xs"></i>
                            <span>Edit Data SDM</span>
                        </a>
                    </div>
                </div>

                <!-- Two-Column Grid: Upload Logo & Legal Details -->
                <div style="display: grid; grid-template-columns: 320px 1fr; gap: 28px; align-items: start;">

                    <!-- Left: Logo Upload & Status Verification Box -->
                    <div>
                        <div class="org-card" style="padding: 24px; margin-bottom: 24px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: var(--org-text-main); margin: 0 0 14px 0;">
                                <i class="fas fa-camera text-red-600 mr-1.5"></i> Perbarui Logo Faskes
                            </h3>
                            <form action="{{ route('upload_foto') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="org-form-group">
                                    <input type="file" accept="image/*" name="logo" required class="org-form-control" style="font-size: 12px; padding: 8px;">
                                    <span style="font-size: 11px; color: var(--org-text-dim); margin-top: 4px; display: block;">Format JPG/PNG, maks. 2MB</span>
                                </div>
                                <button type="submit" class="org-btn org-btn-primary" style="width: 100%; justify-content: center; font-size: 12.5px; padding: 8px;">
                                    <i class="fas fa-upload text-xs"></i>
                                    <span>Unggah Logo</span>
                                </button>
                            </form>
                        </div>

                        <!-- Verification Progress Details -->
                        <div class="org-card" style="padding: 24px;">
                            <h3 style="font-size: 15px; font-weight: 800; color: var(--org-text-main); margin: 0 0 16px 0;">
                                <i class="fas fa-clipboard-check text-red-600 mr-1.5"></i> Status Berjenjang
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13px;">
                                <div>
                                    <span style="color: var(--org-text-muted); display: block; font-size: 11.5px;">Verifikasi Cabang (Kab/Kota):</span>
                                    <strong style="color: var(--org-text-main);">{{ $anggota->verifikasi_cabang ?? 'Belum Diverifikasi Cabang' }}</strong>
                                </div>
                                <div>
                                    <span style="color: var(--org-text-muted); display: block; font-size: 11.5px;">Verifikasi Pusat:</span>
                                    <strong style="color: var(--org-text-main);">{{ $anggota->verifikasi_pusat ?? 'Belum Diverifikasi Pusat' }}</strong>
                                </div>
                                <div>
                                    <span style="color: var(--org-text-muted); display: block; font-size: 11.5px;">Tanggal Pendaftaran:</span>
                                    <strong style="color: var(--org-text-main);">{{ $anggota->created_at ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Comprehensive Legal & Demographic Data Table -->
                    <div class="org-card" style="padding: 28px;">
                        <h3 style="font-size: 17px; font-weight: 800; color: var(--org-text-main); margin: 0 0 20px 0; display: flex; align-items: center; justify-content: space-between;">
                            <span>Informasi Operasional &amp; Izin Klinik</span>
                            <span style="font-size: 12px; font-weight: 600; color: var(--org-text-muted);">Data Resmi Faskes</span>
                        </h3>

                        <table class="org-data-table">
                            <tbody>
                                <tr>
                                    <th>Nama Penanggung Jawab</th>
                                    <td>{{ $anggota->nama_pemilik_klinik ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Kontak Person Resmi</th>
                                    <td>{{ $anggota->nama_kontak ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Email Korespondensi</th>
                                    <td>{{ $anggota->email ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon Faskes</th>
                                    <td>{{ $anggota->tlf_klinik ?? $anggota->tlf ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Izin Operasional (SIO)</th>
                                    <td><strong style="color: var(--org-primary);">{{ $anggota->no_ijin ?? 'Belum Diisi' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Diterbitkan Izin</th>
                                    <td>{{ $anggota->tgl_ijin ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Masa Berlaku Izin s/d</th>
                                    <td>{{ $anggota->tgl_akhir_ijin ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Wilayah Administratif</th>
                                    <td>
                                        {{ $anggota->kelurahan ? $anggota->kelurahan . ', ' : '' }}
                                        {{ $anggota->kecamatan ? $anggota->kecamatan . ', ' : '' }}
                                        {{ $anggota->kota ? $anggota->kota . ', ' : '' }}
                                        {{ $anggota->provinsi ?? 'Belum Diisi' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            @else

                <!-- Welcome Screen if Anggota Record is not yet initiated -->
                <div class="org-card" style="max-width: 680px; margin: 40px auto; text-align: center; padding: 50px 40px;">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(185, 28, 28, 0.1); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px auto;">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <h2 style="font-size: 26px; font-weight: 800; color: var(--org-text-main); margin: 0 0 12px 0;">
                        Selamat Datang di Ruang Anggota ASKLIN
                    </h2>
                    <p style="font-size: 14.5px; color: var(--org-text-muted); line-height: 1.7; margin: 0 0 28px 0;">
                        Akun faskes Anda telah berhasil dibuat. Silakan menekan tombol di bawah untuk menginisiasi pengisian berkas pendaftaran keanggotaan dan persiapan data perizinan klinik Anda.
                    </p>

                    <div style="background: var(--org-border-light); border-radius: var(--org-radius-md); padding: 18px; border: 1px solid var(--org-border); text-align: left; margin-bottom: 30px; font-size: 13px;">
                        <strong style="color: var(--org-text-main); display: block; margin-bottom: 8px;">Dokumen yang Perlu Dipersiapkan:</strong>
                        <div style="display: flex; flex-direction: column; gap: 6px; color: var(--org-text-muted);">
                            <div><i class="fas fa-check text-red-600 mr-2"></i> Surat Izin Operasional Klinik (SIO) yang masih aktif</div>
                            <div><i class="fas fa-check text-red-600 mr-2"></i> Data Dokter Penanggung Jawab dan SIP</div>
                            <div><i class="fas fa-check text-red-600 mr-2"></i> Informasi Fasilitas &amp; SDM Medis Klinik</div>
                        </div>
                    </div>

                    <form action="{{ route('create.anggota') }}" method="POST">
                        @csrf
                        <button type="submit" class="org-btn org-btn-primary" style="padding: 14px 36px; font-size: 15px; font-weight: 800;">
                            <i class="fas fa-play text-xs"></i>
                            <span>Mulai Pengisian Berkas Anggota</span>
                        </button>
                    </form>
                </div>

            @endif

        </div>
    </section>

@endsection

