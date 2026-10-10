@extends('layouts.backend.layout')

@section('title', 'Pusat Notifikasi')
@section('page_title', 'Pusat Notifikasi')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Pusat Notifikasi'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2">
                <span>Pusat Notifikasi & Pemberitahuan</span>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500 text-white animate-pulse">
                        {{ $unreadCount }} Baru
                    </span>
                @endif
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Pantau antrean persetujuan SK kepengurusan, verifikasi anggota, dan pemberitahuan penting organisasi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                        <i class="fas fa-check-double text-xs"></i>
                        <span>Tandai Semua Dibaca</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm flex items-center gap-1.5">
                <i class="fas fa-home text-xs"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter Tab Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('notifications.index') }}" 
           class="px-3.5 py-2 rounded-xl font-semibold transition-all {{ empty($type) && !$unreadOnly ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('notifications.index', ['unread' => 1]) }}" 
           class="px-3.5 py-2 rounded-xl font-semibold transition-all {{ $unreadOnly ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50' }}">
            Belum Dibaca ({{ $unreadCount }})
        </a>
        <a href="{{ route('notifications.index', ['type' => 'sk_pengurus']) }}" 
           class="px-3.5 py-2 rounded-xl font-semibold transition-all {{ $type === 'sk_pengurus' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50' }}">
            <i class="fas fa-stamp mr-1 text-xs"></i> Persetujuan SK
        </a>
        <a href="{{ route('notifications.index', ['type' => 'anggota']) }}" 
           class="px-3.5 py-2 rounded-xl font-semibold transition-all {{ $type === 'anggota' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50' }}">
            <i class="fas fa-user-check mr-1 text-xs"></i> Keanggotaan
        </a>
        <a href="{{ route('notifications.index', ['type' => 'expired_sio']) }}" 
           class="px-3.5 py-2 rounded-xl font-semibold transition-all {{ $type === 'expired_sio' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50' }}">
            <i class="fas fa-exclamation-triangle mr-1 text-xs"></i> Peringatan SIO
        </a>
    </div>

    <!-- Notification List Card -->
    <div class="card overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @foreach($notifications as $item)
                    @php
                        $iconClass = 'fas fa-bell text-slate-500';
                        $bgIcon = 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
                        if ($item->type === 'sk_pengurus') {
                            $iconClass = 'fas fa-stamp text-sky-600 dark:text-sky-400';
                            $bgIcon = 'bg-sky-50 dark:bg-sky-950/50';
                        } elseif ($item->type === 'anggota') {
                            $iconClass = 'fas fa-user-check text-emerald-600 dark:text-emerald-400';
                            $bgIcon = 'bg-emerald-50 dark:bg-emerald-950/50';
                        } elseif ($item->type === 'expired_sio') {
                            $iconClass = 'fas fa-exclamation-triangle text-amber-600 dark:text-amber-400';
                            $bgIcon = 'bg-amber-50 dark:bg-amber-950/50';
                        }
                    @endphp
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors {{ !$item->is_read ? 'bg-emerald-50/20 dark:bg-emerald-950/10' : 'hover:bg-slate-50/50 dark:hover:bg-slate-800/30' }}">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl {{ $bgIcon }} flex items-center justify-center shrink-0 shadow-2xs">
                                <i class="{{ $iconClass }} text-base"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">
                                        {{ $item->title }}
                                    </h4>
                                    @if(!$item->is_read)
                                        <span class="inline-block w-2 h-2 rounded-full bg-rose-500" title="Belum Dibaca"></span>
                                    @endif
                                    @if($item->role_target)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                            {{ $item->role_target }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                    {{ $item->message }}
                                </p>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 pt-0.5">
                                    <span><i class="far fa-clock mr-1"></i>{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $item->created_at ? $item->created_at->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            @if(!empty($item->url))
                                <a href="{{ route('notifications.read', $item->id) }}" 
                                   class="btn btn-primary btn-sm flex items-center gap-1.5 text-xs">
                                    <span>Buka Dokumen / Aksi</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @elseif(!$item->is_read)
                                <a href="{{ route('notifications.read', $item->id) }}" 
                                   class="btn btn-secondary btn-sm text-xs">
                                    Tandai Dibaca
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-12 text-center text-slate-400 space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak Ada Notifikasi</h4>
                <p class="text-xs max-w-sm mx-auto">
                    Semua pemberitahuan telah diperiksa. Anda akan mendapatkan notifikasi saat ada struktur atau dokumen baru yang perlu disetujui.
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
