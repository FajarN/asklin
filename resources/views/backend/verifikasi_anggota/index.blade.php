@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Verifikasi Anggota</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Verifikasi Anggota</div>
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
                                <label class="form-label">Filter Usia Data:</label>
                                <select class="form-control" id="filter_usia">
                                    <option value="">Semua Usia</option>
                                    <option value="tahun_ini">Tahun Ini (2025)</option>
                                    <option value="1_tahun">1 Tahun Lalu (2024)</option>
                                    <option value="2_tahun">2 Tahun Lalu (2023)</option>
                                    <option value="3_tahun">3 Tahun Lalu (2022)</option>
                                    <option value="4_tahun_lebih">4+ Tahun Lalu (≤2021)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Status:</label>
                                <select class="form-control" id="filter_status">
                                    <option value="">Semua Status</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                    <option value="create_dokter">Create Dokter</option>
                                    <option value="Verifikasi Sekjen">Verifikasi Sekjen</option>
                                    <option value="Verifikasi Bendahara">Verifikasi Bendahara</option>
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

                    <!-- Keterangan Warna -->
                    <div class="card-body pt-0 pb-2">
                        <div class="alert alert-secondary mb-2">
                            <strong>Keterangan Warna Tanggal:</strong>
                            <span style="color: #32CD32; font-weight: bold;">■ Tahun ini</span> |
                            <span style="color: #FFA500; font-weight: bold;">■ > 1 tahun</span> |
                            <span style="color: #FF4500; font-weight: bold;">■ > 2 tahun</span> |
                            <span style="color: #FF0000; font-weight: bold;">■ > 3 tahun</span> |
                            <span style="color: #8B0000; font-weight: bold;">■ > 4 tahun</span>
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
                                        <th>Status Verifikasi</th>
                                        <th>Tanggal Daftar</th>
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

    <!-- Modal Konfirmasi Delete untuk Verifikasi Anggota -->
    <div class="modal fade" id="deleteVerifikasiModal" tabindex="-1" role="dialog" aria-labelledby="deleteVerifikasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteVerifikasiModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Data Verifikasi Anggota
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-warning"></i>
                        <strong>PERINGATAN:</strong> Tindakan ini akan menghapus SEMUA data terkait dengan pendaftar ini dan tidak dapat dibatalkan!
                    </div>

                    <div id="deleteVerifikasiDetails">
                        <!-- Detail akan dimuat via AJAX -->
                    </div>

                    <div class="mt-3">
                        <h6><strong>Data yang akan ikut terhapus:</strong></h6>
                        <ul id="relatedVerifikasiDataList" class="list-unstyled">
                            <!-- List akan dimuat via AJAX -->
                        </ul>
                    </div>

                    <div class="form-group mt-4">
                        <label for="confirmVerifikasiText">Ketik <strong>"HAPUS"</strong> untuk konfirmasi:</label>
                        <input type="text" class="form-control" id="confirmVerifikasiText" placeholder="Ketik HAPUS disini">
                        <small class="text-muted">Case sensitive - harus huruf kapital semua</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteVerifikasiBtn" disabled>
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
                ajax: {
                    url: "{{ route('verifikasi_anggota.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_usia = $('#filter_usia').val();
                        d.filter_status = $('#filter_status').val();
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
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tanggal_daftar_formatted',
                        name: 'tanggal_daftar',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "fnDrawCallback": function() {
                    $('.checkbox').change(function(e) {
                        var status_pembayaran = $(this).prop('checked') == true ? 1 : 0;
                        var id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ route('verifikasi_anggota.bendahara') }}",
                            data: {
                                'status_pembayaran': status_pembayaran,
                                'id': id
                            },
                            success: function(data) {
                                Swal.fire("Berhasil!",
                                    "Status pembayaran berhasil diubah.!",
                                    "success");
                            },
                            error: function(data) {
                                Swal.fire("Gagal!",
                                    "Silahkan hubungi administrator !",
                                    "error");
                            }
                        });
                    });
                }
            });

            $("#search").keyup(function() {
                table.draw();
            });

            // Event handler untuk filter
            $("#btn_filter").click(function() {
                table.draw();
            });

            // Event handler untuk reset filter
            $("#btn_reset").click(function() {
                $('#filter_usia').val('');
                $('#filter_status').val('');
                $('#filter_jenis').val('');
                $('#search').val('');
                table.draw();
            });

            // Filter otomatis saat dropdown berubah
            $('#filter_usia, #filter_status, #filter_jenis').change(function() {
                table.draw();
            });
        });

        let currentDeleteVerifikasiId = null;

        function confirmDeleteVerifikasi(id, namaKlinik, status) {
            currentDeleteVerifikasiId = id;

            // Reset form
            $('#confirmVerifikasiText').val('');
            $('#confirmDeleteVerifikasiBtn').prop('disabled', true);

            // Load data yang akan dihapus
            $.get(`/backend/verifikasi-anggota/${id}/confirm-delete`, function(response) {
                let anggota = response.anggota;
                let relatedData = response.related_data;

                // Update detail anggota
                $('#deleteVerifikasiDetails').html(`
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6><strong>Data Pendaftar yang akan dihapus:</strong></h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="150"><strong>Nama Klinik:</strong></td>
                                    <td>${anggota.nama_klinik || 'Tidak tersedia'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Kontak:</strong></td>
                                    <td>${anggota.nama_kontak || 'Tidak tersedia'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>${anggota.email || 'Tidak tersedia'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge badge-warning">${anggota.status || 'Tidak tersedia'}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Klinik:</strong></td>
                                    <td>${anggota.jenis_klinik || 'Tidak tersedia'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `);

                // Update related data list
                let relatedHtml = '';
                if (relatedData.sdm > 0) {
                    relatedHtml += `<li><i class="fas fa-user-md text-primary"></i> <strong>${relatedData.sdm}</strong> data SDM (Dokter, Perawat, dll)</li>`;
                }
                if (relatedData.rumah_sakit > 0) {
                    relatedHtml += `<li><i class="fas fa-hospital text-success"></i> <strong>${relatedData.rumah_sakit}</strong> data Rumah Sakit Terdekat</li>`;
                }
                if (relatedData.asuransi > 0) {
                    relatedHtml += `<li><i class="fas fa-shield-alt text-info"></i> <strong>${relatedData.asuransi}</strong> data Provider Asuransi</li>`;
                }
                if (relatedData.foto_klinik > 0) {
                    relatedHtml += `<li><i class="fas fa-camera text-warning"></i> <strong>${relatedData.foto_klinik}</strong> foto klinik</li>`;
                }
                if (relatedData.pembayaran > 0) {
                    relatedHtml += `<li><i class="fas fa-money-bill text-success"></i> <strong>${relatedData.pembayaran}</strong> data pembayaran</li>`;
                }
                if (relatedData.sertifikat > 0) {
                    relatedHtml += `<li><i class="fas fa-certificate text-primary"></i> <strong>${relatedData.sertifikat}</strong> sertifikat</li>`;
                }

                if (relatedHtml === '') {
                    relatedHtml = '<li class="text-muted"><i class="fas fa-info-circle"></i> Tidak ada data terkait lainnya</li>';
                }

                $('#relatedVerifikasiDataList').html(relatedHtml);
            }).fail(function(xhr) {
                if (xhr.status === 400) {
                    let response = xhr.responseJSON;
                    alert(response.message || 'Data tidak dapat dihapus');
                } else {
                    alert('Gagal memuat data. Silakan coba lagi.');
                }
                return;
            });

            $('#deleteVerifikasiModal').modal('show');
        }

        // Validasi konfirmasi text untuk verifikasi
        $('#confirmVerifikasiText').on('input', function() {
            let text = $(this).val();
            if (text === 'HAPUS') {
                $('#confirmDeleteVerifikasiBtn').prop('disabled', false);
            } else {
                $('#confirmDeleteVerifikasiBtn').prop('disabled', true);
            }
        });

        // Handle delete confirmation untuk verifikasi
        $('#confirmDeleteVerifikasiBtn').click(function() {
            if (!currentDeleteVerifikasiId) return;

            let button = $(this);
            let originalText = button.html();

            // Show loading
            button.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');
            button.prop('disabled', true);

            $.ajax({
                url: `/backend/verifikasi-anggota/${currentDeleteVerifikasiId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('#deleteVerifikasiModal').modal('hide');

                        // Show success message
                        alert('Data pendaftar dan semua data terkait berhasil dihapus!');

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
                },
                complete: function() {
                    // Restore button
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            });
        });

        // Reset modal when closed
        $('#deleteVerifikasiModal').on('hidden.bs.modal', function () {
            currentDeleteVerifikasiId = null;
            $('#confirmVerifikasiText').val('');
            $('#confirmDeleteVerifikasiBtn').prop('disabled', true);
        });
    </script>
@endpush
