@extends('layouts.backend.layout')

@section('title', 'Berita & Publikasi')
@section('page_title', 'Berita')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Berita & Publikasi Organisasi
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola artikel, informasi terkini, liputan kegiatan, dan siaran pers ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            @can('berita-create')
                <a href="{{ route('berita.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Berita Baru</span>
                </a>
            @endcan
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-newspaper text-emerald-600 mr-1.5"></i> Daftar Berita & Artikel
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari judul, lokasi..." id="search">
                    <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Status</th>
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
                ajax: {
                    url: "{{ route('berita.list') }}",
                    data: function(d) {
                        d.search = $('#search').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: "status",
                        "render": function(data, type, row, meta) {
                            if (row.status == '1') {
                                return 'Live';
                            } else if (row.status == '2') {
                                return 'On Going';
                            } else {
                                return 'Draft';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });
        });

        function deleteu(id) {
            if (confirm("Hapus berita ini?") == true) {
                var id = id;
                $.ajax({
                    type: "POST",
                    url: "{{ route('berita.delete') }}",
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
