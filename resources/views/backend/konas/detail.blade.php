@extends('layouts.backend.layout')

@section('title', 'Detail Peserta Konas')
@section('page_title', 'Detail Peserta Konas')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('konas.peserta') }}" class="hover:underline">Konas ASKLIN</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Detail Peserta</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Detail Registrasi Konas ASKLIN ke-2
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Rincian informasi peserta, pemesanan akomodasi kamar hotel, serta jadwal penerbangan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="download_konas.php" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="fas fa-file-invoice"></i>
                <span>Download Invoice</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-5 space-y-6">
            <div class="card overflow-hidden">
                <!-- Header Banner -->
                <div class="h-28 bg-gradient-to-r from-emerald-600 to-teal-700 relative">
                    <div class="absolute -bottom-10 left-6">
                        <div class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-900 p-1.5 shadow-lg border border-slate-200 dark:border-slate-700">
                            <img alt="Konas Logo"
                                 src="{{ asset('assets/images/logo-konas.png') }}"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/backend/img/avatar/avatar-1.png') }}';"
                                 class="w-full h-full rounded-xl object-contain bg-slate-50 dark:bg-slate-800">
                        </div>
                    </div>
                </div>

                <div class="card-body pt-12 pb-6 px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Fajar Nuralamsyah</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 mt-1">
                                Peserta Konas
                            </span>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800">
                            konas-0001
                        </span>
                    </div>

                    <div class="mt-6 divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Nomor Induk Kependudukan</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">10293010</span>
                        </div>
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Nomor WhatsApp / Telp</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">021-032-39383</span>
                        </div>
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Alamat Email</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">fajar.nuralamsyah16@gmail.com</span>
                        </div>
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Komisi Kerja</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">Komisi 1</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="download_konas.php" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-file-download mr-1.5"></i> Unduh Invoice & Bukti Registrasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Accommodation & Flight Cards -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Hotel Reservation -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-hotel text-emerald-600 mr-1.5"></i> Reservasi Kamar Hotel
                    </h4>
                </div>
                <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Hotel</th>
                                    <th>Tipe</th>
                                    <th>Harga/Kamar</th>
                                    <th>Jml</th>
                                    <th>Extrabed</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-xs text-slate-400">
                                        <i class="fas fa-bed text-2xl text-slate-300 block mb-2"></i>
                                        Belum ada data pemesanan kamar hotel tercatat untuk peserta ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Flight Information -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-plane-departure text-emerald-600 mr-1.5"></i> Informasi Jadwal Penerbangan
                    </h4>
                </div>
                <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Penerbangan</th>
                                    <th>Dari Bandara</th>
                                    <th>Tujuan Bandara</th>
                                    <th>Maskapai / Pesawat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-xs text-slate-400">
                                        <i class="fas fa-plane-slash text-2xl text-slate-300 block mb-2"></i>
                                        Belum ada jadwal tiket penerbangan yang dilampirkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection