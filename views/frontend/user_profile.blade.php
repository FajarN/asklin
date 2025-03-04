@extends('layouts.frontend.layout')

@section('content')
    @if (!empty($anggota->id))
        <section id="page-title">
            <div class="container clearfix">
                <h1>Ruang Anggota</h1>
            </div>
        </section>

        @include('frontend.menu')

        <div class="container pt-3 pb-2">
            @if ($anggota->status == 'proses')
                <div class="d-flex justify-content-center">
                    <div>
                        <b>Anda Belum menyelesaikan proses pendaftaran anggota</b><br />
                        <a href="{{ route('pendaftaran.anggota') }}" class="btn btn-warning">Lengkapi Data</a>
                    </div>
                </div>
            @elseif($anggota->status == 'create_dokter')
                <div class="d-flex justify-content-center">
                    <div>
                        <b>Silahkan lengkapi data sdm</b><br />
                        <a href="{{ route('pendaftaran.sdm') }}" class="btn btn-warning">Lengkapi Data</a>
                    </div>
                </div>
            @else
                <div class="row pt-2">
                    <div class="col-lg-3 mt-4 mt-lg-0">
                        <img src="{{ asset('images/file' . '/' . $anggota->logo) }}" width="300">
                        <div class="divider"><i class="icon-circle"></i></div>
                        <form action="{{ route('upload_foto') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <label>Upload Logo klinik:</label><br>
                            <input type="file" accept="image/*" name="logo" class="file-loading"
                                data-allowed-file-extensions='[]' data-show-preview="false">
                            <input type="submit" value="Sumbit Logo" />
                        </form>
                    </div>
                    <div class="col-lg-9">
                        <div id="examples" class="container py-2">
                            <div class="row">
                                <div class="col">
                                    <div class="card event-meta">
                                        <div class="card-body">
                                            <ul class="portfolio-meta mb-0">
                                                <li><span><i class="icon-user"></i>NAMA KLINIK:</span>
                                                    <h3> {{ $anggota->nama_klinik ?? 'Belum Diisi' }}</h3>
                                                </li>
                                                <li><span><i class="icon-screen"></i>Nomor Anggota</span> :
                                                    {{ $anggota->no_anggota ?? 'Belum Diverifikasi Pusat' }}</li>
                                                <li><span><i class="icon-calendar3"></i>Dibuat</span> :
                                                    {{ $anggota->created_at ?? 'Kosong' }}</li>
                                                <li><span><i class="icon-calendar"></i>Diupdate</span> :
                                                    {{ $anggota->updated_at ?? 'Kosong' }}</li>
                                                <li><span><i class="icon-calendar3"></i>Verifikasi Cabang</span> :
                                                    {{ $anggota->verifikasi_cabang ?? 'Belum Diverifikasi Cabang' }}</li>
                                                <li><span><i class="icon-calendar"></i>Verifikasi Pusat</span> :
                                                    {{ $anggota->verifikasi_pusat ?? 'Belum Diverifikasi Pusat' }}</li>
                                                <li><span><i class="icon-link"></i>Status:</span> : <button type="button"
                                                        class="btn btn-outline-default">{{ $anggota->status }}</button>
                                                </li>
                                                <li><span><i class="icon-link"></i>Edit Data :</span> : <a
                                                        href="{{ route('pendaftaran.anggota') }}"
                                                        class="btn btn-warning">Edit data umum</a> </li>
                                                <li><span><i class="icon-link"></i>Edit Data :</span> : <a
                                                        href="{{ route('pendaftaran.sdm') }}" class="btn btn-info">Edit
                                                        data sdm</a> </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col pb-3">
                                            <h4>&nbsp;</h4>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Contak Person</th>
                                                            <td>{{ $anggota->nama_kontak ?? 'Belum Diisi' }}</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>Email</th>
                                                            <td class="text-left">{{ $anggota->email ?? 'Belum Diisi' }}
                                                            </td>
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
                                                            <th>Penanggung Jawab</th>
                                                            <td>{{ $anggota->nama_pemilik_klinik ?? 'Belum Diisi' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Telepom</th>
                                                            <td>{{ $anggota->tlf_klinik ?? 'Belum Diisi' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Pemilik Klinik</th>
                                                            <td>{{ $anggota->nama_pemilik_klinik ?? 'Belum Diisi' }}
                                                                {{ $anggota->nama_badan_usaha ?? '' }}
                                                                ({{ $anggota->status_kepemilikan ?? '' }})</td>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>DETAIL KLINIK</h4>
                    <div class="tabs clearfix" id="tab-3">
                        <ul class="tab-nav tab-nav2 clearfix">
                            <li><a href="#tabs-9">SDM KLINIK</a></li>
                            <li><a href="#tabs-10">Rumah Sakit Terdekat</a></li>
                            <li><a href="#tabs-11">Kerjasama Dengan Provider</a></li>
                            <li class="hidden-phone"><a href="#tabs-12">Dokumen Perizinan</a></li>
                        </ul>
                        <div class="tab-container">
                            <div class="tab-content clearfix" id="tabs-9">
                                <h4>DOKTER PENANGGUNG JAWAB</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table1" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Dokter</th>
                                                <th>NPA IDI/PDGI</th>
                                                <th>No. STR</th>
                                                <th>No. SIP</th>
                                                <th>Tgl. Akhir SIP</th>
                                                <th>Telepon</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>DOKTER PRAKTEK</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table2" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Dokter</th>
                                                <th>NPA IDI/PDGI</th>
                                                <th>No. STR</th>
                                                <th>No. SIP</th>
                                                <th>Tgl. Akhir SIP</th>
                                                <th>Telepon</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>TENAGA KEPERAWATAN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table3" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>No. SIB / SIK</th>
                                                <th>No. STR</th>
                                                <th>Tgl. Akhir STR</th>
                                                <th>Keterangan SIB / SIK</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>TENAGA KESEHATAN LAIN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table4" width="100%">
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h4>TENAGA SDM LAIN</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table5" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Ijazah Terakhir</th>
                                                <th>Jabatan / Pekerjaan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-content clearfix" id="tabs-10">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table6" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Rumah Sakit</th>
                                                <th>Alamat</th>
                                                <th>Jarak</th>
                                                <th>No. Telepon</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-content clearfix" id="tabs-11">
                                <table class="table table-bordered" id="table7" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nama Perusahaan</th>
                                            <th>Nama Kontak</th>
                                            <th>Alamat</th>
                                            <th>No. Telepon</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="tab-content clearfix" id="tabs-12">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="line"></div>
        </div>
    @else
        <form action="{{ route('create.anggota') }}" method="post">
            <section id="slider" class="slider-element min-vh-100 py-5 include-header"
                style="background: linear-gradient(to top, rgba(38,38,38,0.922), rgba(38,38,38,0.922)), url('http://asklin.org/assets/frontend/images/baru/penguruspusat.JPG') no-repeat center center/ cover;">
                <div class="slider-inner">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-lg-4 col-sm-7 col-10 mt-sm-5">
                            <div class="form-widget">
                                @csrf
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane show active" role="tabpanel"
                                        aria-labelledby="get-started-form-select-dates">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="center mb-5 dark">
                                                    <h1 class="fw-semibold display-4 mb-4 text-white">Selamat Datang</h1>
                                                    <p class="fw-normal text-white">Anda Akan memasuki Ruang Pendaftaran
                                                        Anggota Persiapkan Data yang harus dilengkapi sebagai berikut : </p>
                                                    <div class="d-flex justify-content-center mt-4 clearfix">
                                                        <ul class=" text-white">
                                                            Surat izin Klinik (SIO)<br>
                                                            Data Penanggung Jawab Klinik<br>
                                                            SDM Klinik
                                                        </ul>
                                                    </div>
                                                    <input type="submit"
                                                        class="button button-rounded button-large tab-action-btn-next bg-danger mt-5 w-100 py-3"
                                                        value="Daftar Sebagai Anggota">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    @endif
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
                    url: "{{ route('pendaftaran.sdm.pjk') }}"
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
                ]
            });
        });

        // PJ
        $(function() {
            var table2 = $('#table2').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('pendaftaran.sdm.dp') }}"
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
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
                    url: "{{ route('pendaftaran.sdm.tk') }}"
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
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
                    url: "{{ route('pendaftaran.sdm.tkl') }}"
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
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
                    url: "{{ route('pendaftaran.sdm.lain') }}"
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
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
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
                    url: "{{ route('pendaftaran.sdm.rumah_sakit') }}"
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
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
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
                    url: "{{ route('pendaftaran.sdm.asuransi') }}"
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
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
                ]
            });
        });

        // Foto Klinik
        $(function() {
            var table7 = $('#table8').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('pendaftaran.sdm.foto') }}"
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
                                '" width="90px">';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
                ]
            });
        });
    </script>
@endpush
