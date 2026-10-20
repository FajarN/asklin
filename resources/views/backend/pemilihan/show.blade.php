@extends('layouts.backend.layout')

@section('title', 'Detail Agenda Pemilihan - ' . $pemilihan->judul)
@section('page_title', 'Detail Agenda Pemilihan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('pemilihan.index') }}" class="btn btn-secondary btn-sm rounded-xl">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge badge-info bg-cyan-600 text-white px-2 py-0.5 rounded text-xs uppercase font-bold">
                        {{ $pemilihan->tingkat === 'cabang' ? 'Cabang ' . ($pemilihan->kota ? $pemilihan->kota->name : $pemilihan->id_kota) : 'Daerah ' . ($pemilihan->provinsi ? $pemilihan->provinsi->name : $pemilihan->id_provinsi) }}
                    </span>
                    <span class="badge badge-dark bg-slate-700 text-white px-2 py-0.5 rounded text-xs font-semibold">
                        Periode {{ $pemilihan->periode_text }}
                    </span>
                    @php
                        $badges = [
                            'draft' => 'bg-slate-200 text-slate-700',
                            'aktif' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                            'selesai' => 'bg-blue-100 text-blue-800',
                            'ditutup' => 'bg-rose-100 text-rose-800',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $badges[$pemilihan->status] ?? '' }}">
                        {{ $pemilihan->status }}
                    </span>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                    {{ $pemilihan->judul }}
                </h2>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @can('pemilihan-dashboard')
                <a href="{{ route('pemilihan.dashboard', ['pemilihan_id' => $pemilihan->id]) }}" class="btn btn-outline-primary btn-sm rounded-xl">
                    <i class="fas fa-chart-pie mr-1"></i> Dashboard Real Count
                </a>
            @endcan
            @can('pemilihan-edit')
                <a href="{{ route('pemilihan.edit', $pemilihan->id) }}" class="btn btn-warning btn-sm rounded-xl text-white">
                    <i class="fas fa-edit mr-1"></i> Edit Pemilihan
                </a>
            @endcan
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-hospital-user"></i>
            </div>
            <div>
                <div class="text-xs text-slate-400 font-semibold uppercase">DPT Faskes Sah (Approve)</div>
                <div class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ number_format($totalDpt) }}</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-vote-yea"></i>
            </div>
            <div>
                <div class="text-xs text-slate-400 font-semibold uppercase">Suara Masuk</div>
                <div class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ number_format($totalSuaraMasuk) }}</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-percentage"></i>
            </div>
            <div>
                <div class="text-xs text-slate-400 font-semibold uppercase">Partisipasi Suara</div>
                <div class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ $persentasePartisipasi }}%</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="text-xs text-slate-400 font-semibold uppercase">Jumlah Calon</div>
                <div class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ $pemilihan->kandidat->count() }} Orang</div>
            </div>
        </div>
    </div>

    <!-- Informasi Jadwal & Status Cepat -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-b pb-3 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="fas fa-calendar-check text-red-600"></i> Jadwal Pelaksanaan & Aturan
            </span>
            @can('pemilihan-edit')
                <div class="flex items-center gap-2 text-xs normal-case font-normal">
                    <span>Ubah Cepat Status:</span>
                    <select id="quickStatusSelect" class="form-control form-control-sm text-xs rounded-lg py-1 px-2 border-slate-300">
                        <option value="draft" {{ $pemilihan->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ $pemilihan->status === 'aktif' ? 'selected' : '' }}>Aktif (Voting Buka)</option>
                        <option value="selesai" {{ $pemilihan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ $pemilihan->status === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
            @endcan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 font-semibold mb-1">Rentang Waktu Voting:</div>
                <div class="text-slate-700 dark:text-slate-200 font-bold text-sm">
                    {{ $pemilihan->tanggal_mulai->format('d M Y H:i') }} WIB s/d {{ $pemilihan->tanggal_selesai->format('d M Y H:i') }} WIB
                </div>
                <div class="mt-2 text-slate-500">
                    @if ($pemilihan->is_berlangsung)
                        <span class="text-emerald-600 font-bold"><i class="fas fa-circle text-[9px] animate-pulse"></i> Sedang Berlangsung Sekarang</span>
                    @elseif (now()->lt($pemilihan->tanggal_mulai))
                        <span class="text-amber-600 font-semibold"><i class="far fa-clock"></i> Belum Dimulai</span>
                    @else
                        <span class="text-slate-500"><i class="fas fa-check-circle"></i> Waktu Voting Telah Berakhir</span>
                    @endif
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 font-semibold mb-1">Tata Tertib & Deskripsi:</div>
                <div class="text-slate-600 dark:text-slate-300">
                    {{ $pemilihan->deskripsi ?? 'Tidak ada tata tertib khusus yang dilampirkan.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Kandidat Grid -->
    <div>
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
            <i class="fas fa-user-tie text-red-600"></i> Calon Kandidat Ketua
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($pemilihan->kandidat as $kand)
                @php
                    $suaraCalon = $kand->total_suara;
                    $persenCalon = $totalSuaraMasuk > 0 ? round(($suaraCalon / $totalSuaraMasuk) * 100, 1) : 0;
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-between relative overflow-hidden">
                    <!-- Top Ribbon Nomor Urut -->
                    <div class="absolute top-0 right-0 bg-red-600 text-white font-extrabold text-sm px-4 py-1 rounded-bl-2xl shadow">
                        No. {{ $kand->nomor_urut }}
                    </div>

                    <div>
                        <!-- Profile Card -->
                        <div class="flex items-center gap-4 mb-4">
                            @if ($kand->foto_url)
                                <img src="{{ $kand->foto_url }}" alt="{{ $kand->nama_calon }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-red-500/20 shadow">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-slate-200 to-slate-300 text-slate-600 flex items-center justify-center text-2xl font-bold border">
                                    {{ substr($kand->nama_calon, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-base">
                                    {{ $kand->nama_lengkap }}
                                </h4>
                                <p class="text-xs text-slate-500">
                                    <i class="fas fa-clinic-medical mr-1 text-slate-400"></i> {{ $kand->asal_klinik ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Progress Suara -->
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl mb-4 border border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                <span class="text-slate-500">Perolehan Suara:</span>
                                <span class="text-slate-800 dark:text-white font-bold">{{ number_format($suaraCalon) }} Suara ({{ $persenCalon }}%)</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-red-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $persenCalon }}%"></div>
                            </div>
                        </div>

                        <!-- Visi & Misi Toggle Accordion -->
                        <div class="space-y-2 text-xs">
                            @if ($kand->visi)
                                <div class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                                    <div class="font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                                        <i class="fas fa-eye text-red-500"></i> Visi:
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $kand->visi }}</p>
                                </div>
                            @endif

                            @if ($kand->misi)
                                <div class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                                    <div class="font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                                        <i class="fas fa-bullseye text-red-500"></i> Misi:
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $kand->misi }}</p>
                                </div>
                            @endif

                            @if ($kand->program_kerja)
                                <div class="border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                                    <div class="font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                                        <i class="fas fa-tasks text-red-500"></i> Program Kerja:
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $kand->program_kerja }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#quickStatusSelect').on('change', function() {
        const newStatus = $(this).val();
        if (confirm('Ubah status pemilihan menjadi "' + newStatus + '"?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('pemilihan.toggle_status', $pemilihan->id) }}",
                data: { status: newStatus },
                dataType: 'json',
                success: function(res) {
                    alert(res.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Gagal mengubah status: ' + (xhr.responseJSON?.message || ''));
                }
            });
        }
    });
});
</script>
@endpush

