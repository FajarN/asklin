@extends('layouts.backend.layout')

@section('title', 'Dashboard Hasil Pemilihan')
@section('page_title', 'Dashboard Pemilihan')

@section('content')
<div class="max-w-xl mx-auto py-12 text-center">
    <div class="w-20 h-20 rounded-3xl bg-red-50 dark:bg-red-950/40 text-red-600 flex items-center justify-center mx-auto text-3xl mb-4 shadow-sm">
        <i class="fas fa-vote-yea"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Belum Ada Agenda Pemilihan</h3>
    <p class="text-xs text-slate-500 mb-6">
        Saat ini belum ada data agenda pemilihan ketua untuk wilayah Anda. Buat agenda pemilihan baru untuk memulai pemungutan suara.
    </p>
    @can('pemilihan-create')
        <a href="{{ route('pemilihan.create') }}" class="btn btn-primary rounded-xl px-5 text-sm">
            <i class="fas fa-plus mr-1"></i> Buat Agenda Pemilihan
        </a>
    @endcan
</div>
@endsection
