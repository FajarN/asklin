@extends('layouts.backend.layout')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('dashboard.index') }}" class="hover:underline">Dashboard</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Konfigurasi</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Pengaturan Sistem & Aplikasi
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Kelola konfigurasi umum, logo, kontak organisasi, dan preferensi server.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Settings Content Card -->
    <div class="card">
        <div class="card-body p-6">
            @include('app_settings::_settings')
        </div>
    </div>

</div>
@endsection
