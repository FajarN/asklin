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
                <span style="color: #fff; font-weight: 700;">Pengurus Pusat</span>
            </div>
            <h1 class="mod-inner-title">Struktur Pengurus Pusat ASKLIN</h1>
            <p class="mod-inner-sub">{{ $struktur->nama_struktur }} • Periode Bakti: {{ $struktur->periode }}</p>
        </div>
    </div>

    <!-- Subnav -->
    @include('frontend.templates.modern_org.layouts.subnav')

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            @php
                $pembina = [];
                $penasehat = [];
                $bidang = [];

                $ketuaumum = null;
                $wakil_ketua_umum1 = null;
                $wakil_ketua_umum2 = null;
                $sekretaris_jendral = null;
                $sekretaris = null;
                $wakil_sekretaris1 = null;
                $bendahara = null;
                $wakil_bendahara1 = null;

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
                    } elseif ($jabatan === 'bendahara' || $jabatan === 'bendahara umum') {
                        $bendahara = $p;
                    } elseif ($jabatan === 'wakil bendahara 1') {
                        $wakil_bendahara1 = $p;
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

            <!-- Summary Card -->
            <div class="mod-card" style="margin-bottom: 24px; background: linear-gradient(135deg, #020617, #0f172a); color: #fff; border-color: rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #fca5a5; letter-spacing: 1px;">Susunan Kepengurusan</span>
                        <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin: 4px 0 0 0;">{{ $struktur->nama_struktur }}</h2>
                    </div>
                    <span style="padding: 6px 16px; border-radius: 9999px; background: rgba(220, 38, 38, 0.2); border: 1px solid rgba(220, 38, 38, 0.4); color: #fca5a5; font-size: 12.5px; font-weight: 700;">
                        <i class="far fa-calendar-alt mr-1"></i> Periode: {{ $struktur->periode }}
                    </span>
                </div>
            </div>

            <!-- Pembina & Penasehat -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
                @if(count($pembina) > 0)
                    <div class="mod-card" style="margin-bottom: 0;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--mod-border);">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--mod-text-main); margin: 0;">Dewan Pembina</h3>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0; space-y: 10px;">
                            @foreach($pembina as $p)
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 8px;">
                                    <i class="fas fa-check-circle text-red-500"></i>
                                    <span>{{ $p->nama_lengkap }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(count($penasehat) > 0)
                    <div class="mod-card" style="margin-bottom: 0;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--mod-border);">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--mod-text-main); margin: 0;">Dewan Penasehat</h3>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0; space-y: 10px;">
                            @foreach($penasehat as $p)
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 8px;">
                                    <i class="fas fa-check-circle text-red-500"></i>
                                    <span>{{ $p->nama_lengkap }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Pengurus Harian Grid -->
            <div class="mod-card" style="margin-bottom: 28px;">
                <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 20px 0; border-bottom: 1px solid var(--mod-border); padding-bottom: 14px;">
                    Pengurus Harian PP ASKLIN
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
                    @if($ketuaumum)
                        <div style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.06), rgba(153, 27, 27, 0.06)); border: 1px solid rgba(220, 38, 38, 0.25); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, var(--mod-primary), var(--mod-primary-dark)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="fas fa-award"></i></div>
                            <div>
                                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 0.5px;">{{ $ketuaumum->jabatan }}</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $ketuaumum->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                    @if($wakil_ketua_umum1)
                        <div style="background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-weight: 800;">W</div>
                            <div>
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">{{ $wakil_ketua_umum1->jabatan }}</span>
                                <h4 style="font-size: 13.5px; font-weight: 700; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $wakil_ketua_umum1->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                    @if($wakil_ketua_umum2)
                        <div style="background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-weight: 800;">W</div>
                            <div>
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">{{ $wakil_ketua_umum2->jabatan }}</span>
                                <h4 style="font-size: 13.5px; font-weight: 700; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $wakil_ketua_umum2->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                    @if($sekretaris_jendral)
                        <div style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.06), rgba(153, 27, 27, 0.06)); border: 1px solid rgba(220, 38, 38, 0.25); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, var(--mod-primary), var(--mod-primary-dark)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px;"><i class="fas fa-file-contract"></i></div>
                            <div>
                                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 0.5px;">{{ $sekretaris_jendral->jabatan }}</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $sekretaris_jendral->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                    @if($sekretaris)
                        <div style="background: #f8fafc; border: 1px solid var(--mod-border); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-weight: 800;">S</div>
                            <div>
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--mod-text-muted);">{{ $sekretaris->jabatan }}</span>
                                <h4 style="font-size: 13.5px; font-weight: 700; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $sekretaris->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                    @if($bendahara)
                        <div style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.06), rgba(153, 27, 27, 0.06)); border: 1px solid rgba(220, 38, 38, 0.25); border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, var(--mod-primary), var(--mod-primary-dark)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px;"><i class="fas fa-coins"></i></div>
                            <div>
                                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); letter-spacing: 0.5px;">{{ $bendahara->jabatan }}</span>
                                <h4 style="font-size: 14px; font-weight: 800; color: var(--mod-text-main); margin: 2px 0 0 0;">{{ $bendahara->nama_lengkap }}</h4>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bidang-Bidang -->
            @if(count($bidang) > 0)
                <div class="mod-card">
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-main); margin: 0 0 20px 0; border-bottom: 1px solid var(--mod-border); padding-bottom: 14px;">
                        Ketua Bidang &amp; Kompartemen Teknis
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 18px;">
                        @foreach($bidang as $namaBidang => $data)
                            <div style="border: 1px solid var(--mod-border); border-radius: 14px; padding: 20px; background: #f8fafc;">
                                <div style="font-size: 13.5px; font-weight: 800; color: var(--mod-primary); text-transform: uppercase; margin-bottom: 10px;">
                                    {{ $namaBidang }}
                                </div>
                                @if($data['ketua'])
                                    <div style="font-size: 13px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">
                                        <i class="fas fa-user-check text-red-600 mr-1.5"></i> Ketua: {{ $data['ketua']->nama_lengkap }}
                                    </div>
                                @endif
                                @if($data['anggota'] && $data['anggota']->count() > 0)
                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed var(--mod-border);">
                                        <span style="font-size: 11.5px; font-weight: 600; color: var(--mod-text-muted);">Anggota:</span>
                                        <ul style="padding-left: 18px; margin: 4px 0 0 0; font-size: 12.5px; color: var(--mod-text-main);">
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

