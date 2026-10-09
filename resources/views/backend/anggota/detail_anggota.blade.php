@extends('layouts.backend.layout')

@section('title', 'Detail Anggota — ' . ($anggota->nama_klinik ?? 'Klinik'))
@section('page_title', 'Detail Anggota')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                    <i class="fas fa-check-circle mr-1"></i> Anggota Terverifikasi (Approved)
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                    ID #{{ $anggota->id }}
                </span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-red-600 dark:text-red-400">
                {{ $anggota->nama_klinik }}
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400">
                Informasi profil lengkap klinik, legalitas SIO, SDM kesehatan, dan data fasilitas faskes terdaftar.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('anggota.printsk', $anggota->id) }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-print"></i>
                <span>Cetak SK</span>
            </a>
            <a href="{{ route('anggota.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Panel (Logo & Checklist Status) & Right Panel (Profile Details) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Clinic Card (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="card overflow-hidden">
                <!-- Clinic Header Banner with Avatar -->
                <div class="relative bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white text-center">
                    <div class="relative mx-auto w-24 h-24 mb-3">
                        @if($anggota->logo && file_exists(public_path('images/file/' . $anggota->logo)))
                            <img alt="{{ $anggota->nama_klinik }}"
                                 src="{{ asset('images/file/' . $anggota->logo) }}"
                                 class="w-24 h-24 rounded-2xl ring-4 ring-white/30 object-cover shadow-lg mx-auto bg-white">
                        @else
                            <div class="w-24 h-24 rounded-2xl ring-4 ring-white/30 bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold shadow-lg mx-auto">
                                <i class="fas fa-clinic-medical"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-white tracking-tight">{{ $anggota->nama_klinik }}</h3>
                    <p class="text-xs text-emerald-100 font-mono mt-0.5">
                        {{ $anggota->no_anggota ?? 'No Anggota: Belum Approve' }}
                    </p>
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm border border-white/30">
                        Status: {{ ucfirst(str_replace('_', ' ', $anggota->status)) }}
                    </div>
                </div>

                <!-- Approval Checklist Summary (Read Only / Edit Mode) -->
                <form action="{{ route('anggota.update_anggota', $anggota->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-5 space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <i class="fas fa-tasks text-emerald-600 mr-1.5"></i> Status Validasi Berkas
                            </h4>
                        </div>

                        <!-- 4 Item Checklist Matrix -->
                        <div class="space-y-3">
                            <!-- 1. Data Umum -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">1. Data Umum & Legalitas</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Identitas, alamat, kontak person</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 cursor-pointer m-0">
                                        <input type="radio" id="data_umum_0" name="data_umum" value="0"
                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500"
                                               {{ '0' == $anggota->data_umum ? 'checked' : '' }}>
                                        <span>Tolak</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 cursor-pointer m-0">
                                        <input type="radio" id="data_umum_1" name="data_umum" value="1"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                               {{ '1' == $anggota->data_umum ? 'checked' : '' }}>
                                        <span>Setujui</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 2. SDM Klinik -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">2. SDM Tenaga Medis</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Dokter PJ, perawat, SIP & STR</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 cursor-pointer m-0">
                                        <input type="radio" id="sdm_klinik_0" name="sdm_klinik" value="0"
                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500"
                                               {{ '0' == $anggota->sdm_klinik ? 'checked' : '' }}>
                                        <span>Tolak</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 cursor-pointer m-0">
                                        <input type="radio" id="sdm_klinik_1" name="sdm_klinik" value="1"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                               {{ '1' == $anggota->sdm_klinik ? 'checked' : '' }}>
                                        <span>Setujui</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 3. Provider Asuransi -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">3. Provider Asuransi</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Mitra BPJS & penjamin faskes</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 cursor-pointer m-0">
                                        <input type="radio" id="provider_asuransi_0" name="provider_asuransi" value="0"
                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500"
                                               {{ '0' == $anggota->provider_asuransi ? 'checked' : '' }}>
                                        <span>Tolak</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 cursor-pointer m-0">
                                        <input type="radio" id="provider_asuransi_1" name="provider_asuransi" value="1"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                               {{ '1' == $anggota->provider_asuransi ? 'checked' : '' }}>
                                        <span>Setujui</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 4. Foto & Logo Klinik -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">4. Foto Ruang & Logo</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Dokumentasi fisik sarpras</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 cursor-pointer m-0">
                                        <input type="radio" id="foto_logo_klinik_0" name="foto_logo_klinik" value="0"
                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500"
                                               {{ '0' == $anggota->foto_logo_klinik ? 'checked' : '' }}>
                                        <span>Tolak</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 cursor-pointer m-0">
                                        <input type="radio" id="foto_logo_klinik_1" name="foto_logo_klinik" value="1"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                               {{ '1' == $anggota->foto_logo_klinik ? 'checked' : '' }}>
                                        <span>Setujui</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Footer -->
                    <div class="card-footer bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 p-4 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pembaruan status verifikasi</span>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Detail Information Panel (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        <i class="fas fa-id-card text-emerald-600 mr-1.5"></i> Identitas & Legalitas Anggota
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Nomor Anggota</span>
                            <span class="col-span-2 font-mono font-bold text-slate-800 dark:text-slate-200">
                                {{ $anggota->no_anggota ?? 'Belum Approve' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Kontak Person</span>
                            <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium">
                                {{ $anggota->nama_kontak ?? '-' }} ({{ $anggota->status_pendaftar ?? 'Pemilik' }})
                            </span>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Email & Telepon</span>
                            <span class="col-span-2 text-slate-800 dark:text-slate-200">
                                <div><i class="fas fa-envelope text-slate-400 mr-1"></i> {{ $anggota->email ?? '-' }}</div>
                                <div class="mt-1"><i class="fas fa-phone text-slate-400 mr-1"></i> {{ $anggota->tlf ?? '-' }}</div>
                            </span>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Izin Operasional (SIO)</span>
                            <div class="col-span-2 space-y-1">
                                <div class="font-medium text-slate-800 dark:text-slate-200">
                                    No: {{ $anggota->no_ijin ?? 'Belum Diisi' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    Masa Berlaku: {{ $anggota->tgl_ijin ?? '-' }} s/d {{ $anggota->tgl_akhir_ijin ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Badan Usaha & Jenis</span>
                            <span class="col-span-2 text-slate-800 dark:text-slate-200 font-medium">
                                {{ $anggota->nama_badan_usaha ?? '-' }} ({{ $anggota->status_kepemilikan ?? '-' }}) &bull; Klinik {{ $anggota->jenis_klinik ?? '-' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Fasilitas Tersedia</span>
                            <span class="col-span-2 text-slate-700 dark:text-slate-300">
                                @php
                                    $fasilitasNames = [];
                                    foreach (App\Models\FasilitasKlinik::where('status', '1')->get() as $i) {
                                        if (in_array($i->id, explode(',', $anggota->fasilitas_klinik))) {
                                            $fasilitasNames[] = $i->nama;
                                        }
                                    }
                                @endphp
                                {{ count($fasilitasNames) > 0 ? implode(', ', $fasilitasNames) : 'Belum diisi' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 p-3.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <span class="font-semibold text-slate-500 dark:text-slate-400">Alamat Lengkap</span>
                            <span class="col-span-2 text-slate-800 dark:text-slate-200 leading-relaxed">
                                {{ $anggota->alamat_klinik ?? '-' }}, RT {{ $anggota->rt ?? '-' }}/RW {{ $anggota->rw ?? '-' }},
                                Kel. {{ $anggota->kelurahan ?? '-' }}, Kec. {{ $anggota->kecamatan ?? '-' }},
                                {{ $anggota->kota ?? '-' }}, {{ $anggota->provinsi ?? '-' }}
                                @if($anggota->kode_pos) (Kode Pos: {{ $anggota->kode_pos }}) @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Full-Width Bottom Tabs: Data Rinci (SDM, Rumah Sakit, Asuransi, Foto) -->
    <div class="card">
        <div class="card-header border-b border-slate-100 dark:border-slate-800 p-0">
            <ul class="nav nav-tabs border-0 flex flex-wrap text-xs font-semibold px-4 pt-3 gap-2" id="detailTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active px-4 py-2.5 rounded-t-xl" id="home-tab" data-toggle="tab" href="#home" role="tab">
                        <i class="fas fa-user-md text-emerald-600 mr-1.5"></i> SDM Klinik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2.5 rounded-t-xl" id="profile-tab" data-toggle="tab" href="#profile" role="tab">
                        <i class="fas fa-hospital text-blue-600 mr-1.5"></i> RS Terdekat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2.5 rounded-t-xl" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                        <i class="fas fa-hands-helping text-amber-600 mr-1.5"></i> Asuransi & Penjamin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2.5 rounded-t-xl" id="about-tab" data-toggle="tab" href="#about" role="tab">
                        <i class="fas fa-images text-purple-600 mr-1.5"></i> Foto Sarpras Klinik
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-6">
            <div class="tab-content" id="detailTabContent">

                <!-- 1. SDM KLINIK TAB -->
                <div class="tab-pane fade show active space-y-6" id="home" role="tabpanel">
                    
                    <!-- Dokter Penanggung Jawab -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Dokter Penanggung Jawab (PJ)
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped w-full text-xs" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        <th>NPA IDI/PDGI</th>
                                        <th>No. STR</th>
                                        <th>No. SIP</th>
                                        <th>Tgl. Akhir SIP</th>
                                        <th>Telepon</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dokter Praktek -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Dokter Praktek
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped w-full text-xs" id="table2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        <th>NPA IDI/PDGI</th>
                                        <th>No. STR</th>
                                        <th>No. SIP</th>
                                        <th>Tgl. Akhir SIP</th>
                                        <th>Telepon</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tenaga Keperawatan -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Tenaga Keperawatan / Kebidanan
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped w-full text-xs" id="table3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>No. SIB / SIK</th>
                                        <th>No. STR</th>
                                        <th>Tgl. Akhir STR</th>
                                        <th>Telepon</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tenaga SDM Lain -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Tenaga Penunjang / Administrasi Lainnya
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped w-full text-xs" id="table5">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Ijazah Terakhir</th>
                                        <th>Jabatan / Pekerjaan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- 2. RUMAH SAKIT TERDEKAT TAB -->
                <div class="tab-pane fade space-y-3" id="profile" role="tabpanel">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Daftar Rujukan Rumah Sakit Terdekat
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped w-full text-xs" id="table6">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Rumah Sakit</th>
                                    <th>Alamat</th>
                                    <th>Jarak</th>
                                    <th>No. Telepon</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. KERJASAMA ASURANSI TAB -->
                <div class="tab-pane fade space-y-3" id="contact" role="tabpanel">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Kerjasama dengan Provider Asuransi & Penjamin
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped w-full text-xs" id="table7">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Nama Kontak</th>
                                    <th>Alamat</th>
                                    <th>No. Telepon</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. FOTO SARPRAS TAB -->
                <div class="tab-pane fade space-y-3" id="about" role="tabpanel">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Foto Ruang & Sarana Prasarana Klinik
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped w-full text-xs" id="table8">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruang</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 1. Table PJ
        $(function() {
            $('#table1').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.pjk', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_dokter', name: 'nama_dokter' },
                    { data: 'npa_idi', name: 'npa_idi' },
                    { data: 'no_str', name: 'no_str' },
                    { data: 'no_sip', name: 'no_sip' },
                    { data: 'tgl_akhir_sip', name: 'tgl_akhir_sip' },
                    { data: 'no_tlf', name: 'no_tlf' }
                ]
            });
        });

        // 2. Table DP
        $(function() {
            $('#table2').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.dp', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_dokter', name: 'nama_dokter' },
                    { data: 'npa_idi', name: 'npa_idi' },
                    { data: 'no_str', name: 'no_str' },
                    { data: 'no_sip', name: 'no_sip' },
                    { data: 'tgl_akhir_sip', name: 'tgl_akhir_sip' },
                    { data: 'no_tlf', name: 'no_tlf' }
                ]
            });
        });

        // 3. Table TK
        $(function() {
            $('#table3').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.tk', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'no_sib_sik', name: 'no_sib_sik' },
                    { data: 'no_str', name: 'no_str' },
                    { data: 'tgl_akhir_str', name: 'tgl_akhir_str' },
                    { data: 'no_tlf', name: 'no_tlf' }
                ]
            });
        });

        // 5. Table SDM Lain
        $(function() {
            $('#table5').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.lain', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'ijazah_terakhir', name: 'ijazah_terakhir' },
                    { data: 'jabatan', name: 'jabatan' }
                ]
            });
        });

        // 6. Table RS
        $(function() {
            $('#table6').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.rumah_sakit', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'jarak', name: 'jarak' },
                    { data: 'telepon', name: 'telepon' }
                ]
            });
        });

        // 7. Table Asuransi
        $(function() {
            $('#table7').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.asuransi', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'nama_kontak', name: 'nama_kontak' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'telepon', name: 'telepon' }
                ]
            });
        });

        // 8. Table Foto Sarpras
        $(function() {
            $('#table8').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: { url: "{{ route('anggota.sdm.foto', $anggota->id) }}" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    {
                        data: "foto",
                        render: function(data, type, row) {
                            return '<img src="{{ asset("images/file") }}/' + row.foto + '" class="rounded-xl shadow max-w-[320px] max-h-[200px] object-cover">';
                        }
                    }
                ]
            });
        });
    </script>
@endpush
