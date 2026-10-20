@extends('layouts.backend.layout')

@section('title', 'Dashboard Hasil Pemilihan Ketua ASKLIN')
@section('page_title', 'Dashboard Pemilihan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Banner & Selector -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-rose-500 to-red-600 flex items-center justify-center text-white text-xl shadow-md shadow-red-500/20">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                    Dashboard Hasil Pemilihan Ketua
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Rekapitulasi perolehan suara real-time & tingkat partisipasi faskes (LUBER JURDIL).
                </p>
            </div>
        </div>

        <!-- Election Selector -->
        <div class="flex flex-wrap items-center gap-2">
            <form action="{{ route('pemilihan.dashboard') }}" method="GET" class="flex items-center gap-2">
                <select name="pemilihan_id" onchange="this.form.submit()" class="form-control text-xs rounded-xl border-slate-200 dark:border-slate-700 max-w-xs font-medium">
                    @foreach ($allPemilihan as $p)
                        <option value="{{ $p->id }}" {{ $pemilihan->id === $p->id ? 'selected' : '' }}>
                            [{{ strtoupper($p->tingkat) }}] {{ Str::limit($p->judul, 45) }} ({{ $p->status }})
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('pemilihan.laporan.export', $pemilihan->id) }}" class="btn btn-sm btn-outline-success rounded-xl text-xs font-semibold">
                <i class="fas fa-file-excel mr-1"></i> Unduh Excel
            </a>

            @can('pemilihan-audit-suara')
                <a href="{{ route('pemilihan.audit', $pemilihan->id) }}" class="btn btn-sm btn-outline-danger rounded-xl text-xs font-semibold" title="Khusus Superadmin: Audit Bukti Suara">
                    <i class="fas fa-user-secret mr-1"></i> Audit Rahasia
                </a>
            @endcan
        </div>
    </div>

    <!-- Overview Banner of Selected Election -->
    <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-600 text-white">
                    {{ $pemilihan->tingkat === 'cabang' ? 'Cabang ' . $pemilihan->wilayah_nama : 'Daerah ' . $pemilihan->wilayah_nama }}
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-white/10 text-slate-200">
                    Periode {{ $pemilihan->periode_text }}
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pemilihan->status === 'aktif' ? 'bg-emerald-500 text-white' : 'bg-slate-600 text-slate-200' }}">
                    {{ $pemilihan->status }}
                </span>
            </div>
            <h3 class="text-base font-bold text-white">{{ $pemilihan->judul }}</h3>
            <p class="text-xs text-slate-300 mt-1">
                <i class="far fa-clock mr-1"></i> Jadwal: {{ $pemilihan->tanggal_mulai->format('d M Y H:i') }} - {{ $pemilihan->tanggal_selesai->format('d M Y H:i') }} WIB
            </p>
        </div>
        <div class="text-right">
            <div class="text-xs text-slate-400">Total Partisipasi Suara</div>
            <div class="text-3xl font-extrabold text-emerald-400">{{ $persentasePartisipasi }}%</div>
            <div class="text-[11px] text-slate-300">{{ number_format($totalSuara) }} dari {{ number_format($totalDpt) }} faskes sah</div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">DPT Faskes Sah</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-hospital"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalDpt) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Klinik status "Approved"</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Suara Masuk</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ number_format($totalSuara) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Sudah memberikan suara</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Belum Memilih</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ number_format($belumMemilih) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Faskes belum voting</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Calon Terunggul</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="text-base font-bold text-slate-800 dark:text-white truncate">
                {{ $pemenang ? $pemenang->nama_calon : 'Belum Ada Suara' }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1">
                {{ $pemenang ? 'No. ' . $pemenang->nomor_urut . ' (' . $pemenang->total_suara_hitung . ' suara)' : '-' }}
            </p>
        </div>
    </div>

    <!-- Candidate Real Count Cards -->
    <div>
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
            <i class="fas fa-poll text-red-600"></i> Perolehan Suara Calon Ketua (Real Count)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($kandidats as $kand)
                @php
                    $suara = $kand->total_suara_hitung;
                    $persen = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
                    $isUnggul = ($pemenang && $pemenang->id === $kand->id && $totalSuara > 0);
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border {{ $isUnggul ? 'border-2 border-red-500 ring-2 ring-red-500/10' : 'border-slate-100 dark:border-slate-800' }} relative flex flex-col justify-between">
                    @if ($isUnggul)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-[10px] font-extrabold uppercase px-3 py-0.5 rounded-full shadow flex items-center gap-1">
                            <i class="fas fa-crown text-amber-300"></i> Calon Terunggul
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-600 text-white font-extrabold flex items-center justify-center text-lg shadow-sm">
                            {{ $kand->nomor_urut }}
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-slate-800 dark:text-white">{{ $persen }}%</div>
                            <div class="text-xs text-slate-400">{{ number_format($suara) }} Suara</div>
                        </div>
                    </div>

                    <!-- Candidate Identity -->
                    <div class="flex items-center gap-3 mb-4">
                        @if ($kand->foto_url)
                            <img src="{{ $kand->foto_url }}" alt="{{ $kand->nama_calon }}" class="w-14 h-14 rounded-2xl object-cover border shadow-sm">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-slate-200 to-slate-300 text-slate-700 flex items-center justify-center text-xl font-black">
                                {{ substr($kand->nama_calon, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm">
                                {{ $kand->nama_lengkap }}
                            </h4>
                            <p class="text-xs text-slate-500">
                                <i class="fas fa-clinic-medical mr-1 text-slate-400"></i> {{ $kand->asal_klinik ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden p-0.5">
                        <div class="bg-gradient-to-r from-rose-500 to-red-600 h-full rounded-full transition-all duration-700" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Kerahasiaan Suara Notice -->
    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-3">
        <i class="fas fa-shield-alt text-lg text-amber-600"></i>
        <div>
            <span class="font-bold">Asas LUBER JURDIL:</span> Sesuai anggaran dasar organisasi, hasil perolehan suara ditampilkan secara anonim dan transparan tanpa memperlihatkan pilihan masing-masing klinik pemilih guna menjaga independensi dan rahasia pemilihan.
        </div>
    </div>

</div>
@endsection
