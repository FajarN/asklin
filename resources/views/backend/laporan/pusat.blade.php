@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Data Anggota ASKLIN</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Laporan Pusat</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Column Selection Card -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">
                                            <i class="fas fa-columns"></i> Pilih Kolom yang Ingin Ditampilkan
                                        </h4>
                                        <small class="text-muted">Centang kolom yang ingin ditampilkan di tabel dan export</small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-select-all">
                                            <i class="fas fa-check-square"></i> Pilih Semua
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-deselect-all">
                                            <i class="fas fa-square"></i> Kosongkan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-apply-columns">
                                            <i class="fas fa-sync"></i> Terapkan Pilihan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-left-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Catatan:</strong> Pilih minimal satu kolom untuk menampilkan data. Tabel dan export akan mengikuti kolom yang dipilih.
                                </div>
                                
                                <!-- Column Selection Grid -->
                                <div class="row" id="column-selection">
                                    @foreach($availableColumns as $key => $column)
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input column-checkbox" type="checkbox" 
                                                       value="{{ $key }}" id="col_{{ $key }}"
                                                       @if(in_array($key, ['id', 'no_anggota', 'nama_klinik', 'name', 'provinsi'])) checked @endif>
                                                <label class="form-check-label" for="col_{{ $key }}">
                                                    <i class="fas fa-{{ $column['type'] == 'date' ? 'calendar' : ($column['type'] == 'number' ? 'hashtag' : 'text-width') }}"></i>
                                                    {{ $column['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-success" id="selected-count-info" style="display: none;">
                                            <i class="fas fa-check-circle"></i>
                                            <span id="selected-count">0</span> kolom dipilih dari {{ count($availableColumns) }} kolom tersedia
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter & Export Card -->
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">
                                            <i class="fas fa-filter"></i> Filter & Export Data
                                        </h4>
                                        <small class="text-muted">Filter data dan export sesuai kolom yang dipilih</small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success btn-sm btn-export" id="btn-export-excel" disabled>
                                                <i class="fas fa-file-excel"></i> Export Excel
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-export" id="btn-export-pdf" disabled>
                                                <i class="fas fa-file-pdf"></i> Export PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Filter Controls -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="provinsi" class="form-label font-weight-bold">Provinsi:</label>
                                        <select id="provinsi" class="form-control">
                                            <option value="">-- Semua Provinsi --</option>
                                            @foreach ($provinsi as $i)
                                                <option value="{{ $i->code }}">{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="search" class="form-label font-weight-bold">Pencarian:</label>
                                        <input type="text" class="form-control" placeholder="Cari nama klinik, no anggota, kota..." id="search">
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

                                <!-- Loading Indicator -->
                                <div id="export-loading" class="alert alert-warning border-left-warning" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-warning mr-3" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div>
                                            <strong>Sedang memproses export data...</strong><br>
                                            <small>Mohon tunggu, proses ini mungkin memakan waktu beberapa detik.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warning ketika belum pilih kolom -->
                                <div id="no-columns-warning" class="alert alert-warning border-left-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Pilih Kolom Terlebih Dahulu!</strong> 
                                    Centang minimal satu kolom di atas untuk menampilkan data tabel.
                                </div>

                                <!-- Data Table -->
                                <div class="table-responsive" id="table-container" style="display: none;">
                                    <table class="table table-striped table-hover table-sm" id="table">
                                        <thead class="bg-primary text-white" id="table-header">
                                            <!-- Header akan di-generate dinamis -->
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <style>
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        
        .form-check {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-check:hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }
        
        .form-check-input:checked ~ .form-check-label {
            color: #495057;
            font-weight: 600;
        }
        
        .btn-export:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        #column-selection .form-check {
            margin-bottom: 8px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
        let table;
        let searchTimeout;
        let selectedColumns = ['id', 'no_anggota', 'nama_klinik', 'name', 'provinsi']; // Default columns
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            updateSelectedCount();
            updateTableVisibility();

            // Column selection events
            $('.column-checkbox').change(function() {
                updateSelectedColumns();
                updateSelectedCount();
                updateTableVisibility();
            });

            $('#btn-select-all').click(function() {
                $('.column-checkbox').prop('checked', true);
                updateSelectedColumns();
                updateSelectedCount();
                updateTableVisibility();
            });

            $('#btn-deselect-all').click(function() {
                $('.column-checkbox').prop('checked', false);
                updateSelectedColumns();
                updateSelectedCount();
                updateTableVisibility();
            });

            $('#btn-apply-columns').click(function() {
                if (selectedColumns.length > 0) {
                    initializeDataTable();
                } else {
                    showNotification('warning', 'Pilih minimal satu kolom untuk ditampilkan!');
                }
            });

            // Filter events
            $("#search").keyup(function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if (table) table.draw();
                }, 500);
            });

            $('#provinsi').change(function() {
                if (table) table.draw();
            });

            $('#btn-refresh').click(function() {
                if (table) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                    table.ajax.reload(function() {
                        $('#btn-refresh').html('<i class="fas fa-sync-alt"></i> Refresh Data');
                    });
                }
            });

            // Export events
            $('#btn-export-excel').click(function() {
                exportData('excel');
            });

            $('#btn-export-pdf').click(function() {
                exportData('pdf');
            });

            // Initialize table with default columns
            if (selectedColumns.length > 0) {
                initializeDataTable();
            }
        });

        function updateSelectedColumns() {
            selectedColumns = [];
            $('.column-checkbox:checked').each(function() {
                selectedColumns.push($(this).val());
            });
        }

        function updateSelectedCount() {
            const count = $('.column-checkbox:checked').length;
            $('#selected-count').text(count);
            
            if (count > 0) {
                $('#selected-count-info').show();
                $('.btn-export').prop('disabled', false);
            } else {
                $('#selected-count-info').hide();
                $('.btn-export').prop('disabled', true);
            }
        }

        function updateTableVisibility() {
            if (selectedColumns.length > 0) {
                $('#no-columns-warning').hide();
                $('#table-container').show();
            } else {
                $('#no-columns-warning').show();
                $('#table-container').hide();
                if (table) {
                    table.destroy();
                    table = null;
                }
            }
        }

        function initializeDataTable() {
            // Destroy existing table if exists
            if (table) {
                table.destroy();
            }

            // Generate dynamic header
            generateTableHeader();

            // Generate dynamic columns config
            const columns = generateColumnsConfig();

            // Initialize DataTable
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 50,
                order: [[1, 'asc']], // Order by first data column
                ajax: {
                    url: "{{ route('laporan_pusat.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.provinsi = $('#provinsi').val();
                        d.selected_columns = selectedColumns.join(',');
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', error);
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            showNotification('error', xhr.responseJSON.error);
                        }
                    }
                },
                columns: columns,
                drawCallback: function(settings) {
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
        }

        function generateTableHeader() {
            const availableColumns = @json($availableColumns);
            let headerHtml = '<tr><th width="4%" class="text-center">No</th>';
            
            selectedColumns.forEach(function(column) {
                const label = availableColumns[column] ? availableColumns[column].label : column;
                headerHtml += `<th>${label}</th>`;
            });
            
            headerHtml += '</tr>';
            $('#table-header').html(headerHtml);
        }

        function generateColumnsConfig() {
            const columns = [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ];

            selectedColumns.forEach(function(column) {
                columns.push({
                    data: column,
                    name: column,
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') {
                            return '<span class="text-muted">-</span>';
                        }
                        
                        // Special formatting for certain columns
                        switch (column) {
                            case 'status':
                                return getStatusBadge(data);
                            case 'status_pembayaran':
                                return data;
                            case 'email':
                                return data ? `<a href="mailto:${data}">${data}</a>` : '-';
                            case 'tlf':
                            case 'tlf_klinik':
                                return data ? `<a href="tel:${data}">${data}</a>` : '-';
                            default:
                                return data;
                        }
                    }
                });
            });

            return columns;
        }

        function getStatusBadge(status) {
            const statusConfig = {
                'approved': 'success',
                'waiting': 'warning',
                'proses': 'info',
                'ditolak pusat': 'danger',
                'Lunas': 'success',
                'Terverifikasi Cabang': 'success',
                'Terverifikasi Daerah': 'success',
                'Verifikasi Sekjen': 'primary'
            };
            
            const badgeClass = statusConfig[status] || 'secondary';
            return `<span class="badge badge-${badgeClass}">${status}</span>`;
        }

        function exportData(type) {
            if (selectedColumns.length === 0) {
                showNotification('warning', 'Pilih minimal satu kolom untuk export!');
                return;
            }

            const filter = $('#provinsi').val();
            const search = $('#search').val();
            
            // Show loading
            $('#loadingModal').modal('show');
            $('#export-loading').show();
            
            // Disable export buttons
            $('.btn-export').prop('disabled', true);
            
            // Build URL with parameters
            let url = '';
            if (type === 'excel') {
                url = "{{ route('laporan_pusat.export.excel') }}";
            } else {
                url = "{{ route('laporan_pusat.export.pdf') }}";
            }
            
            const params = new URLSearchParams();
            if (filter) params.append('provinsi', filter);
            if (search) params.append('search', search);
            params.append('selected_columns', selectedColumns.join(','));
            
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
                updateSelectedCount(); // This will re-enable buttons if columns selected
                document.body.removeChild(iframe);
                
                // Show success message
                showNotification('success', `Export ${type.toUpperCase()} berhasil! File sedang didownload.`);
            }, 3000);
        }

        function showNotification(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 
                             type === 'warning' ? 'alert-warning' : 'alert-danger';
            const icon = type === 'success' ? 'fas fa-check-circle' : 
                        type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-exclamation-circle';
            
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
    </script>
@endpush