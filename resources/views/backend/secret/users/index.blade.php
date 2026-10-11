@extends('layouts.backend.layout')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Manajemen Sistem'],
        ['label' => 'Data Pengguna'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Manajemen Pengguna Sistem
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola hak akses pengguna, wilayah penugasan, dan peran dalam organisasi ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengguna Baru</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-users-cog text-emerald-600 mr-1.5"></i> Daftar Akun Pengguna
            </h4>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-44">
                    <select id="role" class="form-control w-full text-xs" data-placeholder="Pilih Role...">
                        <option value="">Semua Peran / Role</option>
                        @foreach ($role as $i)
                            <option value="{{ $i->name }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-48">
                    <select id="status_relasi" class="form-control w-full text-xs">
                        <option value="">Semua Status Relasi</option>
                        <option value="linked">✓ Berelasi Anggota</option>
                        <option value="unlinked">✗ Tidak Berelasi</option>
                    </select>
                </div>
                <div class="w-full sm:w-60">
                    <div class="relative">
                        <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari nama, email, klinik..." id="search">
                        <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Group / Role</th>
                            <th>Relasi Anggota</th>
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
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('users.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.role_names = $('#role').val();
                        d.status_relasi = $('#status_relasi').val();
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
                        data: 'status_relasi',
                        name: 'status_relasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'last_login_at',
                        "render": function(data, type, row, meta) {
                            var ip = row.last_login_ip || '-';
                            var tgl = row.last_login_at || 'Belum pernah';
                            return '<span class="text-xs text-slate-600 dark:text-slate-400">' + tgl + '</span><br/><small class="text-slate-400 font-mono text-[10px]">' + ip + '</small>';
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

            var searchTimer = null;
            $("#search").on('keyup input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    table.draw();
                }, 300);
            });

            $('#role, #status_relasi').on('change', function(e) {
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
