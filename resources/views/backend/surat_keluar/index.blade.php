@extends('layouts.backend.layout')

@section('title', 'Surat Keluar')
@section('page_title', 'Surat Keluar')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Persuratan'],
        ['label' => 'Surat Keluar'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Pengelolaan Surat Keluar
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Penerbitan surat resmi, penomoran otomatis, lampiran dokumen, dan riwayat surat keluar ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('surat_keluar.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Surat Baru</span>
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
                <i class="fas fa-envelope-open-text text-emerald-600 mr-1.5"></i> Daftar Arsip Surat Keluar
            </h4>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari nomor, perihal..." id="search">
                    <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Surat</th>
                            <th>Jenis Surat</th>
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