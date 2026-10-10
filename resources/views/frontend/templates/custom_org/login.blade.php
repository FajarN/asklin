@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Institutional Breadcrumbs & Header -->
    <div class="org-inner-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Portal Masuk</span>
            </div>
            <h1 class="org-inner-title">Autentikasi Faskes &amp; Pengurus</h1>
            <p class="org-inner-subtitle">Akses Resmi Sistem Informasi Manajemen Keanggotaan ASKLIN Seluruh Indonesia</p>
        </div>
    </div>

    <!-- Main Auth Content -->
    <section class="org-auth-section">
        <div class="custom-org-container">
            <div class="org-auth-grid" style="max-width: 1020px; margin: 0 auto;">

                <!-- Left: Institutional Accreditation & Trust Panel -->
                <div class="org-flow-panel">
                    <span class="org-tag-pill" style="margin-bottom: 14px;">Keamanan &amp; Integritas Data</span>
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--org-text-main); margin: 0 0 12px 0; line-height: 1.3;">
                        Portal Layanan Terpadu Fasilitas Pelayanan Kesehatan
                    </h2>
                    <p style="font-size: 13.5px; color: var(--org-text-muted); line-height: 1.7; margin-bottom: 24px;">
                        Masuk menggunakan kredensial email klinik yang telah terdaftar untuk mengelola berkas perizinan operasional (SIO), pembaruan data SDM klinis, pembayaran iuran berkala, serta penerbitan E-Sertifikat Anggota.
                    </p>

                    <div style="background: var(--org-border-light); border-radius: var(--org-radius-md); padding: 18px; border: 1px solid var(--org-border); margin-bottom: 24px;">
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <i class="fas fa-shield-check text-xl text-red-600 mt-0.5"></i>
                            <div>
                                <h4 style="font-size: 13.5px; font-weight: 800; color: var(--org-text-main); margin: 0 0 4px 0;">
                                    Protokol Keamanan Akses
                                </h4>
                                <p style="font-size: 12px; color: var(--org-text-muted); margin: 0; line-height: 1.5;">
                                    Kerahasiaan akun dan data operasional klinik Anda dilindungi dengan enkripsi berlapis sesuai standar kepatuhan tata kelola rekam medis nasional.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 13px; color: var(--org-text-muted);">
                        Belum mendaftarkan fasilitas klinik Anda?
                        <a href="{{ route('register') }}" style="font-weight: 700; color: var(--org-primary); margin-left: 4px;">
                            Registrasi Keanggotaan Baru <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: Formal Credential Login Form -->
                <div class="org-auth-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--org-border);">
                        <div>
                            <h2 class="org-auth-title" style="font-size: 20px;">Masuk Akun</h2>
                            <span style="font-size: 12px; color: var(--org-text-muted);">Silakan masukkan kredensial resmi</span>
                        </div>
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(185, 28, 28, 0.1); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <form action="{{ route('login') }}" method="POST" id="orgLoginForm">
                        @csrf

                        <!-- Email Input -->
                        <div class="org-form-group">
                            <label for="email" class="org-form-label">Email Terdaftar :</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="org-form-control @error('email') is-invalid @enderror" placeholder="nama@klinikanda.com">
                            @error('email')
                                <span class="org-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="org-form-group">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <label for="password" class="org-form-label" style="margin-bottom: 0;">Kata Sandi :</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="font-size: 11.5px; color: var(--org-primary); font-weight: 600;">
                                        Lupa Kata Sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="org-input-wrap">
                                <input id="password" type="password" name="password" required autocomplete="current-password" class="org-form-control @error('password') is-invalid @enderror" placeholder="••••••••" style="padding-right: 40px;">
                                <button type="button" class="org-input-icon-btn" onclick="toggleOrgPass('password', this)" aria-label="Lihat Kata Sandi">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="org-form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="org-form-group" style="margin-bottom: 24px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--org-text-main); cursor: pointer; user-select: none;">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: var(--org-primary); width: 16px; height: 16px;">
                                <span>Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="org-btn org-btn-primary" style="width: 100%; padding: 13px; font-size: 14px; font-weight: 800; justify-content: center; box-shadow: var(--org-shadow-md);">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Masuk ke Portal Faskes</span>
                        </button>

                    </form>

                    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--org-border); text-align: center; font-size: 12.5px; color: var(--org-text-muted);">
                        Butuh bantuan login? Hubungi Sekretariat: <strong style="color: var(--org-text-main);">0823-1098-1637</strong>
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

