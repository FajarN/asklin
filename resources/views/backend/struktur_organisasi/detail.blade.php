@extends('layouts.backend.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Detail Struktur Organisasi')
@section('page_title', 'Detail Struktur Organisasi')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('struktur_organisasi.index') }}" class="hover:underline">Struktur Organisasi</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Detail Bagan & Pengajuan SK</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2 flex-wrap">
                <span>{{ $struktur->nama_struktur }}</span>
                @php
                    $statusClass = [
                        'draft' => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border-slate-500/30',
                        'diajukan_daerah' => 'bg-sky-500/15 text-sky-700 dark:text-sky-400 border-sky-500/30',
                        'diajukan_pusat' => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400 border-indigo-500/30',
                        'disetujui' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                        'aktif' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                        'selesai' => 'bg-slate-700/15 text-slate-700 dark:text-slate-400 border-slate-700/30',
                        'revisi' => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/30',
                        'ditolak' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                    ];
                    $statusLabels = [
                        'draft' => 'Draft',
                        'diajukan_daerah' => 'Diajukan ke Daerah',
                        'diajukan_pusat' => 'Diajukan ke Pusat',
                        'disetujui' => 'SK Disahkan',
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
                        'revisi' => 'Perlu Revisi',
                        'ditolak' => 'Ditolak',
                    ];
                @endphp
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider border {{ $statusClass[$struktur->status] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $statusLabels[$struktur->status] ?? ucfirst(str_replace('_', ' ', $struktur->status)) }}
                </span>
                @if($struktur->is_locked)
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700">
                        <i class="fas fa-lock mr-1"></i> Data Terkunci
                    </span>
                @endif
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Tingkatan: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->tingkatanPengurus->nama_tingkatan ?? '-' }}</strong> &bull;
                Periode: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->periode }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if(!$struktur->is_locked || in_array($struktur->status, ['draft', 'revisi']) || auth()->user()->hasRole('Superadmin'))
                <button type="button" class="btn btn-success btn-sm" id="btnOpenModalAjukan">
                    <i class="fas fa-paper-plane mr-1"></i>
                    <span>Ajukan Pengesahan SK</span>
                </button>
            @endif

            @if($sk)
                <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-stamp mr-1"></i>
                    <span>Ruang Kerja SK & Persetujuan</span>
                </a>
            @endif

            @if(auth()->user()->hasRole('Superadmin') && ($struktur->is_locked || $sk))
                <button type="button" class="btn btn-warning btn-sm" onclick="resetTestingStruktur({{ $struktur->id }})">
                    <i class="fas fa-undo mr-1"></i>
                    <span>Reset Uji (Superadmin)</span>
                </button>
            @endif

            <a href="{{ route('struktur_organisasi.print', $struktur->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="fas fa-print mr-1"></i>
                <span>Cetak Bagan</span>
            </a>
            <a href="{{ route('struktur_organisasi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Status Notification Alerts -->
    @if($struktur->status == 'diajukan_daerah')
        <div class="p-4 rounded-xl bg-sky-50 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-300 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs shadow-sm">
            <div class="flex items-start gap-3">
                <i class="fas fa-hourglass-half text-base text-sky-500 mt-0.5"></i>
                <div>
                    <strong class="font-bold">Pengajuan Sedang Diverifikasi oleh Pengurus Daerah</strong>
                    <p class="mt-0.5 text-slate-600 dark:text-slate-300">
                        Susunan kepengurusan cabang telah diajukan dan sedang dalam proses pemeriksaan oleh Pengurus Daerah Provinsi {{ $struktur->provinsi_nama ?? '' }}. Selama proses verifikasi, pengeditan dan penambahan pengurus dinonaktifkan sementara demi kepastian data.
                    </p>
                </div>
            </div>
            @if($sk)
            <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-info btn-sm shrink-0 whitespace-nowrap">
                <i class="fas fa-user-check mr-1"></i> Buka Verifikasi SK &rarr;
            </a>
            @endif
        </div>
    @elseif($struktur->status == 'diajukan_pusat')
        <div class="p-4 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-200 dark:border-indigo-800 text-indigo-800 dark:text-indigo-300 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs shadow-sm">
            <div class="flex items-start gap-3">
                <i class="fas fa-clipboard-check text-base text-indigo-500 mt-0.5"></i>
                <div>
                    <strong class="font-bold">Pengajuan Sedang Dalam Pemeriksaan Pengurus Pusat</strong>
                    <p class="mt-0.5 text-slate-600 dark:text-slate-300">
                        Berkas telah diteruskan ke Pengurus Pusat ASKLIN untuk penomoran SK resmi oleh Sekretaris Jenderal dan persetujuan pengesahan akhir oleh Ketua Umum.
                    </p>
                </div>
            </div>
            @if($sk)
            <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-primary btn-sm shrink-0 whitespace-nowrap">
                <i class="fas fa-stamp mr-1"></i> Buka Ruang Kerja SK &rarr;
            </a>
            @endif
        </div>
    @elseif($struktur->status == 'revisi')
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-300 flex items-start gap-3 text-xs shadow-sm">
            <i class="fas fa-exclamation-triangle text-base text-amber-500 mt-0.5"></i>
            <div>
                <strong class="font-bold">Pengajuan Memerlukan Perbaikan / Revisi</strong>
                <p class="mt-0.5 text-slate-700 dark:text-slate-300">
                    Catatan Verifikator: <em>"{{ $struktur->catatan_revisi ?: 'Silakan periksa kembali kelengkapan pengurus dan berita acara Anda.' }}"</em>
                </p>
                <p class="mt-1 font-semibold text-amber-700 dark:text-amber-400">
                    Kunci data telah dibuka kembali. Anda dapat menambahkan atau mengedit pengurus, lalu klik tombol "Ajukan Pengesahan SK" kembali setelah perbaikan selesai.
                </p>
            </div>
        </div>
    @elseif($struktur->status == 'disetujui' || $struktur->status == 'aktif')
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-start gap-3 text-xs shadow-sm">
            <i class="fas fa-check-circle text-base text-emerald-500 mt-0.5"></i>
            <div>
                <strong class="font-bold">Surat Keputusan (SK) Resmi Telah Terbit & Disahkan</strong>
                <p class="mt-0.5 text-slate-600 dark:text-slate-300">
                    Susunan kepengurusan ini telah resmi disahkan oleh Ketua Umum Pengurus Pusat ASKLIN. Dokumen SK resmi ber-QR Code telah diterbitkan dan tersimpan dalam arsip digital organisasi.
                </p>
            </div>
        </div>
    @endif

    <!-- Metadata Card -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-info-circle text-emerald-600 mr-1.5"></i> Informasi Wilayah & Struktur
            </h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Nama Struktur</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $struktur->nama_struktur }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tingkatan & Periode</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->tingkatanPengurus->nama_tingkatan ?? '-' }} ({{ $struktur->periode }})
                    </span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Cakupan Wilayah</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->provinsi_nama ?? '-' }} {{ $struktur->kota_nama ? ' / ' . $struktur->kota_nama : '' }}
                    </span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tanggal Muscab/Musda</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->tgl_muscab ? date('d-m-Y', strtotime($struktur->tgl_muscab)) : '-' }}
                    </span>
                </div>
            </div>

            @if($struktur->file_berita_acara)
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">
                        <i class="fas fa-file-pdf text-rose-500 mr-1.5"></i> Berkas Berita Acara / Surat Pengantar:
                    </span>
                    <a href="{{ asset('storage/berita_acara/' . $struktur->file_berita_acara) }}" target="_blank" class="text-emerald-600 hover:underline font-semibold flex items-center gap-1">
                        <span>Lihat Dokumen Terlampir</span>
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tree Structure Card -->
    <div class="card shadow-sm">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <i class="fas fa-sitemap text-emerald-600 mr-1.5"></i> Susunan Dewan Pengurus
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Total Personil: <strong class="text-slate-700 dark:text-slate-200">{{ $totalPengurus ?? count($pengurus) }} Pengurus</strong></p>
            </div>
            @if(!$struktur->is_locked || auth()->user()->hasRole('Superadmin'))
            <button type="button" class="btn btn-primary btn-sm" id="btnAddPengurus">
                <i class="fas fa-plus mr-1"></i>
                <span>Tambah Pengurus Pimpinan</span>
            </button>
            @endif
        </div>
        <div class="card-body">
            <div class="org-chart space-y-4">
                @if (count($pengurus) > 0)
                    @foreach ($pengurus as $item)
                        @include('backend.struktur_organisasi.components.pengurus_card', [
                            'pengurus' => $item,
                            'isLocked' => $struktur->is_locked && !auth()->user()->hasRole('Superadmin')
                        ])
                    @endforeach
                @else
                    <div class="p-8 text-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 mx-auto flex items-center justify-center text-xl mb-3">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Belum Ada Susunan Pengurus</h4>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">
                            Struktur organisasi ini belum memiliki data pengurus. Silakan klik tombol di bawah untuk menambahkan pengurus pimpinan awal.
                        </p>
                        @if(!$struktur->is_locked || auth()->user()->hasRole('Superadmin'))
                        <button type="button" class="btn btn-primary btn-sm" onclick="$('#btnAddPengurus').click()">
                            <i class="fas fa-plus mr-1"></i> Tambah Pengurus Pertama
                        </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timeline Audit Trail Card -->
    @if(isset($logs) && count($logs) > 0)
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-history text-slate-500 mr-1.5"></i> Riwayat & Jejak Audit Pengajuan SK
            </h4>
        </div>
        <div class="card-body p-4 sm:p-5">
            <div class="space-y-4">
                @foreach($logs as $log)
                    <div class="flex items-start gap-3 text-xs pb-3 border-b border-slate-100 dark:border-slate-800 last:border-b-0 last:pb-0">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <span class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $log->user->name ?? 'Pengguna' }} ({{ $log->role_name }})
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 mt-1">
                                <span class="badge badge-light border text-[10px] uppercase font-semibold mr-1">{{ str_replace('_', ' ', $log->action) }}</span>
                                {{ $log->catatan }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal Ajukan Pengesahan SK -->
<div class="modal fade" id="modalAjukanSk" tabindex="-1" role="dialog" aria-labelledby="modalAjukanSkLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm text-slate-800 dark:text-white" id="modalAjukanSkLabel">
                    <i class="fas fa-paper-plane text-emerald-600 mr-1.5"></i> Ajukan Pengesahan SK Kepengurusan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAjukanSk" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-xs space-y-3">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/30 text-amber-900 dark:text-amber-300 rounded-lg border border-amber-200 dark:border-amber-800">
                        <div class="font-bold mb-1"><i class="fas fa-lock mr-1"></i> Perhatian Penting:</div>
                        Setelah diajukan, seluruh data susunan pengurus akan <b>terkunci</b> secara otomatis dan tidak dapat diubah hingga proses verifikasi oleh pihak berwenang selesai.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">Nomor Surat Pengantar / Berita Acara (Opsional)</label>
                        <input type="text" name="no_pengantar" id="no_pengantar" class="form-control text-xs" placeholder="Contoh: 012/PC-ASKLIN/BA-MUSCAB/X/2024">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">File Berita Acara Muscab / Surat Pengantar</label>
                        <input type="file" name="file_berita_acara" id="file_berita_acara" class="form-control-file text-xs" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="text-muted block mt-1">Format: PDF, Word (Doc/Docx), JPG/PNG. Maks: 5MB.</small>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">Catatan / Pengantar Tambahan</label>
                        <textarea name="catatan_pengantar" id="catatan_pengantar" class="form-control text-xs" rows="3" placeholder="Tuliskan catatan permohonan pengesahan susunan pengurus..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 dark:bg-slate-800/50 py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" id="btnSubmitAjukan">
                        <i class="fas fa-check mr-1"></i> Ya, Ajukan Pengesahan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Pengurus -->
<div class="modal fade" id="pengurusModal" tabindex="-1" role="dialog" aria-labelledby="pengurusModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-slate-800 dark:text-white" id="pengurusModalLabel">Tambah Pengurus</h5>
                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pengurusForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body space-y-4">
                    <input type="hidden" id="pengurus_id" name="id">
                    <input type="hidden" id="id_struktur_organisasi" name="id_struktur_organisasi" value="{{ $struktur->id }}">
                    <input type="hidden" id="parent_id" name="parent_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Ketua Umum, Sekretaris" required>
                        </div>
                        <div class="form-group">
                            <label for="id_kelompok" class="form-label">Kelompok Pengurus</label>
                            <select class="form-control" id="id_kelompok" name="id_kelompok">
                                <option value="">-- Pilih Kelompok Pengurus --</option>
                                @foreach($kelompokPengurusList as $kelompok)
                                    <option value="{{ $kelompok->id }}">{{ $kelompok->nama_kelompok }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="dr. John Doe, Sp.A" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="form-label">Keterangan / Bidang</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Bidang Organisasi & Hukum">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="no_telp" class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="urutan" class="form-label">Urutan Tampilan <span class="text-rose-500">*</span></label>
                            <input type="number" class="form-control" id="urutan" name="urutan" min="1" value="1" required>
                            <small class="text-xs text-slate-400 mt-1 block">Urutan prioritas dalam struktur bagian ini</small>
                        </div>
                        <div class="form-group">
                            <label for="status_pengurus" class="form-label">Status <span class="text-rose-500">*</span></label>
                            <select class="form-control" id="status_pengurus" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non Aktif</option>
                                <option value="mengundurkan_diri">Mengundurkan Diri</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="foto_pengurus" class="form-label">Foto Profil Pengurus</label>
                        <input type="file" class="form-control" id="foto_pengurus" name="foto_pengurus" accept="image/*">
                        <small class="text-xs text-slate-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 2MB</small>
                        <div id="preview-container" class="mt-3" style="display: none;">
                            <img id="preview-image" src="#" alt="Preview" class="h-20 w-20 rounded-xl object-cover ring-2 ring-emerald-500/20 shadow-md">
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSavePengurus">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Pengurus Confirmation Modal -->
<div class="modal fade" id="deletePengurusModal" tabindex="-1" role="dialog" aria-labelledby="deletePengurusModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-slate-800 dark:text-white" id="deletePengurusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 mx-auto flex items-center justify-center text-xl mb-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Apakah Anda yakin ingin menghapus pengurus ini?</p>
                <p class="text-xs text-rose-500 mt-1">Peringatan: Pengurus yang masih memiliki bawahan tidak dapat dihapus!</p>
            </div>
            <div class="modal-footer flex items-center justify-center gap-2 border-t border-slate-100 dark:border-slate-800">
                <input type="hidden" id="delete_pengurus_id" name="delete_pengurus_id">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnDeletePengurus">Hapus Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Buka Modal Ajukan SK
            $('#btnOpenModalAjukan').click(function() {
                $('#formAjukanSk')[0].reset();
                $('#modalAjukanSk').modal('show');
            });

            // Submit Form Ajukan SK
            $('#formAjukanSk').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $('#btnSubmitAjukan').attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Pengajuan...');

                $.ajax({
                    url: "{{ route('struktur_organisasi.ajukan', $struktur->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modalAjukanSk').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Pengajuan SK Berhasil!',
                                text: response.message,
                                showCancelButton: true,
                                confirmButtonColor: '#059669',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: '<i class="fas fa-stamp mr-1"></i> Buka Ruang Kerja SK Sekarang',
                                cancelButtonText: 'Tetap di Halaman Ini'
                            }).then((result) => {
                                if (result.isConfirmed && response.sk_url) {
                                    window.location.href = response.sk_url;
                                } else {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                for (var f in errors) {
                                    iziToast.error({
                                        title: 'Peringatan',
                                        message: errors[f][0],
                                        position: 'topRight'
                                    });
                                }
                            } else {
                                iziToast.error({
                                    title: 'Peringatan',
                                    message: xhr.responseJSON.message,
                                    position: 'topRight'
                                });
                            }
                        } else {
                            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                            iziToast.error({
                                title: 'Error',
                                message: msg,
                                position: 'topRight'
                            });
                        }
                    },
                    complete: function() {
                        $('#btnSubmitAjukan').attr('disabled', false).html('<i class="fas fa-check mr-1"></i> Ya, Ajukan Pengesahan Sekarang');
                    }
                });
            });

            // File input preview foto pengurus
            $('#foto_pengurus').change(function() {
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#preview-image').attr('src', event.target.result);
                        $('#preview-container').show();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Add new root pengurus
            $('#btnAddPengurus').click(function() {
                $('#pengurusModal').find('.modal-title').text('Tambah Pengurus Pimpinan');
                $('#pengurusForm')[0].reset();
                $('#pengurus_id').val('');
                $('#parent_id').val('');
                $('#preview-container').hide();
                $('#pengurusModal').modal('show');
            });

            // Add sub pengurus
            $(document).on('click', '.add-sub-btn', function() {
                let parentId = $(this).data('id');
                $('#pengurusModal').find('.modal-title').text('Tambah Anggota / Bawahan');
                $('#pengurusForm')[0].reset();
                $('#pengurus_id').val('');
                $('#parent_id').val(parentId);
                $('#preview-container').hide();
                $('#pengurusModal').modal('show');
            });

            // Edit pengurus
            $(document).on('click', '.edit-pengurus-btn', function() {
                let id = $(this).data('id');
                $('#pengurusModal').find('.modal-title').text('Edit Data Pengurus');

                $.ajax({
                    url: "{{ route('pengurus.edit') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#pengurus_id').val(data.id);
                        $('#id_kelompok').val(data.id_kelompok);
                        $('#jabatan').val(data.jabatan);
                        $('#keterangan').val(data.keterangan);
                        $('#nama_lengkap').val(data.nama_lengkap);
                        $('#no_telp').val(data.no_telp);
                        $('#email').val(data.email);
                        $('#urutan').val(data.urutan);
                        $('#status_pengurus').val(data.status);
                        $('#parent_id').val(data.parent_id);

                        if (data.foto_pengurus) {
                            $('#preview-image').attr('src', "{{ asset('storage/pengurus') }}/" + data.foto_pengurus);
                            $('#preview-container').show();
                        } else {
                            $('#preview-container').hide();
                        }

                        $('#pengurusModal').modal('show');
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan saat mengambil data',
                            position: 'topRight'
                        });
                    }
                });
            });

            // Submit pengurus form
            $('#pengurusForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#btnSavePengurus').text('Menyimpan...').attr('disabled', true);

                $.ajax({
                    url: "{{ route('pengurus.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#pengurusModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: data.message,
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessages = '';
                            $.each(errors, function(key, value) {
                                errorMessages += value[0] + '<br>';
                            });
                            iziToast.error({
                                title: 'Error',
                                message: errorMessages,
                                position: 'topRight'
                            });
                        } else {
                            let message = xhr.responseJSON.message || 'Terjadi kesalahan';
                            iziToast.error({
                                title: 'Error',
                                message: message,
                                position: 'topRight'
                            });
                        }
                    },
                    complete: function() {
                        $('#btnSavePengurus').text('Simpan Data').attr('disabled', false);
                    }
                });
            });

            // Delete confirmation
            $(document).on('click', '.delete-pengurus-btn', function() {
                let id = $(this).data('id');
                $('#delete_pengurus_id').val(id);
                $('#deletePengurusModal').modal('show');
            });

            // Delete action
            $('#btnDeletePengurus').click(function() {
                let id = $('#delete_pengurus_id').val();
                $('#btnDeletePengurus').text('Menghapus...').attr('disabled', true);

                $.ajax({
                    url: "{{ route('pengurus.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#deletePengurusModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: data.message,
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON.message,
                            position: 'topRight'
                        });
                    },
                    complete: function() {
                        $('#btnDeletePengurus').text('Hapus Sekarang').attr('disabled', false);
                    }
                });
            });
        });

        function resetTestingStruktur(id) {
            Swal.fire({
                title: 'Reset Pengujian Struktur & SK?',
                text: 'Status struktur dan SK terkait akan dikembalikan ke status Draft awal sehingga Anda dapat menguji kembali proses pengajuan dari awal.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-undo mr-1"></i> Ya, Reset ke Draft',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('struktur_organisasi.reset-testing', ':id') }}".replace(':id', id),
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
