@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <section class="mod-auth-section">
        <div class="mod-hero-glow-blob mod-hero-glow-blob-1"></div>
        <div class="mod-hero-glow-blob mod-hero-glow-blob-2"></div>

        <div class="modern-org-container">
            <div class="mod-auth-bento-grid">

                <!-- Left: Accelerated Digital Onboarding Modules (4 Modul Onboarding) -->
                <div class="mod-onboarding-card">
                    <span class="mod-auth-badge">
                        <span class="mod-pulse-dot" style="width: 6px; height: 6px;"></span>
                        <span>DIGITAL ONBOARDING HUB</span>
                    </span>

                    <h1 style="font-size: 28px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 10px 0; line-height: 1.25; letter-spacing: -0.5px;">
                        Alur Akselerasi <span class="mod-gradient-text">Keanggotaan Faskes</span>
                    </h1>

                    <p style="font-size: 14px; color: var(--mod-text-muted); line-height: 1.7; margin: 0 0 24px 0;">
                        Proses pendaftaran terintegrasi digital untuk mempercepat verifikasi izin operasional dan penerbitan nomor registrasi klinik nasional.
                    </p>

                    <!-- Bento Step List -->
                    <div class="mod-onboarding-steps">

                        <!-- Module 1 -->
                        <div class="mod-onboarding-step active">
                            <div class="mod-onboarding-icon">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <div>
                                <span style="font-size: 10px; font-weight: 800; color: var(--mod-primary-light); text-transform: uppercase; letter-spacing: 0.5px;">MODUL 01 • AKTIF</span>
                                <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 2px 0 4px 0;">Genesis Akun Faskes</h3>
                                <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0; line-height: 1.5;">
                                    Pembuatan akun digital tunggal perwakilan klinik untuk login ke seluruh layanan asosiasi.
                                </p>
                            </div>
                        </div>

                        <!-- Module 2 -->
                        <div class="mod-onboarding-step">
                            <div class="mod-onboarding-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                                <i class="fas fa-file-medical-alt"></i>
                            </div>
                            <div>
                                <span style="font-size: 10px; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px;">MODUL 02</span>
                                <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 2px 0 4px 0;">Sinkronisasi SIO &amp; SDM Medis</h3>
                                <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0; line-height: 1.5;">
                                    Input data Surat Izin Operasional dan tenaga medis penanggung jawab fasilitas.
                                </p>
                            </div>
                        </div>

                        <!-- Module 3 -->
                        <div class="mod-onboarding-step">
                            <div class="mod-onboarding-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <div>
                                <span style="font-size: 10px; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.5px;">MODUL 03</span>
                                <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 2px 0 4px 0;">Dual-Tier Digital Review</h3>
                                <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0; line-height: 1.5;">
                                    Peninjauan validitas faskes oleh tim verifikator Cabang dan Pengurus Daerah secara online.
                                </p>
                            </div>
                        </div>

                        <!-- Module 4 -->
                        <div class="mod-onboarding-step">
                            <div class="mod-onboarding-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <div>
                                <span style="font-size: 10px; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px;">MODUL 04</span>
                                <h3 style="font-size: 15px; font-weight: 800; color: var(--mod-text-heading); margin: 2px 0 4px 0;">Smart Digital ID &amp; QR Sertifikat</h3>
                                <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0; line-height: 1.5;">
                                    Penerbitan Nomor Anggota Nasional ASKLIN dan Sertifikat resmi ber-QR Code valid.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: Glassmorphic Registration Form -->
                <div class="mod-auth-card">
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary-light); letter-spacing: 1px; display: block; margin-bottom: 4px;">
                            Registrasi Baru
                        </span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px 0;">
                            Daftarkan Klinik Anda
                        </h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0;">
                            Lengkapi data awal untuk membuat akun pendaftaran faskes
                        </p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" id="modRegisterForm">
                        @csrf

                        <!-- Nama Klinik -->
                        <div class="mod-form-group">
                            <label for="name" class="mod-form-label">Nama Fasilitas Klinik :</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="mod-form-input @error('name') is-invalid @enderror" placeholder="Contoh: Klinik Pratama Medika Utama">
                            <span style="font-size: 11px; color: var(--mod-text-dim); margin-top: 3px; display: block;">Sesuai izin operasional (SIO) yang diterbitkan dinas kesehatan/PTSP</span>
                            @error('name')
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mod-form-group">
                            <label for="email" class="mod-form-label">Email Resmi Faskes :</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="mod-form-input @error('email') is-invalid @enderror" placeholder="official@klinikanda.com">
                            <span style="font-size: 11px; color: var(--mod-text-dim); margin-top: 3px; display: block;">Akan digunakan untuk login dan korespondensi resmi organisasi</span>
                            @error('email')
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mod-form-group">
                            <label for="password" class="mod-form-label">Kata Sandi :</label>
                            <div class="mod-input-wrapper">
                                <input id="password" type="password" name="password" required autocomplete="new-password" class="mod-form-input @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" style="padding-right: 42px;">
                                <button type="button" onclick="toggleModPass('password', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--mod-text-dim); cursor: pointer; font-size: 14px;">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mod-form-group">
                            <label for="password-confirm" class="mod-form-label">Konfirmasi Kata Sandi :</label>
                            <div class="mod-input-wrapper">
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="mod-form-input" placeholder="Ulangi kata sandi" style="padding-right: 42px;">
                                <button type="button" onclick="toggleModPass('password-confirm', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--mod-text-dim); cursor: pointer; font-size: 14px;">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Agreement Checkbox -->
                        <div class="mod-form-group" style="margin-bottom: 24px;">
                            <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 12.5px; color: var(--mod-text-muted); cursor: pointer; line-height: 1.5;">
                                <input type="checkbox" name="agree" required style="accent-color: var(--mod-primary); width: 16px; height: 16px; margin-top: 2px;">
                                <span>Menyetujui persyaratan keanggotaan ASKLIN dan berkomitmen menjaga standar mutu klinis serta kepatuhan hukum faskes.</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="mod-btn mod-btn-primary" style="width: 100%; padding: 13px; font-size: 14px; font-weight: 800; justify-content: center;">
                            <i class="fas fa-rocket text-xs"></i>
                            <span>Daftar Sekarang</span>
                        </button>

                    </form>

                    <div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--mod-border); text-align: center; font-size: 12.5px; color: var(--mod-text-muted);">
                        Sudah memiliki akun pendaftaran faskes?
                        <a href="{{ route('login') }}" style="font-weight: 800; color: var(--mod-primary-light); margin-left: 4px;">
                            Masuk Portal di Sini
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('js')
<script>
    function toggleModPass(fieldId, btn) {
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

