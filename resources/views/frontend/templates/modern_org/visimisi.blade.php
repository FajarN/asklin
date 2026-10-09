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
                <span style="color: #fff; font-weight: 700;">Visi &amp; Misi</span>
            </div>
            <h1 class="mod-inner-title">Visi, Misi &amp; Program Kerja</h1>
            <p class="mod-inner-sub">Fondasi Filosofis dan Target Strategis Asosiasi Klinik Indonesia Menuju Kemandirian Pelayanan</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.modern_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 30px;">

                <!-- Visi Card -->
                <div class="mod-card" style="margin-bottom: 0; background: linear-gradient(135deg, #020617, #0f172a); color: #fff; border-color: rgba(255,255,255,0.1);">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(220, 38, 38, 0.2); color: #fca5a5; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #fca5a5; letter-spacing: 1px;">Visi Strategis</span>
                            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0;">VISI</h2>
                        </div>
                    </div>
                    <blockquote style="margin: 0; padding: 22px; background: rgba(255,255,255,0.04); border-left: 3px solid var(--mod-primary); border-radius: 0 12px 12px 0; font-size: 16px; font-style: italic; color: #e2e8f0; line-height: 1.8;">
                        &ldquo;Menjadikan Klinik sebagai sarana pelayanan kesehatan terdepan dalam melayani kesehatan masyarakat dengan sistem rujukan yang terarah dan terpadu.&rdquo;
                    </blockquote>
                </div>

                <!-- Misi Card -->
                <div class="mod-card" style="margin-bottom: 0;">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div>
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 1px;">Aksi Berkelanjutan</span>
                            <h2 style="font-size: 22px; font-weight: 800; color: var(--mod-text-main); margin: 0;">MISI</h2>
                        </div>
                    </div>
                    <ol style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8; color: var(--mod-text-main);">
                        <li style="margin-bottom: 8px;">Membantu/memfasilitasi klinik menyelesaikan permasalahan.</li>
                        <li style="margin-bottom: 8px;">Menjaga standar klinik dalam memberikan pelayanan kepada masyarakat.</li>
                        <li style="margin-bottom: 8px;">Menghindari/mencegah persaingan tidak sehat antar klinik.</li>
                        <li style="margin-bottom: 8px;">Melakukan advokasi/pembelaan bagi klinik anggota.</li>
                        <li style="margin-bottom: 8px;">Menyampaikan informasi kepada klinik tentang program-program Pemerintah tentang kesehatan.</li>
                        <li>Membantu terlaksananya tertib administrasi klinik menuju pelayanan bermutu dan akuntabel.</li>
                    </ol>
                </div>

            </div>

            <!-- Tujuan & Fungsi Organisasi -->
            <div class="mod-card">
                <h3 style="font-size: 20px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 20px 0; border-bottom: 1px solid var(--mod-border); padding-bottom: 14px;">
                    Tujuan &amp; Fungsi Kelembagaan ASKLIN
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 36px;">
                    <div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mod-primary); margin: 0 0 12px 0;">
                            A. Tujuan Organisasi
                        </h4>
                        <div style="font-size: 14px; line-height: 1.8; color: var(--mod-text-main);">
                            <p style="font-weight: 700; margin-bottom: 6px;">Tujuan Umum:</p>
                            <ul style="padding-left: 20px; margin-top: 0;">
                                <li>Melakukan pemberdayaan klinik untuk meningkatkan pengelolaan dan mutu.</li>
                                <li>Meningkatkan profesionalisme penyelenggaraan klinik di Indonesia.</li>
                                <li>Meningkatkan sistem rujukan medis yang optimal dan terintegrasi.</li>
                            </ul>

                            <p style="font-weight: 700; margin: 16px 0 6px 0;">Tujuan Khusus:</p>
                            <ul style="padding-left: 20px; margin-top: 0;">
                                <li><strong>Untuk Klinik:</strong> Pendampingan manajerial, pengembangan usaha, dan fasilitas pembiayaan.</li>
                                <li><strong>Untuk SDM:</strong> Peningkatan kompetensi klinis, etika profesi, dan sertifikasi.</li>
                                <li><strong>Untuk Pemerintah:</strong> Membantu visi Indonesia Sehat dan keberlanjutan program JKN.</li>
                                <li><strong>Untuk Masyarakat:</strong> Memudahkan akses fasilitas klinik terpercaya dan bermutu.</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mod-primary); margin: 0 0 12px 0;">
                            B. Empat Fungsi Strategis
                        </h4>
                        <div style="space-y: 12px; font-size: 13.5px; line-height: 1.7;">
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 12px; border-left: 3px solid var(--mod-primary); margin-bottom: 10px;">
                                <strong style="color: var(--mod-text-main);">1. Pemberdayaan:</strong> Mendorong klinik mandiri dan menjadi pelaku utama pelayanan kesehatan berkualitas di tengah masyarakat.
                            </div>
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 12px; border-left: 3px solid var(--mod-primary); margin-bottom: 10px;">
                                <strong style="color: var(--mod-text-main);">2. Advokasi:</strong> Perlindungan hukum bagi klinik serta penyampaian aspirasi kebijakan kepada pemerintah.
                            </div>
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 12px; border-left: 3px solid var(--mod-primary); margin-bottom: 10px;">
                                <strong style="color: var(--mod-text-main);">3. Fasilitasi:</strong> Peningkatan kapasitas SDM, integrasi rujukan antarklinik, dan kemudahan fasilitas permodalan.
                            </div>
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 12px; border-left: 3px solid var(--mod-primary);">
                                <strong style="color: var(--mod-text-main);">4. Kemitraan:</strong> Kerjasama produktif dengan asuransi kesehatan swasta, BPJS, dan institusi penunjang medis.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

