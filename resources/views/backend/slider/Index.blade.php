@extends('layouts.backend.layout')

@section('title', 'Banner Slider')
@section('page_title', 'Slider')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Banner & Slider Beranda
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola foto spanduk/banner carousel yang tampil pada halaman utama portal ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="javascript:void(0)" onclick="add()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Slider Baru</span>
            </a>
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
                <i class="fas fa-images text-emerald-600 mr-1.5"></i> Daftar Banner Slider
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari slider..." id="search">
                    <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table id="table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Foto Slider</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form" class="form" action="javascript:void(0)">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" id="judul" name="judul" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Foto Slider</label>
                            <input type="file" id="foto_slider" name="foto_slider" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" id="btn-save" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
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
                    url: "{{ route('slider.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'foto_slider', name: 'foto_slider' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });
        });

        function add() {
            $('#form').trigger("reset");
            $('.modal-title').html("Tambah Slider");
            $('#tambah').modal('show');
            $('#id').val('');
        }

        function edit(id) {
            $.ajax({
                type: "POST",
                url: '{{ route('slider.edit') }}',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    $('.modal-title').html("Edit Slider");
                    $('#tambah').modal('show');
                    $('#id').val(data.id);
                    $('#judul').val(data.judul);
                    $('#status').val(data.status);
                }
            });
        }

        $('#form').submit(function(e) {
            e.preventDefault();
            $("#btn-save").attr("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('slider.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#tambah").modal('hide');
                    $('#table').DataTable().draw(false);
                    $("#btn-save").attr("disabled", false);
                },
                error: function(data) {
                    $("#btn-save").attr("disabled", false);
                }
            });
        });

        function deleteu(id) {
            if (confirm("Hapus Slider ini?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('slider.delete') }}",
                    data: { id: id },
                    dataType: 'json',
                    success: function(res) {
                        $('#table').DataTable().draw(false);
                    }
                });
            }
        }
    </script>
@endpush
