@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Galeri Dokumentasi</span>
            </div>
            <h1 class="org-page-title">Galeri Dokumentasi Kegiatan ASKLIN</h1>
            <p class="org-page-subtitle">Dokumentasi Visual Musyawarah, Pertemuan Ilmiah, Pelantikan Pengurus &amp; Kunjungan Kerja Klinik</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <div class="org-gallery-grid">
                @forelse($galery as $item)
                    <div class="org-gallery-item">
                        @if(file_exists(public_path($item->foto)))
                            <img src="{{ asset($item->foto) }}" alt="{{ $item->judul ?? 'Galeri ASKLIN' }}" class="org-gallery-img">
                        @elseif(file_exists(public_path('storage/' . $item->foto)))
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul ?? 'Galeri ASKLIN' }}" class="org-gallery-img">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                <i class="fas fa-image text-3xl"></i>
                            </div>
                        @endif
                        <div class="org-gallery-overlay">
                            <span class="org-gallery-caption">{{ $item->judul ?? 'Dokumentasi ASKLIN' }}</span>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed var(--org-border);">
                        <i class="fas fa-images text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--org-secondary); font-size: 16px; font-weight: 700;">Belum Ada Foto Dokumentasi</h4>
                        <p style="color: var(--org-text-muted); font-size: 13px;">Dokumentasi kegiatan organisasi akan segera diperbarui.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

@endsection

