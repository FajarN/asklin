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
                <span style="color: #ffffff; font-weight: 700;">Pengurus Pusat</span>
            </div>
            <h1 class="org-page-title">Struktur Organisasi Pengurus Pusat</h1>
            <p class="org-page-subtitle">{{ $struktur->nama_struktur }} • Periode: {{ $struktur->periode }}</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.custom_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            @php
                $pembina = [];
                $penasehat = [];
                $bidang = [];
                $koordinator_daerah = [];

                $ketuaumum = null;
                $wakil_ketua_umum1 = null;
                $wakil_ketua_umum2 = null;
                $sekretaris_jendral = null;
                $sekretaris = null;
                $wakil_sekretaris1 = null;
                $wakil_sekretaris2 = null;
                $bendahara = null;
                $wakil_bendahara1 = null;
                $wakil_bendahara2 = null;

                foreach ($pengurus as $p) {
                    $jabatan = strtolower(trim($p->jabatan));

                    if (strpos($jabatan, 'pembina') !== false) {
                        $pembina[] = $p;
                    } elseif (strpos($jabatan, 'penasehat') !== false || strpos($jabatan, 'penasihat') !== false) {
                        $penasehat[] = $p;
                    } elseif ($jabatan === 'ketua umum') {
                        $ketuaumum = $p;
                    } elseif ($jabatan === 'wakil ketua umum 1') {
                        $wakil_ketua_umum1 = $p;
                    } elseif ($jabatan === 'wakil ketua umum 2') {
                        $wakil_ketua_umum2 = $p;
                    } elseif ($jabatan === 'sekretaris jenderal' || $jabatan === 'sekjen') {
                        $sekretaris_jendral = $p;
                    } elseif ($jabatan === 'sekretaris') {
                        $sekretaris = $p;
                    } elseif ($jabatan === 'wakil sekretaris 1') {
                        $wakil_sekretaris1 = $p;
                    } elseif ($jabatan === 'wakil sekretaris 2') {
                        $wakil_sekretaris2 = $p;
                    } elseif ($jabatan === 'bendahara' || $jabatan === 'bendahara umum') {
                        $bendahara = $p;
                    } elseif ($jabatan === 'wakil bendahara 1') {
                        $wakil_bendahara1 = $p;
                    } elseif ($jabatan === 'wakil bendahara 2') {
                        $wakil_bendahara2 = $p;
                    } elseif (strpos($jabatan, 'koordinator') !== false) {
                        $koordinator_daerah[] = $p;
                    } elseif (strpos($jabatan, 'bidang') !== false) {
                        $bidangName = $p->jabatan;
                        if (!isset($bidang[$bidangName])) {
                            $bidang[$bidangName] = [
                                'ketua' => null,
                                'anggota' => [],
                            ];
                        }
                        $anggota = \App\Models\StrukturPengurus::where('parent_id', $p->id)
                            ->orderBy('urutan', 'asc')
                            ->get();
                        $bidang[$bidangName]['ketua'] = $p;
                        $bidang[$bidangName]['anggota'] = $anggota;
                    }
                }
            @endphp

            <!-- Summary Meta Card -->
            <div class="org-content-card" style="margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--org-primary);">Kepengurusan Resmi</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--org-secondary); margin: 4px 0 0 0;">{{ $struktur->nama_struktur }}</h2>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <span style="padding: 6px 14px; background: var(--org-primary-soft); color: var(--org-primary-dark); border-radius: 9999px; font-size: 12px; font-weight: 700;">
                            <i class="far fa-calendar-alt mr-1"></i> Periode: {{ $struktur->periode }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Dewan Pembina & Penasehat -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                @if(count($pembina) > 0)
                    <div class="org-structure-box">
                        <div class="org-structure-header">
                            <i class="fas fa-user-shield"></i>
                            <span>Dewan Pembina</span>
                        </div>
                        <div style="padding: 16px; background: #fff;">
                            <ul style="list-style: none; padding: 0; margin: 0; space-y: 10px;">
                                @foreach($pembina as $p)
                                    <li style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--org-secondary); margin-bottom: 8px;">
                                        <i class="fas fa-check-circle text-red-600"></i>
                                        <span>{{ $p->nama_lengkap }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(count($penasehat) > 0)
                    <div class="org-structure-box">
                        <div class="org-structure-header">
                            <i class="fas fa-user-tie"></i>
                            <span>Dewan Penasehat</span>
                        </div>
                        <div style="padding: 16px; background: #fff;">
                            <ul style="list-style: none; padding: 0; margin: 0; space-y: 10px;">
                                @foreach($penasehat as $p)
                                    <li style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--org-secondary); margin-bottom: 8px;">
                                        <i class="fas fa-check-circle text-red-600"></i>
                                        <span>{{ $p->nama_lengkap }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pengurus Harian (Ketua Umum, Sekjen, Bendahara) -->
            <div class="org-structure-box" style="margin-bottom: 24px;">
                <div class="org-structure-header" style="background-color: var(--org-primary-dark);">
                    <i class="fas fa-users-cog"></i>
                    <span>Pengurus Harian PP ASKLIN</span>
                </div>
                <div class="org-structure-grid">
                    @if($ketuaumum)
                        <div class="org-pengurus-card" style="border-left: 4px solid var(--org-primary);">
                            <div class="org-pengurus-avatar"><i class="fas fa-star"></i></div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $ketuaumum->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $ketuaumum->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($wakil_ketua_umum1)
                        <div class="org-pengurus-card">
                            <div class="org-pengurus-avatar">W</div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $wakil_ketua_umum1->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $wakil_ketua_umum1->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($wakil_ketua_umum2)
                        <div class="org-pengurus-card">
                            <div class="org-pengurus-avatar">W</div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $wakil_ketua_umum2->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $wakil_ketua_umum2->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($sekretaris_jendral)
                        <div class="org-pengurus-card" style="border-left: 4px solid var(--org-primary);">
                            <div class="org-pengurus-avatar"><i class="fas fa-file-signature"></i></div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $sekretaris_jendral->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $sekretaris_jendral->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($sekretaris)
                        <div class="org-pengurus-card">
                            <div class="org-pengurus-avatar">S</div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $sekretaris->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $sekretaris->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($wakil_sekretaris1)
                        <div class="org-pengurus-card">
                            <div class="org-pengurus-avatar">S</div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $wakil_sekretaris1->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $wakil_sekretaris1->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($bendahara)
                        <div class="org-pengurus-card" style="border-left: 4px solid var(--org-primary);">
                            <div class="org-pengurus-avatar"><i class="fas fa-coins"></i></div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $bendahara->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $bendahara->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                    @if($wakil_bendahara1)
                        <div class="org-pengurus-card">
                            <div class="org-pengurus-avatar">B</div>
                            <div class="org-pengurus-info">
                                <span class="org-pengurus-jabatan">{{ $wakil_bendahara1->jabatan }}</span>
                                <div class="org-pengurus-nama">{{ $wakil_bendahara1->nama_lengkap }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bidang-Bidang -->
            @if(count($bidang) > 0)
                <div class="org-structure-box">
                    <div class="org-structure-header" style="background-color: var(--org-secondary);">
                        <i class="fas fa-th-list"></i>
                        <span>Ketua Bidang &amp; Anggota Kompartemen</span>
                    </div>
                    <div style="padding: 20px; background: #fff; display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
                        @foreach($bidang as $namaBidang => $data)
                            <div style="border: 1px solid var(--org-border); border-radius: 8px; padding: 16px; background-color: var(--org-bg-body);">
                                <div style="font-size: 13px; font-weight: 800; color: var(--org-primary-dark); margin-bottom: 8px; text-transform: uppercase;">
                                    {{ $namaBidang }}
                                </div>
                                @if($data['ketua'])
                                    <div style="font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">
                                        <i class="fas fa-user-check text-red-600 mr-1"></i> Ketua: {{ $data['ketua']->nama_lengkap }}
                                    </div>
                                @endif
                                @if($data['anggota'] && $data['anggota']->count() > 0)
                                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--org-border);">
                                        <span style="font-size: 11px; font-weight: 600; color: var(--org-text-muted);">Anggota:</span>
                                        <ul style="padding-left: 16px; margin: 4px 0 0 0; font-size: 12px; color: var(--org-text-main);">
                                            @foreach($data['anggota'] as $ang)
                                                <li>{{ $ang->nama_lengkap }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

