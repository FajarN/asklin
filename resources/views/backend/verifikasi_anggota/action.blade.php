<div class="flex items-center gap-1.5 flex-nowrap" role="group">
    @hasanyrole('Admin Cabang')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all duration-150 whitespace-nowrap">
            <i class="fas fa-check-circle text-[11px]"></i>
            <span>Verifikasi Cabang</span>
        </a>
    @endhasanyrole

    @hasanyrole('Admin Daerah')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all duration-150 whitespace-nowrap">
            <i class="fas fa-check-circle text-[11px]"></i>
            <span>Verifikasi Daerah</span>
        </a>
    @endhasanyrole

    @hasanyrole('Admin Pusat|Ketua Umum')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all duration-150 whitespace-nowrap">
            <i class="fas fa-check-circle text-[11px]"></i>
            <span>Verifikasi Ketum</span>
        </a>
    @endhasanyrole

    @hasanyrole('Sekjen')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all duration-150 whitespace-nowrap">
            <i class="fas fa-check-circle text-[11px]"></i>
            <span>Verifikasi Sekjen</span>
        </a>
    @endhasanyrole

    @hasanyrole('Bendahara')
        <label class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 cursor-pointer hover:bg-slate-200/70 transition-colors m-0">
            <input class="checkbox w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600"
                   type="checkbox" name="status_pembayaran" id="status_pembayaran_{{ $id }}" data-id="{{ $id }}"
                   {{ $status_pembayaran == 1 ? ' checked' : '' }}>
            <span>Lunas</span>
        </label>
    @endhasanyrole

    @hasanyrole('Superadmin')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow transition-all duration-150 whitespace-nowrap">
            <i class="fas fa-clipboard-check text-[11px]"></i>
            <span>Verifikasi</span>
        </a>
        <label class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 cursor-pointer hover:bg-slate-200/70 transition-colors m-0">
            <input class="checkbox w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600"
                   type="checkbox" name="status_pembayaran" id="status_pembayaran_{{ $id }}" data-id="{{ $id }}"
                   {{ $status_pembayaran == 1 ? ' checked' : '' }}>
            <span>Lunas</span>
        </label>
    @endhasanyrole

    {{-- Tombol Delete untuk semua role yang memiliki akses --}}
    @hasanyrole('Admin Cabang|Admin Daerah|Admin Pusat|Ketua Umum|Sekjen|Superadmin')
        <button type="button"
                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-950/40 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white border border-rose-200 dark:border-rose-900 transition-all duration-150 whitespace-nowrap"
                onclick="confirmDeleteVerifikasi({{ $id }}, '{{ addslashes($nama_klinik ?? 'Nama tidak tersedia') }}', '{{ $status }}')"
                title="Hapus data permohonan">
            <i class="fas fa-trash-alt text-[11px]"></i>
            <span class="hidden sm:inline">Delete</span>
        </button>
    @endhasanyrole
</div>
