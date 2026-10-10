@extends('layouts.backend.layout')

@section('title', 'Master Data Ketua Umum')
@section('page_title', 'Master Data Ketua Umum')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Master Data Ketua Umum ASKLIN
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Pengaturan Ketua Umum Pengurus Pusat aktif, tanda tangan digital resmi, dan arsip riwayat kepengurusan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            @can('master-ketua-umum-create')
            <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                <i class="fas fa-plus"></i>
                <span>Tambah Ketua Umum</span>
            </button>
            @endcan
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Active Ketua Umum Banner Card -->
    <div class="card border-l-4 border-l-emerald-500 shadow-sm bg-gradient-to-r from-emerald-50/50 via-white to-white dark:from-emerald-950/20 dark:via-slate-900 dark:to-slate-900">
        <div class="card-body p-4 sm:p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="badge badge-success text-[10px] uppercase font-bold tracking-wider px-2 py-0.5">
                                <i class="fas fa-check-circle mr-1"></i> Ketua Umum Menjabat Aktif
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                Periode: <strong class="text-slate-700 dark:text-slate-200">{{ $ketumAktif ? $ketumAktif->periode_mulai . ' - ' . $ketumAktif->periode_selesai : '-' }}</strong>
                            </span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white mt-1">
                            {{ $ketumAktif ? $ketumAktif->nama_lengkap : 'Belum Ada Ketua Umum Aktif' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $ketumAktif && $ketumAktif->keterangan ? $ketumAktif->keterangan : 'Pimpinan tertinggi Pengurus Pusat Asosiasi Klinik Indonesia (PP ASKLIN).' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-white dark:bg-slate-800/80 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 self-start md:self-auto">
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Tanda Tangan Digital</span>
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                            {{ $ketumAktif && $ketumAktif->file_ttd ? 'Tersedia & Terverifikasi' : 'Belum Ada TTD' }}
                        </span>
                    </div>
                    @if($ketumAktif && $ketumAktif->file_ttd && Storage::exists('public/ttd_ketum/' . $ketumAktif->file_ttd))
                        <div class="h-10 w-24 bg-white rounded border border-slate-200 p-1 flex items-center justify-center">
                            <img src="{{ asset('storage/ttd_ketum/' . $ketumAktif->file_ttd) }}" alt="TTD Ketum" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="h-10 w-24 bg-slate-100 dark:bg-slate-700 rounded border border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center text-[10px] text-slate-400">
                            No TTD
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                <i class="fas fa-info-circle text-emerald-500"></i>
                <span>Data Ketua Umum aktif di atas otomatis disematkan pada setiap penerbitan Surat Keputusan (SK) baru di tingkat Pusat, Daerah, maupun Cabang.</span>
            </div>
        </div>
    </div>

    <!-- Table Card: Riwayat Seluruh Ketua Umum -->
    <div class="card shadow-sm">
        <div class="card-header flex items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-history text-slate-500 mr-1.5"></i> Riwayat & Daftar Ketua Umum ASKLIN
            </h4>
            <span class="text-xs text-slate-400">Mendukung pemilihan Ketum baru & menjaga keutuhan riwayat SK lama</span>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped w-full text-xs" id="table-ketum">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Nama Lengkap & Gelar</th>
                            <th>Masa Periode</th>
                            <th>Tanda Tangan Digital</th>
                            <th>Status Jabatan</th>
                            <th>Keterangan</th>
                            <th width="160" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah / Edit Ketua Umum -->
<div class="modal fade" id="modalKetum" tabindex="-1" role="dialog" aria-labelledby="modalKetumLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modalKetumLabel">Tambah Ketua Umum</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formKetum" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="ketum_id">

                <div class="modal-body text-xs space-y-3">
                    <div class="form-group mb-3">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap & Seluruh Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control text-xs" placeholder="Contoh: dr. Eddi Junaidi, SpOG., SH., MKes" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-semibold text-slate-700 dark:text-slate-300">Tahun Mulai <span class="text-danger">*</span></label>
                                <input type="number" name="periode_mulai" id="periode_mulai" class="form-control text-xs" placeholder="2023" min="2000" max="2100" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-semibold text-slate-700 dark:text-slate-300">Tahun Selesai <span class="text-danger">*</span></label>
                                <input type="number" name="periode_selesai" id="periode_selesai" class="form-control text-xs" placeholder="2028" min="2000" max="2100" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">Status Jabatan <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control text-xs" required>
                            <option value="aktif">Aktif (Menjabat Sekarang)</option>
                            <option value="nonaktif">Nonaktif (Demisioner / Terdahulu)</option>
                        </select>
                        <small class="text-muted block mt-1">Jika memilih <b>Aktif</b>, Ketua Umum aktif sebelumnya akan otomatis diubah menjadi <b>Nonaktif</b>.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">File Scan Tanda Tangan Digital</label>
                        <input type="file" name="file_ttd" id="file_ttd" class="form-control-file text-xs" accept="image/png,image/jpeg,image/jpg,image/webp">
                        <small class="text-muted block mt-1">Format: PNG (disarankan transparan), JPG, WEBP. Maks: 2MB.</small>
                        <div id="previewTtdContainer" class="mt-2 hidden">
                            <span class="text-[10px] text-muted block mb-1">Pratinjau Tanda Tangan Saat Ini:</span>
                            <div class="p-2 border rounded bg-slate-50 dark:bg-slate-800 inline-block">
                                <img id="previewTtd" src="" alt="TTD Preview" style="max-height: 50px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-semibold text-slate-700 dark:text-slate-300">Keterangan / Catatan Kepengurusan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control text-xs" rows="2" placeholder="Catatan Kongres, SK penetapan, atau informasi tambahan..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 dark:bg-slate-800/50 py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSave">
                        <i class="fas fa-save mr-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Set Aktif -->
<div class="modal fade" id="modalSetAktif" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-b-0 pb-0">
                <h5 class="modal-title font-bold text-sm text-slate-800 dark:text-white">Tetapkan Ketum Aktif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-xs pt-2">
                <p class="text-slate-600 dark:text-slate-300">
                    Apakah Anda yakin ingin menetapkan <strong id="namaKetumSetAktif" class="text-slate-900 dark:text-white"></strong> sebagai <b>Ketua Umum Aktif</b> saat ini?
                </p>
                <div class="p-2.5 mt-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 rounded border border-emerald-200 dark:border-emerald-800 text-[11px]">
                    <i class="fas fa-info-circle mr-1"></i> Seluruh SK baru yang disahkan akan otomatis mencantumkan nama dan tanda tangan beliau. SK lama di masa lalu tetap aman dan tidak berubah.
                </div>
                <input type="hidden" id="idKetumSetAktif">
            </div>
            <div class="modal-footer border-t-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm" id="btnConfirmSetAktif">
                    <i class="fas fa-check mr-1"></i> Ya, Jadikan Aktif
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-b-0 pb-0">
                <h5 class="modal-title font-bold text-sm text-danger">Hapus Data Ketua Umum</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-xs pt-2">
                <p class="text-slate-600 dark:text-slate-300">
                    Apakah Anda yakin ingin menghapus data riwayat Ketua Umum ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer border-t-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnConfirmDelete">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    var table = $('#table-ketum').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('master_ketua_umum.list') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama_lengkap', name: 'nama_lengkap', className: 'font-semibold' },
            { data: 'periode', name: 'periode_mulai' },
            { data: 'ttd_preview', name: 'file_ttd', orderable: false, searchable: false, className: 'text-center' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Buka Modal Tambah
    $('#btnAdd').click(function() {
        $('#formKetum')[0].reset();
        $('#ketum_id').val('');
        $('#modalKetumLabel').text('Tambah Ketua Umum');
        $('#previewTtdContainer').addClass('hidden');
        $('#status').val('nonaktif');
        $('#modalKetum').modal('show');
    });

    // Simpan Form (Tambah / Edit)
    $('#formKetum').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $('#btnSave').attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('master_ketua_umum.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });
                    $('#modalKetum').modal('hide');
                    table.ajax.reload();
                    // Reload halaman agar banner atas terupdate jika status aktif berubah
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        iziToast.error({
                            title: 'Validasi',
                            message: errors[field][0],
                            position: 'topRight'
                        });
                    }
                } else {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                    iziToast.error({
                        title: 'Error',
                        message: msg,
                        position: 'topRight'
                    });
                }
            },
            complete: function() {
                $('#btnSave').attr('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Data');
            }
        });
    });

    // Buka Modal Edit
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var editUrl = "{{ route('master_ketua_umum.edit', ':id') }}".replace(':id', id);

        $.ajax({
            url: editUrl,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#modalKetumLabel').text('Edit Ketua Umum');
                    $('#formKetum')[0].reset();
                    $('#ketum_id').val(response.data.id);
                    $('#nama_lengkap').val(response.data.nama_lengkap);
                    $('#periode_mulai').val(response.data.periode_mulai);
                    $('#periode_selesai').val(response.data.periode_selesai);
                    $('#status').val(response.data.status);
                    $('#keterangan').val(response.data.keterangan);

                    if (response.ttd_url) {
                        $('#previewTtd').attr('src', response.ttd_url);
                        $('#previewTtdContainer').removeClass('hidden');
                    } else {
                        $('#previewTtdContainer').addClass('hidden');
                    }

                    $('#modalKetum').modal('show');
                }
            },
            error: function() {
                iziToast.error({
                    title: 'Error',
                    message: 'Gagal memuat data Ketua Umum',
                    position: 'topRight'
                });
            }
        });
    });

    // Buka Modal Set Aktif
    $(document).on('click', '.set-aktif-btn', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        $('#idKetumSetAktif').val(id);
        $('#namaKetumSetAktif').text(nama);
        $('#modalSetAktif').modal('show');
    });

    // Konfirmasi Set Aktif
    $('#btnConfirmSetAktif').click(function() {
        var id = $('#idKetumSetAktif').val();
        var url = "{{ route('master_ketua_umum.set-aktif', ':id') }}".replace(':id', id);

        $('#btnConfirmSetAktif').attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });
                    $('#modalSetAktif').modal('hide');
                    table.ajax.reload();
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal mengubah status aktif';
                iziToast.error({
                    title: 'Error',
                    message: msg,
                    position: 'topRight'
                });
            },
            complete: function() {
                $('#btnConfirmSetAktif').attr('disabled', false).html('<i class="fas fa-check mr-1"></i> Ya, Jadikan Aktif');
            }
        });
    });

    // Buka Modal Delete
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#delete_id').val(id);
        $('#modalDelete').modal('show');
    });

    // Konfirmasi Delete
    $('#btnConfirmDelete').click(function() {
        var id = $('#delete_id').val();

        $('#btnConfirmDelete').attr('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...');

        $.ajax({
            url: "{{ route('master_ketua_umum.delete') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    iziToast.success({
                        title: 'Sukses',
                        message: response.message,
                        position: 'topRight'
                    });
                    $('#modalDelete').modal('hide');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menghapus data';
                iziToast.error({
                    title: 'Error',
                    message: msg,
                    position: 'topRight'
                });
            },
            complete: function() {
                $('#btnConfirmDelete').attr('disabled', false).html('<i class="fas fa-trash mr-1"></i> Hapus');
            }
        });
    });
});
</script>
@endpush
