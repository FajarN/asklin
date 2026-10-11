@extends('layouts.backend.layout')

@section('title', 'History SK')
@section('page_title', 'History Surat Keputusan (SK)')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Surat Keputusan', 'url' => route('sk_pengurus.index')],
        ['label' => 'History SK'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-history text-red-600"></i>
                <span>History Surat Keputusan (SK)</span>
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Repository digital riwayat berkas dokumen SK kepengurusan terdahulu (dokumen Word atau PDF) yang pernah terbit dengan pelacakan masa berlaku (aktif / expired).
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary btn-sm" id="btnUploadModal">
                <i class="fas fa-cloud-upload-alt mr-1"></i>
                <span>Upload History SK</span>
            </button>
            <a href="{{ route('sk_pengurus.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-file-signature mr-1"></i>
                <span>SK Sekarang</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <div class="card p-3.5 border-l-4 border-l-sky-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total History SK</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white" id="stat-total">{{ $totalSk }}</span>
                <i class="fas fa-history text-sky-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3.5 border-l-4 border-l-red-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">SK Pengurus Pusat</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-red-600 dark:text-red-400">{{ $totalPusat }}</span>
                <i class="fas fa-landmark text-red-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3.5 border-l-4 border-l-amber-500 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">SK Daerah & Cabang</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">{{ $totalDaerah + $totalCabang }}</span>
                <i class="fas fa-map-marked-alt text-amber-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3.5 border-l-4 border-l-rose-500 shadow-sm bg-rose-50/15 dark:bg-rose-950/10">
            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-500 block mb-1">Sudah Expired</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-rose-600 dark:text-rose-400">{{ $totalExpired }}</span>
                <i class="fas fa-history text-rose-500 text-lg opacity-80"></i>
            </div>
        </div>

        <div class="card p-3.5 border-l-4 border-l-emerald-500 shadow-sm bg-emerald-50/15 dark:bg-emerald-950/10">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block mb-1">Masih Aktif</span>
            <div class="flex items-center justify-between">
                <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $totalAktif }}</span>
                <i class="fas fa-check-circle text-emerald-500 text-lg opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card p-4 shadow-sm border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">
                <i class="fas fa-filter text-red-500"></i>
                <span>Filter & Pencarian History SK</span>
            </div>
            <button type="button" id="btnResetFilter" class="text-xs text-slate-500 hover:text-red-600 dark:hover:text-red-400 transition flex items-center gap-1">
                <i class="fas fa-undo-alt text-[10px]"></i> Reset Filter
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Tingkatan</label>
                <select id="filter_tingkatan" class="form-control text-xs w-full">
                    <option value="">Semua Tingkatan</option>
                    <option value="pusat">Pengurus Pusat (PP)</option>
                    <option value="daerah">Pengurus Daerah (Pengda)</option>
                    <option value="cabang">Pengurus Cabang (Pengcab)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Status Masa Berlaku</label>
                <select id="filter_status_masa" class="form-control text-xs w-full">
                    <option value="">Semua Status (Aktif & Expired)</option>
                    <option value="aktif">Masih Aktif</option>
                    <option value="expired">Sudah Expired</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Provinsi</label>
                <select id="filter_provinsi" class="form-control text-xs w-full">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsis as $p)
                        <option value="{{ $p->code }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Kota / Kabupaten</label>
                <select id="filter_kota" class="form-control text-xs w-full">
                    <option value="">Semua Kota/Kabupaten</option>
                </select>
            </div>
        </div>
    </div>

    <!-- DataTables Table Card -->
    <div class="card shadow-sm border border-slate-200/80 dark:border-slate-800">
        <div class="card-header flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-table text-slate-400"></i>
                <span>Daftar Dokumen History SK</span>
            </h3>
            <span class="text-[11px] text-slate-400">Word (.doc, .docx) & PDF (.pdf)</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped w-full text-xs" id="table-sk-lama">
                    <thead>
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th>Judul & Nomor SK</th>
                            <th width="140">Tingkatan</th>
                            <th>Cakupan Wilayah</th>
                            <th width="190">Periode & Status</th>
                            <th width="130">Dokumen Berkas</th>
                            <th width="100" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Upload / Edit History SK -->
