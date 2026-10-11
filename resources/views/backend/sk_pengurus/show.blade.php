@extends('layouts.backend.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Ruang Kerja Persetujuan SK')
@section('page_title', 'Ruang Kerja Persetujuan SK')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Surat Keputusan (SK)', 'url' => route('sk_pengurus.index')],
        ['label' => $struktur->nama_struktur, 'url' => route('struktur_organisasi.detail', $struktur->id)],
        ['label' => 'Ruang Kerja Persetujuan SK'],
    ]" />

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2 flex-wrap">
                <span>{{ $sk->no_sk ?: 'Draft SK: ' . $struktur->nama_struktur }}</span>
                @php
                    $statusMap = [
                        'draft' => ['badge-secondary', 'Draft Pengajuan'],
                        'menunggu_daerah' => ['badge-info', 'Tahap Verifikasi Daerah'],
                        'menunggu_sekjen' => ['badge-primary', 'Tahap Pemeriksaan Sekjen'],
                        'menunggu_ketum' => ['badge-warning', 'Tahap Pengesahan Ketum'],
                        'terbit' => ['badge-success', 'SK Terbit & Sah Resmi'],
                        'dicabut' => ['badge-danger', 'Ditolak / Dicabut'],
                    ];
                    $badgeInfo = $statusMap[$sk->status] ?? ['badge-secondary', ucfirst($sk->status)];
                @endphp
                <span class="badge {{ $badgeInfo[0] }} text-xs px-2.5 py-1 uppercase tracking-wider font-bold">
                    {{ $badgeInfo[1] }}
                </span>
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Tingkatan: <strong class="text-slate-700 dark:text-slate-200 uppercase">{{ $sk->tingkatan }}</strong> &bull;
                Wilayah: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->kota_nama ? $struktur->kota_nama . ' - ' : '' }}{{ $struktur->provinsi_nama }}</strong> &bull;
                Periode: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->periode }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if($sk->status === 'terbit')
                <a href="{{ route('sk_pengurus.print', $sk->id) }}" target="_blank" class="btn btn-success btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i>
                    <span>Cetak SK Resmi (PDF)</span>
                </a>
            @elseif(auth()->user()->hasRole('Superadmin'))
                <a href="{{ route('sk_pengurus.print', $sk->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i>
                    <span>Pratinjau Draf PDF</span>
                </a>
            @endif

            @if(auth()->user()->hasRole('Superadmin'))
                <button type="button" class="btn btn-warning btn-sm" onclick="resetTestingSk({{ $sk->id }})">
                    <i class="fas fa-undo mr-1"></i>
                    <span>Reset Alur SK (Superadmin)</span>
                </button>
            @endif

            @if($sk->tingkatan !== 'pusat')
            <a href="{{ route('sk_pengurus.timeline', $sk->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-stream mr-1"></i>
                <span>Alur & Timeline SK</span>
            </a>
            @endif

            <a href="{{ route('struktur_organisasi.detail', $struktur->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-sitemap mr-1"></i>
                <span>Lihat Bagan Organisasi</span>
            </a>
            <a href="{{ route('sk_pengurus.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    @if(auth()->user()->hasRole('Superadmin'))
    <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-300 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs shadow-xs">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <strong class="font-bold">Mode Pengujian Superadmin Aktif:</strong>
                <span class="opacity-90">Anda memiliki wewenang penuh untuk memproses semua tahapan persetujuan (Verifikasi Daerah, Draf Sekjen, & Pengesahan Ketum) secara langsung tanpa perlu berganti akun.</span>
            </div>
        </div>
        <button type="button" class="btn btn-warning btn-xs shrink-0" onclick="resetTestingSk({{ $sk->id }})">
            <i class="fas fa-sync-alt mr-1"></i> Reset ke Tahap Awal
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Review Form & Actions (Col 7) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Card Metadata Pengajuan -->
            <div class="card shadow-sm">
                <div class="card-header flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-file-invoice text-emerald-600 mr-1.5"></i> Data Administrasi Permohonan SK
                    </h4>
                    <span class="text-[11px] text-slate-400">Diajukan: {{ $struktur->diajukan_at ? $struktur->diajukan_at->format('d M Y, H:i') : '-' }}</span>
                </div>
                <div class="card-body text-xs space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Perihal SK</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $sk->perihal }}</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Musyawarah</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $sk->tgl_musyawarah ? $sk->tgl_musyawarah->format('d-m-Y') : '-' }}</span>
                        </div>
                    </div>

                    @if($sk->no_rekomendasi_daerah)
                    <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Rekomendasi Pengurus Daerah</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">
                            Nomor: {{ $sk->no_rekomendasi_daerah }} 
                            @if($sk->tgl_rekomendasi_daerah) &bull; Tanggal: {{ $sk->tgl_rekomendasi_daerah->format('d-m-Y') }} @endif
                        </span>
                    </div>
                    @endif

                    @if($struktur->file_berita_acara)
                    <div class="p-3 rounded-lg bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Berkas Berita Acara / Surat Pengantar</span>
                                <span class="text-[11px] text-slate-500">Diunggah oleh pihak pemohon cabang</span>
                            </div>
                        </div>
                        <a href="{{ asset('storage/berita_acara/' . $struktur->file_berita_acara) }}" target="_blank" class="btn btn-outline-success btn-xs">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka Berkas
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ACTION PANEL 1: PENGURUS DAERAH VERIFICATION -->
            @if($sk->tingkatan !== 'pusat' && $sk->status === 'menunggu_daerah' && auth()->user()->hasRole(['Admin Daerah', 'Superadmin']))
            <div class="card border-l-4 border-l-sky-500 shadow-md">
                <div class="card-header bg-sky-50/40 dark:bg-sky-950/30">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-sky-800 dark:text-sky-300">
                        <i class="fas fa-user-check text-sky-600 mr-1.5"></i> Verifikasi Pengurus Daerah (Provinsi)
                    </h4>
                </div>
                <div class="card-body text-xs space-y-3">
                    <p class="text-slate-600 dark:text-slate-300">
                        Sebagai Pengurus Daerah, periksa kesesuaian susunan pengurus cabang di sebelah kanan serta berkas berita acara Muscab. Berikan nomor surat rekomendasi sebelum meneruskan ke Pengurus Pusat.
                    </p>
                    <form id="formVerifikasiDaerah">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Nomor Surat Rekomendasi Pengurus Daerah <span class="text-danger">*</span></label>
                            <input type="text" name="no_rekomendasi_daerah" id="no_rekomendasi_daerah" class="form-control text-xs" placeholder="Contoh: 045/PD-ASKLIN/REK/XI/2024" value="{{ $sk->no_rekomendasi_daerah }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Tanggal Surat Rekomendasi</label>
                            <input type="date" name="tgl_rekomendasi_daerah" id="tgl_rekomendasi_daerah" class="form-control text-xs" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Catatan / Keterangan Tambahan</label>
                            <textarea name="catatan" id="catatan_daerah" class="form-control text-xs" rows="2" placeholder="Catatan rekomendasi atau catatan perbaikan jika minta revisi..."></textarea>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" class="btn btn-success btn-sm" onclick="submitVerifikasiDaerah('rekomendasi')">
                                <i class="fas fa-check mr-1"></i> Rekomendasikan ke Pusat
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="submitVerifikasiDaerah('revisi')">
                                <i class="fas fa-undo mr-1"></i> Minta Revisi Cabang
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="submitVerifikasiDaerah('tolak')">
                                <i class="fas fa-times mr-1"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- ACTION PANEL 2: SEKRETARIS JENDERAL PP EDITOR & PENOMORAN -->
            @if($sk->tingkatan !== 'pusat' && $sk->status === 'menunggu_sekjen' && auth()->user()->hasRole(['Sekjen', 'Admin Pusat', 'Superadmin']))
            <div class="card border-l-4 border-l-indigo-500 shadow-md">
                <div class="card-header bg-indigo-50/40 dark:bg-indigo-950/30">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-800 dark:text-indigo-300">
                        <i class="fas fa-edit text-indigo-600 mr-1.5"></i> Pemeriksaan & Penomoran Resmi Sekjen PP
                    </h4>
                </div>
                <div class="card-body text-xs space-y-3">
                    <p class="text-slate-600 dark:text-slate-300">
                        Tetapkan nomor SK resmi, tanggal penetapan, dan pengaturan QR Code keabsahan sebelum diajukan ke Ketua Umum untuk pengesahan final.
                    </p>
                    <form id="formDraftSekjen">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Nomor Surat Keputusan Resmi <span class="text-danger">*</span></label>
                            <input type="text" name="no_sk" id="no_sk" class="form-control text-xs font-bold text-slate-800 dark:text-white" value="{{ $suggestedNoSk }}" required>
                            <small class="text-muted block mt-0.5">Format baku resmi ASKLIN. Anda dapat menyesuaikan nomor urut jika diperlukan.</small>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="form-group mb-3">
                                <label class="font-semibold text-slate-700 dark:text-slate-300">Tanggal Penetapan SK <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_penetapan" id="tgl_penetapan" class="form-control text-xs" value="{{ $sk->tgl_penetapan ? $sk->tgl_penetapan->format('Y-m-d') : date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-semibold text-slate-700 dark:text-slate-300">Kota Penetapan</label>
                                <input type="text" name="kota_penetapan" id="kota_penetapan" class="form-control text-xs" value="{{ $sk->kota_penetapan ?: 'Jakarta' }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Rencana Tanggal Pelantikan (Opsional)</label>
                            <input type="date" name="tgl_pelantikan" id="tgl_pelantikan" class="form-control text-xs" value="{{ $sk->tgl_pelantikan ? $sk->tgl_pelantikan->format('Y-m-d') : '' }}">
                            <small class="text-muted block mt-0.5">Sesuai ART, masa jabatan pengurus daerah/cabang berlaku sejak tanggal pelantikan.</small>
                        </div>

                        <!-- TOGGLE QR CODE -->
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 mb-3 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block text-xs">Tampilkan QR Code Keabsahan</span>
                                <span class="text-[11px] text-slate-500">QR Code akan dicetak di sebelah kiri tanda tangan untuk verifikasi publik digital.</span>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="switchShowQr" name="show_qr" value="1" {{ $sk->show_qr ? 'checked' : '' }}>
                                <label class="custom-control-label" for="switchShowQr"></label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Catatan untuk Ketua Umum / Pengusul</label>
                            <textarea name="catatan" id="catatan_sekjen" class="form-control text-xs" rows="2" placeholder="Catatan pemeriksaan kelengkapan berkas..."></textarea>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" class="btn btn-primary btn-sm" onclick="submitDraftSekjen('teruskan_ketum')">
                                <i class="fas fa-paper-plane mr-1"></i> Teruskan ke Ketua Umum untuk Pengesahan
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="submitDraftSekjen('minta_revisi')">
                                <i class="fas fa-undo mr-1"></i> Kembalikan untuk Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- ACTION PANEL 3: KETUA UMUM PP FINAL APPROVAL -->
            @if(($sk->status === 'menunggu_ketum' || ($sk->tingkatan === 'pusat' && $sk->status !== 'terbit')) && auth()->user()->hasRole(['Ketua Umum', 'Superadmin']))
            <div class="card border-l-4 border-l-emerald-500 shadow-md">
                <div class="card-header bg-emerald-50/40 dark:bg-emerald-950/30">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                        <i class="fas fa-stamp text-emerald-600 mr-1.5"></i> Pengesahan Akhir Ketua Umum PP ASKLIN
                    </h4>
                </div>
                <div class="card-body text-xs space-y-3">
                    <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-start gap-3">
                        <i class="fas fa-user-tie text-xl text-emerald-600 mt-1"></i>
                        <div>
                            <span class="font-bold block">Ketua Umum Penandatangan:</span>
                            <span class="text-sm font-black">{{ $ketumAktif ? $ketumAktif->nama_lengkap : 'Belum Ada Ketua Umum Aktif' }}</span>
                            <span class="text-[11px] block text-emerald-700 dark:text-emerald-400">Periode {{ $ketumAktif ? $ketumAktif->periode_mulai . ' - ' . $ketumAktif->periode_selesai : '-' }}</span>
                        </div>
                    </div>

                    <p class="text-slate-600 dark:text-slate-300">
                        Dengan menekan tombol <b>Setujui & Sahkan SK</b>, dokumen resmi akan diterbitkan dengan nomor <b>{{ $sk->no_sk }}</b>, dibubuhi tanda tangan digital dan QR Code keabsahan, serta susunan pengurus dinyatakan sah secara organisasi.
                    </p>

                    <form id="formApproveKetum">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Catatan Pengesahan (Opsional)</label>
                            <textarea name="catatan" id="catatan_ketum" class="form-control text-xs" rows="2" placeholder="Pesan atau amanat Ketua Umum untuk kepengurusan ini..."></textarea>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" class="btn btn-success btn-sm" onclick="submitApproveKetum('approve')">
                                <i class="fas fa-stamp mr-1"></i> Setujui & Sahkan SK Resmi Sekarang
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="submitApproveKetum('tolak')">
                                <i class="fas fa-undo mr-1"></i> Kembalikan untuk Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Jejak Audit & Riwayat Timeline -->
            @if(isset($sk->logs) && count($sk->logs) > 0)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-history text-slate-500 mr-1.5"></i> Jejak Audit Proses Persetujuan SK
                    </h4>
                </div>
                <div class="card-body p-4 text-xs space-y-3">
                    @foreach($sk->logs as $log)
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800 last:border-b-0 last:pb-0">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400 flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5 font-bold">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between flex-wrap">
                                    <strong class="text-slate-800 dark:text-slate-200">{{ $log->user->name ?? 'User' }} ({{ $log->role_name }})</strong>
                                    <span class="text-[10px] text-slate-400">{{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 mt-0.5">
                                    <span class="badge badge-light border text-[9px] uppercase mr-1">{{ str_replace('_', ' ', $log->action) }}</span>
                                    {{ $log->catatan }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Lampiran Susunan Pengurus (Col 5) -->
        <div class="lg:col-span-5 space-y-6">

            <div class="card shadow-sm">
                <div class="card-header flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-users text-emerald-600 mr-1.5"></i> Lampiran Susunan Pengurus
                        </h4>
                        <span class="text-[10px] text-slate-400">Diambil otomatis dari basis data struktur organisasi</span>
                    </div>
                    <span class="badge badge-secondary text-xs">{{ count($semuaPengurus) }} Personil</span>
                </div>
                <div class="card-body p-0 max-h-[600px] overflow-y-auto">
                    <table class="table table-sm table-striped text-xs w-full mb-0">
                        <thead>
                            <tr>
                                <th width="35" class="text-center">No</th>
                                <th>Jabatan</th>
                                <th>Nama Lengkap & Gelar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semuaPengurus as $idx => $item)
                                <tr>
                                    <td class="text-center text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="font-bold text-slate-700 dark:text-slate-300">{{ $item->jabatan }}</td>
                                    <td class="font-semibold text-slate-900 dark:text-white">{{ $item->nama_lengkap }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada pengurus diinput</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pratinjau Tanda Tangan & QR Code Dokumen -->
            <div class="card shadow-sm border border-slate-200/80 dark:border-slate-800">
                <div class="card-header">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-signature text-slate-500 mr-1.5"></i> Format Pengesahan Akhir SK
                    </h4>
                </div>
                <div class="card-body text-xs p-4 bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex items-end justify-between gap-4 pt-2">
                        <!-- SISI KIRI: QR CODE KEABSAHAN -->
                        <div class="text-left w-1/2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Sebelah Kiri: QR Keabsahan</span>
                            @if($sk->show_qr)
                                <div class="w-20 h-20 bg-white border border-slate-200 rounded p-1 flex flex-col items-center justify-center shadow-xs">
                                    <i class="fas fa-qrcode text-3xl text-slate-800"></i>
                                    <span class="text-[8px] text-slate-400 mt-1 uppercase font-bold">Valid ASKLIN</span>
                                </div>
                                <span class="text-[9px] text-emerald-600 block mt-1"><i class="fas fa-check-circle"></i> QR Aktif Dicetak</span>
                            @else
                                <div class="w-20 h-20 bg-slate-100 rounded border border-dashed border-slate-300 flex items-center justify-center text-center p-2 text-[9px] text-slate-400">
                                    QR Dinonaktifkan
                                </div>
                                <span class="text-[9px] text-slate-400 block mt-1">Tanpa QR Code</span>
                            @endif
                        </div>

                        <!-- SISI KANAN: TANDA TANGAN KETUA UMUM -->
                        <div class="text-right w-1/2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Sebelah Kanan: Tanda Tangan</span>
                            <div class="text-[10px] text-slate-500 mb-1">
                                Ditetapkan di: {{ $sk->kota_penetapan ?: 'Jakarta' }}<br>
                                Tanggal: {{ $sk->tgl_penetapan ? $sk->tgl_penetapan->format('d M Y') : date('d M Y') }}
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-300 block text-[10px] uppercase">Ketua Umum PP ASKLIN</span>
                            <div class="h-12 flex items-center justify-end my-1">
                                @php
                                    $ketumObj = $sk->ketuaUmum ?: $ketumAktif;
                                @endphp
                                @if($ketumObj && $ketumObj->file_ttd && Storage::exists('public/ttd_ketum/' . $ketumObj->file_ttd))
                                    <img src="{{ asset('storage/ttd_ketum/' . $ketumObj->file_ttd) }}" alt="TTD" class="max-h-full">
                                @else
                                    <span class="text-[10px] text-slate-400 italic">( Tanda Tangan Digital )</span>
                                @endif
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white underline block text-xs">
                                {{ $ketumObj ? $ketumObj->nama_lengkap : 'dr. Eddi Junaidi, SpOG., SH., MKes' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function submitVerifikasiDaerah(actionType) {
    var form = $('#formVerifikasiDaerah');
    var data = form.serialize() + '&action=' + actionType;

    $.ajax({
        url: "{{ route('sk_pengurus.verifikasi-daerah', $sk->id) }}",
        type: "POST",
        data: data,
        success: function(res) {
            if (res.success) {
                iziToast.success({ title: 'Sukses', message: res.message, position: 'topRight' });
                setTimeout(function() { location.reload(); }, 800);
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memproses verifikasi';
            iziToast.error({ title: 'Error', message: msg, position: 'topRight' });
        }
    });
}

function submitDraftSekjen(actionType) {
    var form = $('#formDraftSekjen');
    var showQrVal = $('#switchShowQr').is(':checked') ? 1 : 0;
    var data = form.serialize() + '&action=' + actionType + '&show_qr=' + showQrVal;

    $.ajax({
        url: "{{ route('sk_pengurus.draft-sekjen', $sk->id) }}",
        type: "POST",
        data: data,
        success: function(res) {
            if (res.success) {
                iziToast.success({ title: 'Sukses', message: res.message, position: 'topRight' });
                setTimeout(function() { location.reload(); }, 800);
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memproses draft';
            iziToast.error({ title: 'Error', message: msg, position: 'topRight' });
        }
    });
}

function submitApproveKetum(actionType) {
    var form = $('#formApproveKetum');
    var data = form.serialize() + '&action=' + actionType;

    $.ajax({
        url: "{{ route('sk_pengurus.approve-ketum', $sk->id) }}",
        type: "POST",
        data: data,
        success: function(res) {
            if (res.success) {
                iziToast.success({ title: 'Sukses', message: res.message, position: 'topRight' });
                setTimeout(function() { location.reload(); }, 800);
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memproses persetujuan';
            iziToast.error({ title: 'Error', message: msg, position: 'topRight' });
        }
    });
}

function resetTestingSk(id) {
    Swal.fire({
        title: 'Reset Alur SK untuk Pengujian?',
        text: 'Status SK dan Struktur akan dikembalikan ke tahap awal (Menunggu Verifikasi Daerah) sehingga Anda dapat menguji kembali langkah persetujuan ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-undo mr-1"></i> Ya, Reset Alur',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('sk_pengurus.reset', $sk->id) }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        iziToast.success({
                            title: 'Berhasil',
                            message: res.message,
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 600);
                    }
                },
                error: function(err) {
                    iziToast.error({
                        title: 'Gagal',
                        message: err.responseJSON ? err.responseJSON.message : 'Terjadi kesalahan sistem',
                        position: 'topRight'
                    });
                }
            });
        }
    });
}
</script>
@endpush
