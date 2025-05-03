@extends('layouts.backend.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen Struktur Organisasi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Struktur Organisasi</div>
                </div>
            </div>
        </section>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" id="btnAdd">
                            <i class="fas fa-plus"></i> Tambah Struktur
                        </button>
                        <div class="d-flex">
                            <div class="form-group mb-0 mr-2">
                                <select class="form-control" id="filter_tingkatan">
                                    <option value="">Semua Tingkatan</option>
                                    @foreach ($tingkatanList as $tingkatan)
                                        <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama_tingkatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-info" id="btnFilter">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button type="button" class="btn btn-sm btn-light" id="btnReset">
                                    <i class="fas fa-sync"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="table-struktur">
                                            <thead>
                                                <tr>
                                                    <th>Nama&nbsp;Struktur</th>
                                                    <th>Tingkatan</th>
                                                    <th>Propinsi</th>
                                                    <th>Kabupaten/kota</th>
                                                    <th>Periode</th>
                                                    <th>Tanggal&nbsp;Muscab</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be filled with DataTables -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>

                <!-- Modal for Add/Edit Struktur -->
                <div class="modal fade" id="strukturModal" tabindex="-1" role="dialog"
                    aria-labelledby="strukturModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="strukturModalLabel">Tambah Struktur Organisasi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="strukturForm">
                                <div class="modal-body">
                                    <input type="hidden" id="struktur_id" name="id">

                                    <div class="form-group">
                                        <label for="nama_struktur">Nama Struktur <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_struktur"
                                            name="nama_struktur" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tingkatan_pengurus">Tingkatan Pengurus <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="tingkatan_pengurus" name="tingkatan_pengurus"
                                            required>
                                            <option value="">Pilih Tingkatan</option>
                                            @foreach ($tingkatanList as $tingkatan)
                                                <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama_tingkatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_provinsi">Provinsi</label>
                                                <select class="form-control" id="id_provinsi" name="id_provinsi"
                                                    style="width:100%">
                                                    <option value="">-- Pilih Provinsi --</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->code }}">{{ $province->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_kota">Kota/Kabupaten</label>
                                                <select class="form-control" id="id_kota" name="id_kota"
                                                    style="width:100%">
                                                    <option value="">-- Pilih Provinsi terlebih dahulu --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="periode">Periode <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="periode"
                                                    name="periode" placeholder="contoh: 2023-2028" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tgl_muscab">Tanggal Muscab <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="tgl_muscab"
                                                    name="tgl_muscab" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="draft">Draft</option>
                                            <option value="aktif">Aktif</option>
                                            <option value="selesai">Selesai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus struktur organisasi ini?</p>
                                <p class="text-danger">Peringatan: Struktur dengan pengurus tidak dapat dihapus!</p>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" id="delete_id" name="delete_id">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-danger" id="btnDelete">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

