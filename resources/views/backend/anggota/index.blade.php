@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Anggota Approved</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Anggota Approved</div>
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
                                <label class="form-label">Filter Tahun Approve:</label>
                                <select class="form-control" id="filter_tahun_approve">
                                    <option value="">Semua Tahun</option>
                                    @for ($year = date('Y'); $year >= 2018; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Bulan Approve:</label>
                                <select class="form-control" id="filter_bulan_approve">
                                    <option value="">Semua Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
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
                                        <th>Tanggal Daftar</th>
                                        <th>Tanggal Approve</th>
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
                order: [
                    [7, 'desc']
                ], // Sort by tanggal approve terbaru
                ajax: {
                    url: "{{ route('anggota.list') }}", // Sesuaikan dengan route Anda
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_tahun_approve = $('#filter_tahun_approve').val();
                        d.filter_bulan_approve = $('#filter_bulan_approve').val();
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
                        data: 'tanggal_daftar_formatted',
                        name: 'tanggal_daftar',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'tanggal_approve_formatted',
                        name: 'tanggal_approve',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });

            // Event handler untuk filter
            $("#btn_filter").click(function() {
                table.draw();
            });

            $("#btn_reset").click(function() {
                $('#filter_tahun_approve').val('');
                $('#filter_bulan_approve').val('');
                $('#filter_jenis').val('');
                $('#search').val('');
                table.draw();
            });

            $('#filter_tahun_approve, #filter_bulan_approve, #filter_jenis').change(function() {
                table.draw();
            });
        });
    </script>
@endpush
