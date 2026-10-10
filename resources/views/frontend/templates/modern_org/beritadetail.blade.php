@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('berita') }}">Berita</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Detail Artikel</span>
            </div>
            <h1 class="mod-inner-title" style="font-size: 30px; max-width: 900px; line-height: 1.3;">{{ $berita->judul }}</h1>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 14px; font-size: 13px; color: #cbd5e1; flex-wrap: wrap;">
                <span><i class="far fa-calendar-alt text-red-400 mr-1.5"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}</span>
                <span><i class="far fa-user text-red-400 mr-1.5"></i> {{ $berita->creator->name ?? 'Redaksi ASKLIN' }}</span>
                <span><i class="far fa-eye text-red-400 mr-1.5"></i> {{ $berita->visits->count() ?? 1 }} Pembaca</span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">
            
            <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 40px; align-items: start;">
                
                <!-- Article Column -->
                <article class="mod-card">
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
                        <div style="border-radius: 16px; overflow: hidden; margin-bottom: 28px; max-height: 460px;">
                            <img src="{{ $thumbPath }}" alt="{{ $berita->judul }}" style="width: 100%; height: auto; object-fit: cover; display: block;">
                        </div>
                    @endif

                    <div style="font-size: 15.5px; line-height: 1.95; color: var(--mod-text-main); text-align: justify;" class="article-content">
                        {!! $berita->konten !!}
                    </div>

                    @if(isset($images) && count($images) > 0)
                        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--mod-border);">
                            <h4 style="font-size: 16px; font-weight: 800; color: var(--mod-text-main); margin-bottom: 16px;">
                                <i class="fas fa-images text-red-500 mr-1.5"></i> Dokumentasi Terkait
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px;">
                                @foreach($images as $img)
                                    <div style="border-radius: 12px; overflow: hidden; border: 1px solid var(--mod-border);">
                                        <img src="{{ asset('storage/' . $img->image) }}" alt="Foto" style="width: 100%; height: 140px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--mod-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <a href="{{ route('berita') }}" class="mod-btn mod-btn-glass" style="color: var(--mod-text-main) !important; border-color: var(--mod-border); background: #f8fafc; font-size: 12.5px; padding: 8px 18px;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Warta</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 12.5px; font-weight: 700; color: var(--mod-text-muted);">Share:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; background: #22c55e; color: #fff; font-size: 12px; font-weight: 700;">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside>
                    <!-- Keanggotaan Modern Box -->
                    <div style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: var(--mod-radius-lg); padding: 28px; color: #fff; margin-bottom: 24px; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #fca5a5; letter-spacing: 1px;">Keanggotaan</span>
                        <h4 style="font-size: 17px; font-weight: 800; color: #fff; margin: 8px 0 12px 0;">Registrasi Anggota Klinik</h4>
                        <p style="font-size: 13px; color: #94a3b8; line-height: 1.6; margin-bottom: 20px;">
                            Dapatkan perlindungan hukum profesi, bimbingan akreditasi, dan jejaring fasilitas klinik nasional.
                        </p>
                        <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="width: 100%; font-size: 13px; padding: 12px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Daftar Sekarang</span>
                        </a>
                    </div>

                    <!-- Kontak Quick Box -->
                    <div class="mod-card" style="padding: 24px;">
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 12px 0;">Sekretariat PP ASKLIN</h4>
                        <p style="font-size: 12.5px; color: var(--mod-text-muted); line-height: 1.6; margin-bottom: 14px;">
                            Hubungi narahubung resmi kami jika membutuhkan konsultasi atau informasi organisasi.
                        </p>
                        <div style="font-size: 12.5px; color: var(--mod-text-main); space-y: 8px;">
                            <div class="flex items-center gap-2 mb-2"><i class="fas fa-phone-alt text-red-600"></i> 021-8690 1234</div>
                            <div class="flex items-center gap-2"><i class="fas fa-envelope text-red-600"></i> pp_asklin@asklin.org</div>
                        </div>
                    </div>
                </aside>

            </div>

        </div>
    </div>

@endsection
