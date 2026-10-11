@extends('layouts.frontend.layout')

@section('content')

<style>
    /* Scoped styles for Rakernas Canvas Landing Page */
    .rkn-cv-hero {
        background: linear-gradient(135deg, #064e3b 0%, #047857 55%, #0f766e 100%);
        color: #ffffff;
        padding: 70px 0 60px;
        position: relative;
    }
    .rkn-cv-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 8px;
        margin-bottom: 12px;
    }
    .rkn-cv-badge-gold {
        background: rgba(251, 191, 36, 0.2);
        color: #fde68a;
        border: 1px solid rgba(251, 191, 36, 0.4);
    }
    .rkn-cv-badge-light {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .rkn-cv-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .rkn-cv-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .rkn-cv-period-select {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }
    .rkn-cv-section-title {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        position: relative;
        display: inline-block;
    }
    .rkn-cv-section-title::after {
        content: '';
        display: block;
        width: 48px;
        height: 3px;
        background: #047857;
        margin: 8px auto 0;
        border-radius: 2px;
    }
    .rkn-form-control-highlight {
        border: 2px solid #047857 !important;
        background-color: #f0fdf4 !important;
        font-weight: 600 !important;
    }
</style>

<!-- Period Selection Top Bar -->
<div class="bg-light py-2 border-bottom">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex align-items-center my-1">
            <i class="icon-calendar3 text-success me-2" style="font-size: 18px;"></i>
            <span class="fw-bold text-dark me-2">Pilih Riwayat RAKERNAS:</span>
            <span class="text-muted small">
                {{ $selectedPeriode->nama ?? 'RAKERNAS ASKLIN' }}
                @if($isPeriodeActive)
                    <span class="badge bg-success ms-1">Sedang Aktif</span>
                @else
                    <span class="badge bg-secondary ms-1">Arsip</span>
                @endif
            </span>
        </div>
        <div class="my-1">
            <form action="{{ route('rakernas') }}" method="GET" class="d-inline-flex align-items-center">
                <select name="periode_id" onchange="this.form.submit()" class="rkn-cv-period-select">
                    @foreach($allPeriodes as $p)
                        <option value="{{ $p->id }}" {{ ($selectedPeriode && $selectedPeriode->id == $p->id) ? 'selected' : '' }}>
                            {{ $p->nama }} (Periode {{ $p->periode_kepengurusan }}) {{ $p->status == '1' ? '★ Aktif' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

<!-- Hero Banner -->
<div class="rkn-cv-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div>
                    <span class="rkn-cv-badge rkn-cv-badge-gold">
                        <i class="icon-trophy"></i> Rakernas Ke-{{ $selectedPeriode->rakernas_ke ?? '1' }}
                    </span>
                    <span class="rkn-cv-badge rkn-cv-badge-light">
                        <i class="icon-line-calendar"></i> Periode Kepengurusan {{ $selectedPeriode->periode_kepengurusan ?? '2023-2028' }}
                    </span>
                    @if($isPeriodeActive)
                        <span class="rkn-cv-badge" style="background: rgba(16, 185, 129, 0.3); border: 1px solid rgba(16, 185, 129, 0.6); color: #a7f3d0;">
                            <i class="icon-check"></i> Registrasi Terbuka
                        </span>
                    @endif
                </div>

                <h1 class="text-white fw-bold display-5 mb-3">
                    {{ $selectedPeriode->nama ?? 'Rapat Kerja Nasional (RAKERNAS) ASKLIN' }}
                </h1>

                <p class="lead text-white-50 mb-4">
                    {{ $selectedPeriode->tema ?? 'Penguatan Peran & Mutu Fasilitas Pelayanan Kesehatan Primer Dalam Era Transformasi Kesehatan Nasional' }}
                </p>

                <!-- Quick info items -->
                <div class="row g-3 mb-4 text-white">
                    <div class="col-sm-4">
                        <div class="bg-white bg-opacity-10 p-3 rounded border border-white border-opacity-25">
                            <small class="text-white-50 d-block text-uppercase fw-bold"><i class="icon-line-calendar me-1"></i> Tanggal</small>
                            <span class="fw-bold">{{ $selectedPeriode->tanggal_formatted }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="bg-white bg-opacity-10 p-3 rounded border border-white border-opacity-25">
                            <small class="text-white-50 d-block text-uppercase fw-bold"><i class="icon-line-map-pin me-1"></i> Lokasi</small>
                            <span class="fw-bold">{{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}</span>
                            <div class="small text-white-50">{{ $selectedPeriode->kota ?? 'Jakarta' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="bg-white bg-opacity-10 p-3 rounded border border-white border-opacity-25">
                            <small class="text-white-50 d-block text-uppercase fw-bold"><i class="icon-line-clock me-1"></i> Pendaftaran</small>
                            <span class="fw-bold">{{ $selectedPeriode && $selectedPeriode->tgl_tutup_daftar ? $selectedPeriode->tgl_tutup_daftar->translatedFormat('d F Y') : 'Hingga Kuota Penuh' }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @if($isPeriodeActive)
                        <a href="#form-daftar" class="btn btn-warning btn-lg fw-bold me-2 mb-2 text-dark">
                            <i class="icon-user-plus me-1"></i> Form Pendaftaran Peserta
                        </a>
                    @endif
                    <a href="#tentang" class="btn btn-outline-light btn-lg me-2 mb-2">
                        <i class="icon-info-circle me-1"></i> Tentang RAKERNAS
                    </a>
                    <a href="#undangan" class="btn btn-outline-light btn-lg me-2 mb-2">
                        <i class="icon-users me-1"></i> Pihak Diundang
                    </a>
                    <a href="#venue" class="btn btn-outline-light btn-lg mb-2">
                        <i class="icon-building me-1"></i> Venue &amp; Hotel
                    </a>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-25">
                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="ASKLIN" class="img-fluid mb-3 bg-white p-2 rounded" style="max-height: 80px;" onerror="this.src='/storage/{{ setting('logo') }}'">
                    <h4 class="text-white fw-bold mb-1">RAKERNAS ASKLIN</h4>
                    <p class="text-white-50 small mb-3">Forum Pengurus Pusat, Daerah, Cabang &amp; Klinik Anggota</p>
                    <div class="bg-dark bg-opacity-25 p-3 rounded text-start small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white-50">Pelaksanaan:</span>
                            <span class="text-white fw-bold">Maks 2x per Periode</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white-50">Delegasi:</span>
                            <span class="text-white fw-bold">Klinik Seluruh Indonesia</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-white-50">Persyaratan:</span>
                            <span class="text-warning fw-bold">Nama Klinik Wajib</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Body -->
<section id="content" class="py-5" style="background: #f8fafc;">
    <div class="container">

        <!-- Tentang RAKERNAS -->
        <div id="tentang" class="mb-5 pt-3">
            <div class="text-center mb-4">
                <h2 class="rkn-cv-section-title">Tentang Rapat Kerja Nasional ASKLIN</h2>
                <p class="text-muted mx-auto" style="max-width: 750px;">
                    Rapat Kerja Nasional (RAKERNAS) diselenggarakan oleh Pengurus Pusat ASKLIN maksimal 2 kali dalam satu periode kepengurusan (periode 2023–2028). Merupakan wadah konsolidasi kerja, evaluasi program, dan perumusan rekomendasi strategis bagi penguatan klinik di Indonesia.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="rkn-cv-card p-4 border-top border-4 border-success">
                        <div class="text-success mb-3"><i class="icon-sitemap display-5"></i></div>
                        <h4 class="fw-bold mb-2">Konsolidasi Nasional</h4>
                        <p class="text-muted small">Menyatukan gerak langkah Pengurus Pusat, Pengurus Daerah (Komda), Pengurus Cabang (Komcab), dan seluruh klinik anggota di 38 provinsi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rkn-cv-card p-4 border-top border-4 border-primary">
                        <div class="text-primary mb-3"><i class="icon-line-heart display-5"></i></div>
                        <h4 class="fw-bold mb-2">Sinergi Pemangku Kepentingan</h4>
                        <p class="text-muted small">Menyelenggarakan dialog interaktif dengan Kementerian Kesehatan RI, BPJS Kesehatan, dan dinas kesehatan terkait regulasi akreditasi dan tarif kapitasi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rkn-cv-card p-4 border-top border-4 border-warning">
                        <div class="text-warning mb-3"><i class="icon-line-award display-5"></i></div>
                        <h4 class="fw-bold mb-2">Pemberdayaan Mutu Klinik</h4>
                        <p class="text-muted small">Mendukung percepatan digitalisasi SatuSehat Kemenkes, rekam medis elektronik, serta peningkatan profesionalisme tenaga kesehatan klinik.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pihak yang Diundang -->
        <div id="undangan" class="mb-5 pt-4">
            <div class="text-center mb-4">
                <h2 class="rkn-cv-section-title">Pihak yang Diundang</h2>
                <p class="text-muted">Penyelenggaraan RAKERNAS mengundang jajaran pimpinan kementerian, pejabat pemerintah daerah, dan mitra strategis.</p>
            </div>

            <div class="row g-3">
                @if($undangan->isNotEmpty())
                    @foreach($undangan as $u)
                        <div class="col-sm-6 col-lg-3">
                            <div class="rkn-cv-card p-3 text-center">
                                <div class="mx-auto rounded-circle overflow-hidden mb-3 bg-light border" style="width: 72px; height: 72px;">
                                    @if($u->foto)
                                        <img src="{{ asset('storage/' . $u->foto) }}" alt="{{ $u->nama }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <i class="icon-user display-6 text-muted mt-3 d-block"></i>
                                    @endif
                                </div>
                                <span class="badge bg-secondary mb-2 text-uppercase">{{ $u->kategori }}</span>
                                <h6 class="fw-bold mb-1 text-dark">{{ $u->nama }}</h6>
                                <div class="text-muted small mb-1">{{ $u->jabatan }}</div>
                                @if($u->instansi)
                                    <div class="text-success small fw-semibold">{{ $u->instansi }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-sm-6 col-lg-3">
                        <div class="rkn-cv-card p-3 text-center">
                            <div class="text-danger mb-2"><i class="icon-landmark display-6"></i></div>
                            <span class="badge bg-danger mb-2">KEMENTERIAN</span>
                            <h6 class="fw-bold mb-1">Kementerian Kesehatan RI</h6>
                            <div class="text-muted small">Menteri Kesehatan / Dirjen Pelayanan Kesehatan</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="rkn-cv-card p-3 text-center">
                            <div class="text-primary mb-2"><i class="icon-line-heart display-6"></i></div>
                            <span class="badge bg-primary mb-2">BPJS KESEHATAN</span>
                            <h6 class="fw-bold mb-1">Direksi BPJS Kesehatan</h6>
                            <div class="text-muted small">Direktur Jaminan Pelayanan Kesehatan</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="rkn-cv-card p-3 text-center">
                            <div class="text-warning mb-2"><i class="icon-award display-6"></i></div>
                            <span class="badge bg-warning text-dark mb-2">PEMERINTAH DAERAH</span>
                            <h6 class="fw-bold mb-1">Gubernur &amp; Bupati / Walikota</h6>
                            <div class="text-muted small">Pemerintah Daerah Tuan Rumah Acara</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="rkn-cv-card p-3 text-center">
                            <div class="text-success mb-2"><i class="icon-line-shield display-6"></i></div>
                            <span class="badge bg-success mb-2">SPONSOR &amp; MITRA</span>
                            <h6 class="fw-bold mb-1">Industri Farmasi &amp; Alkes</h6>
                            <div class="text-muted small">Sponsor Resmi Pendukung RAKERNAS ASKLIN</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Venue & Hotel Penyelenggaraan -->
        <div id="venue" class="mb-5 pt-4">
            <div class="text-center mb-4">
                <h2 class="rkn-cv-section-title">Venue &amp; Akomodasi Hotel</h2>
                <p class="text-muted">Fasilitas penyelenggaraan acara dan rekomendasi hotel rekanan bagi peserta.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="rkn-cv-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="icon-line-map-pin text-success display-6 me-3"></i>
                            <div>
                                <h5 class="fw-bold mb-0">Lokasi Penyelenggaraan</h5>
                                <div class="text-muted small">{{ $selectedPeriode->kota ?? 'Jakarta' }}</div>
                            </div>
                        </div>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="fw-bold text-dark">{{ $selectedPeriode->tempat ?? 'Grand Ballroom' }}</div>
                            <div class="text-muted small">{{ $selectedPeriode->alamat_lengkap ?? 'Petunjuk rute dan informasi akses akan diinformasikan kepada peserta terdaftar.' }}</div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small border-top pt-2">
                            <span>Waktu Pelaksanaan:</span>
                            <span class="fw-bold text-success">{{ $selectedPeriode->tanggal_formatted }}</span>
                        </div>
                    </div>

                    <div class="rkn-cv-card p-4 mt-4">
                        <h6 class="fw-bold mb-2"><i class="icon-bed text-success me-1"></i> Akomodasi Hotel Rekanan</h6>
                        <p class="text-muted small">Bagi peserta luar kota, panitia telah bekerjasama dengan hotel di sekitar venue dengan tarif khusus peserta RAKERNAS ASKLIN.</p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="rkn-cv-card p-4">
                        <h6 class="fw-bold mb-3"><i class="icon-camera text-success me-1"></i> Galeri &amp; Dokumentasi Lokasi</h6>
                        @if($galeriVenue->isNotEmpty())
                            <div class="row g-3">
                                @foreach($galeriVenue as $g)
                                    <div class="col-sm-6">
                                        <div class="rounded overflow-hidden border position-relative" style="height: 180px;">
                                            <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->judul }}" class="w-100 h-100" style="object-fit: cover;">
                                            <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 small">
                                                <div class="fw-bold">{{ $g->judul }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="bg-light rounded p-4 text-center border">
                                        <i class="icon-building display-4 text-muted mb-2"></i>
                                        <div class="fw-bold text-dark">Grand Ballroom</div>
                                        <div class="small text-muted">Ruang Sidang Utama Pleno</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="bg-light rounded p-4 text-center border">
                                        <i class="icon-bed display-4 text-muted mb-2"></i>
                                        <div class="fw-bold text-dark">Hotel Rekanan</div>
                                        <div class="small text-muted">Akomodasi Standar Bintang</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulir Pendaftaran Peserta (Nama Klinik Wajib Diisi) -->
        <div id="form-daftar" class="pt-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border border-success">
                <div class="bg-success text-white p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-warning text-dark text-uppercase mb-2"><i class="icon-edit"></i> Formulir Mandiri</span>
                            <h3 class="text-white fw-bold mb-1">Pendaftaran Peserta RAKERNAS ASKLIN</h3>
                            <p class="mb-0 text-white-50 small">
                                Pendaftaran otomatis terdaftar pada: <strong>{{ $activePeriode->nama ?? 'RAKERNAS ASKLIN' }}</strong> (Periode {{ $activePeriode->periode_kepengurusan ?? '2023-2028' }})
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    @if(!$activePeriode)
                        <div class="text-center py-5">
                            <i class="icon-lock display-4 text-muted mb-3"></i>
                            <h5 class="fw-bold">Pendaftaran Belum Dibuka</h5>
                            <p class="text-muted small">Saat ini belum ada periode RAKERNAS yang aktif dibuka untuk pendaftaran.</p>
                        </div>
                    @else
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="icon-info-circle me-3 fs-4"></i>
                            <div class="small">
                                <strong>Ketentuan Formulir:</strong> Pendaftaran bersifat mandiri dan lebih ringkas dari KONAS (tanpa surat mandat/delegasi). 
                                <strong class="text-decoration-underline">Nama Klinik Wajib Diisi</strong> sebagai data utama identitas peserta.
                            </div>
                        </div>

                        <form id="rakernasFormCv" action="{{ route('rakernas.daftar') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- NAMA KLINIK (WAJIB) -->
                                <div class="col-12 bg-light p-3 rounded border border-success">
                                    <label for="nama_klinik_cv" class="form-label fw-bold text-success text-uppercase small d-flex justify-content-between">
                                        <span><i class="icon-clinic-medical me-1"></i> Nama Klinik <span class="text-danger">*</span></span>
                                        <span class="badge bg-success">DATA UTAMA WAJIB</span>
                                    </label>
                                    <input type="text" name="nama_klinik" id="nama_klinik_cv" required placeholder="Contoh: Klinik Pratama Sehat Mandiri / Klinik Utama Bunda" class="form-control rkn-form-control-highlight">
                                    <small class="text-muted">Isikan nama resmi fasilitas pelayanan kesehatan klinik yang Anda wakili.</small>
                                </div>

                                <!-- Nama Peserta -->
                                <div class="col-md-6">
                                    <label for="nama_peserta_cv" class="form-label fw-bold small text-uppercase">Nama Lengkap Peserta <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_peserta" id="nama_peserta_cv" required placeholder="Nama lengkap &amp; gelar" class="form-control">
                                </div>

                                <!-- Jabatan -->
                                <div class="col-md-6">
                                    <label for="jabatan_cv" class="form-label fw-bold small text-uppercase">Jabatan di Klinik / Organisasi</label>
                                    <input type="text" name="jabatan" id="jabatan_cv" placeholder="Pimpinan Klinik / Dokter / Pengurus Komda" class="form-control">
                                </div>

                                <!-- No HP / WA -->
                                <div class="col-md-6">
                                    <label for="no_hp_cv" class="form-label fw-bold small text-uppercase">No. WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                                    <input type="text" name="no_hp" id="no_hp_cv" required placeholder="08123456789" class="form-control">
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email_cv" class="form-label fw-bold small text-uppercase">Alamat Email Aktif <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email_cv" required placeholder="nama@klinik.com" class="form-control">
                                </div>

                                <!-- Provinsi -->
                                <div class="col-md-6">
                                    <label for="id_provinsi_cv" class="form-label fw-bold small text-uppercase">Provinsi Asal</label>
                                    <select name="id_provinsi" id="id_provinsi_cv" class="form-select">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsi as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Cabang / Kota -->
                                <div class="col-md-6">
                                    <label for="cabang_cv" class="form-label fw-bold small text-uppercase">Cabang ASKLIN / Kota</label>
                                    <input type="text" name="cabang" id="cabang_cv" placeholder="Komcab / Kota domisili klinik" class="form-control">
                                </div>

                                <!-- Rencana Kehadiran -->
                                <div class="col-md-6">
                                    <label for="status_kehadiran_cv" class="form-label fw-bold small text-uppercase">Rencana Kehadiran</label>
                                    <select name="status_kehadiran" id="status_kehadiran_cv" class="form-select">
                                        <option value="Hadir">Hadir Langsung di Lokasi (Fisik)</option>
                                        <option value="Online">Mengikuti Secara Daring / Zoom (Online)</option>
                                    </select>
                                </div>

                                <!-- Alamat Klinik -->
                                <div class="col-md-6">
                                    <label for="alamat_klinik_cv" class="form-label fw-bold small text-uppercase">Alamat Klinik</label>
                                    <input type="text" name="alamat_klinik" id="alamat_klinik_cv" placeholder="Alamat lengkap klinik" class="form-control">
                                </div>

                                <!-- Catatan Khusus -->
                                <div class="col-12">
                                    <label for="kebutuhan_khusus_cv" class="form-label fw-bold small text-uppercase">Catatan / Kebutuhan Khusus (Opsional)</label>
                                    <textarea name="kebutuhan_khusus" id="kebutuhan_khusus_cv" rows="2" placeholder="Catatan akomodasi, konsumsi, atau pertanyaan ke panitia" class="form-control"></textarea>
                                </div>

                                <div class="col-12 d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                                    <small class="text-muted">Data pendaftaran akan diverifikasi oleh panitia RAKERNAS ASKLIN.</small>
                                    <button type="submit" id="btnSubmitCv" class="btn btn-success btn-lg px-4 fw-bold">
                                        <i class="icon-line-check me-1"></i> Kirim Pendaftaran RAKERNAS
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rakernasFormCv');
    const submitBtn = document.getElementById('btnSubmitCv');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const namaKlinik = document.getElementById('nama_klinik_cv').value.trim();
            if (!namaKlinik) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Klinik Wajib Diisi',
                    text: 'Mohon isi nama klinik Anda sebagai data utama pendaftaran RAKERNAS.',
                    confirmButtonColor: '#047857'
                });
                document.getElementById('nama_klinik_cv').focus();
                return;
            }

            const formData = new FormData(form);
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="icon-line-loader icon-spin me-1"></i> Memproses...';

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
                                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px; margin-bottom: 12px; text-align: center;">
                                    <div style="font-size: 11px; text-transform: uppercase; color: #047857; font-weight: bold;">Nomor Registrasi Anda</div>
                                    <div style="font-size: 20px; font-weight: 800; color: #064e3b; letter-spacing: 1px; font-family: monospace;">${data.no_registrasi}</div>
                                </div>
                                <div><strong>Nama Peserta:</strong> ${data.data.nama_peserta}</div>
                                <div><strong>Nama Klinik:</strong> ${data.data.nama_klinik}</div>
                                <div><strong>Status:</strong> Terdaftar Resmi</div>
                            </div>
                        `,
                        confirmButtonText: '<i class="icon-print me-1"></i> Cetak Kartu Registrasi',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#047857',
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
                        confirmButtonColor: '#047857'
                    });
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Tidak dapat menghubungi server. Silakan coba beberapa saat lagi.',
                    confirmButtonColor: '#047857'
                });
            });
        });
    }
});
</script>
@endpush

