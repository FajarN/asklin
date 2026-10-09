@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Galeri Dokumentasi</span>
            </div>
            <h1 class="mod-inner-title">Galeri Dokumentasi ASKLIN</h1>
            <p class="mod-inner-sub">Koleksi Visual Dokumentasi Kongres, Pelatihan Ilmiah &amp; Pertemuan Asosiasi Klinik Seluruh Indonesia</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                @forelse($galery as $item)
                    <div style="position: relative; aspect-ratio: 4 / 3; border-radius: 16px; overflow: hidden; box-shadow: var(--mod-shadow-sm); border: 1px solid var(--mod-border); group;">
                        @if(file_exists(public_path($item->foto)))
                            <img src="{{ asset($item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                        @elseif(file_exists(public_path('storage/' . $item->foto)))
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul ?? 'Galeri' }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #64748b;">
                                <i class="fas fa-image text-3xl"></i>
                            </div>
                        @endif
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent, rgba(2, 6, 23, 0.9)); padding: 14px 18px; color: #fff; font-size: 13px; font-weight: 700;">
                            {{ $item->judul ?? 'Dokumentasi ASKLIN' }}
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed var(--mod-border);">
                        <i class="fas fa-images text-4xl text-slate-300 mb-3"></i>
                        <h4 style="color: var(--mod-text-main); font-size: 16px; font-weight: 700;">Belum Ada Foto</h4>
                        <p style="color: var(--mod-text-muted); font-size: 13px;">Dokumentasi kegiatan organisasi akan segera diunggah.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

@endsection

