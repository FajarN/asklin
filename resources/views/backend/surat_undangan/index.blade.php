@extends('layouts.backend.layout')

@section('title', 'Surat Undangan')
@section('page_title', 'Surat Undangan')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Persuratan'],
        ['label' => 'Surat Undangan'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Daftar Surat Undangan
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Penerbitan surat undangan acara, musyawarah, dan rapat koordinasi organisasi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-envelope text-emerald-600 mr-1.5"></i> Arsip Surat Undangan
            </h4>
        </div>
        <div class="card-body pt-4 pb-4">
            <form method="GET" action="{{ route('surat_undangan.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="form-label" for="start_date">Dari Tanggal:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control w-full" value="{{ $startDate }}">
                    </div>
                    <div>
                        <label class="form-label" for="end_date">Sampai Tanggal:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control w-full" value="{{ $endDate }}">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary flex-1">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('surat_undangan.index') }}" class="btn btn-secondary flex-1">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Tanggal Surat</th>
                            <th>No Surat</th>
                            <th>Perihal</th>
                            <th>Hari</th>
                            <th>Tanggal Agenda</th>
                            <th>Waktu</th>
                            <th>Tempat</th>
                            <th>Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratUndangan as $item)
                            <tr>
                                <td>{{ $item->suratKeluar->tgl_surat }}</td>
                                <td>{{ $item->suratKeluar->no_surat }}</td>
                                <td>{{ $item->suratKeluar->perihal }}</td>
                                <td>{{ $item->hari }}</td>
                                <td>{{ $item->tgl_acara }}</td>
                                <td>{{ $item->waktu_acara }}</td>
                                <td>{{ $item->lokasi_acara }}</td>
                                <td>
                                    <a href="{{ route('surat_undangan.print', $item->id_surat) }}" target="_blank"  class="btn btn-sm btn-success">
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center p-4">
                    {{ $suratUndangan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
