@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengajuan Sertifikat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Sertifikat</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <a href="javascript:void(0)" onclick="add()"
                                class="btn btn-icon icon-left btn-danger btn-outline-secondary">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
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
                                        <th>Klinik</th>
                                        <th>Kab/Kota</th>
                                        <th>Periode Sertifikat</th>
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
                                <label>Anggota</label>
                                <select class="form-control" id="id_anggota" name="id_anggota" style="width:100%">
                                    @foreach ($anggota as $i)
                                        <option value="{{ $i->id }}">{{ $i->nama_klinik }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" id="dari" name="dari" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" id="sampai" name="sampai" class="form-control form-control-sm">
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="submit" id="btn-save" class="btn btn-primary">Simpan</button>
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
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
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
                    url: "{{ route('sertifikat.list') }}",
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
                        data: 'nama_klinik',
                        name: 'nama_klinik'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: "dari",
                        "render": function(data, type, row, meta) {
                            return row.dari + ' - ' + row.sampai;
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
        });

        function add() {
            $('#form').trigger("reset");
            $('.modal-title').html("Tambah");
            $('#tambah').modal('show');
            $('#id').val('');
            $('#id_anggota').select2({
                dropdownParent: $('#tambah'),
                width: 'resolve',
                placeholder: "Pilih anggota",
            });
        }

        function edit(id) {
            $.ajax({
                type: "POST",
                url: '{{ route('sertifikat.edit') }}',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $('.modal-title').html("Edit");
                    $('#tambah').modal('show');
                    $('#id').val(data['id']);
                    $("#id_anggota").select2({
                        dropdownParent: $('#tambah'),
                        width: 'resolve',
                        placeholder: "Pilih anggota",
                    }).val(data['id_anggota']).trigger("change");

                    $('#dari').val(data['dari']);
                    $('#sampai').val(data['sampai']);
                }
            });
        }

        $('#form').submit(function(e) {
            e.preventDefault();
            $("#btn-save").attr("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('sertifikat.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#tambah").modal('hide');
                    $('#table').dataTable().fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                },
                error: function(data) {
                    $("#btn-save").attr("disabled", false);
                }
            });
        });

        function deleteu(id) {
            if (confirm("Hapus sertifikat ini?") == true) {
                var id = id;
                $.ajax({
                    type: "POST",
                    url: "{{ route('sertifikat.delete') }}",
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
