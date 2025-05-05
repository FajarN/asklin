@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Slider</div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="javascript:void(0)" onclick="add()"
                            class="btn btn-icon icon-left btn-info btn-outline-secondary">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </h4>
                    <div class="card-header-form">
                        <input type="text" class="form-control" placeholder="Search" id="search">
                    </div>
                </div>
                <div class="card-body p-0">
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
