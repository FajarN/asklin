@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen Group</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Roles Management</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <button type="button" class="btn btn-icon icon-left btn-danger btn-outline-secondary"
                                id="create-btn" onclick="showModal()">
                                <i class="fas fa-plus"></i> Tambah Roles
                            </button>
                        </h4>
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
                                        <th>Group</th>
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

    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="roleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="roleForm" action="javascript:void(0)" autocomplete="off">
                    @csrf
                    <input type="hidden" id="roleId" name="id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label>Roles</label>
                                <input type="text" id="roleName" name="name" class="form-control">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <input type="checkbox" id="checkAll">
                                    <label for="checkAll"><strong>Ceklis Semua</strong></label>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Permission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedPermissions as $category => $perms)
                                            <tr>
                                                <td rowspan="{{ $perms->count() }}" class="align-middle">
                                                    <input type="checkbox" class="checkCategory"
                                                        data-category="{{ $category }}">
                                                    <strong>{{ ucwords(str_replace('-', ' ', $category)) }}</strong>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="permission[]" class="permission-checkbox"
                                                        value="{{ $perms->first()->id }}"
                                                        data-category="{{ $category }}"
                                                        id="permission_{{ $perms->first()->id }}">
                                                    <label for="permission_{{ $perms->first()->id }}">
                                                        {{ ucwords(str_replace('-', ' ', $perms->first()->name)) }}
                                                    </label>
                                                </td>
                                            </tr>
                                            @foreach ($perms->skip(1) as $perm)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="permission[]"
                                                            class="permission-checkbox" value="{{ $perm->id }}"
                                                            data-category="{{ $category }}"
                                                            id="permission_{{ $perm->id }}">
                                                        <label for="permission_{{ $perm->id }}">
                                                            {{ ucwords(str_replace('-', ' ', $perm->name)) }}
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </form>
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
    <script src="{{ asset('assets/backend/modules/sweetalert/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('roles.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="editRole(${row.id})">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteRole(${row.id})">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        `;
                        }
                    }
                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });

            window.showModal = function() {
                $('#roleForm').trigger("reset");
                $('.invalid-feedback').text('');
                $('input[name="permission[]"]').prop('checked', false);
                $('#modalTitle').html("Tambah Role");
                $('#roleId').val('');
                $('#roleModal').modal('show');
            };

            window.editRole = function(id) {
                $('#roleForm').trigger("reset");
                $('.invalid-feedback').text('');
                $('input[name="permission[]"]').prop('checked', false);

                $('#modalTitle').html("Edit Role");
                $('#roleId').val(id);

                $.get("{{ route('roles.edit', '') }}/" + id, function(response) {
                    if (response.success) {
                        $('#roleName').val(response.role.name);

                        if (response.permissions && response.permissions.length > 0) {
                            response.permissions.forEach(function(permId) {
                                $('input[name="permission[]"][value="' + permId + '"]').prop(
                                    'checked', true);
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message || "Gagal memuat data role",
                            icon: "error"
                        });
                    }
                }).fail(function(xhr) {
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan saat memuat data",
                        icon: "error"
                    });
                });

                $('#roleModal').modal('show');
            };

            $('#roleForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var url = "{{ route('roles.store') }}";
                var method = "POST";

                $('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                $('#saveBtn').attr('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#roleModal').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr) {
                        var errorMessage = '';
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + 'Error').text(value[0]);
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan';
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan');
                        $('#saveBtn').attr('disabled', false);
                    }
                });
            });

            window.deleteRole = function(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('roles.delete') }}",
                            type: "POST",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload();
                                    Swal.fire(
                                        'Terhapus!',
                                        response.message,
                                        'success'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message || 'Gagal menghapus data',
                                    'error'
                                );
                            }
                        });
                    }
                });
            };

            $('#checkAll').change(function() {
                $('.permission-checkbox, .checkCategory').prop('checked', this.checked);
            });

            $('.checkCategory').change(function() {
                var category = $(this).data('category');
                $('.permission-checkbox[data-category="' + category + '"]').prop('checked', this.checked);
            });
        });
    </script>
@endpush
