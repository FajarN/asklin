<div class="flex items-center gap-1.5 flex-nowrap" role="group">
    <a href="{{ route('anggota.detail_anggota', $id) }}"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white dark:bg-emerald-950/40 dark:text-emerald-300 dark:hover:bg-emerald-600 dark:hover:text-white border border-emerald-200 dark:border-emerald-800 shadow-sm transition-all duration-150 whitespace-nowrap"
       title="Lihat Detail Profil Anggota">
        <i class="fas fa-eye text-[11px]"></i>
        <span>Detail</span>
    </a>
    <a href="{{ route('anggota.printsk', $id) }}" target="_blank"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-600 hover:text-white dark:bg-sky-950/40 dark:text-sky-300 dark:hover:bg-sky-600 dark:hover:text-white border border-sky-200 dark:border-sky-800 shadow-sm transition-all duration-150 whitespace-nowrap"
       title="Cetak Surat Keputusan">
        <i class="fas fa-print text-[11px]"></i>
        <span>Print SK</span>
    </a>
    <button type="button"
            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-950/40 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white border border-rose-200 dark:border-rose-900 transition-all duration-150 whitespace-nowrap"
            onclick="confirmDeleteAnggota({{ $id }}, '{{ addslashes($nama_klinik ?? 'Nama tidak tersedia') }}')"
            title="Hapus data anggota">
        <i class="fas fa-trash-alt text-[11px]"></i>
        <span class="hidden sm:inline">Delete</span>
    </button>
</div>
