@extends('layouts.backend.layout')

@section('title', 'Alur Persetujuan & Riwayat SK — ' . $struktur->nama_struktur)
@section('page_title', 'Timeline Persetujuan SK')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Surat Keputusan (SK)', 'url' => route('sk_pengurus.index')],
        ['label' => $struktur->nama_struktur, 'url' => route('sk_pengurus.show', $sk->id)],
        ['label' => 'Alur & Riwayat Persetujuan SK'],
    ]" />

    <!-- Page Header Banner -->
    <div class="card p-5 sm:p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="badge badge-primary text-[10px] uppercase font-bold tracking-wider px-2 py-0.5">
                    {{ strtoupper($sk->tingkatan) }}
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">{{ $struktur->periode }}</span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs font-mono text-slate-600 dark:text-slate-300">{{ $sk->no_sk ?: 'Draft (Belum Terbit)' }}</span>
            </div>
            <h2 class="text-base sm:text-xl font-bold tracking-tight text-slate-800 dark:text-white">
                {{ $struktur->nama_struktur }}
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Pelacakan alur verifikasi pengesahan kepengurusan berjenjang dari pengajuan hingga penerbitan SK resmi Ketua Umum.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Tombol Aksi Cepat Sesuai Wewenang User -->
            @if(($sk->status === 'menunggu_sekjen' && auth()->user()->hasRole(['Sekjen', 'Superadmin'])) ||
                ($sk->status === 'menunggu_ketum' && auth()->user()->hasRole(['Ketua Umum', 'Superadmin'])) ||
                ($sk->status === 'menunggu_daerah' && auth()->user()->hasRole(['Admin Daerah', 'Superadmin'])))
                <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-success btn-sm flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-check-circle text-xs"></i>
                    <span>Periksa & Proses SK Sekarang</span>
                </a>
            @else
                <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                    <i class="fas fa-stamp text-xs"></i>
                    <span>Ruang Kerja SK</span>
                </a>
            @endif

            @if($sk->status === 'terbit')
                <a href="{{ route('sk_pengurus.print', $sk->id) }}" target="_blank" class="btn btn-outline-success btn-sm flex items-center gap-1.5">
                    <i class="fas fa-file-pdf text-xs"></i>
                    <span>Cetak SK PDF</span>
                </a>
            @endif

            <a href="{{ route('sk_pengurus.index') }}" class="btn btn-secondary btn-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Stepper Controls (Switch Horizontal / Vertikal) -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-stream text-emerald-600 mr-1.5"></i> Pelacak Tahapan (Step by Step Tracker)
            </h3>
        </div>
        <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800 p-1 border border-slate-200/80 dark:border-slate-700 text-xs">
            <button type="button" id="btn-view-horizontal"
                    class="px-3 py-1.5 rounded-lg font-semibold transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-2xs">
                <i class="fas fa-arrows-alt-h mr-1"></i> Ke Kiri & Kanan (Horizontal)
            </button>
            <button type="button" id="btn-view-vertical"
                    class="px-3 py-1.5 rounded-lg font-semibold transition-all text-slate-600 dark:text-slate-400 hover:text-slate-900">
                <i class="fas fa-arrows-alt-v mr-1"></i> Ke Atas & Bawah (Vertikal)
            </button>
        </div>
    </div>

    @php
        // Definisi 5 Tahap Alur SK
        // 1. Pengajuan Draf
        // 2. Verifikasi Daerah (jika cabang)
        // 3. Pemeriksaan Sekjen
        // 4. Pengesahan Ketum
        // 5. SK Resmi Terbit

        $isCabang = ($sk->tingkatan === 'cabang');

        $steps = [
            'step_1' => [
                'title' => 'Pengajuan Draf Kepengurusan',
                'actor' => $isCabang ? 'Pengurus Cabang' : ($sk->tingkatan === 'daerah' ? 'Pengurus Daerah' : 'Pengurus Pusat'),
                'icon' => 'fa-file-signature',
                'status' => 'completed', // Karena sudah ada SK
                'date' => $struktur->diajukan_at ? \Carbon\Carbon::parse($struktur->diajukan_at)->isoFormat('D MMMM Y, HH:mm') : '-',
                'waiting_time' => 'Selesai',
                'notes' => 'Susunan pengurus dan berkas berita acara musyawarah diajukan ke sistem.',
            ],
            'step_2' => [
                'title' => 'Verifikasi Rekomendasi Daerah',
                'actor' => 'Pengurus Daerah',
                'icon' => 'fa-building',
                'status' => !$isCabang ? 'skipped' : (
                    in_array($sk->status, ['menunggu_sekjen', 'menunggu_ketum', 'terbit']) ? 'completed' : (
                        $sk->status === 'menunggu_daerah' ? 'active' : 'pending'
                    )
                ),
                'date' => $struktur->diverifikasi_daerah_at ? \Carbon\Carbon::parse($struktur->diverifikasi_daerah_at)->isoFormat('D MMMM Y, HH:mm') : null,
                'waiting_time' => $isCabang ? ($sk->status === 'menunggu_daerah' ? ($struktur->diajukan_at ? \Carbon\Carbon::parse($struktur->diajukan_at)->diffForHumans(null, true) . ' (Menunggu)' : '-') : ($struktur->diverifikasi_daerah_at && $struktur->diajukan_at ? \Carbon\Carbon::parse($struktur->diajukan_at)->diffForHumans(\Carbon\Carbon::parse($struktur->diverifikasi_daerah_at), true) : 'Selesai')) : 'Tidak Diperlukan (Tingkat Daerah/Pusat)',
                'notes' => $isCabang ? ($sk->no_rekomendasi_daerah ? 'No. Rekomendasi: ' . $sk->no_rekomendasi_daerah : 'Menunggu surat rekomendasi daerah') : 'Otomatis diteruskan ke Sekjen PP.',
            ],
            'step_3' => [
                'title' => 'Pemeriksaan & Penomoran Sekjen',
                'actor' => 'Sekretaris Jenderal PP ASKLIN',
                'icon' => 'fa-user-tie',
                'status' => in_array($sk->status, ['menunggu_ketum', 'terbit']) ? 'completed' : (
                    $sk->status === 'menunggu_sekjen' ? 'active' : 'pending'
                ),
                'date' => $sk->no_sk ? ($sk->tgl_penetapan ? \Carbon\Carbon::parse($sk->tgl_penetapan)->isoFormat('D MMMM Y') : null) : null,
                'waiting_time' => $sk->status === 'menunggu_sekjen' ? ($struktur->diverifikasi_daerah_at ? \Carbon\Carbon::parse($struktur->diverifikasi_daerah_at)->diffForHumans(null, true) . ' (Menunggu Sekjen)' : ($struktur->diajukan_at ? \Carbon\Carbon::parse($struktur->diajukan_at)->diffForHumans(null, true) . ' (Menunggu Sekjen)' : '-')) : (in_array($sk->status, ['menunggu_ketum', 'terbit']) ? 'Selesai diverifikasi' : 'Menunggu tahap sebelumnya'),
                'notes' => $sk->no_sk ? 'Draf SK telah dinomori: ' . $sk->no_sk : 'Pemeriksaan kepatuhan AD/ART dan penomoran resmi SK.',
            ],
            'step_4' => [
                'title' => 'Pengesahan & Tanda Tangan Ketum',
                'actor' => 'Ketua Umum PP ASKLIN',
                'icon' => 'fa-signature',
                'status' => $sk->status === 'terbit' ? 'completed' : (
                    $sk->status === 'menunggu_ketum' ? 'active' : 'pending'
                ),
                'date' => $sk->status === 'terbit' ? ($sk->updated_at ? \Carbon\Carbon::parse($sk->updated_at)->isoFormat('D MMMM Y, HH:mm') : null) : null,
                'waiting_time' => $sk->status === 'menunggu_ketum' ? ($sk->updated_at ? \Carbon\Carbon::parse($sk->updated_at)->diffForHumans(null, true) . ' (Menunggu Ketum)' : '-') : ($sk->status === 'terbit' ? 'Telah Ditandatangani' : 'Menunggu persetujuan Sekjen'),
                'notes' => $sk->status === 'terbit' ? 'Disahkan oleh Ketua Umum: ' . ($sk->ketuaUmum->nama_lengkap ?? 'Ketua Umum PP ASKLIN') : 'Pemberian persetujuan akhir dan tanda tangan digital resmi.',
            ],
            'step_5' => [
                'title' => 'Penerbitan SK Resmi & QR Code',
                'actor' => 'Sistem ASKLIN & PP ASKLIN',
                'icon' => 'fa-stamp',
                'status' => $sk->status === 'terbit' ? 'completed' : 'pending',
                'date' => $sk->status === 'terbit' ? ($sk->updated_at ? \Carbon\Carbon::parse($sk->updated_at)->isoFormat('D MMMM Y') : null) : null,
                'waiting_time' => $sk->status === 'terbit' ? 'Sah & Berlaku' : 'Belum Diterbitkan',
                'notes' => $sk->status === 'terbit' ? 'Dokumen PDF resmi siap dicetak dengan validasi QR Code.' : 'Menunggu seluruh proses tanda tangan selesai.',
            ],
        ];
    @endphp

    <!-- Container Timeline Horizontal -->
    <div id="timeline-horizontal-container" class="card p-6 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <div class="min-w-[900px] flex items-stretch justify-between relative py-4">
            <!-- Garis Penghubung Belakang -->
            <div class="absolute top-10 left-12 right-12 h-1 bg-slate-200 dark:bg-slate-800 -z-0"></div>

            @foreach($steps as $key => $s)
                @php
                    $isDone = ($s['status'] === 'completed');
                    $isActive = ($s['status'] === 'active');
                    $isSkipped = ($s['status'] === 'skipped');
                @endphp
                <div class="flex-1 flex flex-col items-center text-center px-2 relative z-10">
                    <!-- Icon Circle -->
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-lg mb-3 shadow-sm transition-all
                        {{ $isDone ? 'bg-emerald-600 text-white shadow-emerald-500/20' : ($isActive ? 'bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-950 animate-bounce' : ($isSkipped ? 'bg-slate-300 dark:bg-slate-800 text-slate-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-300 dark:border-slate-700')) }}">
                        @if($isDone)
                            <i class="fas fa-check font-bold"></i>
                        @elseif($isActive)
                            <i class="fas fa-hourglass-half animate-spin"></i>
                        @else
                            <i class="fas {{ $s['icon'] }}"></i>
                        @endif
                    </div>

                    <!-- Step Title -->
                    <h4 class="text-xs font-bold text-slate-800 dark:text-white leading-tight mb-1">
                        {{ $s['title'] }}
                    </h4>
                    <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 block mb-1">
                        {{ $s['actor'] }}
                    </span>

                    <!-- Status Pill -->
                    @if($isDone)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 mb-1">
                            Selesai
                        </span>
                    @elseif($isActive)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 mb-1 animate-pulse">
                            Sedang Berjalan
                        </span>
                    @elseif($isSkipped)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 mb-1">
                            Dilewati
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 mb-1">
                            Menunggu
                        </span>
                    @endif

                    <!-- Waktu Tunggu / Durasi -->
                    <p class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-semibold mb-0.5">
                        <i class="far fa-clock mr-0.5"></i> {{ $s['waiting_time'] }}
                    </p>
                    @if($s['date'])
                        <span class="text-[9px] text-slate-400 block">{{ $s['date'] }}</span>
                    @endif

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 px-1">
                        {{ $s['notes'] }}
                    </p>

                    <!-- Tombol Cepat Contextual -->
                    @if($isActive)
                        <div class="mt-3">
                            <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-primary btn-xs text-[10px] py-1 px-2.5 shadow-xs">
                                Periksa SK Sekarang &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Container Timeline Vertikal (Hidden by default, toggleable) -->
    <div id="timeline-vertical-container" class="card p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hidden">
        <div class="space-y-6 relative pl-6 before:absolute before:left-8 before:top-4 before:bottom-4 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
            @foreach($steps as $key => $s)
                @php
                    $isDone = ($s['status'] === 'completed');
                    $isActive = ($s['status'] === 'active');
                    $isSkipped = ($s['status'] === 'skipped');
                @endphp
                <div class="relative flex items-start gap-4">
                    <!-- Icon Bullet -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm shrink-0 shadow-sm relative z-10
                        {{ $isDone ? 'bg-emerald-600 text-white' : ($isActive ? 'bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-950 animate-pulse' : ($isSkipped ? 'bg-slate-300 dark:bg-slate-800 text-slate-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-300 dark:border-slate-700')) }}">
                        @if($isDone)
                            <i class="fas fa-check"></i>
                        @elseif($isActive)
                            <i class="fas fa-hourglass-half"></i>
                        @else
                            <i class="fas {{ $s['icon'] }}"></i>
                        @endif
                    </div>

                    <!-- Content Card -->
                    <div class="card p-4 rounded-xl flex-1 border border-slate-200/80 dark:border-slate-800 {{ $isActive ? 'bg-amber-50/20 dark:bg-amber-950/10 border-amber-300 dark:border-amber-900' : '' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
                                    {{ $s['title'] }}
                                </h4>
                                <span class="text-[11px] font-semibold text-slate-500">
                                    Penanggung Jawab: {{ $s['actor'] }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                @if($isDone)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        SELESAI
                                    </span>
                                @elseif($isActive)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 animate-pulse">
                                        SEDANG BERJALAN
                                    </span>
                                @elseif($isSkipped)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        DILEWATI
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                        MENUNGGU
                                    </span>
                                @endif
                                <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $s['waiting_time'] }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">
                            {{ $s['notes'] }}
                        </p>

                        @if($s['date'])
                            <p class="text-[11px] text-slate-400 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $s['date'] }}
                            </p>
                        @endif

                        @if($isActive)
                            <div class="mt-3 pt-3 border-t border-amber-200/50 dark:border-amber-900/50">
                                <a href="{{ route('sk_pengurus.show', $sk->id) }}" class="btn btn-primary btn-sm text-xs py-1.5 px-3">
                                    <i class="fas fa-edit mr-1"></i> Periksa & Tindak Lanjuti di Ruang Kerja SK &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Log Aktivitas Nyata dari Database -->
    <div class="card rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-xs">
        <div class="card-header flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800">
            <h4 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-history text-emerald-600 mr-1.5"></i> Riwayat Log & Jejak Audit Persetujuan
            </h4>
            <span class="text-xs text-slate-400 font-mono">{{ $logs->count() }} Catatan Aktivitas</span>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
            @forelse($logs as $log)
                <div class="p-4 sm:p-5 flex items-start gap-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0">
                        <i class="fas fa-clipboard-check text-sm"></i>
                    </div>
                    <div class="space-y-1 flex-1">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs sm:text-sm text-slate-800 dark:text-white">
                                    {{ $log->user->name ?? 'Sistem / Pengurus' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                    {{ $log->role_name ?? 'Pengurus' }}
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">
                                {{ $log->created_at ? $log->created_at->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $log->catatan ?: 'Tindakan: ' . str_replace('_', ' ', $log->action) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Belum ada riwayat aktivitas tercatat untuk SK ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnHorizontal = document.getElementById('btn-view-horizontal');
        const btnVertical = document.getElementById('btn-view-vertical');
        const containerHorizontal = document.getElementById('timeline-horizontal-container');
        const containerVertical = document.getElementById('timeline-vertical-container');

        if (btnHorizontal && btnVertical && containerHorizontal && containerVertical) {
            btnHorizontal.addEventListener('click', function () {
                containerHorizontal.classList.remove('hidden');
                containerVertical.classList.add('hidden');

                btnHorizontal.classList.add('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-2xs');
                btnHorizontal.classList.remove('text-slate-600', 'dark:text-slate-400');

                btnVertical.classList.remove('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-2xs');
                btnVertical.classList.add('text-slate-600', 'dark:text-slate-400');
            });

            btnVertical.addEventListener('click', function () {
                containerVertical.classList.remove('hidden');
                containerHorizontal.classList.add('hidden');

                btnVertical.classList.add('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-2xs');
                btnVertical.classList.remove('text-slate-600', 'dark:text-slate-400');

                btnHorizontal.classList.remove('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-2xs');
                btnHorizontal.classList.add('text-slate-600', 'dark:text-slate-400');
            });
        }
    });
</script>
@endpush

