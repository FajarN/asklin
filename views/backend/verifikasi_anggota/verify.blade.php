@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Verifikasi Anggota</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('verifikasi_anggota.index') }}">Verifikasi Anggota</a>
                    </div>
                    <div class="breadcrumb-item active">Verifikasi</div>
                </div>
            </div>
        </section>
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ asset('images/file' . '/' . $anggota->logo) }}" class="img-fluid">
                            <div class="profile-widget-items"></div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                <h3>{{ $anggota->nama_klinik }}</h3>
                            </div>
                        </div>
                        <form action="{{ route('verifikasi_anggota.update_anggota', $anggota->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Data Umum</th>
                                        <td class="text-left">
                                            <div>
                                                <input class="form-check-input" type="radio" id="data_umum"
                                                    name="data_umum" value="0"
                                                    {{ '0' == $anggota->data_umum ? 'checked' : '' }}>
                                                <label class="form-check-label" for="data_umum">Tolak</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="data_umum"
                                                    name="data_umum" value="1"
                                                    {{ '1' == $anggota->data_umum ? 'checked' : '' }}>
                                                <label class="form-check-label" for="data_umum">Setujui</label>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>SDM Klinik</th>
                                        <td class="text-left">
                                            <div>
                                                <input class="form-check-input" type="radio" id="sdm_klinik"
                                                    name="sdm_klinik" value="0"
                                                    {{ '0' == $anggota->sdm_klinik ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sdm_klinik">Tolak</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="sdm_klinik"
                                                    name="sdm_klinik" value="1"
                                                    {{ '1' == $anggota->sdm_klinik ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sdm_klinik">Setujui</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Provider Asuransi</th>
                                        <td class="text-left">
                                            <div>
                                                <input class="form-check-input" type="radio" id="provider_asuransi"
                                                    name="provider_asuransi" value="0"
                                                    {{ '0' == $anggota->provider_asuransi ? 'checked' : '' }}>
                                                <label class="form-check-label" for="provider_asuransi">Tolak</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="provider_asuransi"
                                                    name="provider_asuransi" value="1"
                                                    {{ '1' == $anggota->provider_asuransi ? 'checked' : '' }}>
                                                <label class="form-check-label" for="provider_asuransi">Setujui</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Foto & Logo Klinik</th>
                                        <td class="text-left">
                                            <div>
                                                <input class="form-check-input" type="radio" id="foto_logo_klinik"
                                                    name="foto_logo_klinik" value="0"
                                                    {{ '0' == $anggota->foto_logo_klinik ? 'checked' : '' }}>
                                                <label class="form-check-label" for="foto_logo_klinik">Tolak</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="foto_logo_klinik"
                                                    name="foto_logo_klinik" value="1"
                                                    {{ '1' == $anggota->foto_logo_klinik ? 'checked' : '' }}>
                                                <label class="form-check-label" for="foto_logo_klinik">Setujui</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label>Pilih <code>.dropdown ini untuk mengubah status verifikasi</code></label>
                                @role('Superadmin')
                                    <select class="form-control form-control-sm" name="status">
                                        <option value="waiting" {{ 'waiting' == $anggota->status ? 'selected' : '' }}>
                                            Waiting</option>
                                        <option value="create_dokter"
                                            {{ 'create_dokter' == $anggota->status ? 'selected' : '' }}>Belum input SDM
                                        </option>
                                        <option value="Perlu Perbaikan"
                                            {{ 'Perlu Perbaikan' == $anggota->status ? 'selected' : '' }}>Perlu Perbaikan
                                        </option>
                                        <option value="Diproses Cabang"
                                            {{ 'Diproses Cabang' == $anggota->status ? 'selected' : '' }}>Di Proses Cabang
                                        </option>
                                        <option value="Diproses Daerah"
                                            {{ 'Diproses Daerah' == $anggota->status ? 'selected' : '' }}>Di Proses Daerah
                                        </option>
                                        <option value="proses" {{ 'Diproses Pusat' == $anggota->status ? 'selected' : '' }}>
                                            Di Proses Pusat</option>
                                        <option value="Terverifikasi Cabang"
                                            {{ 'Terverifikasi Cabang' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Cabang</option>
                                        <option value="Terverifikasi Daerah"
                                            {{ 'Terverifikasi Daerah' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Daerah</option>
                                        <option value="approved" {{ 'approved' == $anggota->status ? 'selected' : '' }}>
                                            Approve Pusat</option>
                                        <option value="ditolak pusat"
                                            {{ 'ditolak pusat' == $anggota->status ? 'selected' : '' }}>Ditolak Pusat
                                        </option>
                                    </select>
                                @endrole
                                @role('Admin Cabang')
                                    <select class="form-control form-control-sm" name="status">
                                        <option value="Diproses Cabang"
                                            {{ 'Diproses Cabang' == $anggota->status ? 'selected' : '' }}>Di Proses Cabang
                                        </option>
                                        <option value="Perlu Perbaikan"
                                            {{ 'Perlu Perbaikan' == $anggota->status ? 'selected' : '' }}>Perlu Perbaikan
                                        </option>
                                        <option value="Terverifikasi Cabang"
                                            {{ 'Terverifikasi Cabang' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Cabang</option>
                                    </select>
                                @endrole
                                @role('Admin Daerah')
                                    <select class="form-control form-control-sm" name="status">
                                        <option value="Diproses Daerah"
                                            {{ 'Diproses Daerah' == $anggota->status ? 'selected' : '' }}>Di Proses Daerah
                                        </option>
                                        <option value="Perlu Perbaikan"
                                            {{ 'Perlu Perbaikan' == $anggota->status ? 'selected' : '' }}>Perlu Perbaikan
                                        </option>
                                        <option value="Terverifikasi Daerah"
                                            {{ 'Terverifikasi Daerah' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Daerah</option>
                                    </select>
                                @endrole
                                @role('Ketua Umum')
                                    <select class="form-control form-control-sm" name="status">
                                        <option value="proses"
                                            {{ 'Diproses Pusat' == $anggota->status ? 'selected' : '' }}>Di Proses Pusat
                                        </option>
                                        <option value="Perlu Perbaikan"
                                            {{ 'Perlu Perbaikan' == $anggota->status ? 'selected' : '' }}>Perlu Perbaikan
                                        </option>
                                        <option value="Terverifikasi Cabang"
                                            {{ 'Terverifikasi Cabang' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Cabang</option>
                                        <option value="Terverifikasi Daerah"
                                            {{ 'Terverifikasi Daerah' == $anggota->status ? 'selected' : '' }}>Terverifikasi
                                            Daerah</option>
                                        <option value="approved" {{ 'approved' == $anggota->status ? 'selected' : '' }}>
                                            Approve Pusat</option>
                                        <option value="ditolak pusat"
                                            {{ 'ditolak pusat' == $anggota->status ? 'selected' : '' }}>Ditolak Pusat
                                        </option>
                                    </select>
                                @endrole
                                @role('Sekjen')
                                    <select class="form-control form-control-sm" name="status">
                                        <option value="Verifikasi Sekjen"
                                            {{ 'Verifikasi Sekjen' == $anggota->status ? 'selected' : '' }}>Verifikasi
                                            Sekjen</option>
                                    </select>
                                @endrole
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-md" name="keterangan"
                                    value="{{ $anggota->keterangan }}">
                            </div>
                            <button type="submit" class="btn btn-danger">Update Status</button>
                    </div>
                    </form>
                </div>

                <div class="col-12 col-md-12 col-lg-7">
                    <h4>&nbsp;</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-md">
                            <thead>
                                <tr>
                                    <th>No Anggota</th>
                                    <td>{{ $anggota->no_anggota ?? 'Belum Approve' }}</td>
                                </tr>
                                <tr>
                                    <th>Contak Person</th>
                                    <td>{{ $anggota->nama_kontak ?? 'Belum Diisi' }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Email</th>
                                    <td class="text-left">{{ $anggota->email ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td class="text-left">{{ $anggota->tlf ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $anggota->status_pendaftar ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Klinik</th>
                                    <td>{{ $anggota->nama_klinik ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Ijin Klinik</th>
                                    <td>{{ $anggota->no_ijin ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Tgl Izin</th>
                                    <td>{{ $anggota->tgl_ijin ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Berlaku S/d</th>
                                    <td>{{ $anggota->tgl_akhir_ijin ?? 'Belum Diisi' }}</td>
                                </tr>

                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $anggota->tlf_klinik ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Pemilik Klinik</th>
                                    <td>{{ $anggota->nama_badan_usaha ?? 'Belum Diisi' }}
                                        ({{ $anggota->status_kepemilikan ?? 'Belum Diisi' }})</td>
                                </tr>
                                <tr>
                                    <th>Jenis Layanan</th>
                                    <td>{{ $anggota->jenis_klinik ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Fasilitas Klinik</th>
                                    <td>
                                        @foreach (App\Models\FasilitasKlinik::where('status', '1')->get() as $i)
                                            {{ in_array($i->id, explode(',', $anggota->fasilitas_klinik)) ? $i->nama . ', ' : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $anggota->alamat_klinik ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>RT/RW</th>
                                    <td>
                                        {{ $anggota->rt . '/' . $anggota->rw ?? 'Belum Diisi' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Propinsi</th>
                                    <td>{{ $anggota->provinsi ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Kabupaten/Kota</th>
                                    <td>{{ $anggota->kota ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Kecamatan</th>
                                    <td>{{ $anggota->kecamatan ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Kelurahan</th>
                                    <td>{{ $anggota->kelurahan ?? 'Belum Diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode POS</th>
                                    <td>{{ $anggota->kode_pos ?? 'Belum Diisi' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12">
                    <h4>DETAIL KLINIK</h4>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">SDM Klinik</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">Rumah Sakit Terdekat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                                    aria-controls="contact" aria-selected="false">Kerjasama dengan Provider Asuransi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="about-tab" data-toggle="tab" href="#about" role="tab"
                                    aria-controls="about" aria-selected="false">Foto Ruang Klinik</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table1">
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

                                <h4>DOKTER PRAKTEK</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table2">
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

                                <h4>TENAGA KEPERAWATAN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table3">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>No. SIB / SIK</th>
                                                <th>No. STR</th>
                                                <th>Tgl. Akhir STR</th>
                                                <th>Keterangan SIB / SIK</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>TENAGA KESEHATAN LAIN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Teknik Kefarmasian / Apoteker</th>
                                                <th>No. STR</th>
                                                <th>Tgl. Akhir STR</th>
                                                <th>No. SIPA</th>
                                                <th>No. SIAA / SIK</th>
                                                <th>Keterangan SIPA / SIAA / SIK</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>TENAGA SDM LAIN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table5">
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
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table6">
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
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table7">
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
                            <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
                                <table class="table table-bordered" id="table8">
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
    </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // PJ
        $(function() {
            var table1 = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.pjk', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_dokter',
                        name: 'nama_dokter'
                    },
                    {
                        data: 'npa_idi',
                        name: 'npa_idi'
                    },
                    {
                        data: 'no_str',
                        name: 'no_str'
                    },
                    {
                        data: 'no_sip',
                        name: 'no_sip'
                    },
                    {
                        data: 'tgl_akhir_sip',
                        name: 'tgl_akhir_sip'
                    },
                    {
                        data: 'no_tlf',
                        name: 'no_tlf'
                    }
                ]
            });
        });

        // DP
        $(function() {
            var table2 = $('#table2').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.dp', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_dokter',
                        name: 'nama_dokter'
                    },
                    {
                        data: 'npa_idi',
                        name: 'npa_idi'
                    },
                    {
                        data: 'no_str',
                        name: 'no_str'
                    },
                    {
                        data: 'no_sip',
                        name: 'no_sip'
                    },
                    {
                        data: 'tgl_akhir_sip',
                        name: 'tgl_akhir_sip'
                    },
                    {
                        data: 'no_tlf',
                        name: 'no_tlf'
                    },
                ]
            });
        });

        // TK
        $(function() {
            var table3 = $('#table3').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.tk', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_sib_sik',
                        name: 'no_sib_sik'
                    },
                    {
                        data: 'no_str',
                        name: 'no_str'
                    },
                    {
                        data: 'tgl_akhir_str',
                        name: 'tgl_akhir_str'
                    },
                    {
                        data: 'no_tlf',
                        name: 'no_tlf'
                    },
                ]
            });
        });

        // TKL
        $(function() {
            var table4 = $('#table4').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.tkl', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'farmasi_apoteker',
                        name: 'farmasi_apoteker'
                    },
                    {
                        data: 'no_str',
                        name: 'no_str'
                    },
                    {
                        data: 'tgl_akhir_str',
                        name: 'tgl_akhir_str'
                    },
                    {
                        data: 'no_sip',
                        name: 'no_sip'
                    },
                    {
                        data: 'no_sib_sik',
                        name: 'no_sib_sik'
                    },
                    {
                        data: 'ket_sib_sik',
                        name: 'ket_sib_sik'
                    },
                ]
            });
        });

        // SDM Lain
        $(function() {
            var table5 = $('#table5').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.lain', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'ijazah_terakhir',
                        name: 'ijazah_terakhir'
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan'
                    }
                ]
            });
        });

        // SDM Rumah Sakit
        $(function() {
            var table6 = $('#table6').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.rumah_sakit', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'jarak',
                        name: 'jarak'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    }
                ]
            });
        });

        // Asurasi
        $(function() {
            var table7 = $('#table7').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.asuransi', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'nama_kontak',
                        name: 'nama_kontak'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    }
                ]
            });
        });

        // Foto Klinik
        $(function() {
            var table8 = $('#table8').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('verifikasi_anggota.sdm.foto', $anggota->id) }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: "foto",
                        "render": function(data, type, row, meta) {
                            return '<img src="{{ asset('images/file') }}/' + row.foto +
                                '" width="500px">';
                        }
                    }
                ]
            });
        });
    </script>
@endpush