<div id="modalSkLama" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2" id="modalTitle">
                    <i class="fas fa-cloud-upload-alt text-red-600"></i>
                    <span>Upload History SK</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5" id="modalSubtitle">
                    Unggah berkas dokumen history SK kepengurusan (Word atau PDF).
                </p>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition p-1 rounded-lg" id="btnCloseModal">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="formSkLama" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="id" id="sk_id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tingkatan Kepengurusan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Tingkatan SK <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkatan" id="modal_tingkatan" class="form-control text-xs w-full" required>
                        <option value="pusat">Pengurus Pusat (Nasional)</option>
                        <option value="daerah">Pengurus Daerah (Provinsi)</option>
                        <option value="cabang">Pengurus Cabang (Kabupaten/Kota)</option>
                    </select>
                </div>

                <!-- Nomor SK (Opsional) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Nomor SK (Bila Ada)
                    </label>
                    <input type="text" name="no_sk" id="modal_no_sk" class="form-control text-xs w-full" placeholder="Contoh: 012/SK/PP-ASKLIN/III/2019">
                </div>
            </div>

            <!-- Judul SK -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Judul SK / Perihal <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="judul" id="modal_judul" class="form-control text-xs w-full" placeholder="Contoh: SK Pengesahan Susunan Pengurus Daerah ASKLIN Jawa Barat Masa Bakti 2018 - 2023" required>
            </div>

            <!-- Wilayah (Conditional: Daerah / Cabang) -->
            <div id="wrapper_wilayah" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800">
                <div id="col_provinsi">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Provinsi <span class="text-rose-500">*</span>
                    </label>
                    <select name="id_provinsi" id="modal_provinsi" class="form-control text-xs w-full">
                        <option value="">== Pilih Provinsi ==</option>
                        @foreach($provinsis as $p)
                            <option value="{{ $p->code }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="col_kota" class="hidden">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Kabupaten / Kota <span class="text-rose-500">*</span>
                    </label>
                    <select name="id_kota" id="modal_kota" class="form-control text-xs w-full">
                        <option value="">== Pilih Kota/Kabupaten ==</option>
                    </select>
                </div>
            </div>

            <!-- Periode & Tahun Masa Bakti -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Periode Masa Bakti <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="periode" id="modal_periode" class="form-control text-xs w-full" placeholder="Contoh: 2018 - 2023" required>
                    <span class="text-[10px] text-slate-400">Contoh format: 2018 - 2023</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Tahun Mulai
                    </label>
                    <input type="number" name="tahun_mulai" id="modal_tahun_mulai" class="form-control text-xs w-full" placeholder="2018" min="1990" max="2099">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Tahun Selesai (Expired)
                    </label>
                    <input type="number" name="tahun_selesai" id="modal_tahun_selesai" class="form-control text-xs w-full" placeholder="2023" min="1990" max="2099">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tanggal Penetapan SK -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Tanggal Penetapan SK (Bila Ada)
                    </label>
                    <input type="date" name="tgl_penetapan" id="modal_tgl_penetapan" class="form-control text-xs w-full">
                </div>

                <!-- Upload File Berkas -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        File Dokumen SK <span class="text-rose-500" id="file_required_marker">*</span>
                    </label>
                    <input type="file" name="file_sk" id="modal_file_sk" class="form-control text-xs w-full" accept=".pdf,.doc,.docx">
                    <span class="text-[10px] text-slate-400 block mt-1" id="file_help_text">
                        Boleh PDF (.pdf) bertanda tangan atau Word (.doc, .docx) sebelum tanda tangan. Maks. 30MB.
                    </span>
                    <div id="file_current_preview" class="hidden mt-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> <span id="current_file_name"></span>
                    </div>
                </div>
            </div>

            <!-- Catatan / Keterangan -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    Catatan / Keterangan Tambahan
                </label>
                <textarea name="keterangan" id="modal_keterangan" rows="2" class="form-control text-xs w-full" placeholder="Keterangan singkat terkait SK ini (opsional)..."></textarea>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" class="btn btn-secondary btn-sm" id="btnCancelModal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-sm flex items-center gap-1.5" id="btnSubmitForm">
                    <i class="fas fa-save"></i>
                    <span id="btnSubmitText">Simpan Arsip</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable History SK
    const table = $('#table-sk-lama').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('sk_lama.list') }}",
            data: function (d) {
                d.tingkatan = $('#filter_tingkatan').val();
                d.status_masa = $('#filter_status_masa').val();
                d.id_provinsi = $('#filter_provinsi').val();
                d.id_kota = $('#filter_kota').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { 
                data: 'judul', 
                name: 'judul',
                render: function(data, type, row) {
                    let noSk = row.no_sk ? `<div class="text-[11px] font-mono text-slate-500 dark:text-slate-400">${row.no_sk}</div>` : '';
                    let tgl = row.tgl_penetapan ? `<div class="text-[10px] text-slate-400 mt-0.5"><i class="far fa-calendar-alt mr-1"></i> Ditetapkan: ${row.tgl_penetapan}</div>` : '';
                    return `<div>
                                <div class="font-bold text-slate-800 dark:text-white text-xs leading-snug">${data}</div>
                                ${noSk}
                                ${tgl}
                            </div>`;
                }
            },
            { data: 'tingkatan_badge', name: 'tingkatan' },
            { data: 'wilayah_display', name: 'wilayah_display', orderable: false },
            { data: 'periode_info', name: 'periode' },
            { data: 'file_display', name: 'file_display', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
            search: "Cari SK:",
            searchPlaceholder: "Ketik kata kunci...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ history SK",
            infoEmpty: "Tidak ada data history SK",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    // Trigger Filter
    $('#filter_tingkatan, #filter_status_masa').on('change', function() {
        table.draw();
    });

    $('#filter_provinsi').on('change', function() {
        let provId = $(this).val();
        $('#filter_kota').html('<option value="">Semua Kota/Kabupaten</option>');
        
        if (provId) {
            $.ajax({
                url: "{{ route('getKota') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id_provinsi: provId
                },
                success: function(response) {
                    $('#filter_kota').html(response);
                    table.draw();
                }
            });
        } else {
            table.draw();
        }
    });

    $('#filter_kota').on('change', function() {
        table.draw();
    });

    $('#btnResetFilter').on('click', function() {
        $('#filter_tingkatan').val('');
        $('#filter_status_masa').val('');
        $('#filter_provinsi').val('');
        $('#filter_kota').html('<option value="">Semua Kota/Kabupaten</option>').val('');
        table.draw();
    });

    // Auto calculate tahun dari periode input
    $('#modal_periode').on('blur change keyup', function() {
        let val = $(this).val().trim();
        let match = val.match(/(\d{4})\s*[-–—/]\s*(\d{4})/);
        if (match) {
            if (!$('#modal_tahun_mulai').val()) {
                $('#modal_tahun_mulai').val(match[1]);
            }
            if (!$('#modal_tahun_selesai').val()) {
                $('#modal_tahun_selesai').val(match[2]);
            }
        } else {
            let singleMatch = val.match(/(\d{4})/);
            if (singleMatch && !$('#modal_tahun_selesai').val()) {
                $('#modal_tahun_selesai').val(singleMatch[1]);
            }
        }
    });

    // Toggle Wilayah berdasarkan tingkatan pada Modal
    function toggleWilayahForm(tingkatan, selectedProv = null, selectedKota = null) {
        if (tingkatan === 'pusat') {
            $('#wrapper_wilayah').addClass('hidden');
            $('#col_provinsi').addClass('hidden');
            $('#col_kota').addClass('hidden');
            $('#modal_provinsi').val('').removeAttr('required');
            $('#modal_kota').val('').removeAttr('required');
        } else if (tingkatan === 'daerah') {
            $('#wrapper_wilayah').removeClass('hidden');
            $('#col_provinsi').removeClass('hidden');
            $('#col_kota').addClass('hidden');
            $('#modal_provinsi').attr('required', 'required');
            $('#modal_kota').val('').removeAttr('required');
            if (selectedProv) {
                $('#modal_provinsi').val(selectedProv);
            }
        } else if (tingkatan === 'cabang') {
            $('#wrapper_wilayah').removeClass('hidden');
            $('#col_provinsi').removeClass('hidden');
            $('#col_kota').removeClass('hidden');
            $('#modal_provinsi').attr('required', 'required');
            $('#modal_kota').attr('required', 'required');
            if (selectedProv) {
                $('#modal_provinsi').val(selectedProv);
                loadCitiesForModal(selectedProv, selectedKota);
            }
        }
    }

    $('#modal_tingkatan').on('change', function() {
        toggleWilayahForm($(this).val());
    });

    function loadCitiesForModal(provId, selectedKota = null) {
        if (!provId) {
            $('#modal_kota').html('<option value="">== Pilih Kota/Kabupaten ==</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getKota') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id_provinsi: provId
            },
            success: function(response) {
                $('#modal_kota').html(response);
                if (selectedKota) {
                    $('#modal_kota').val(selectedKota);
                }
            }
        });
    }

    $('#modal_provinsi').on('change', function() {
        loadCitiesForModal($(this).val());
    });

    // Modal Controls
    function openModal() {
        $('#modalSkLama').removeClass('hidden');
        $('body').addClass('overflow-hidden');
    }

    function closeModal() {
        $('#modalSkLama').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        $('#formSkLama')[0].reset();
        $('#sk_id').val('');
        $('#file_current_preview').addClass('hidden');
        $('#file_required_marker').removeClass('hidden');
        $('#modal_file_sk').attr('required', 'required');
        $('#modalTitle span').text('Upload History SK');
        $('#modalSubtitle').text('Unggah berkas dokumen history SK kepengurusan (Word atau PDF).');
        $('#btnSubmitText').text('Simpan Data');
        toggleWilayahForm('pusat');
    }

    $('#btnUploadModal').on('click', function() {
        closeModal();
        openModal();
    });

    $('#btnCloseModal, #btnCancelModal').on('click', function() {
        closeModal();
    });

    // Submit Form (Store / Update)
    $('#formSkLama').on('submit', function(e) {
        e.preventDefault();

        let id = $('#sk_id').val();
        let url = id ? "{{ url('sk-lama/update') }}/" + id : "{{ route('sk_lama.store') }}";
        let formData = new FormData(this);

        $('#btnSubmitForm').attr('disabled', true).addClass('opacity-70 cursor-not-allowed');
        $('#btnSubmitText').text('Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#btnSubmitForm').removeAttr('disabled').removeClass('opacity-70 cursor-not-allowed');
                $('#btnSubmitText').text(id ? 'Simpan Perubahan' : 'Simpan Data');

                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    closeModal();
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Terjadi kesalahan sistem.'
                    });
                }
            },
            error: function(xhr) {
                $('#btnSubmitForm').removeAttr('disabled').removeClass('opacity-70 cursor-not-allowed');
                $('#btnSubmitText').text(id ? 'Simpan Perubahan' : 'Simpan Data');

                let errMsg = 'Gagal menyimpan data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi / Kesalahan',
                    text: errMsg
                });
            }
        });
    });

    // Edit Handler
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        let btn = $(this);
        btn.addClass('opacity-50 pointer-events-none');

        $.ajax({
            url: "{{ url('sk-lama/edit') }}/" + id,
            type: 'GET',
            success: function(res) {
                btn.removeClass('opacity-50 pointer-events-none');
                if (res.success) {
                    let d = res.data;
                    $('#sk_id').val(d.id);
                    $('#modal_judul').val(d.judul);
                    $('#modal_no_sk').val(d.no_sk || '');
                    $('#modal_tingkatan').val(d.tingkatan);
                    $('#modal_periode').val(d.periode);
                    $('#modal_tahun_mulai').val(d.tahun_mulai || '');
                    $('#modal_tahun_selesai').val(d.tahun_selesai || '');
                    $('#modal_tgl_penetapan').val(d.tgl_penetapan ? d.tgl_penetapan.substring(0, 10) : '');
                    $('#modal_keterangan').val(d.keterangan || '');

                    // File opsional saat edit
                    $('#file_required_marker').addClass('hidden');
                    $('#modal_file_sk').removeAttr('required');
                    if (d.file_nama_asli) {
                        $('#current_file_name').text('File aktif: ' + d.file_nama_asli);
                        $('#file_current_preview').removeClass('hidden');
                    }

                    // Tampilkan Wilayah
                    toggleWilayahForm(d.tingkatan, d.id_provinsi, d.id_kota);

                    $('#modalTitle span').text('Edit History SK');
                    $('#modalSubtitle').text('Perbarui metadata dokumen atau ganti berkas SK.');
                    $('#btnSubmitText').text('Simpan Perubahan');

                    openModal();
                }
            },
            error: function() {
                btn.removeClass('opacity-50 pointer-events-none');
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal mengambil data history SK.'
                });
            }
        });
    });

    // Delete Handler (Tombol icon saja sesuai instruksi)
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let judul = $(this).data('judul');

        Swal.fire({
            title: 'Hapus History SK?',
            text: `Yakin ingin menghapus dokumen "${judul}"? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('sk-lama/delete') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Gagal menghapus data dari server.'
                        });
                    }
                });
            }
        });
    });

});
</script>
@endpush
