@extends('layouts.backend.layout')

@section('title', 'Detail Surat Keluar')
@section('page_title', 'Detail Surat')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                    <i class="fas fa-envelope-open-text mr-1"></i> Surat Keluar
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                    {{ $suratKeluar->no_surat }}
                </span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                {{ $suratKeluar->perihal }}
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400">
                Informasi detail arsip surat, riwayat disposisi, dan dokumen lampiran resmi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('surat_keluar.print', $suratKeluar->id) }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-print"></i>
                <span>Cetak Surat</span>
            </a>
            <a href="{{ route('surat_keluar.edit', $suratKeluar->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('surat_keluar.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Card: Informasi Surat Keluar -->
    <div class="card">
        <div class="card-header">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-info-circle text-emerald-600 mr-1.5"></i> Ringkasan Surat
            </h4>
            @php
                $statusColors = [
                    'draft' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800',
                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800',
                    'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800',
                    'terkirim' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-800',
                ];
                $color = $statusColors[$suratKeluar->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                Status: {{ ucfirst($suratKeluar->status) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-800 text-xs">
                <!-- Kolom Kiri -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Jenis Surat</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium">
                            {{ $suratKeluar->jenisSurat->nama_jenis ?? '-' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Nomor Surat</span>
                        <span class="col-span-2 font-mono font-bold text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->no_surat }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Tanggal Surat</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ \Carbon\Carbon::parse($suratKeluar->tgl_surat)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Perihal</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                            {{ $suratKeluar->perihal }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Dibuat Pada</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->created_at ? $suratKeluar->created_at->format('d-m-Y H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conditional Module: Surat Tugas -->
    @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'tugas') !== false && $suratKeluar->suratTugas)
        <div class="card space-y-4">
            <div class="card-header">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <i class="fas fa-briefcase text-emerald-600 mr-1.5"></i> Informasi Pelaksanaan Surat Tugas
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Asal & Nomor Dasar</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium">
                            {{ $suratKeluar->suratTugas->asal_surat }} &bull; (No: {{ $suratKeluar->suratTugas->nomor_asal_surat }})
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Agenda / Kegiatan</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                            {{ $suratKeluar->suratTugas->agenda }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Waktu & Tanggal</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->suratTugas->hari }}, {{ \Carbon\Carbon::parse($suratKeluar->suratTugas->tgl_agenda)->translatedFormat('d F Y') }} (Pukul: {{ $suratKeluar->suratTugas->waktu_agenda }})
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Tempat Penugasan</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->suratTugas->tempat_agenda }}
                        </span>
                    </div>
                </div>

                <!-- Table Daftar Pengurus yang Ditugaskan -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Daftar Pengurus Ditugaskan
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped w-full text-xs">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengurus</th>
                                    <th>Jabatan Organisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suratKeluar->suratTugas->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-semibold text-slate-800 dark:text-slate-200">{{ $detail->nama_pengurus }}</td>
                                        <td>{{ $detail->jabatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Conditional Module: Surat Undangan -->
    @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'undangan') !== false && $suratKeluar->suratUndangan)
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <i class="fas fa-calendar-check text-emerald-600 mr-1.5"></i> Detail Surat Undangan
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Kepada Penerima</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-bold">
                            {{ $suratKeluar->suratUndangan->nama_penerima }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Alamat Penerima</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->suratUndangan->alamat_penerima }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Judul & Tujuan Acara</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium">
                            {{ $suratKeluar->suratUndangan->judul_acara }} &bull; {{ $suratKeluar->suratUndangan->tujuan_acara }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Waktu & Lokasi</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->suratUndangan->waktu_tgl_acara }} &bull; Tempat: {{ $suratKeluar->suratUndangan->lokasi_acara }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Agenda Kegiatan</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200 leading-relaxed">
                            {{ $suratKeluar->suratUndangan->agenda_acara }}
                        </span>
                    </div>
                    @if($suratKeluar->suratUndangan->informasi_tambahan)
                    <div class="grid grid-cols-3 p-3.5">
                        <span class="font-semibold text-slate-500">Informasi Tambahan</span>
                        <span class="col-span-2 text-slate-800 dark:text-slate-200">
                            {{ $suratKeluar->suratUndangan->informasi_tambahan }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
