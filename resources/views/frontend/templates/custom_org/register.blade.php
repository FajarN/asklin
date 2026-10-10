@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Institutional Breadcrumbs & Header -->
    <div class="org-inner-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Registrasi Anggota</span>
            </div>
            <h1 class="org-inner-title">Pendaftaran Fasilitas Pelayanan Kesehatan</h1>
            <p class="org-inner-subtitle">Tata Cara Resmi Bergabung Menjadi Anggota Terdaftar Asosiasi Klinik Indonesia (ASKLIN)</p>
        </div>
    </div>

    <!-- Main Registration Section -->
    <section class="org-auth-section">
        <div class="custom-org-container">
            <div class="org-auth-grid">

                <!-- Left: Formal Institutional Registration Flow (Alur 5 Tahap) -->
                <div class="org-flow-panel">
                    <span class="org-tag-pill" style="margin-bottom: 12px;">Alur Prosedural Organisasi</span>
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--org-text-main); margin: 0 0 10px 0; line-height: 1.3;">
                        Tahapan Registrasi &amp; Verifikasi Keanggotaan
                    </h2>
                    <p style="font-size: 13.5px; color: var(--org-text-muted); line-height: 1.7; margin: 0;">
                        Proses pendaftaran keanggotaan klinik diatur secara tertib dan transparan melalui 5 tahapan berjenjang:
                    </p>

                    <!-- Vertical Flow List -->
                    <div class="org-flow-list">

                        <!-- Step 1 -->
                        <div class="org-flow-item active">
                            <div class="org-flow-num">1</div>
                            <div class="org-flow-text">
                                <h3 class="org-flow-heading">Tahap 1: Registrasi Akun Kredensial</h3>
                                <p class="org-flow-body">
                                    Membuat akun pendaftaran resmi faskes menggunakan email aktif klinik dan kata sandi pengelola berwenang.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="org-flow-item">
                            <div class="org-flow-num">2</div>
                            <div class="org-flow-text">
                                <h3 class="org-flow-heading">Tahap 2: Pemberkasan Legalitas &amp; SIO</h3>
                                <p class="org-flow-body">
                                    Mengisi profil lengkap faskes, penanggung jawab teknis medis, serta mengunggah Surat Izin Operasional (SIO) yang masih berlaku.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="org-flow-item">
                            <div class="org-flow-num">3</div>
                            <div class="org-flow-text">
                                <h3 class="org-flow-heading">Tahap 3: Pendataan Tenaga Kesehatan (SDM)</h3>
                                <p class="org-flow-body">
                                    Menginput data dokter penanggung jawab, dokter pelaksana, dan tenaga paramedis pendukung fasilitas klinik.
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="org-flow-item">
                            <div class="org-flow-num">4</div>
                            <div class="org-flow-text">
                                <h3 class="org-flow-heading">Tahap 4: Verifikasi Berjenjang (Cabang &amp; Daerah)</h3>
                                <p class="org-flow-body">
                                    Pemeriksaan keabsahan berkas oleh Pengurus Cabang kabupaten/kota setempat dan rekomendasi Pengurus Daerah provinsi.
                                </p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="org-flow-item">
                            <div class="org-flow-num">5</div>
                            <div class="org-flow-text">
                                <h3 class="org-flow-heading">Tahap 5: Pengesahan &amp; E-Sertifikat Nasional</h3>
                                <p class="org-flow-body">
                                    Penerbitan Nomor Anggota Nasional resmi oleh Pengurus Pusat, E-SK Keanggotaan, serta Sertifikat ber-QR Code valid.
                                </p>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 28px; padding: 16px; background: rgba(185, 28, 28, 0.05); border: 1px dashed var(--org-primary); border-radius: var(--org-radius-md); font-size: 12.5px; color: var(--org-text-main); line-height: 1.6;">
                        <i class="fas fa-info-circle text-red-600 mr-1.5"></i>
                        <strong>Catatan Penting:</strong> Pastikan dokumen SIO dalam format PDF atau foto yang jelas sebelum melanjutkan pengisian data faskes setelah akun dibuat.
                    </div>
                </div>

                <!-- Right: Formal Registration Form -->
                <div class="org-auth-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--org-border);">
                        <div>
                            <h2 class="org-auth-title" style="font-size: 20px;">Formulir Calon Anggota</h2>
                            <span style="font-size: 12px; color: var(--org-text-muted);">Langkah 1: Pembentukan Akun Portal</span>
                        </div>
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(185, 28, 28, 0.1); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>

                    <form action="{{ route('register') }}" method="POST" id="orgRegisterForm">
                        @csrf

                        <!-- Nama Klinik -->
                        <div class="org-form-group">
                            <label for="name" class="org-form-label">Nama Fasilitas Klinik :</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="org-form-control @error('name') is-invalid @enderror" placeholder="Contoh: Klinik Pratama Sehat Bersama">
                            <span style="font-size: 11px; color: var(--org-text-dim); margin-top: 3px; display: block;">Sesuai dengan nama yang tercantum pada Izin Operasional</span>
                            @error('name')
                                <span class="org-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Klinik -->
                        <div class="org-form-group">
                            <label for="email" class="org-form-label">Email Resmi Korespondensi :</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="org-form-control @error('email') is-invalid @enderror" placeholder="info@klinikpratama.co.id">
                            <span style="font-size: 11px; color: var(--org-text-dim); margin-top: 3px; display: block;">Digunakan untuk pengiriman notifikasi verifikasi dan login portal</span>
                            @error('email')
                                <span class="org-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="org-form-group">
                            <label for="password" class="org-form-label">Kata Sandi :</label>
                            <div class="org-input-wrap">
                                <input id="password" type="password" name="password" required autocomplete="new-password" class="org-form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" style="padding-right: 40px;">
                                <button type="button" class="org-input-icon-btn" onclick="toggleOrgPass('password', this)" aria-label="Lihat Kata Sandi">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="org-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="org-form-group">
                            <label for="password-confirm" class="org-form-label">Konfirmasi Kata Sandi :</label>
                            <div class="org-input-wrap">
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="org-form-control" placeholder="Ketik ulang kata sandi di atas" style="padding-right: 40px;">
                                <button type="button" class="org-input-icon-btn" onclick="toggleOrgPass('password-confirm', this)" aria-label="Lihat Kata Sandi">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Pakta Integritas Checkbox -->
                        <div class="org-form-group" style="margin-bottom: 24px;">
                            <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 12.5px; color: var(--org-text-muted); cursor: pointer; line-height: 1.5;">
                                <input type="checkbox" name="agree" required style="accent-color: var(--org-primary); width: 16px; height: 16px; margin-top: 2px;">
                                <span>Saya menyatakan bahwa data yang diisikan adalah benar mewakili fasilitas kesehatan berizin dan bersedia mentaati AD/ART serta Kode Etik ASKLIN.</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="org-btn org-btn-primary" style="width: 100%; padding: 13px; font-size: 14px; font-weight: 800; justify-content: center; box-shadow: var(--org-shadow-md);">
                            <i class="fas fa-check-circle text-xs"></i>
                            <span>Daftarkan Akun Faskes</span>
                        </button>

                    </form>

                    <div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--org-border); text-align: center; font-size: 13px; color: var(--org-text-muted);">
                        Sudah memiliki akun pendaftaran sebelumnya?
                        <a href="{{ route('login') }}" style="font-weight: 800; color: var(--org-primary); margin-left: 4px;">
                            Masuk di Sini
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('js')
<script>
    function toggleOrgPass(fieldId, btn) {
        var input = document.getElementById(fieldId);
        var icon = btn.querySelector('i');
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'far fa-eye-slash text-red-500';
        } else {
            input.type = 'password';
            icon.className = 'far fa-eye';
        }
    }
</script>
@endpush

