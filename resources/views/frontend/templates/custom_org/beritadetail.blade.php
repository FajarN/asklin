@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('berita') }}">Berita</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Detail Warta</span>
            </div>
            <h1 class="org-page-title" style="font-size: 28px; max-width: 900px;">{{ $berita->judul }}</h1>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 12px; font-size: 13px; color: #cbd5e1;">
                <span><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}</span>
                <span><i class="far fa-user mr-1"></i> {{ $berita->creator->name ?? 'Redaksi ASKLIN' }}</span>
                <span><i class="far fa-eye mr-1"></i> {{ $berita->visits->count() ?? 1 }} Pembaca</span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">
            
            <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 40px; align-items: start;">
                
                <!-- Main Article Body -->
                <article class="org-content-card">
                    
                    <!-- Featured Image -->
                    @php
                        $thumbPath = null;
                        if (!empty($berita->thumb)) {
                            $thumbPath = file_exists(public_path('assets/images/berita/thumbnails/' . $berita->thumb))
                                ? asset('assets/images/berita/thumbnails/' . $berita->thumb)
                                : asset('storage/' . $berita->thumb);
                        } elseif (!empty($berita->thumbnail)) {
                            $thumbPath = asset('storage/' . $berita->thumbnail);
                        }
                    @endphp
                    @if($thumbPath)
                        <div style="border-radius: 12px; overflow: hidden; margin-bottom: 24px; max-height: 440px;">
                            <img src="{{ $thumbPath }}" alt="{{ $berita->judul }}" style="width: 100%; height: auto; object-fit: cover; display: block;">
                        </div>
                    @endif

                    <!-- Article Text -->
                    <div style="font-size: 15px; line-height: 1.9; color: var(--org-text-main); text-align: justify;" class="article-rich-text">
                        {!! $berita->konten !!}
                    </div>

                    <!-- Additional Images / Gallery if exists -->
                    @if(isset($images) && count($images) > 0)
                        <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid var(--org-border);">
                            <h4 style="font-size: 16px; font-weight: 800; color: var(--org-secondary); margin-bottom: 16px;">
                                <i class="fas fa-images text-red-600 mr-1.5"></i> Foto Dokumentasi Terkait:
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px;">
                                @foreach($images as $img)
                                    <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--org-border);">
                                        <img src="{{ asset('storage/' . $img->image) }}" alt="Dokumentasi" style="width: 100%; height: 140px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Article Footer / Share -->
                    <div style="margin-top: 36px; padding-top: 20px; border-top: 1px solid var(--org-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <a href="{{ route('berita') }}" class="org-btn org-btn-outline" style="font-size: 12px; padding: 8px 16px;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Indeks Berita</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 12px; font-weight: 700; color: var(--org-text-muted);">Bagikan:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" target="_blank" style="padding: 6px 12px; border-radius: 6px; background: #22c55e; color: #fff; font-size: 12px; font-weight: 700;">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>

                </article>

                <!-- Sidebar Right -->
                <aside>
                    <!-- Keanggotaan Box -->
                    <div class="org-content-card" style="padding: 24px; margin-bottom: 24px; background: var(--org-primary-soft); border-color: #fecaca;">
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--org-primary);">Informasi Anggota</span>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--org-primary-dark); margin: 6px 0 10px 0;">Pendaftaran Klinik Resmi</h4>
                        <p style="font-size: 13px; color: var(--org-secondary); line-height: 1.6; margin-bottom: 16px;">
                            Bergabunglah bersama ribuan fasilitas pelayanan kesehatan klinik se-Indonesia dalam jejaring ASKLIN.
                        </p>
                        <a href="{{ route('register') }}" class="org-btn org-btn-primary" style="width: 100%; font-size: 13px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Registrasi Sekarang</span>
                        </a>
                    </div>

                    <!-- Kontak Sekretariat Box -->
                    <div class="org-content-card" style="padding: 24px;">
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--org-secondary); margin: 0 0 12px 0;">Sekretariat PP ASKLIN</h4>
                        <p style="font-size: 12.5px; color: var(--org-text-muted); line-height: 1.6; margin-bottom: 12px;">
                            Pusat koordinasi, advokasi, dan layanan konsultasi perizinan klinik.
                        </p>
                        <div style="font-size: 12px; color: var(--org-secondary); space-y: 6px;">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-phone-alt text-red-600"></i> 021-8690 1234</div>
                            <div class="flex items-center gap-2"><i class="fas fa-envelope text-red-600"></i> ppasklin.indonesia@gmail.com</div>
                        </div>
                    </div>
                </aside>

            </div>

        </div>
    </div>

@endsection
