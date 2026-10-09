@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">

    <!-- Welcome Greeting Header Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 p-6 sm:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm border border-white/30">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengurus' }}
                    </span>
                    <span class="text-xs text-emerald-100 font-medium">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-xs text-emerald-100 max-w-2xl leading-relaxed">
                    Sistem Manajemen Keanggotaan Asosiasi Klinik Indonesia. Pantau status registrasi, verifikasi berkas faskes, dan laporan persebaran klinik nasional.
                </p>
            </div>
            
            <div class="flex items-center gap-3 shrink-0">
                @can('verifikasi-anggota')
                    <a href="{{ route('verifikasi_anggota.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-emerald-800 hover:bg-emerald-50 font-bold text-xs shadow-sm transition hover:scale-105">
                        <i class="fas fa-clipboard-check text-emerald-600"></i>
                        <span>Antrean Verifikasi</span>
                    </a>
                @endcan
                @can('data-anggota')
                    <a href="{{ route('anggota.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700/80 hover:bg-emerald-700 text-white font-semibold text-xs border border-white/20 transition">
                        <i class="fas fa-clinic-medical"></i>
                        <span>Direktori Anggota</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Decorative Background Pattern -->
        <div class="absolute -right-12 -bottom-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -top-12 w-48 h-48 rounded-full bg-teal-400/20 blur-xl pointer-events-none"></div>
    </div>

    <!-- 8 KPI Status Metrics Grid -->
    <div>
        <div class="flex items-center justify-between mb-3.5">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Ringkasan Status Registrasi
            </h3>
            <span class="text-xs text-slate-400 dark:text-slate-500">
                Total 8 Tahapan Alur Keanggotaan
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Disetujui Pusat -->
            <x-stat-card
                title="Disetujui Pusat"
                value="{{ $approved }}"
                variant="emerald"
                icon="fas fa-check-circle"
                subtitle="Terverifikasi Aktif"
                href="{{ route('anggota.index') }}" />

            <!-- 2. Terverifikasi Cabang -->
            <x-stat-card
                title="Terverifikasi Cabang"
                value="{{ $terverifikasi_cabang }}"
                variant="purple"
                icon="fas fa-tasks"
                subtitle="Menuju Daerah/Pusat"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 3. Diverifikasi Cabang -->
            <x-stat-card
                title="Verifikasi Cabang"
                value="{{ $diverifikasi_cabang }}"
                variant="cyan"
                icon="fas fa-building"
                subtitle="Tahap Verif Awal"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 4. Waiting Review -->
            <x-stat-card
                title="Menunggu (Waiting)"
                value="{{ $waiting }}"
                variant="blue"
                icon="fas fa-clock"
                subtitle="Antrean Verifikasi"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 5. Proses Input -->
            <x-stat-card
                title="Proses Input"
                value="{{ $proses }}"
                variant="amber"
                icon="fas fa-edit"
                subtitle="Pengisian Data Klinik"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 6. Perlu Perbaikan -->
            <x-stat-card
                title="Perlu Perbaikan"
                value="{{ $perbaikan }}"
                variant="indigo"
                icon="fas fa-tools"
                subtitle="Revisi Dokumen/SIO"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 7. Belum Lengkap -->
            <x-stat-card
                title="Belum Lengkap"
                value="{{ $create_dokter }}"
                variant="slate"
                icon="fas fa-user-clock"
                subtitle="Dokter Belum Terisi"
                href="{{ route('verifikasi_anggota.index') }}" />

            <!-- 8. Ditolak Pusat -->
            <x-stat-card
                title="Ditolak Pusat"
                value="{{ $ditolak }}"
                variant="rose"
                icon="fas fa-times-circle"
                subtitle="Pendaftaran Ditolak"
                href="{{ route('verifikasi_anggota.index') }}" />
        </div>
    </div>

    <!-- Main Analytics Section: Regional Bar Chart + Status Doughnut -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Persebaran Wilayah (2/3 width) -->
        <div class="lg:col-span-2">
            <x-card title="Sebaran Anggota per Wilayah" subtitle="Grafik interaktif persebaran klinik berdasarkan Provinsi dan Kota/Kabupaten">
                <x-slot:action>
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Dropdown Provinsi -->
                        <div class="relative">
                            <select id="filter-provinsi"
                                class="w-40 sm:w-48 text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 py-1.5 pl-3 pr-8 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Semua Provinsi</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->code }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Kota -->
                        <div class="relative">
                            <select id="filter-kota"
                                class="w-40 sm:w-48 text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 py-1.5 pl-3 pr-8 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Pilih Kota/Kab</option>
                            </select>
                        </div>
                    </div>
                </x-slot:action>

                <div class="relative h-80 w-full mt-2">
                    <canvas id="chartWilayahCanvas"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Kolom Kanan: Proporsi Status Donut (1/3 width) -->
        <div class="lg:col-span-1">
            <x-card title="Proporsi Status Anggota" subtitle="Distribusi persentase seluruh tahapan" class="h-full">
                <div class="relative h-64 w-full flex items-center justify-center">
                    <canvas id="chartStatusDonutCanvas"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-[11px] text-slate-600 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        <span class="truncate">Disetujui: {{ $approved }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 shrink-0"></span>
                        <span class="truncate">Verif Cabang: {{ $diverifikasi_cabang }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>
                        <span class="truncate">Waiting: {{ $waiting }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                        <span class="truncate">Proses: {{ $proses }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Bottom Analytics: Demographics & Activities (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- 1. Jenis Kepemilikan Klinik -->
        <x-card title="Jenis Kepemilikan" subtitle="Distribusi kepemilikan modal klinik">
            @php
                $maxKepemilikan = $perKepemilikan->max() ?: 1;
            @endphp
            <div class="space-y-4 mt-1">
                @forelse ($perKepemilikan as $jenis => $total)
                    @php
                        $pct = round(($total / $maxKepemilikan) * 100);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $jenis ?: 'Belum Terdefinisi' }}
                            </span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">
                                {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-500"
                                style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada data kepemilikan</p>
                @endforelse
            </div>
        </x-card>

        <!-- 2. Jenis Klinik (Utama vs Pratama) -->
        <x-card title="Klasifikasi Klinik" subtitle="Perbandingan Klinik Pratama & Utama">
            <div class="relative h-60 w-full flex items-center justify-center">
                <canvas id="chartJenisKlinikCanvas"></canvas>
            </div>
        </x-card>

        <!-- 3. Badan Hukum (PT, CV, Yayasan, Koperasi) -->
        <x-card title="Bentuk Badan Hukum" subtitle="Bentuk legalitas penyelenggara klinik">
            <div class="relative h-60 w-full flex items-center justify-center">
                <canvas id="chartBadanHukumCanvas"></canvas>
            </div>
        </x-card>

    </div>

    <!-- Recent Activities Timeline -->
    <x-card title="Aktivitas Verifikasi Terkini" subtitle="Data 5 pengajuan anggota yang terakhir diperbarui">
        <x-slot:action>
            @can('verifikasi-anggota')
                <a href="{{ route('verifikasi_anggota.index') }}"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                    <span>Lihat Semua Antrean</span>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
            @endcan
        </x-slot:action>

        <div class="divide-y divide-slate-100 dark:divide-slate-800/80 -mx-6 -mb-6">
            @forelse ($recent as $item)
                <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-red-600 dark:text-red-400 truncate">
                                {{ $item->nama_klinik }}
                            </h4>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                                {{ $item->nama_pemilik_klinik ?? 'Pemilik Belum Diisi' }} &bull; No: {{ $item->no_anggota ?? 'Menunggu No' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @php
                            $statusBadgeVariant = match(strtolower($item->status ?? '')) {
                                'approved' => 'success',
                                'proses' => 'warning',
                                'waiting' => 'blue',
                                'perlu perbaikan' => 'amber',
                                'ditolak pusat' => 'danger',
                                default => 'slate',
                            };
                        @endphp
                        <x-badge :variant="$statusBadgeVariant" size="sm" :dot="true">
                            {{ $item->status }}
                        </x-badge>
                        <span class="hidden sm:inline-block text-[11px] text-slate-400 dark:text-slate-500">
                            {{ $item->updated_at ? $item->updated_at->diffForHumans() : '-' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400">
                    Tidak ada aktivitas pendaftaran terkini.
                </div>
            @endforelse
        </div>
    </x-card>

</div>
@endsection

@push('js')
<!-- Chart.js 4 (Universal Modern Bundle) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDarkMode = () => document.documentElement.classList.contains('dark');
    const getTextColor = () => isDarkMode() ? '#94a3b8' : '#64748b';
    const getGridColor = () => isDarkMode() ? 'rgba(51, 65, 85, 0.4)' : 'rgba(226, 232, 240, 0.8)';

    // --- 1. DOUGHNUT STATUS ANGGOTA CHART ---
    const ctxStatus = document.getElementById('chartStatusDonutCanvas')?.getContext('2d');
    let chartStatus;
    if (ctxStatus) {
        chartStatus = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: [
                    'Disetujui Pusat', 'Terverifikasi Cabang', 'Verifikasi Cabang',
                    'Waiting', 'Proses Input', 'Perlu Perbaikan', 'Belum Lengkap', 'Ditolak'
                ],
                datasets: [{
                    data: [
                        {{ $approved }}, {{ $terverifikasi_cabang }}, {{ $diverifikasi_cabang }},
                        {{ $waiting }}, {{ $proses }}, {{ $perbaikan }}, {{ $create_dokter }}, {{ $ditolak }}
                    ],
                    backgroundColor: [
                        '#10b981', '#a855f7', '#06b6d4',
                        '#3b82f6', '#f59e0b', '#6366f1', '#64748b', '#f43f5e'
                    ],
                    borderWidth: 2,
                    borderColor: isDarkMode() ? '#0f172a' : '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        boxPadding: 4,
                        usePointStyle: true,
                    }
                },
                cutout: '72%',
            }
        });
    }

    // --- 2. REGIONAL SEBARAN BAR CHART ---
    const ctxWilayah = document.getElementById('chartWilayahCanvas')?.getContext('2d');
    let chartWilayah;

    function renderWilayahChart(labels, values) {
        if (chartWilayah) {
            chartWilayah.destroy();
        }
        if (!ctxWilayah) return;

        chartWilayah = new Chart(ctxWilayah, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Klinik Terdaftar',
                    data: values,
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: getTextColor(),
                            font: { size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: getGridColor() },
                        ticks: {
                            color: getTextColor(),
                            font: { size: 11 },
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        usePointStyle: true,
                    }
                }
            }
        });
    }

    function fetchWilayahData(provinsiCode, kotaCode) {
        fetch('{{ route("getStatistikWilayah") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: new URLSearchParams({
                provinsi: provinsiCode || '',
                kota: kotaCode || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.provinsi || item.kota || 'Tidak Diketahui');
            const values = data.map(item => item.total || 0);
            renderWilayahChart(labels, values);
        })
        .catch(err => console.error('Error fetching wilayah chart:', err));
    }

    // Initial load
    fetchWilayahData('', '');

    // Event listener: Filter Provinsi
    const provSelect = document.getElementById('filter-provinsi');
    const kotaSelect = document.getElementById('filter-kota');

    if (provSelect) {
        provSelect.addEventListener('change', function () {
            const provCode = this.value;
            // Fetch cities for this province
            fetch('{{ route("getKota") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new URLSearchParams({
                    id_provinsi: provCode,
                })
            })
            .then(res => res.text())
            .then(html => {
                if (kotaSelect) {
                    kotaSelect.innerHTML = html;
                }
                fetchWilayahData(provCode, '');
            })
            .catch(err => console.error('Error fetching kota options:', err));
        });
    }

    if (kotaSelect) {
        kotaSelect.addEventListener('change', function () {
            const provCode = provSelect ? provSelect.value : '';
            const kotaCode = this.value;
            fetchWilayahData(provCode, kotaCode);
        });
    }

    // --- 3. JENIS KLINIK DONUT CHART ---
    fetch('{{ route("getStatistikJenisKlinik") }}')
        .then(res => res.json())
        .then(res => {
            const ctxJenis = document.getElementById('chartJenisKlinikCanvas')?.getContext('2d');
            if (!ctxJenis) return;
            new Chart(ctxJenis, {
                type: 'doughnut',
                data: {
                    labels: res.labels,
                    datasets: [{
                        data: res.data,
                        backgroundColor: ['#0d9488', '#10b981'],
                        borderWidth: 2,
                        borderColor: isDarkMode() ? '#0f172a' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: getTextColor(),
                                padding: 14,
                                font: { size: 11, weight: 'bold' }
                            }
                        }
                    },
                    cutout: '65%',
                }
            });
        })
        .catch(err => console.error('Error fetching jenis klinik:', err));

    // --- 4. BADAN HUKUM CHART ---
    fetch('{{ route("getStatistikBadanHukum") }}')
        .then(res => res.json())
        .then(res => {
            const ctxBadan = document.getElementById('chartBadanHukumCanvas')?.getContext('2d');
            if (!ctxBadan) return;
            new Chart(ctxBadan, {
                type: 'pie',
                data: {
                    labels: res.labels,
                    datasets: [{
                        data: res.data,
                        backgroundColor: ['#f59e0b', '#06b6d4', '#6366f1', '#64748b'],
                        borderWidth: 2,
                        borderColor: isDarkMode() ? '#0f172a' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: getTextColor(),
                                padding: 14,
                                font: { size: 11, weight: 'bold' }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Error fetching badan hukum:', err));

    // Re-sync chart colors on Dark Mode change
    window.addEventListener('themeChanged', function () {
        if (chartWilayah) {
            chartWilayah.options.scales.x.ticks.color = getTextColor();
            chartWilayah.options.scales.y.ticks.color = getTextColor();
            chartWilayah.options.scales.y.grid.color = getGridColor();
            chartWilayah.update();
        }
    });
});
</script>
@endpush
