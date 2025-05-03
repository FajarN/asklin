@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Surat Keluar</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat Keluar</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <a href="{{ route('surat_keluar.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Surat
                            </a>
                        </h4>
                        <div class="card-header-form">
                        </div>
                        <div class="card-header-form">
                            <input type="text" class="form-control" placeholder="Search" id="search">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No&nbsp;Surat</th>
                                        <th>Jenis&nbsp;Surat</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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
    <!-- Tambahkan CSS iziToast -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@push('js')
    <!-- Pastikan jQuery dimuat pertama -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Kemudian Select2 -->
    <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
    <!-- Script lainnya -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            @if(session('success'))
                iziToast.success({
                    title: 'Berhasil',
                    message: '{{ session('success') }}',
                    position: 'topRight'
                });
            @endif

            @if(session('error'))
                iziToast.error({
                    title: 'Gagal',
                    message: '{{ session('error') }}',
                    position: 'topRight'
                });
            @endif

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: "{{ route('surat_keluar.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'no_surat', name: 'no_surat'},
                    {data: 'jenis_surat', name: 'jenis_surat'},
                    {data: 'tgl_surat', name: 'tgl_surat'},
                    {data: 'status_badge', name: 'status_badge'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data surat akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('surat_keluar.delete') }}",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(response) {
                                if (response.success) {
                                    iziToast.success({
                                        title: 'Berhasil',
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                    table.ajax.reload();
                                } else {
                                    iziToast.error({
                                        title: 'Gagal',
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                iziToast.error({
                                    title: 'Error',
                                    message: 'Terjadi kesalahan pada server.',
                                    position: 'topRight'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush