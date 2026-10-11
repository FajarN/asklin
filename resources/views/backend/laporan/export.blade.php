@extends('layouts.backend.layout')

@section('title', 'Laporan Keseluruhan (Export) ASKLIN')
@section('page_title', 'Laporan Keseluruhan (Export)')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Laporan & Rekapitulasi'],
        ['label' => 'Laporan Keseluruhan (Export)'],
    ]" />

    <!-- Page Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-2">
                    <i class="fas fa-file-export"></i> Pusat Ekspor Data Nasional
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Laporan Keseluruhan & Ekspor Fleksibel
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
                    Kustomisasi pemilihan kolom, filter wilayah (Provinsi & Kabupaten/Kota), serta validasi status persetujuan keanggotaan berdasarkan nomor registrasi anggota (NRA).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan_pusat.index') }}" class="btn btn-outline-light btn-sm text-xs">
                    <i class="fas fa-chart-line mr-1"></i> Dashboard Pusat
                </a>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm text-xs">
                    <i class="fas fa-arrow-left mr-1"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- 1. Column Selection Card -->
    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full">
                <div>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                        <i class="fas fa-columns text-indigo-600 dark:text-indigo-400"></i>
                        Pilih Kolom Data yang Ingin Ditampilkan & Diekspor
                    </h5>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Centang kolom yang ingin dimuat ke dalam tabel preview dan dokumen ekspor Excel/PDF
                    </p>
                </div>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" class="btn btn-xs btn-outline-primary" id="btn-select-all">
                        <i class="fas fa-check-double mr-1"></i> Pilih Semua
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary" id="btn-deselect-all">
                        <i class="fas fa-times mr-1"></i> Kosongkan
                    </button>
                    <button type="button" class="btn btn-xs btn-primary font-semibold" id="btn-apply-columns">
                        <i class="fas fa-sync-alt mr-1"></i> Terapkan Kolom
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-4 sm:p-5">
            <div class="alert alert-info py-2 px-3 text-xs mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-base"></i>
                <span><strong>Catatan:</strong> Status persetujuan anggota diverifikasi langsung dari <strong>kehadiran Nomor Anggota (NRA)</strong> untuk keakuratan data 100%.</span>
            </div>

            <!-- Grid Checkbox Kolom -->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3" id="column-selection">
                @foreach($availableColumns as $key => $column)
                    <div class="col">
                        <div class="form-check p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 border border-slate-100 dark:border-slate-800 transition">
                            <input class="form-check-input column-checkbox" type="checkbox" 
                                   value="{{ $key }}" id="col_{{ $key }}"
                                   @if(in_array($key, ['no_anggota', 'nama_klinik', 'status_approval', 'provinsi', 'name', 'status_pembayaran', 'tgl_daftar', 'nama_pemilik_klinik', 'tlf'])) checked @endif>
                            <label class="form-check-label text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer ml-1" for="col_{{ $key }}">
                                <i class="fas fa-{{ $column['type'] == 'date' || $column['type'] == 'datetime' ? 'calendar-alt' : ($column['type'] == 'number' ? 'hashtag' : 'font') }} text-[10px] text-slate-400 mr-1"></i>
                                {{ $column['label'] }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <span id="selected-count-badge" class="badge badge-light border text-xs text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200">
                    <i class="fas fa-check-circle mr-1"></i> <span id="selected-count">0</span> kolom dipilih dari {{ count($availableColumns) }} kolom tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Filter & Export Data Card -->
    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full">
                <div>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                        <i class="fas fa-filter text-indigo-600 dark:text-indigo-400"></i>
                        Filter Wilayah & Ekspor Data
                    </h5>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Filter berdasarkan provinsi, kabupaten/kota, dan status persetujuan nomor anggota
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="btn btn-success btn-sm font-medium shadow-sm" id="btn-export-excel">
                        <i class="fas fa-file-excel mr-1.5"></i> Export Excel (.xlsx)
                    </button>
                    <button type="button" class="btn btn-danger btn-sm font-medium shadow-sm" id="btn-export-pdf">
                        <i class="fas fa-file-pdf mr-1.5"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-4 sm:p-5">
            <!-- Filter Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
                <!-- 1. Provinsi -->
                <div>
                    <label for="filter_provinsi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Provinsi:
                    </label>
                    <select id="filter_provinsi" class="form-control form-control-sm text-xs">
                        <option value="">-- Semua Provinsi --</option>
                        @foreach ($provinsi as $p)
                            <option value="{{ $p->code }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Kabupaten / Kota (Chained) -->
                <div>
                    <label for="filter_kota" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Kabupaten / Kota:
                    </label>
                    <select id="filter_kota" class="form-control form-control-sm text-xs">
                        <option value="">-- Semua Kab/Kota --</option>
                    </select>
                </div>

                <!-- 3. Status Approval (Berbasis No. Anggota) -->
                <div>
                    <label for="filter_status_approval" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Status Persetujuan (NRA):
                    </label>
                    <select id="filter_status_approval" class="form-control form-control-sm text-xs">
                        <option value="">-- Semua Status Persetujuan --</option>
                        <option value="approved">Disetujui (Memiliki No. Anggota)</option>
                        <option value="belum_approved">Belum Disetujui (Tanpa No. Anggota)</option>
                    </select>
                </div>

                <!-- 4. Status Pembayaran -->
                <div>
                    <label for="filter_status_pembayaran" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Status Iuran:
                    </label>
                    <select id="filter_status_pembayaran" class="form-control form-control-sm text-xs">
                        <option value="">-- Semua Status Iuran --</option>
                        <option value="1">Lunas</option>
                        <option value="0">Belum Lunas</option>
                    </select>
                </div>

                <!-- 5. Pencarian & Tombol Aksi -->
                <div>
                    <label for="filter_search" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Pencarian Kata Kunci:
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control text-xs" placeholder="Klinik, pemilik, tlf..." id="filter_search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="btn-filter-apply" title="Terapkan Filter">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-filter-reset" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Alert -->
            <div id="export-loading" class="alert alert-warning py-2 px-3 text-xs mb-3" style="display: none;">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-warning mr-2" role="status"></div>
                    <div>
                        <strong>Sedang memproses ekspor data...</strong> Mohon tunggu beberapa saat.
                    </div>
                </div>
            </div>

            <!-- No Columns Warning -->
            <div id="no-columns-warning" class="alert alert-warning py-3 text-xs" style="display: none;">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                <strong>Pilih Minimal Satu Kolom!</strong> Silakan centang kolom di bagian atas, lalu klik <strong>Terapkan Kolom</strong>.
            </div>

            <!-- Data Table Container -->
            <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800" id="table-container">
                <table class="table table-hover table-sm text-xs mb-0" id="export-table" style="width: 100%;">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs" id="table-header">
                        <!-- Dynamic TH Generated via JavaScript -->
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 p-3 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                <i class="fas fa-shield-alt text-emerald-600 mr-1"></i> Modul Laporan Ekspor Nasional ASKLIN
            </div>
            <div>
                Total Rekor Terfilter: <strong id="total-records" class="text-slate-800 dark:text-white">-</strong> data
            </div>
        </div>
    </div>

</div>

<!-- Loading Modal -->
<div class="modal fade" id="exportLoadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <h5 class="modal-title font-bold text-slate-800 mb-1">Sedang Menyiapkan Berkas Ekspor</h5>
                <p class="text-xs text-slate-500 mb-0">Sistem sedang merangkum dan mengonversi data sesuai filter aktif...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let dataTable = null;
    let availableColumns = @json($availableColumns);

    // Hitung jumlah kolom terpilih
    function updateSelectedCount() {
        let count = $('.column-checkbox:checked').length;
        $('#selected-count').text(count);
        if (count === 0) {
            $('#no-columns-warning').show();
            $('#table-container').hide();
            $('#btn-export-excel, #btn-export-pdf').prop('disabled', true);
        } else {
            $('#no-columns-warning').hide();
            $('#table-container').show();
            $('#btn-export-excel, #btn-export-pdf').prop('disabled', false);
        }
    }

    // Ambil array kolom yang dicentang
    function getSelectedColumns() {
        let cols = [];
        $('.column-checkbox:checked').each(function() {
            cols.push($(this).val());
        });
        return cols;
    }

    // Chained AJAX: Provinsi -> Kota/Kabupaten
    $('#filter_provinsi').on('change', function() {
        let provId = $(this).val();
        let $kotaSelect = $('#filter_kota');
        $kotaSelect.html('<option value="">-- Memuat Kota... --</option>');

        if (!provId) {
            $kotaSelect.html('<option value="">-- Semua Kab/Kota --</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getKota') }}",
            type: "POST",
            data: {
                id_provinsi: provId,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $kotaSelect.html(response);
            },
            error: function() {
                $kotaSelect.html('<option value="">-- Gagal Memuat Kota --</option>');
            }
        });
    });

    // Inisialisasi DataTable secara dinamis
    function initDataTable() {
        let selectedCols = getSelectedColumns();
        if (selectedCols.length === 0) {
            updateSelectedCount();
            return;
        }

        if ($.fn.DataTable.isDataTable('#export-table')) {
            dataTable.destroy();
            $('#export-table').empty();
        }

        // Susun HTML Header <tr><th>
        let theadHtml = '<tr><th style="width: 40px;" class="text-center">No</th>';
        let dtColumns = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' }];

        selectedCols.forEach(function(colKey) {
            let label = availableColumns[colKey] ? availableColumns[colKey].label : colKey;
            theadHtml += '<th>' + label + '</th>';
            dtColumns.push({
                data: colKey,
                name: colKey,
                orderable: true,
                searchable: true,
                defaultContent: '-'
            });
        });
        theadHtml += '</tr>';

        $('#export-table').html('<thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">' + theadHtml + '</thead><tbody></tbody>');

        dataTable = $('#export-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan_export.list') }}",
                data: function(d) {
                    d.selected_columns = selectedCols.join(',');
                    d.provinsi = $('#filter_provinsi').val();
                    d.kota = $('#filter_kota').val();
                    d.status_approval = $('#filter_status_approval').val();
                    d.status_pembayaran = $('#filter_status_pembayaran').val();
                    d.search = $('#filter_search').val();
                }
            },
            columns: dtColumns,
            order: [[1, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            },
            drawCallback: function(settings) {
                let info = settings.json;
                if (info) {
                    $('#total-records').text(info.recordsFiltered || 0);
                }
            }
        });
    }

    // Button: Pilih Semua
    $('#btn-select-all').on('click', function() {
        $('.column-checkbox').prop('checked', true);
        updateSelectedCount();
    });

    // Button: Kosongkan
    $('#btn-deselect-all').on('click', function() {
        $('.column-checkbox').prop('checked', false);
        updateSelectedCount();
    });

    // Button: Terapkan Kolom
    $('#btn-apply-columns').on('click', function() {
        updateSelectedCount();
        initDataTable();
    });

    // Button: Terapkan Filter
    $('#btn-filter-apply').on('click', function() {
        if (dataTable) {
            dataTable.ajax.reload();
        } else {
            initDataTable();
        }
    });

    // Enter pada input search
    $('#filter_search').on('keypress', function(e) {
        if (e.which === 13) {
            $('#btn-filter-apply').click();
        }
    });

    // Button: Reset Filter
    $('#btn-filter-reset').on('click', function() {
        $('#filter_provinsi').val('');
        $('#filter_kota').html('<option value="">-- Semua Kab/Kota --</option>');
        $('#filter_status_approval').val('');
        $('#filter_status_pembayaran').val('');
        $('#filter_search').val('');
        if (dataTable) {
            dataTable.ajax.reload();
        }
    });

    // Export Helper
    function triggerExport(url) {
        let selectedCols = getSelectedColumns();
        if (selectedCols.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Kolom',
                text: 'Centang minimal satu kolom untuk melakukan ekspor.'
            });
            return;
        }

        let params = new URLSearchParams({
            selected_columns: selectedCols.join(','),
            provinsi: $('#filter_provinsi').val(),
            kota: $('#filter_kota').val(),
            status_approval: $('#filter_status_approval').val(),
            status_pembayaran: $('#filter_status_pembayaran').val(),
            search: $('#filter_search').val()
        });

        $('#exportLoadingModal').modal('show');

        // Gunakan iframe atau direct window location untuk mengunduh berkas
        let downloadUrl = url + '?' + params.toString();
        
        let iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);

        setTimeout(function() {
            $('#exportLoadingModal').modal('hide');
        }, 3000);
    }

    // Export Excel
    $('#btn-export-excel').on('click', function() {
        triggerExport("{{ route('laporan_export.export.excel') }}");
    });

    // Export PDF
    $('#btn-export-pdf').on('click', function() {
        triggerExport("{{ route('laporan_export.export.pdf') }}");
    });

    // Inisialisasi awal
    updateSelectedCount();
    initDataTable();
});
</script>
@endpush