@push('css')
    <!-- Tambahkan ini -->
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <style>
        /* Tambahkan CSS ini untuk mengatasi masalah z-index */
        .select2-container {
            z-index: 99999 !important;
        }
    </style>
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

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2 untuk filter di luar modal
            $('#filter_provinsi, #filter_kota').select2({
                width: '100%'
            });

            var table = $('#table-struktur').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('struktur_organisasi.list') }}",
                    data: function(d) {
                        d.tingkatan_id = $('#filter_tingkatan').val();
                        d.provinsi_id = $('#filter_provinsi').val();
                        d.kota_id = $('#filter_kota').val();
                    }
                },
                columns: [{
                        data: 'nama_struktur',
                        name: 'nama_struktur'
                    },
                    {
                        data: 'tingkatan',
                        name: 'tingkatan'
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
                        data: 'periode',
                        name: 'periode'
                    },
                    {
                        data: 'tgl_muscab',
                        name: 'tgl_muscab'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#filter_kota').on('change', function(e) {
                table.draw();
            });

            // Filter button click
            $('#btnFilter').click(function() {
                table.ajax.reload();
            });

            // Reset filters
            $('#btnReset').click(function() {
                $('#filter_tingkatan').val('');
                $('#filter_provinsi').val('').trigger('change');
                $('#filter_kota').val('').trigger('change');
                table.ajax.reload();
            });

            // Add new struktur
            $('#btnAdd').click(function() {
                $('#strukturModal').find('.modal-title').text('Tambah Struktur Organisasi');
                $('#strukturForm')[0].reset();
                $('#struktur_id').val('');

                // Inisialisasi Select2 dalam modal
                initSelect2InModal();

                $('#strukturModal').modal('show');
            });

            // Fungsi untuk inisialisasi Select2 dalam modal
            function initSelect2InModal() {
                $('#id_provinsi').select2({
                    dropdownParent: $('#strukturModal'),
                    width: '100%',
                    placeholder: "Pilih Provinsi",
                });

                $('#id_kota').select2({
                    dropdownParent: $('#strukturModal'),
                    width: '100%',
                    placeholder: "Pilih Kota",
                });
            }

            // Handler untuk perubahan provinsi
            $('#id_provinsi').on('change', function() {
                var provinceCode = $(this).val();
                var kotaSelect = $('#id_kota');

                if (provinceCode) {
                    $.ajax({
                        url: "{{ route('getKota') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_provinsi: provinceCode
                        },
                        beforeSend: function() {
                            kotaSelect.html('<option value="">Loading...</option>');
                        },
                        success: function(response) {
                            kotaSelect.html(response);
                            kotaSelect.trigger('change');
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            kotaSelect.html('<option value="">Error loading data</option>');
                        }
                    });
                } else {
                    kotaSelect.html('<option value="">Pilih Provinsi terlebih dahulu</option>');
                }
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                var editUrl = "{{ route('struktur_organisasi.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: editUrl,
                    type: "GET",
                    success: function(response) {
                        $('#strukturModalLabel').text('Edit Struktur Organisasi');
                        $('#strukturForm')[0].reset();

                        $('#struktur_id').val(id);
                        $('#nama_struktur').val(response.data.nama_struktur);
                        $('#tingkatan_pengurus').val(response.data.id_tingkatan_pengurus);
                        $('#periode').val(response.data.periode);

                        var tglMuscab = new Date(response.data.tgl_muscab);
                        var formattedDate = tglMuscab.getFullYear() + '-' +
                            String(tglMuscab.getMonth() + 1).padStart(2, '0') + '-' +
                            String(tglMuscab.getDate()).padStart(2, '0');
                        $('#tgl_muscab').val(formattedDate);

                        $('#status').val(response.data.status);

                        // Inisialisasi Select2 dalam modal
                        initSelect2InModal();

                        $('#strukturModal').modal('show');

                        // Set nilai provinsi dan kota setelah modal terbuka
                        setTimeout(function() {
                            if (response.data.id_provinsi) {
                                $('#id_provinsi').val(response.data.id_provinsi)
                                    .trigger('change');

                                // Tunggu sampai kota dimuat
                                setTimeout(function() {
                                    if (response.data.id_kota) {
                                        $('#id_kota').val(response.data.id_kota)
                                            .trigger('change');
                                    }
                                }, 500);
                            }
                        }, 100);
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Gagal memuat data untuk diedit',
                            position: 'topRight'
                        });
                    }
                });
            });

            $('#strukturForm').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                console.log('Form data:', formData);

                $.ajax({
                    url: "{{ route('struktur_organisasi.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.message
                            });
                            $('#strukturModal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            for (var field in errors) {
                                iziToast.error({
                                    message: errors[field][0]
                                });
                            }
                        } else {
                            iziToast.error({
                                message: 'Terjadi kesalahan server'
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                $('#delete_id').val(id);
                $('#deleteModal').modal('show');
            });

            $('#btnDelete').click(function() {
                let id = $('#delete_id').val();
                $('#btnDelete').text('Menghapus...').attr('disabled', true);

                $.ajax({
                    url: "{{ route('struktur_organisasi.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();

                        iziToast.success({
                            title: 'Sukses',
                            message: data.message,
                            position: 'topRight'
                        });
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON.message,
                            position: 'topRight'
                        });
                    },
                    complete: function() {
                        $('#btnDelete').text('Hapus').attr('disabled', false);
                    }
                });
            });
        });
                    </script>
                @endpush
