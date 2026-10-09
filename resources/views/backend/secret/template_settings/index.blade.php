@extends('layouts.backend.layout')

@section('title', 'Pengaturan Template Frontend')
@section('page_title', 'Pengaturan Template Frontend')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    Frontend Management
                </span>
                <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Multi-Template Engine</span>
            </div>
            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-slate-800 dark:text-white">
                Pengaturan Template Frontend Website
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Pilih dan aktifkan tema visual frontend resmi ASKLIN. Perubahan disimpan secara persisten di database.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm flex items-center gap-2">
                <i class="fas fa-external-link-alt text-xs"></i>
                <span>Buka Frontend Utama</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold">Berhasil Diperbarui!</p>
                    <p class="text-xs opacity-90">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-xs p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($isPreview)
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-eye text-sm animate-pulse"></i>
                </div>
                <div>
                    <p class="text-xs font-bold flex items-center gap-2">
                        <span>Mode Pratinjau Sedang Aktif</span>
                        <span class="px-2 py-0.5 rounded text-[10px] bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-100 font-extrabold uppercase">
                            {{ $templates[$previewTemplate]['name'] ?? $previewTemplate }}
                        </span>
                    </p>
                    <p class="text-[11px] text-amber-700 dark:text-amber-300 mt-0.5">
                        Anda sedang mempratinjau template ini di sesi Anda. Pengunjung publik tetap melihat template aktif saat ini (<strong>{{ $templates[$activeTemplate]['name'] ?? $activeTemplate }}</strong>).
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka Hasil Pratinjau
                </a>
                <a href="{{ route('template_settings.exit_preview') }}" class="btn btn-sm btn-secondary text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                    <i class="fas fa-times mr-1"></i> Hentikan Pratinjau
                </a>
            </div>
        </div>
    @endif

    <!-- Template Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($templates as $key => $template)
            @php
                $isActive = ($key === $activeTemplate);
                $isPreviewCurrent = ($isPreview && $key === $previewTemplate);
            @endphp
            <div class="card relative flex flex-col overflow-hidden transition-all duration-300 {{ $isActive ? 'ring-2 ring-emerald-500 dark:ring-emerald-400 shadow-lg' : 'hover:shadow-md' }}">

                <!-- Card Header with Badges -->
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-start justify-between gap-3 bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-extrabold tracking-wider uppercase px-2 py-0.5 rounded-md border {{ $template['badge_class'] }}">
                                {{ $template['badge'] }}
                            </span>
                            @if($isActive)
                                <span class="flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-500 text-white shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    AKTIF DI PRODUCTION
                                </span>
                            @elseif($isPreviewCurrent)
                                <span class="flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-500 text-white">
                                    <i class="fas fa-eye text-[9px]"></i> SESI PRATINJAU
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white mt-2">
                            {{ $template['name'] }}
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $template['subtitle'] }}
                        </p>
                    </div>

                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-extrabold text-sm shrink-0 {{ $isActive ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }}">
                        #{{ $template['number'] }}
                    </div>
                </div>

                <!-- Template Preview Thumbnail -->
                <div class="relative bg-slate-100 dark:bg-slate-950 p-3 sm:p-4 border-b border-slate-100 dark:border-slate-800 group">
                    <div class="relative aspect-[16/10] overflow-hidden rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs bg-white dark:bg-slate-900">
                        <img src="{{ $template['thumbnail'] }}" alt="{{ $template['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                        <!-- Overlay Quick Actions -->
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                            <a href="{{ route('template_settings.preview', $template['id']) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white text-slate-900 text-xs font-bold hover:bg-slate-100 transition flex items-center gap-1.5 shadow-md">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                <span>Buka Pratinjau</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Body Details -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed text-justify">
                            {{ $template['description'] }}
                        </p>

                        <!-- Key Features -->
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                                Fitur & Karakter Visual:
                            </p>
                            <ul class="space-y-1.5">
                                @foreach($template['features'] as $feat)
                                    <li class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300">
                                        <i class="fas fa-check-circle text-emerald-500 text-xs mt-0.5 shrink-0"></i>
                                        <span>{{ $feat }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Technical Specs -->
                        <div class="mt-3 pt-3 border-t border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500">
                            <span>Arsitektur:</span>
                            <span class="font-mono font-semibold text-slate-600 dark:text-slate-300">{{ $template['tech_stack'] }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Preview Button -->
                            <a href="{{ route('template_settings.preview', $template['id']) }}" target="_blank"
                               class="btn btn-secondary btn-sm flex items-center justify-center gap-1.5 text-xs font-semibold py-2">
                                <i class="fas fa-eye text-xs"></i>
                                <span>Lihat Preview</span>
                            </a>

                            <!-- Activate Button -->
                            @if($isActive)
                                <button type="button" disabled
                                    class="w-full py-2 px-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-1.5">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Sedang Aktif</span>
                                </button>
                            @else
                                <button type="button"
                                    onclick="confirmSwitchTemplate('{{ $template['id'] }}', '{{ addslashes($template['name']) }}')"
                                    class="btn btn-primary btn-sm flex items-center justify-center gap-1.5 text-xs font-bold py-2 shadow-sm hover:scale-[1.01] transition-transform">
                                    <i class="fas fa-bolt text-xs"></i>
                                    <span>Aktifkan Template</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <!-- Hidden Form for Switching Template -->
    <form id="switch-template-form" action="{{ route('template_settings.update') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="template" id="selected-template-input" value="">
    </form>

    <!-- System Architecture & Safety Notice -->
    <div class="card p-5 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <i class="fas fa-shield-alt text-lg"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white">
                    Keamanan &amp; Integritas Multi-Template ASKLIN
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Sistem multi-template menggunakan arsitektur <strong>Isolated Scope</strong>. Mengganti template aktif <strong>tidak mengubah atau menghapus data database apapun</strong> (berita, data klinik, pengurus, agenda, atau galeri tetap utuh dan tersinkronisasi otomatis). Konfigurasi tersimpan secara persisten pada tabel <code class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-[11px] font-mono">settings (frontend_template)</code>, sehingga tidak akan kembali ke default setelah restart server atau refresh browser.
                </p>
                <div class="pt-2 flex flex-wrap gap-4 text-[11px] text-slate-400 dark:text-slate-500">
                    <span class="flex items-center gap-1"><i class="fas fa-check text-emerald-500"></i> Zero Database Migration Required</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check text-emerald-500"></i> Responsive Smartphone / Tablet / Laptop / Desktop</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check text-emerald-500"></i> Fallback Terproteksi ke Template 1 (Canvas)</span>
                </div>
            </div>
        </div>
    </div>

</div>

@push('js')
<script>
    function confirmSwitchTemplate(templateId, templateName) {
        Swal.fire({
            title: 'Konfirmasi Penggantian Template',
            html: `Apakah Anda yakin ingin mengaktifkan <strong>${templateName}</strong> sebagai template frontend utama website ASKLIN?<br><br><span class="text-xs text-slate-500">Seluruh pengunjung publik akan langsung melihat tampilan template baru ini.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Aktifkan Sekarang',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
            reverseButtons: true,
            focusConfirm: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                cancelButton: 'rounded-xl text-xs font-bold px-4 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menerapkan Perubahan...',
                    html: 'Sedang menyimpan konfigurasi dan mengaktifkan template.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                document.getElementById('selected-template-input').value = templateId;
                document.getElementById('switch-template-form').submit();
            }
        });
    }
</script>
@endpush
@endsection

