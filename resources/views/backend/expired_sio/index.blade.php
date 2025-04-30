@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Expired SIO</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Exired SIO</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h4 class="mb-0 mr-3">Filter</h4>
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="form-group mb-0 mr-2">
                                <select id="filter_expired" class="form-control">
                                    <option value="">-- Semua --</option>
                                    <option value="expired">Sudah Expired</option>
                                    <option value="besok">Besok</option>
                                    <option value="seminggu">Dalam Seminggu</option>
                                    <option value="sebulan">Dalam Sebulan</option>
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" placeholder="Search" id="search">
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kab/Kota</th>
                                        <th>Nama&nbsp;Klinik</th>
                                        <th>Email</th>
                                        <th>No&nbsp;Ijin</th>
                                        <th>Tgl&nbsp;Ijin</th>
                                        <th>Tgl&nbsp;Akhir&nbsp;Ijin</th>
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
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 50,
                ajax: {
                    url: "{{ route('expired_sio.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_expired = $('#filter_expired').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'no_ijin',
                        name: 'no_ijin'
                    },
                    {
                        data: 'tgl_ijin',
                        name: 'tgl_ijin'
                    },
                    {
                        data: 'tgl_akhir_ijin',
                        name: 'tgl_akhir_ijin'
                    },
                ]
            });

            $('#filter_expired').change(function() {
                table.draw();
            });

            $("#search").keyup(function() {
                table.draw();
            });
        });
    </script>
@endpush
