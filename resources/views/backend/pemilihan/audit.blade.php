@extends('layouts.backend.layout')

@section('title', 'Audit Bukti Suara Pemilihan (Superadmin Only)')
@section('page_title', 'Audit Kerahasiaan Suara')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Warning Banner -->
    <div class="p-5 rounded-2xl bg-slate-900 text-white shadow-lg border-2 border-red-500/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-red-600/30 text-red-500 flex items-center justify-center text-2xl border border-red-500/30">
                <i class="fas fa-user-secret"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge badge-danger bg-red-600 text-white text-[10px] uppercase font-black px-2 py-0.5 rounded">
                        Sangat Rahasia
                    </span>
                    <span class="text-xs text-red-400 font-bold">Hanya Hak Akses Superadmin</span>
                </div>
                <h2 class="text-lg font-bold text-white">
                    Audit Log Suara Pemilih: {{ $pemilihan->judul }}
                </h2>
                <p class="text-xs text-slate-400">
                    Data berikut hanya digunakan untuk keperluan klarifikasi / investigasi sengketa pemilihan resmi.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('pemilihan.laporan') }}" class="btn btn-secondary btn-sm rounded-xl">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('pemilihan.audit.export', $pemilihan->id) }}" class="btn btn-danger btn-sm rounded-xl shadow-md shadow-red-600/30 font-bold">
                <i class="fas fa-file-excel mr-1"></i> Export Audit Excel
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-4 bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                <i class="fas fa-fingerprint text-red-600"></i> Log Digital Suara Masuk (Total: {{ $suaraLogs->total() }})
            </h4>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-full text-xs">
                    <thead>
                        <tr class="uppercase text-slate-500 border-b border-slate-200 dark:border-slate-700">
                            <th>No</th>
                            <th>Waktu Pemilihan</th>
                            <th>No Anggota</th>
                            <th>Nama Faskes / Klinik</th>
                            <th>Email Akun</th>
                            <th>Pilihan Calon</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suaraLogs as $idx => $s)
                            <tr class="border-b border-slate-100 dark:border-slate-800">
                                <td>{{ $suaraLogs->firstItem() + $idx }}</td>
                                <td class="font-mono text-[11px]">
                                    {{ $s->waktu_vote ? $s->waktu_vote->format('d M Y H:i:s') : '-' }}
                                </td>
                                <td class="font-mono font-bold text-slate-700 dark:text-slate-300">
                                    {{ $s->anggota ? $s->anggota->no_anggota : '-' }}
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $s->anggota ? $s->anggota->nama_klinik : '-' }}</div>
                                </td>
                                <td class="text-slate-500">
                                    {{ $s->user ? $s->user->email : '-' }}
                                </td>
                                <td>
                                    @if ($s->kandidat)
                                        <span class="badge badge-danger bg-red-600 text-white font-bold px-2 py-0.5 rounded text-[10px]">
                                            No. {{ $s->kandidat->nomor_urut }}
                                        </span>
                                        <span class="font-bold text-slate-800 dark:text-white ml-1">
                                            {{ $s->kandidat->nama_calon }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="font-mono text-[11px] text-slate-400">
                                    {{ $s->ip_address ?? '-' }}
                                </td>
                                <td class="text-[10px] text-slate-400 max-w-xs truncate" title="{{ $s->user_agent }}">
                                    {{ $s->user_agent ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-slate-400">
                                    Belum ada catatan suara masuk untuk agenda pemilihan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $suaraLogs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
