@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Expired Sertifikat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Expired Sertifikat</div>
                </div>
            </div>

            <div class="section-body">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">Filter & Export Data</h4>
                                        <small class="text-muted">Kelola dan ekspor data sertifikat yang akan expired</small>
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
                                        <label for="filter_expired" class="form-label font-weight-bold">Status Sertifikat:</label>
                                        <select id="filter_expired" class="form-control">
                                            <option value="">-- Semua Status --</option>
                                            <option value="expired">🔴 Sudah Expired</option>
                                            <option value="besok">⚠️ Besok Expired</option>
                                            <option value="seminggu">🟡 Dalam Seminggu</option>
                                            <option value="sebulan">🔵 Dalam Sebulan</option>
                                        </select>
                                        <small class="text-muted">Filter berdasarkan status kadaluwarsa sertifikat</small>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="search" class="form-label font-weight-bold">Pencarian:</label>
                                        <input type="text" class="form-control" placeholder="Cari nama klinik, no anggota, kota..." id="search">
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
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Hari Ini</div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-hari-ini">-</div>
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
                                        <div class="card border-left-info shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Seminggu</div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="stat-seminggu">-</div>
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
                                    Excel cocok untuk analisis data, PDF untuk laporan cetak. Export akan mengikuti filter yang aktif.
                                </div>

                                <!-- Loading Indicator for Export -->
                                <div id="export-loading" class="alert alert-warning border-left-warning" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-warning mr-3" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div>
                                            <strong>Sedang memproses export data...</strong><br>
                                            <small>Mohon tunggu, proses ini mungkin memakan waktu beberapa detik tergantung jumlah data.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Table -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-sm" id="table">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th width="4%" class="text-center">No</th>
                                                <th width="20%">Nama Klinik</th>
                                                <th width="12%">Kab/Kota</th>
                                                <th width="10%">No Sertifikat</th>
                                                <th width="9%">Tgl Terbit</th>
                                                <th width="9%">Tgl Expired</th>
                                                <th width="10%" class="text-center">Status</th>
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
        </section>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        
        .btn-export {
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
                order: [[5, 'asc']], // Order by expired_date
                ajax: {
                    url: "{{ route('expired_sertifikat.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.filter_expired = $('#filter_expired').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_klinik',
                        name: 'nama_klinik',
                        render: function(data, type, row) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return '<div class="font-weight-bold">' + data + '</div>' +
                                   '<small class="text-muted">' + (row.no_anggota || 'No anggota belum ada') + '</small>';
                        }
                    },
                    {
                        data: 'kota',
                        name: 'kota',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'no_sertifikat',
                        name: 'no_sertifikat',
                        render: function(data) {
                            return data || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'dari',
                        name: 'dari',
                        className: 'text-center'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date',
                        className: 'text-center'
                    },
                    {
                        data: 'status_expired',
                        name: 'status_expired',
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<button class="btn btn-sm btn-outline-primary" onclick="viewDetail(' + row.id_anggota + ')" title="Lihat Detail">' +
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

        function exportData(type) {
            const filter = $('#filter_expired').val();
            const search = $('#search').val();
            
            // Show loading
            $('#loadingModal').modal('show');
            $('#export-loading').show();
            
            // Disable export buttons
            $('.btn-export').prop('disabled', true);
            
            // Build URL with parameters
            let url = '';
            if (type === 'excel') {
                url = "{{ route('expired_sertifikat.export.excel') }}";
            } else {
                url = "{{ route('expired_sertifikat.export.pdf') }}";
            }
            
            const params = new URLSearchParams();
            if (filter) params.append('filter_expired', filter);
            if (search) params.append('search', search);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            // Create invisible iframe for download
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);
            
            // Hide loading after delay and re-enable buttons
            setTimeout(function() {
                $('#loadingModal').modal('hide');
                $('#export-loading').hide();
                $('.btn-export').prop('disabled', false);
                document.body.removeChild(iframe);
                
                // Show success message
                showNotification('success', 'Export berhasil! File sedang didownload.');
            }, 3000);
        }

        function updateStatistics() {
            // Get current table data
            const info = table.page.info();
            const data = table.rows({page: 'current'}).data();
            
            let stats = {
                total: info.recordsDisplay,
                expired: 0,
                hari_ini: 0,
                besok: 0,
                seminggu: 0,
                aman: 0
            };

            // Count status for current page (basic count)
            data.each(function(row) {
                if (row.expired_date) {
                    const today = moment();
                    const expiredDate = moment(row.expired_date, 'DD-MM-YYYY');
                    const diffDays = expiredDate.diff(today, 'days');
                    
                    if (diffDays < 0) {
                        stats.expired++;
                    } else if (diffDays === 0) {
                        stats.hari_ini++;
                    } else if (diffDays === 1) {
                        stats.besok++;
                    } else if (diffDays <= 7) {
                        stats.seminggu++;
                    } else {
                        stats.aman++;
                    }
                }
            });

            // Update stat cards
            $('#stat-total').text(stats.total);
            $('#stat-expired').text(stats.expired);
            $('#stat-hari-ini').text(stats.hari_ini);
            $('#stat-besok').text(stats.besok);
            $('#stat-seminggu').text(stats.seminggu);
            $('#stat-aman').text(stats.aman);
        }

        function updateFilterInfo() {
            const filter = $('#filter_expired').val();
            const filterText = $('#filter_expired option:selected').text();
            
            if (filter) {
                console.log('Filter aktif: ' + filterText);
            } else {
                console.log('Menampilkan semua data');
            }
        }

        function viewDetail(id) {
            // Implementasi untuk melihat detail
            window.open('{{ route("verifikasi_anggota.verify", ":id") }}'.replace(':id', id), '_blank');
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