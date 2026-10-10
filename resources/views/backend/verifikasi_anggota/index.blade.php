@extends('layouts.backend.layout')

@section('title', 'Verifikasi Anggota')
@section('page_title', 'Verifikasi Anggota')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Verifikasi Anggota Klinik'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Verifikasi Anggota Klinik
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Tinjau dan proses permohonan keanggotaan klinik berjenjang (Cabang &rarr; Daerah &rarr; Pusat).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <i class="fas fa-filter text-emerald-600 mr-1.5"></i> Filter & Pencarian
                </h4>
            </div>
            <div class="w-full sm:w-72">
                <div class="relative">
                    <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari nama klinik, nomor..." id="search">
                    <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- Filter Grid -->
        <div class="card-body pt-4 pb-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="form-label" for="filter_usia">Usia Pendaftaran:</label>
                    <select class="form-control" id="filter_usia">
                        <option value="">Semua Usia</option>
                        <option value="tahun_ini">Tahun Ini (2025/2026)</option>
                        <option value="1_tahun">1 Tahun Lalu</option>
                        <option value="2_tahun">2 Tahun Lalu</option>
                        <option value="3_tahun">3 Tahun Lalu</option>
                        <option value="4_tahun_lebih">4+ Tahun Lalu</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="filter_status">Status Verifikasi:</label>
                    <select class="form-control" id="filter_status">
                        <option value="">Semua Status</option>
                        <option value="waiting">Waiting</option>
                        <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                        <option value="create_dokter">Create Dokter</option>
                        <option value="Verifikasi Sekjen">Verifikasi Sekjen</option>
                        <option value="Verifikasi Bendahara">Verifikasi Bendahara</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="filter_jenis">Jenis Klinik:</label>
                    <select class="form-control" id="filter_jenis">
                        <option value="">Semua Jenis</option>
                        <option value="Pratama">Pratama</option>
                        <option value="Utama">Utama</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="btn btn-primary flex-1 justify-center" id="btn_filter">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <button type="button" class="btn btn-secondary flex-1 justify-center" id="btn_reset">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>

            <!-- Color Age Legend -->
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center gap-2 text-xs">
                <span class="font-bold text-slate-500 dark:text-slate-400 text-[11px] uppercase mr-1">Indikator Usia:</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tahun Ini
                </span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> &gt; 1 Tahun
                </span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-orange-50 text-orange-700 dark:bg-orange-950/60 dark:text-orange-400 border border-orange-200/60 dark:border-orange-800/60">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span> &gt; 2 Tahun
                </span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> &gt; 3 Tahun
                </span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-100 text-red-900 dark:bg-red-950/80 dark:text-red-300 border border-red-300/60 dark:border-red-800/60">
                    <span class="w-2 h-2 rounded-full bg-red-700"></span> &gt; 4 Tahun
                </span>
            </div>
        </div>

        <!-- Table Responsive Container -->
        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped w-full text-xs" id="table">
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

    <!-- Modal Konfirmasi Delete untuk Verifikasi Anggota -->
    <div class="modal fade" id="deleteVerifikasiModal" tabindex="-1" role="dialog" aria-labelledby="deleteVerifikasiModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteVerifikasiModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Data Verifikasi Anggota
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">
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
                        name: 'nama_klinik',
                        render: function(data) {
                            return data ? '<span class="nama-klinik-highlight font-bold text-red-600 dark:text-red-400">' + data + '</span>' : '<span class="text-slate-400">-</span>';
                        }
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
                        name: 'status',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-slate-400 text-xs">-</span>';
                            var cls = 'bg-slate-100 text-slate-700 border-slate-200';
                            var icon = 'fa-clock';
                            var s = data.toLowerCase();
                            if (s.includes('approved') || s.includes('disetujui') || s.includes('selesai')) {
                                cls = 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30';
                                icon = 'fa-check-circle';
                            } else if (s.includes('cabang')) {
                                cls = 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border-indigo-500/30';
                                icon = 'fa-code-branch';
                            } else if (s.includes('daerah')) {
                                cls = 'bg-sky-500/15 text-sky-700 dark:text-sky-300 border-sky-500/30';
                                icon = 'fa-map-marker-alt';
                            } else if (s.includes('sekjen') || s.includes('ketum')) {
                                cls = 'bg-purple-500/15 text-purple-700 dark:text-purple-300 border-purple-500/30';
                                icon = 'fa-stamp';
                            } else if (s.includes('dokter') || s.includes('create')) {
                                cls = 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border-cyan-500/30';
                                icon = 'fa-user-md';
                            } else if (s.includes('perlu') || s.includes('tolak') || s.includes('revisi')) {
                                cls = 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border-rose-500/30';
                                icon = 'fa-exclamation-circle';
                            } else if (s.includes('waiting') || s.includes('menunggu')) {
                                cls = 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/30';
                                icon = 'fa-hourglass-half';
                            }
                            return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border ' + cls + '"><i class="fas ' + icon + ' text-[10px]"></i>' + data + '</span>';
                        }
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
