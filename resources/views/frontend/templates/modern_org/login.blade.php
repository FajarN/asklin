@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <section class="mod-auth-section">
        <div class="mod-hero-glow-blob mod-hero-glow-blob-1"></div>
        <div class="mod-hero-glow-blob mod-hero-glow-blob-2"></div>

        <div class="modern-org-container">
            <div class="mod-auth-bento-grid" style="max-width: 1040px; margin: 0 auto;">

                <!-- Left: Next-Gen Value Proposition & Ecosystem Features -->
                <div class="mod-onboarding-card">
                    <span class="mod-auth-badge">
                        <span class="mod-pulse-dot" style="width: 6px; height: 6px;"></span>
                        <span>PORTAL DIGITAL ANGGOTA</span>
                    </span>

                    <h1 style="font-size: 28px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 12px 0; line-height: 1.25; letter-spacing: -0.5px;">
                        Akselerasi Layanan <span class="mod-gradient-text">Fasilitas Klinik</span>
                    </h1>

                    <p style="font-size: 14px; color: var(--mod-text-muted); line-height: 1.7; margin: 0 0 28px 0;">
                        Sistem informasi terintegrasi bagi pengelola klinik anggota ASKLIN se-Indonesia untuk monitoring status legalitas, perizinan berjenjang, dan tata kelola faskes modern.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px;">
                        <div style="display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary-light); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 13.5px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 2px 0;">E-Sertifikat Terverifikasi QR</h4>
                                <span style="font-size: 11.5px; color: var(--mod-text-muted);">Validasi keaslian dokumen anggota secara instan</span>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: var(--mod-radius-md); background: var(--mod-bg-alt); border: 1px solid var(--mod-border);">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(2, 132, 199, 0.1); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                <i class="fas fa-laptop-medical"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 13.5px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 2px 0;">Kesiapan RME &amp; SATUSEHAT</h4>
                                <span style="font-size: 11.5px; color: var(--mod-text-muted);">Panduan integrasi standar rekam medis Kemenkes RI</span>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 13px; color: var(--mod-text-muted);">
                        Klinik Anda belum terdaftar di ASKLIN?
                        <a href="{{ route('register') }}" style="font-weight: 800; color: var(--mod-primary-light); margin-left: 4px;">
                            Mulai Registrasi Baru <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: Glassmorphic Next-Gen Login Card -->
                <div class="mod-auth-card">
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary-light); letter-spacing: 1px; display: block; margin-bottom: 4px;">
                            Autentikasi Akun
                        </span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--mod-text-heading); margin: 0 0 4px 0;">
                            Masuk Portal Faskes
                        </h2>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); margin: 0;">
                            Gunakan email dan kata sandi yang telah didaftarkan
                        </p>
                    </div>

                    <form action="{{ route('login') }}" method="POST" id="modLoginForm">
                        @csrf

                        <!-- Email -->
                        <div class="mod-form-group">
                            <label for="email" class="mod-form-label">Email Faskes :</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="mod-form-input @error('email') is-invalid @enderror" placeholder="email@klinik.id">
                            @error('email')
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mod-form-group">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <label for="password" class="mod-form-label" style="margin-bottom: 0;">Kata Sandi :</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="font-size: 12px; color: var(--mod-primary-light); font-weight: 600;">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>
                            <div class="mod-input-wrapper">
                                <input id="password" type="password" name="password" required autocomplete="current-password" class="mod-form-input @error('password') is-invalid @enderror" placeholder="••••••••" style="padding-right: 42px;">
                                <button type="button" onclick="toggleModPass('password', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--mod-text-dim); cursor: pointer; font-size: 14px;">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mod-form-group" style="margin-bottom: 24px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--mod-text-body); cursor: pointer;">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: var(--mod-primary); width: 16px; height: 16px;">
                                <span>Ingat kredensial di sesi ini</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="mod-btn mod-btn-primary" style="width: 100%; padding: 13px; font-size: 14px; font-weight: 800; justify-content: center;">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Buka Portal Anggota</span>
                        </button>

                    </form>

                    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--mod-border); text-align: center; font-size: 12.5px; color: var(--mod-text-muted);">
                        Butuh bantuan login? Hubungi Sekretariat: <strong style="color: var(--mod-text-heading);">0823-1098-1637</strong>
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

