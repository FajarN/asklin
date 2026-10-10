@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Ambient Hero Top Section -->
    <section class="mod-profile-section">
        <div class="mod-hero-glow-blob mod-hero-glow-blob-1"></div>
        <div class="mod-hero-glow-blob mod-hero-glow-blob-2"></div>

        <div class="modern-org-container">

            @if(!empty($anggota->id))

                <!-- Top Breadcrumbs & Title -->
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 24px;">
                    <div>
                        <div class="mod-breadcrumbs" style="justify-content: flex-start; margin-bottom: 8px;">
                            <a href="{{ route('home') }}">Beranda</a>
                            <span>/</span>
                            <span style="color: var(--mod-text-heading); font-weight: 700;">Portal Anggota</span>
                        </div>
                        <h1 style="font-size: 28px; font-weight: 800; color: var(--mod-text-heading); margin: 0; letter-spacing: -0.5px;">
                            Dashboard Ekosistem Faskes
                        </h1>
                    </div>

                    <!-- Live Member Badge -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($anggota->status == 'approved')
                            <span class="mod-auth-badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; border-color: rgba(16, 185, 129, 0.3); margin: 0; padding: 6px 16px;">
                                <span class="mod-pulse-dot" style="background: #10b981; box-shadow: 0 0 8px #10b981;"></span>
                                <span>STATUS: TERVERIFIKASI NASIONAL</span>
                            </span>
                        @elseif($anggota->status == 'proses')
                            <span class="mod-auth-badge" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); margin: 0; padding: 6px 16px;">
                                <span class="mod-pulse-dot" style="background: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></span>
                                <span>STATUS: TAHAP PEMBERKASAN</span>
                            </span>
                        @else
                            <span class="mod-auth-badge" style="margin: 0; padding: 6px 16px;">
                                <span class="mod-pulse-dot"></span>
                                <span>STATUS: {{ strtoupper(str_replace('_', ' ', $anggota->status)) }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Process Alert Banners -->
                @if($anggota->status == 'proses')
                    <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(15, 23, 42, 0.05)); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: var(--mod-radius-lg); padding: 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: var(--mod-radius-md); background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; shrink-0;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 2px 0;">Pemberkasan Data Umum Belum Lengkap</h4>
                                <p style="font-size: 13px; color: var(--mod-text-muted); margin: 0;">Silakan melengkapi data profil klinik serta mengunggah scan Surat Izin Operasional (SIO).</p>
                            </div>
                        </div>
                        <a href="{{ route('pendaftaran.anggota') }}" class="mod-btn mod-btn-primary" style="padding: 10px 22px; font-size: 13px;">
                            <i class="fas fa-edit text-xs"></i>
                            <span>Lengkapi Data Umum</span>
                        </a>
                    </div>
                @elseif($anggota->status == 'create_dokter')
                    <div style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.12), rgba(15, 23, 42, 0.05)); border: 1px solid rgba(2, 132, 199, 0.35); border-radius: var(--mod-radius-lg); padding: 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: var(--mod-radius-md); background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; shrink-0;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 2px 0;">Tahap Pengisian SDM Medis</h4>
                                <p style="font-size: 13px; color: var(--mod-text-muted); margin: 0;">Lengkapi susunan dokter penanggung jawab dan tenaga kesehatan klinik Anda.</p>
                            </div>
                        </div>
                        <a href="{{ route('pendaftaran.sdm') }}" class="mod-btn mod-btn-primary" style="padding: 10px 22px; font-size: 13px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Lengkapi Data SDM</span>
                        </a>
                    </div>
                @endif

                <!-- Multi-Tier Stepper Progress -->
                <div class="mod-tier-stepper">
                    <div class="mod-tier-box completed">
                        <i class="fas fa-check-circle text-emerald-500 text-lg mb-1"></i>
                        <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-heading);">1. Akun Terdaftar</div>
                        <span style="font-size: 11px; color: #10b981;">Selesai</span>
                    </div>

                    <div class="mod-tier-box {{ !empty($anggota->verifikasi_cabang) ? 'completed' : 'active' }}">
                        @if(!empty($anggota->verifikasi_cabang))
                            <i class="fas fa-check-circle text-emerald-500 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-heading);">2. Validasi Cabang</div>
                            <span style="font-size: 11px; color: #10b981;">{{ $anggota->verifikasi_cabang }}</span>
                        @else
                            <i class="fas fa-clock text-amber-500 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-heading);">2. Validasi Cabang</div>
                            <span style="font-size: 11px; color: #f59e0b;">Menunggu Verifikasi</span>
                        @endif
                    </div>

                    <div class="mod-tier-box {{ !empty($anggota->verifikasi_pusat) ? 'completed' : '' }}">
                        @if(!empty($anggota->verifikasi_pusat))
                            <i class="fas fa-check-circle text-emerald-500 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-heading);">3. Rekomendasi DPD</div>
                            <span style="font-size: 11px; color: #10b981;">Disetujui</span>
                        @else
                            <i class="fas fa-hourglass-start text-slate-400 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-muted);">3. Rekomendasi DPD</div>
                            <span style="font-size: 11px; color: var(--mod-text-dim);">Antrean Review</span>
                        @endif
                    </div>

                    <div class="mod-tier-box {{ $anggota->status == 'approved' ? 'completed' : '' }}">
                        @if($anggota->status == 'approved')
                            <i class="fas fa-award text-emerald-500 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-heading);">4. Pengesahan Pusat</div>
                            <span style="font-size: 11px; color: #10b981;">E-SK Diterbitkan</span>
                        @else
                            <i class="fas fa-certificate text-slate-400 text-lg mb-1"></i>
                            <div style="font-size: 12px; font-weight: 800; color: var(--mod-text-muted);">4. Pengesahan Pusat</div>
                            <span style="font-size: 11px; color: var(--mod-text-dim);">Tahap Akhir</span>
                        @endif
                    </div>
                </div>

                <!-- Bento Profile Hub Grid -->
                <div class="mod-profile-bento-grid">

                    <!-- Left Column: Smart Digital ID Card & Quick Logo Upload -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">

                        <!-- Smart Digital Member ID Card -->
                        <div class="mod-smart-id-card">
                            <div class="mod-smart-id-glow"></div>
                            
                            <div class="mod-smart-id-header">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(220, 38, 38, 0.8); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; color: #fff;">
                                        A
                                    </div>
                                    <div>
                                        <div style="font-size: 13px; font-weight: 800; letter-spacing: 0.5px; line-height: 1.1;">ASKLIN</div>
                                        <span style="font-size: 9.5px; opacity: 0.7;">SMART MEMBER CARD</span>
                                    </div>
                                </div>
                                <i class="fas fa-wifi" style="transform: rotate(90deg); opacity: 0.7; font-size: 16px;"></i>
                            </div>

                            <div class="mod-smart-id-body">
                                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #fca5a5; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                    NAMA FASILITAS KESEHATAN
                                </span>
                                <h3 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0 0 10px 0; line-height: 1.25;">
                                    {{ $anggota->nama_klinik ?? 'Nama Klinik Belum Diisi' }}
                                </h3>
                                <div style="font-family: monospace; font-size: 15px; letter-spacing: 2px; color: #f87171; font-weight: 700;">
                                    {{ $anggota->no_anggota ?? 'NO: PENDING-PUSAT' }}
                                </div>
                            </div>

                            <div class="mod-smart-id-footer">
                                <div>
                                    <span style="font-size: 9.5px; opacity: 0.7; display: block; text-transform: uppercase;">Masa Berlaku SIO</span>
                                    <strong style="font-size: 12px; color: #fff;">{{ $anggota->tgl_akhir_ijin ?? 'Sesuai Izin' }}</strong>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 9.5px; opacity: 0.7; display: block; text-transform: uppercase;">Wilayah</span>
                                    <strong style="font-size: 12px; color: #fff;">{{ $anggota->kota ?? 'Indonesia' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Fast Actions Grid -->
                        <div class="mod-action-grid">
                            <a href="{{ route('pendaftaran.anggota') }}" class="mod-action-card">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary-light); display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-heading); display: block;">Data Umum</strong>
                                    <span style="font-size: 11px; color: var(--mod-text-muted);">Profil &amp; SIO</span>
                                </div>
                            </a>

                            <a href="{{ route('pendaftaran.sdm') }}" class="mod-action-card">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(2, 132, 199, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-heading); display: block;">Tenaga Medis</strong>
                                    <span style="font-size: 11px; color: var(--mod-text-muted);">SDM Dokter &amp; Perawat</span>
                                </div>
                            </a>

                            <a href="{{ route('tagihan') }}" class="mod-action-card">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-heading); display: block;">Iuran Faskes</strong>
                                    <span style="font-size: 11px; color: var(--mod-text-muted);">Cek Tagihan</span>
                                </div>
                            </a>

                            @if($anggota->status == 'approved')
                                <a href="{{ route('sertifikat') }}" class="mod-action-card">
                                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size: 13px; color: var(--mod-text-heading); display: block;">E-Sertifikat</strong>
                                        <span style="font-size: 11px; color: var(--mod-text-muted);">Unduh Dokumen</span>
                                    </div>
                                </a>
                            @endif
                        </div>

                        <!-- Logo Upload Box -->
                        <div class="mod-auth-card" style="padding: 24px;">
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-image text-red-500"></i>
                                <span>Logo Resmi Fasilitas Klinik</span>
                            </h4>
                            <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 16px;">
                                @if($anggota->logo && file_exists(public_path('images/file/' . $anggota->logo)))
                                    <img src="{{ asset('images/file/' . $anggota->logo) }}" alt="Logo" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover; border: 1px solid var(--mod-border);">
                                @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="Logo" style="width: 64px; height: 64px; border-radius: 12px; object-fit: contain; padding: 6px; border: 1px solid var(--mod-border); background: var(--mod-bg-alt);">
                                @else
                                    <div style="width: 64px; height: 64px; border-radius: 12px; background: var(--mod-bg-alt); display: flex; align-items: center; justify-content: center; color: var(--mod-text-dim);">
                                        <i class="fas fa-clinic-medical text-2xl"></i>
                                    </div>
                                @endif
                                <div style="flex: 1;">
                                    <form action="{{ route('upload_foto') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" accept="image/*" name="logo" required class="mod-form-input" style="padding: 6px 10px; font-size: 11.5px; margin-bottom: 8px;">
                                        <button type="submit" class="mod-btn mod-btn-primary" style="padding: 6px 14px; font-size: 11.5px; width: 100%; justify-content: center;">
                                            <span>Update Logo</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Clinic Demographic & Legal Matrix -->
                    <div class="mod-auth-card" style="padding: 32px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--mod-border);">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px 0;">
                                    Informasi Operasional &amp; Izin Klinik
                                </h3>
                                <span style="font-size: 12px; color: var(--mod-text-muted);">Data tersinkronisasi dalam basis data keanggotaan ASKLIN</span>
                            </div>
                            <span class="mod-auth-badge" style="margin: 0;">DATA RESMI</span>
                        </div>

                        <!-- Data Grid List -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Penanggung Jawab</span>
                                <strong style="font-size: 14px; color: var(--mod-text-heading);">{{ $anggota->nama_pemilik_klinik ?? 'Belum Diisi' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Kontak Person</span>
                                <strong style="font-size: 14px; color: var(--mod-text-heading);">{{ $anggota->nama_kontak ?? 'Belum Diisi' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Email Faskes</span>
                                <strong style="font-size: 14px; color: var(--mod-text-heading);">{{ $anggota->email ?? 'Belum Diisi' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Nomor Telepon</span>
                                <strong style="font-size: 14px; color: var(--mod-text-heading);">{{ $anggota->tlf_klinik ?? $anggota->tlf ?? 'Belum Diisi' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border); grid-column: 1 / -1;">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Nomor Izin Operasional (SIO)</span>
                                <strong style="font-size: 16px; color: var(--mod-primary-light);">{{ $anggota->no_ijin ?? 'Belum Diisi' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Tanggal Diterbitkan Izin</span>
                                <strong style="font-size: 13.5px; color: var(--mod-text-heading);">{{ $anggota->tgl_ijin ?? '-' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Berlaku Sampai Dengan</span>
                                <strong style="font-size: 13.5px; color: var(--mod-text-heading);">{{ $anggota->tgl_akhir_ijin ?? '-' }}</strong>
                            </div>

                            <div style="padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border); grid-column: 1 / -1;">
                                <span style="font-size: 11px; font-weight: 700; color: var(--mod-text-muted); display: block; text-transform: uppercase;">Lokasi Administratif</span>
                                <strong style="font-size: 13.5px; color: var(--mod-text-heading);">
                                    {{ $anggota->kelurahan ? $anggota->kelurahan . ', ' : '' }}
                                    {{ $anggota->kecamatan ? $anggota->kecamatan . ', ' : '' }}
                                    {{ $anggota->kota ? $anggota->kota . ', ' : '' }}
                                    {{ $anggota->provinsi ?? 'Belum Diisi' }}
                                </strong>
                            </div>

                        </div>
                    </div>

                </div>

            @else

                <!-- Welcome Screen if Anggota Record is not yet initiated -->
                <div class="mod-auth-card" style="max-width: 680px; margin: 40px auto; text-align: center; padding: 50px 36px;">
                    <div style="width: 72px; height: 72px; border-radius: var(--mod-radius-lg); background: linear-gradient(135deg, var(--mod-primary), var(--mod-ruby)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px auto; box-shadow: var(--mod-shadow-glow);">
                        <i class="fas fa-hospital-user"></i>
                    </div>

                    <h2 style="font-size: 26px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 10px 0; letter-spacing: -0.5px;">
                        Selamat Datang di Portal Anggota ASKLIN
                    </h2>
                    <p style="font-size: 14px; color: var(--mod-text-muted); line-height: 1.7; margin: 0 0 28px 0;">
                        Akun faskes Anda telah terverifikasi awal. Lanjutkan ke pengisian data identitas operasional klinik untuk proses verifikasi berjenjang oleh Pengurus Cabang &amp; Daerah.
                    </p>

                    <form action="{{ route('create.anggota') }}" method="POST">
                        @csrf
                        <button type="submit" class="mod-btn mod-btn-primary" style="padding: 14px 36px; font-size: 14.5px;">
                            <i class="fas fa-rocket text-xs"></i>
                            <span>Inisiasi Pendaftaran Faskes</span>
                        </button>
                    </form>
                </div>

            @endif

        </div>
    </section>

@endsection

