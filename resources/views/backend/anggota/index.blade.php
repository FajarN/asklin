@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Anggota Approved</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Anggota Approved</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-form">
                            <input type="text" class="form-control" placeholder="Search" id="search">
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="card-body pt-3 pb-2">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Filter Tahun Approve:</label>
                                <select class="form-control" id="filter_tahun_approve">
                                    <option value="">Semua Tahun</option>
                                    @for ($year = date('Y'); $year >= 2018; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Bulan Approve:</label>
                                <select class="form-control" id="filter_bulan_approve">
                                    <option value="">Semua Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Jenis Klinik:</label>
                                <select class="form-control" id="filter_jenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="Pratama">Pratama</option>
                                    <option value="Utama">Utama</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="btn_filter">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="btn_reset">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Anggota</th>
                                        <th>Kab/Kota</th>
                                        <th>Nama Klinik</th>
                                        <th>Jenis Klinik</th>
                                        <th>Kriteria Klinik</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Tanggal Approve</th>
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

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Data Anggota
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-warning"></i>
                        <strong>PERINGATAN:</strong> Tindakan ini akan menghapus SEMUA data terkait dengan anggota ini dan
                        tidak dapat dibatalkan!
                    </div>

                    <div id="deleteDetails">
                        <!-- Detail akan dimuat via AJAX -->
                    </div>

                    <div class="mt-3">
                        <h6><strong>Data yang akan ikut terhapus:</strong></h6>
                        <ul id="relatedDataList" class="list-unstyled">
                            <!-- List akan dimuat via AJAX -->
                        </ul>
                    </div>

                    <div class="form-group mt-4">
                        <label for="confirmText">Ketik <strong>"HAPUS"</strong> untuk konfirmasi:</label>
                        <input type="text" class="form-control" id="confirmText" placeholder="Ketik HAPUS disini">
                        <small class="text-muted">Case sensitive - harus huruf kapital semua</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash"></i> Hapus Permanen
                    </button>
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
                pageLength: 50,
                order: [
                    [7, 'desc']
                ], // Sort by tanggal approve terbaru
                ajax: {
                    url: "{{ route('anggota.list') }}", // Sesuaikan dengan route Anda
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_tahun_approve = $('#filter_tahun_approve').val();
                        d.filter_bulan_approve = $('#filter_bulan_approve').val();
                        d.filter_jenis = $('#filter_jenis').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nama_klinik',
                        name: 'nama_klinik'
                    },
                    {
                        data: 'jenis_klinik',
                        name: 'jenis_klinik'
                    },
                    {
                        data: 'kriteria',
                        name: 'kriteria'
                    },
                    {
                        data: 'tanggal_daftar_formatted',
                        name: 'tanggal_daftar',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'tanggal_approve_formatted',
                        name: 'tanggal_approve',
                        orderable: true,
                        searchable: false
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

            // Event handler untuk filter
            $("#btn_filter").click(function() {
                table.draw();
            });

            $("#btn_reset").click(function() {
                $('#filter_tahun_approve').val('');
                $('#filter_bulan_approve').val('');
                $('#filter_jenis').val('');
                $('#search').val('');
                table.draw();
            });

            $('#filter_tahun_approve, #filter_bulan_approve, #filter_jenis').change(function() {
                table.draw();
            });
        });


        let currentDeleteId = null;

        function confirmDeleteAnggota(id, namaKlinik) {
            currentDeleteId = id;

            // Reset form
            $('#confirmText').val('');
            $('#confirmDeleteBtn').prop('disabled', true);

            // Load data yang akan dihapus
            $.get(`/backend/anggota/${id}/confirm-delete`, function(response) {
                let anggota = response.anggota;
                let relatedData = response.related_data;

                // Update detail anggota
                $('#deleteDetails').html(`
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6><strong>Data Anggota yang akan dihapus:</strong></h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="150"><strong>No. Anggota:</strong></td>
                                    <td>${anggota.no_anggota || 'Belum ada'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Klinik:</strong></td>
                                    <td>${anggota.nama_klinik || 'Tidak tersedia'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Kontak:</strong></td>
                                    <td>${anggota.nama_kontak || 'Tidak tersedia'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge badge-info">${anggota.status || 'Tidak tersedia'}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    `);

                // Update related data list
                let relatedHtml = '';
                if (relatedData.sdm > 0) {
                    relatedHtml += `<li><i class="fas fa-user-md text-primary"></i> <strong>${relatedData.sdm}</strong> data SDM (Dokter,
        Perawat, dll)</li>`;
                }
                if (relatedData.rumah_sakit > 0) {
                    relatedHtml += `<li><i class="fas fa-hospital text-success"></i> <strong>${relatedData.rumah_sakit}</strong> data Rumah
        Sakit Terdekat</li>`;
                }
                if (relatedData.asuransi > 0) {
                    relatedHtml += `<li><i class="fas fa-shield-alt text-info"></i> <strong>${relatedData.asuransi}</strong> data Provider
        Asuransi</li>`;
                }
                if (relatedData.foto_klinik > 0) {
                    relatedHtml += `<li><i class="fas fa-camera text-warning"></i> <strong>${relatedData.foto_klinik}</strong> foto klinik
    </li>`;
                }
                if (relatedData.pembayaran > 0) {
                    relatedHtml += `<li><i class="fas fa-money-bill text-success"></i> <strong>${relatedData.pembayaran}</strong> data
        pembayaran</li>`;
                }
                if (relatedData.sertifikat > 0) {
                    relatedHtml += `<li><i class="fas fa-certificate text-primary"></i> <strong>${relatedData.sertifikat}</strong>
        sertifikat</li>`;
                }

                if (relatedHtml === '') {
                    relatedHtml =
                        '<li class="text-muted"><i class="fas fa-info-circle"></i> Tidak ada data terkait lainnya</li>';
                }

                $('#relatedDataList').html(relatedHtml);
            }).fail(function() {
                alert('Gagal memuat data. Silakan coba lagi.');
            });

            $('#deleteModal').modal('show');
        }

        // Validasi konfirmasi text
        $('#confirmText').on('input', function() {
            let text = $(this).val();
            if (text === 'HAPUS') {
                $('#confirmDeleteBtn').prop('disabled', false);
            } else {
                $('#confirmDeleteBtn').prop('disabled', true);
            }
        });

        // Handle delete confirmation
        $('#confirmDeleteBtn').click(function() {
            if (!currentDeleteId) return;

            let button = $(this);
            let originalText = button.html();

            // Show loading
            button.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');
            button.prop('disabled', true);

            $.ajax({
                url: `/backend/anggota/${currentDeleteId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');

                        // Show success message
                        alert('Data anggota dan semua data terkait berhasil dihapus!');

                        // Reload table
                        table.draw();
                    } else {
                        alert('Gagal menghapus data: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menghapus data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                }
            });
        });
    </script>
@endpush
