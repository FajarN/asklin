@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Verifikasi Anggota</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Verifikasi Anggota</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-form">
                            <input type="text" class="form-control" placeholder="Search" id="search">
                        </div>
                    </div>
                    
                    <!-- Filter Section -->
                    <div class="card-body pt-3 pb-2">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Filter Usia Data:</label>
                                <select class="form-control" id="filter_usia">
                                    <option value="">Semua Usia</option>
                                    <option value="tahun_ini">Tahun Ini (2025)</option>
                                    <option value="1_tahun">1 Tahun Lalu (2024)</option>
                                    <option value="2_tahun">2 Tahun Lalu (2023)</option>
                                    <option value="3_tahun">3 Tahun Lalu (2022)</option>
                                    <option value="4_tahun_lebih">4+ Tahun Lalu (≤2021)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Status:</label>
                                <select class="form-control" id="filter_status">
                                    <option value="">Semua Status</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                    <option value="create_dokter">Create Dokter</option>
                                    <option value="Verifikasi Sekjen">Verifikasi Sekjen</option>
                                    <option value="Verifikasi Bendahara">Verifikasi Bendahara</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Jenis Klinik:</label>
                                <select class="form-control" id="filter_jenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="Pratama">Pratama</option>
                                    <option value="Utama">Utama</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="btn_filter">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="btn_reset">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Keterangan Warna -->
                    <div class="card-body pt-0 pb-2">
                        <div class="alert alert-secondary mb-2">
                            <strong>Keterangan Warna Tanggal:</strong>
                            <span style="color: #32CD32; font-weight: bold;">■ Tahun ini</span> | 
                            <span style="color: #FFA500; font-weight: bold;">■ > 1 tahun</span> | 
                            <span style="color: #FF4500; font-weight: bold;">■ > 2 tahun</span> | 
                            <span style="color: #FF0000; font-weight: bold;">■ > 3 tahun</span> | 
                            <span style="color: #8B0000; font-weight: bold;">■ > 4 tahun</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Anggota</th>
                                        <th>Kab/Kota</th>
                                        <th>Nama Klinik</th>
                                        <th>Jenis Klinik</th>
                                        <th>Kriteria Klinik</th>
                                        <th>Status Verifikasi</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Actions</th>
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
        $(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 50,
                ajax: {
                    url: "{{ route('verifikasi_anggota.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_usia = $('#filter_usia').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_jenis = $('#filter_jenis').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nama_klinik',
                        name: 'nama_klinik'
                    },
                    {
                        data: 'jenis_klinik',
                        name: 'jenis_klinik'
                    },
                    {
                        data: 'kriteria',
                        name: 'kriteria'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tanggal_daftar_formatted',
                        name: 'tanggal_daftar',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "fnDrawCallback": function() {
                    $('.checkbox').change(function(e) {
                        var status_pembayaran = $(this).prop('checked') == true ? 1 : 0;
                        var id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ route('verifikasi_anggota.bendahara') }}",
                            data: {
                                'status_pembayaran': status_pembayaran,
                                'id': id
                            },
                            success: function(data) {
                                Swal.fire("Berhasil!",
                                    "Status pembayaran berhasil diubah.!",
                                    "success");
                            },
                            error: function(data) {
                                Swal.fire("Gagal!",
                                    "Silahkan hubungi administrator !",
                                    "error");
                            }
                        });
                    });
                }
            });

            $("#search").keyup(function() {
                table.draw();
            });

            // Event handler untuk filter
            $("#btn_filter").click(function() {
                table.draw();
            });

            // Event handler untuk reset filter
            $("#btn_reset").click(function() {
                $('#filter_usia').val('');
                $('#filter_status').val('');
                $('#filter_jenis').val('');
                $('#search').val('');
                table.draw();
            });

            // Filter otomatis saat dropdown berubah
            $('#filter_usia, #filter_status, #filter_jenis').change(function() {
                table.draw();
            });
        });
    </script>
@endpush