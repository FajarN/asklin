@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Users Managemen</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <a href="{{ route('users.create') }}"
                                class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
                        </h4>
                        <div class="card-header-form">
                            <select id="role" class="form-control form-control-sm">
                                <option></option>
                                @foreach ($role as $i)
                                    <option value="{{ $i->name }}">{{ $i->name }}
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Group</th>
                                        <th>Propinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Login Terakhir</th>
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
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets/backend/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
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
                pageLength: 200,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('users.list') }}",
                    data: function(d) {
                        d.search = $('#search').val(),
                            d.role_names = $('#role').val()
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role_names',
                        name: 'roles'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi'
                    },
                    {
                        data: 'last_login_at',
                        "render": function(data, type, row, meta) {
                            return row.last_login_at + '<br/>' + row.last_login_ip;
                        }
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
            $('#role').on('change', function(e) {
                table.draw();
            });
        });

        function deleteu(id) {
            if (confirm("Hapus pengguna ini?") == true) {
                var id = id;
                $.ajax({
                    type: "POST",
                    url: "{{ route('users.delete') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#table').dataTable().fnDraw(false);
                    }
                });
            }
        }
    </script>
@endpush
