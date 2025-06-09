@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Expired SIO</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Expired SIO</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row w-100 align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0">Filter & Export Data</h4>
                                <small class="text-muted">Kelola dan ekspor data SIO yang akan expired</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group" role="group" aria-label="Export buttons">
                                    <button type="button" class="btn btn-success btn-sm btn-export" id="btn-export-excel">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-export" id="btn-export-pdf">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="filter_expired" class="form-label font-weight-bold">Status SIO:</label>
                                <select id="filter_expired" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="expired">🔴 Sudah Expired</option>
                                    <option value="besok">⚠️ Besok Expired</option>
                                    <option value="seminggu">🟡 Dalam Seminggu</option>
                                    <option value="sebulan">🔵 Dalam Sebulan</option>
                                </select>
                                <small class="text-muted">Filter berdasarkan status kadaluwarsa SIO</small>
                            </div>
                            <div class="col-md-5">
                                <label for="search" class="form-label font-weight-bold">Pencarian:</label>
                                <input type="text" class="form-control"
                                    placeholder="Cari nama klinik, no anggota, kota..." id="search">
                                <small class="text-muted">Ketik minimal 3 karakter untuk mencari</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label font-weight-bold">Aksi:</label>
                                <div class="d-flex flex-column">
                                    <button type="button" class="btn btn-primary btn-sm mb-2" id="btn-refresh">
                                        <i class="fas fa-sync-alt"></i> Refresh Data
                                    </button>
                                    <small class="text-info">
                                        <i class="fas fa-info-circle"></i> Total: <span id="total-records">-</span> data
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats Cards -->
                        <div class="row mb-4" id="stats-cards">
                            <div class="col-md-2">
                                <div class="card border-left-primary shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-total">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-left-danger shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Expired</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-expired">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-left-warning shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Besok</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-besok">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-left-warning shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Seminggu
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-seminggu">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-left-info shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sebulan</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-sebulan">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-left-success shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aman</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-aman">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Info Alert -->
                        <div class="alert alert-info border-left-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Info Export:</strong>
                            Excel cocok untuk analisis data, PDF untuk laporan cetak. Export akan mengikuti filter yang
                            aktif.
                        </div>

                        <!-- Loading Indicator for Export -->
                        <div id="export-loading" class="alert alert-warning border-left-warning" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-warning mr-3" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div>
                                    <strong>Sedang memproses export data...</strong><br>
                                    <small>Mohon tunggu, proses ini mungkin memakan waktu beberapa detik tergantung jumlah
                                        data.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm" id="table">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th width="4%" class="text-center">No</th>
                                        <th width="12%">Kab/Kota</th>
                                        <th width="20%">Nama Klinik</th>
                                        <th width="15%">Email</th>
                                        <th width="10%">No Ijin</th>
                                        <th width="9%">Tgl Ijin</th>
                                        <th width="9%">Tgl Akhir Ijin</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="8%" class="text-center">Sisa Hari</th>
                                        <th width="3%" class="text-center">
                                            <i class="fas fa-cog" title="Action"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Data diupdate real-time
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">
                                    Role: <strong>{{ Auth::user()->getRoleNames()->first() }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="modal-title mb-2">Sedang Memproses Export</h5>
                    <p class="text-muted mb-0">Mohon tunggu, sistem sedang menyiapkan file export Anda...</p>
                    <small class="text-muted">Proses ini dapat memakan waktu beberapa detik</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .btn-export {
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table thead th {
            border: none;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
        }

        .badge-status {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        #stats-cards .card {
            transition: all 0.3s ease;
        }

        #stats-cards .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>

    <script type="text/javascript">
        let table;
        let searchTimeout;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            // Initialize DataTable
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 25,
                order: [
                    [6, 'asc']
                ], // Order by tgl_akhir_ijin
                ajax: {
                    url: "{{ route('expired_sio.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_expired = $('#filter_expired').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'nama_klinik',
                        name: 'nama_klinik',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return '<div class="font-weight-bold">' + data + '</div>' +
                                '<small class="text-muted">' + (row.no_anggota ||
                                    'No anggota belum ada') + '</small>';
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return '<a href="mailto:' + data + '" class="text-primary">' + data +
                                '</a>';
                        }
                    },
                    {
                        data: 'no_ijin',
                        name: 'no_ijin',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'tgl_ijin',
                        name: 'tgl_ijin',
                        className: 'text-center',
                        render: function(data) {
                            if (data && moment(data).isValid()) {
                                return moment(data).format('DD-MM-YYYY');
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'tgl_akhir_ijin',
                        name: 'tgl_akhir_ijin',
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_akhir_ijin',
                        name: 'status',
                        orderable: false,
                        className: 'text-center',
                        render: function(data) {
                            if (!data)
                            return '<span class="badge badge-secondary badge-status">-</span>';

                            const today = moment();
                            const tglAkhir = moment(data);
                            const diffDays = tglAkhir.diff(today, 'days');

                            if (diffDays < 0) {
                                return '<span class="badge badge-danger badge-status">EXPIRED</span>';
                            } else if (diffDays === 0) {
                                return '<span class="badge badge-warning badge-status">HARI INI</span>';
                            } else if (diffDays === 1) {
                                return '<span class="badge badge-warning badge-status">BESOK</span>';
                            } else if (diffDays <= 7) {
                                return '<span class="badge badge-warning badge-status">SEMINGGU</span>';
                            } else if (diffDays <= 30) {
                                return '<span class="badge badge-info badge-status">SEBULAN</span>';
                            } else {
                                return '<span class="badge badge-success badge-status">AMAN</span>';
                            }
                        }
                    },
                    {
                        data: 'tgl_akhir_ijin',
                        name: 'sisa_hari',
                        orderable: false,
                        className: 'text-center',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">-</span>';

                            const today = moment();
                            const tglAkhir = moment(data);
                            const diffDays = tglAkhir.diff(today, 'days');

                            if (diffDays < 0) {
                                return '<span class="text-danger font-weight-bold">' + Math.abs(
                                    diffDays) + ' hari lalu</span>';
                            } else if (diffDays === 0) {
                                return '<span class="text-warning font-weight-bold">Hari ini</span>';
                            } else {
                                return '<span class="text-primary">' + diffDays + ' hari</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-outline-primary" onclick="viewDetail(' +
                                row.id + ')" title="Lihat Detail">' +
                                '<i class="fas fa-eye"></i></button>';
                        }
                    }
                ],
                drawCallback: function(settings) {
                    updateStatistics();
                    $('#total-records').text(settings.json ? settings.json.recordsFiltered : 0);
                },
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div> Memuat data...',
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Event handlers
            $('#filter_expired').change(function() {
                table.draw();
                updateFilterInfo();
            });

            // Debounced search
            $("#search").keyup(function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    table.draw();
                }, 500);
            });

            $('#btn-refresh').click(function() {
                $(this).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                table.ajax.reload(function() {
                    $('#btn-refresh').html('<i class="fas fa-sync-alt"></i> Refresh Data');
                });
            });

            // Export handlers
            $('#btn-export-excel').click(function() {
                exportData('excel');
            });

            $('#btn-export-pdf').click(function() {
                exportData('pdf');
            });

            // Initialize filter info
            updateFilterInfo();
        });

        // Tambahkan di view untuk debugging
        function exportData(type) {
            const filter = $('#filter_expired').val();
            const search = $('#search').val();

            console.log('Export dimulai:', type); // Debug log
            console.log('Filter:', filter); // Debug log
            console.log('Search:', search); // Debug log

            // Show loading
            $('#loadingModal').modal('show');
            $('#export-loading').show();

            // Disable export buttons
            $('.btn-export').prop('disabled', true);

            // Build URL with parameters
            let url = '';
            if (type === 'excel') {
                url = "{{ route('expired_sio.export.excel') }}";
            } else {
                url = "{{ route('expired_sio.export.pdf') }}";
            }

            const params = new URLSearchParams();
            if (filter) params.append('filter_expired', filter);
            if (search) params.append('search', search);

            if (params.toString()) {
                url += '?' + params.toString();
            }

            console.log('Final URL:', url); // Debug log

            // METHOD 1: Direct window.open (lebih reliable)
            try {
                window.open(url, '_blank');
                console.log('Window.open berhasil'); // Debug log
            } catch (error) {
                console.error('Window.open error:', error); // Debug log

                // METHOD 2: Fallback dengan createElement
                const link = document.createElement('a');
                link.href = url;
                link.download = '';
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                console.log('Fallback method digunakan'); // Debug log
            }

            // Hide loading after delay and re-enable buttons
            setTimeout(function() {
                $('#loadingModal').modal('hide');
                $('#export-loading').hide();
                $('.btn-export').prop('disabled', false);

                // Show success message
                showNotification('success', 'Export dimulai! Cek folder download Anda.');
                console.log('Export selesai'); // Debug log
            }, 2000);
        }

        function updateStatistics() {
            // Get current table data
            const info = table.page.info();
            const data = table.rows({
                page: 'current'
            }).data();

            let stats = {
                total: info.recordsDisplay,
                expired: 0,
                besok: 0,
                seminggu: 0,
                sebulan: 0,
                aman: 0
            };

            // Count status for current page (basic count)
            data.each(function(row) {
                if (row.tgl_akhir_ijin) {
                    const today = moment();
                    const tglAkhir = moment(row.tgl_akhir_ijin);
                    const diffDays = tglAkhir.diff(today, 'days');

                    if (diffDays < 0) {
                        stats.expired++;
                    } else if (diffDays <= 1) {
                        stats.besok++;
                    } else if (diffDays <= 7) {
                        stats.seminggu++;
                    } else if (diffDays <= 30) {
                        stats.sebulan++;
                    } else {
                        stats.aman++;
                    }
                }
            });

            // Update stat cards
            $('#stat-total').text(stats.total);
            $('#stat-expired').text(stats.expired);
            $('#stat-besok').text(stats.besok);
            $('#stat-seminggu').text(stats.seminggu);
            $('#stat-sebulan').text(stats.sebulan);
            $('#stat-aman').text(stats.aman);
        }

        function updateFilterInfo() {
            const filter = $('#filter_expired').val();
            const filterText = $('#filter_expired option:selected').text();

            if (filter) {
                $('#filter-info').html('<i class="fas fa-filter"></i> Filter aktif: ' + filterText);
            } else {
                $('#filter-info').html('<i class="fas fa-list"></i> Menampilkan semua data');
            }
        }

        function viewDetail(id) {
            // Implementasi untuk melihat detail
            window.open('{{ route('verifikasi_anggota.verify', ':id') }}'.replace(':id', id), '_blank');
        }

        function showNotification(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

            const notification = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="${icon}"></i> ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;

            $('.main-content').prepend(notification);

            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Load moment.js if not available
        if (typeof moment === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js';
            document.head.appendChild(script);
        }
    </script>
@endpush
