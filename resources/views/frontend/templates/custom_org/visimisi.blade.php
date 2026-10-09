@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span>Profil Organisasi</span>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Visi &amp; Misi</span>
            </div>
            <h1 class="org-page-title">Visi &amp; Misi ASKLIN</h1>
            <p class="org-page-subtitle">Arah Strategis, Tujuan, Fungsi Kelembagaan &amp; Program Kerja Asosiasi Klinik Indonesia</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.custom_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <!-- Visi & Misi Cards Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 26px; margin-bottom: 30px;">

                <!-- Card Visi -->
                <div class="org-content-card" style="margin-bottom: 0; border-top: 4px solid var(--org-primary);">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
                        <div style="width: 48px; height: 48px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--org-primary);">Cita-Cita Luhur</span>
                            <h2 style="font-size: 22px; font-weight: 800; color: var(--org-secondary); margin: 0;">VISI</h2>
                        </div>
                    </div>
                    <blockquote style="margin: 0; padding: 20px; background: var(--org-bg-body); border-left: 4px solid var(--org-primary); border-radius: 0 8px 8px 0; font-size: 16px; font-weight: 600; font-style: italic; color: var(--org-secondary); line-height: 1.7;">
                        &ldquo;Menjadikan Klinik sebagai sarana pelayanan kesehatan terdepan dalam melayani kesehatan masyarakat dengan sistem rujukan yang terarah dan terpadu.&rdquo;
                    </blockquote>
                </div>

                <!-- Card Misi -->
                <div class="org-content-card" style="margin-bottom: 0; border-top: 4px solid var(--org-primary-dark);">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
                        <div style="width: 48px; height: 48px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary-dark); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--org-primary);">Langkah Nyata</span>
                            <h2 style="font-size: 22px; font-weight: 800; color: var(--org-secondary); margin: 0;">MISI</h2>
                        </div>
                    </div>
                    <ol style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8; color: var(--org-text-main);">
                        <li style="margin-bottom: 8px;">Membantu dan memfasilitasi klinik dalam menyelesaikan kendala operasional dan regulasi.</li>
                        <li style="margin-bottom: 8px;">Menjaga standar kualitas klinik dalam memberikan pelayanan kesehatan masyarakat.</li>
                        <li style="margin-bottom: 8px;">Menghindari dan mencegah persaingan tidak sehat antar fasilitas kesehatan klinik.</li>
                        <li style="margin-bottom: 8px;">Melakukan advokasi serta pembelaan hukum bagi klinik anggota.</li>
                        <li style="margin-bottom: 8px;">Menyampaikan informasi program-program kesehatan pemerintah kepada klinik dan masyarakat.</li>
                        <li>Membantu terlaksananya tertib administrasi klinis menuju pelayanan berkualitas dan akuntabel.</li>
                    </ol>
                </div>

            </div>

            <!-- Tujuan & Fungsi ASKLIN -->
            <div class="org-content-card">
                <div style="border-bottom: 1px solid var(--org-border-light); padding-bottom: 16px; margin-bottom: 24px;">
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--org-secondary); margin: 0;">
                        Tujuan &amp; Fungsi Asosiasi bagi Anggota
                    </h3>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--org-primary-dark); margin: 0 0 12px 0;">
                            <i class="fas fa-bullseye text-red-600 mr-1.5"></i> Tujuan ASKLIN
                        </h4>
                        <div style="font-size: 14px; line-height: 1.8; color: var(--org-text-main);">
                            <p style="font-weight: 700; margin-bottom: 6px;">A. Tujuan Umum:</p>
                            <ul style="padding-left: 20px; margin-top: 0;">
                                <li>Melakukan pemberdayaan klinik guna meningkatkan mutu pengelolaan dan layanan.</li>
                                <li>Meningkatkan profesionalisme penyelenggaraan klinik pratama dan utama.</li>
                                <li>Meningkatkan sistem rujukan medis berjenjang yang optimal.</li>
                            </ul>

                            <p style="font-weight: 700; margin: 14px 0 6px 0;">B. Tujuan Khusus:</p>
                            <ul style="padding-left: 20px; margin-top: 0;">
                                <li><strong>Bagi Klinik:</strong> Membantu manajemen, pengembangan usaha, dan fasilitas pembiayaan.</li>
                                <li><strong>Bagi SDM Medis:</strong> Peningkatan kompetensi, sertifikasi, dan etika profesi.</li>
                                <li><strong>Bagi Pemerintah:</strong> Mendukung percepatan visi Indonesia Sehat dan JKN.</li>
                                <li><strong>Bagi Masyarakat:</strong> Memudahkan akses layanan klinik bermutu dan terjangkau.</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: var(--org-primary-dark); margin: 0 0 12px 0;">
                            <i class="fas fa-cogs text-red-600 mr-1.5"></i> Empat Fungsi Strategis
                        </h4>
                        <div style="space-y: 12px; font-size: 13.5px; line-height: 1.7; color: var(--org-text-main);">
                            <div style="padding: 12px 16px; background: var(--org-bg-body); border-radius: 8px; border-left: 3px solid var(--org-primary); margin-bottom: 10px;">
                                <strong>1. Pemberdayaan:</strong> Mendorong klinik menempatkan diri secara mandiri dan profesional.
                            </div>
                            <div style="padding: 12px 16px; background: var(--org-bg-body); border-radius: 8px; border-left: 3px solid var(--org-primary); margin-bottom: 10px;">
                                <strong>2. Advokasi:</strong> Pembelaan kepentingan klinik dalam implementasi regulasi dan kemitraan JKN.
                            </div>
                            <div style="padding: 12px 16px; background: var(--org-bg-body); border-radius: 8px; border-left: 3px solid var(--org-primary); margin-bottom: 10px;">
                                <strong>3. Fasilitasi:</strong> Peningkatan kapasitas SDM, pembiayaan, serta standarisasi rekam medis.
                            </div>
                            <div style="padding: 12px 16px; background: var(--org-bg-body); border-radius: 8px; border-left: 3px solid var(--org-primary);">
                                <strong>4. Kemitraan:</strong> Kerjasama produktif dengan instansi pemerintah, BPJS, dan swasta.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

