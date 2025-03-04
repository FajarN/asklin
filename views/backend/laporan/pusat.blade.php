@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Data Anggota ASKLIN</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Laporan Pusat</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-form">
                            <select id="provinsi" class="form-control form-control">
                                <option></option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}
                                @endforeach
                            </select>
                        </div>
                        <div class="card-header-form">
                            <input type="text" class="form-control" placeholder="Search" id="search">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>No. Anggota</th>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Nama Klinik</th>
                                        <th>Jenis Klinik</th>
                                        <th>Kriteria Klinik</th>
                                        <th>Status Verifikasi</th>
                                        <th>no ijin</th>
                                        <th>tgl izin</th>
                                        <th>Created</th>
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
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'pdf', 'print', {
                    extend: 'excel',
                    text: 'Export To Excel'
                }],
                pageLength: 30,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('laporan_pusat.list') }}",
                    data: function(d) {
                        d.search = $('#search').val(),
                            d.provinsi = $('#provinsi').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi'
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
                        data: 'no_ijin',
                        name: 'no_ijin'
                    },
                      {
                        data: 'tgl_ijin',
                        name: 'tgl_ijin'
                    },
                   
                   
                    // {
                    //     data: 'status_pembayaran',
                    //     "render": function(data, type, row, meta) {
                    //         if (row.status_pembayaran == "0") {
                    //             return "Belum Lunas";
                    //         } else {
                    //             return "Lunas";
                    //         }
                    //     }
                    // },
                    {
                        data: 'created_on',
                        name: 'created_on'
                    }

                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });
            $('#provinsi').on('change', function(e) {
                table.draw();
            });
        });
    </script>
@endpush
