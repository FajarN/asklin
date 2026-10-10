@php
    $statusClasses = [
        'aktif' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30',
        'nonaktif' => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border-slate-500/30',
        'mengundurkan_diri' => 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border-rose-500/30',
    ];
    $statusClass = $statusClasses[$pengurus->status] ?? 'bg-slate-500/15 text-slate-700 border-slate-500/30';
    $fotoSrc = $pengurus->foto_pengurus && file_exists(public_path('storage/pengurus/' . $pengurus->foto_pengurus))
        ? asset('storage/pengurus/' . $pengurus->foto_pengurus)
        : null;
@endphp

<div class="card mb-4 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all">
    <div class="card-body p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                @if ($fotoSrc)
                    <img src="{{ $fotoSrc }}"
                         alt="{{ $pengurus->nama_lengkap }}"
                         class="w-14 h-14 rounded-2xl object-cover ring-2 ring-emerald-500/20 shadow-inner">
                @else
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white flex items-center justify-center font-bold text-lg shadow-md">
                        {{ strtoupper(substr($pengurus->nama_lengkap ?? 'P', 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $pengurus->nama_lengkap }}
                        </h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $pengurus->status)) }}
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <i class="fas fa-briefcase mr-1"></i> {{ $pengurus->jabatan }}
                        @if($pengurus->keterangan)
                            <span class="text-slate-400 font-normal">({{ $pengurus->keterangan }})</span>
                        @endif
                    </p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                        @if ($pengurus->no_telp)
                            <span class="inline-flex items-center gap-1.5 hover:text-emerald-600">
                                <i class="fas fa-phone text-[10px] text-slate-400"></i> {{ $pengurus->no_telp }}
                            </span>
                        @endif
                        @if ($pengurus->email)
                            <span class="inline-flex items-center gap-1.5 hover:text-emerald-600">
                                <i class="fas fa-envelope text-[10px] text-slate-400"></i> {{ $pengurus->email }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                            <i class="fas fa-sort-numeric-down text-[10px]"></i> Urutan: {{ $pengurus->urutan }}
                        </span>
                    </div>
                </div>
            </div>

            @if(!empty($isLocked))
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 px-2.5 py-1 bg-slate-100 dark:bg-slate-800/80 rounded-lg border border-slate-200 dark:border-slate-700">
                    <i class="fas fa-lock text-slate-400"></i> Terkunci
                </span>
            @else
                <div class="flex items-center gap-1.5 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 dark:border-slate-800">
                    <button type="button" class="btn btn-sm btn-outline-success add-sub-btn" data-id="{{ $pengurus->id }}" title="Tambah Bawahan">
                        <i class="fas fa-user-plus"></i>
                        <span class="hidden md:inline text-xs ml-1">Bawahan</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary edit-pengurus-btn" data-id="{{ $pengurus->id }}" title="Edit Pengurus">
                        <i class="fas fa-edit"></i>
                        <span class="hidden md:inline text-xs ml-1">Edit</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-pengurus-btn" data-id="{{ $pengurus->id }}" title="Hapus Pengurus">
                        <i class="fas fa-trash"></i>
                        <span class="hidden md:inline text-xs ml-1">Hapus</span>
                    </button>
                </div>
            @endif
        </div>

        @php
            $children = App\Models\StrukturPengurus::where('parent_id', $pengurus->id)->orderBy('urutan', 'asc')->get();
        @endphp

        @if (count($children) > 0)
            <div class="mt-4 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800/80 pl-3 sm:pl-6 border-l-2 border-l-emerald-500/40 space-y-3">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mb-2">
                    <i class="fas fa-level-down-alt"></i> Anggota Tim / Bawahan Langsung ({{ count($children) }})
                </div>
                @foreach ($children as $child)
                    @include('backend.struktur_organisasi.components.pengurus_card', ['pengurus' => $child, 'isLocked' => !empty($isLocked)])
                @endforeach
            </div>
        @endif
    </div>
</div>
