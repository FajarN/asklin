@extends('layouts.backend.layout')

@section('title', 'Profil Pengguna')
@section('page_title', 'Profil Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Profil Pengguna
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola informasi akun, kata sandi, dan data personal Anda.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left: Profile Summary Card -->
        <div class="lg:col-span-4">
            <div class="card text-center p-6 space-y-4">
                <div class="relative mx-auto w-24 h-24">
                    <img alt="Avatar" src="{{ asset('assets/backend/img/avatar/avatar-1.png') }}" class="w-24 h-24 rounded-2xl ring-4 ring-emerald-500/20 object-cover shadow-md mx-auto">
                    <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"></span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        <i class="fas fa-shield-alt mr-1.5"></i>
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 text-left space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Bergabung:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Status Akun:</span>
                        <span class="font-medium text-emerald-600">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Edit Form Card -->
        <div class="lg:col-span-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-base font-bold text-slate-800 dark:text-white">
                        <i class="fas fa-user-edit text-emerald-600 mr-2"></i> Perbarui Informasi Akun
                    </h4>
                </div>
                <form action="{{ route('profile.update', Auth::user()->id) }}" method="post" class="needs-validation">
                    @csrf
                    @method('PUT')
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">                               
                            <div>
                                <label class="form-label" for="name">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" id="name" name="name" class="form-control w-full" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div>
                                <label class="form-label" for="email">Alamat Email <span class="text-rose-500">*</span></label>
                                <input type="email" id="email" name="email" class="form-control w-full" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                                Ubah Password (Kosongkan jika tidak ingin mengubah)
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label" for="password">Password Baru</label>
                                    <input type="password" id="password" name="password" class="form-control w-full" placeholder="Minimal 8 karakter">
                                </div>
                                <div>
                                    <label class="form-label" for="confirm-password">Konfirmasi Password</label>
                                    <input type="password" id="confirm-password" name="confirm-password" class="form-control w-full" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 p-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection