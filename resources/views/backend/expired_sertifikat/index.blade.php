@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Expired Sertifikat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Expired Sertifikat</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                            <h4 class="mb-0">Filter & Pencarian</h4>
                            <div class="d-flex flex-wrap align-items-center gap-2">
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
                                            <th>Klinik</th>
                                            <th>Kab/Kota</th>
                                            <th>Expired</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 50,
                ajax: {
                    url: "{{ route('expired_serfitikat.list') }}",
                    data: function (d) {
                        d.search = $('#search').val();
                        d.filter_expired = $('#filter_expired').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_klinik', name: 'nama_klinik' },
                    { data: 'kota', name: 'kota' },
                    { data: 'expired_date', name: 'expired_date' },
                ]
            });

            $('#search, #filter_expired').on('keyup change', function () {
                table.draw();
            });
        });
    </script>
@endpush
