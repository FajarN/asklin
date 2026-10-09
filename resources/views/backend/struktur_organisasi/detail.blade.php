@extends('layouts.backend.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Detail Struktur Organisasi')
@section('page_title', 'Detail Struktur Organisasi')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('struktur_organisasi.index') }}" class="hover:underline">Struktur Organisasi</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Detail Bagan</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white flex items-center gap-2">
                <span>{{ $struktur->nama_struktur }}</span>
                @php
                    $statusClass = [
                        'draft' => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/30',
                        'aktif' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                        'selesai' => 'bg-sky-500/15 text-sky-700 dark:text-sky-400 border-sky-500/30',
                    ];
                @endphp
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider border {{ $statusClass[$struktur->status] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ ucfirst($struktur->status) }}
                </span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Tingkatan: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->tingkatanPengurus->nama_tingkatan ?? '-' }}</strong> &bull;
                Periode: <strong class="text-slate-700 dark:text-slate-200">{{ $struktur->periode }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('struktur_organisasi.print', $struktur->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i>
                <span>Cetak SK / Bagan</span>
            </a>
            <a href="{{ route('struktur_organisasi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Metadata Card -->
    <div class="card">
        <div class="card-header">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-info-circle text-emerald-600 mr-1.5"></i> Informasi Wilayah & Struktur
            </h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Nama Struktur</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $struktur->nama_struktur }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tingkatan & Periode</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->tingkatanPengurus->nama_tingkatan ?? '-' }} ({{ $struktur->periode }})
                    </span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Cakupan Wilayah</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->provinsi_nama ?? '-' }} {{ $struktur->kota_nama ? ' / ' . $struktur->kota_nama : '' }}
                    </span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tanggal Muscab/Musda</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {{ $struktur->tgl_muscab ? date('d-m-Y', strtotime($struktur->tgl_muscab)) : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tree Structure Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <i class="fas fa-sitemap text-emerald-600 mr-1.5"></i> Susunan Dewan Pengurus
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Hirarki jabatan dan personil kepengurusan ASKLIN</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="btnAddPengurus">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengurus Tingkat Atas</span>
            </button>
        </div>
        <div class="card-body">
            <div class="org-chart space-y-4">
                @if (count($pengurus) > 0)
                    @foreach ($pengurus as $item)
                        @include('backend.struktur_organisasi.components.pengurus_card', [
                            'pengurus' => $item,
                        ])
                    @endforeach
                @else
                    <div class="p-8 text-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 mx-auto flex items-center justify-center text-xl mb-3">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Belum Ada Susunan Pengurus</h4>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">
                            Struktur organisasi ini belum memiliki data pengurus. Silakan klik tombol di bawah untuk menambahkan pengurus pimpinan awal.
                        </p>
                        <button type="button" class="btn btn-primary btn-sm" onclick="$('#btnAddPengurus').click()">
                            <i class="fas fa-plus mr-1"></i> Tambah Pengurus Pertama
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Pengurus -->
<div class="modal fade" id="pengurusModal" tabindex="-1" role="dialog" aria-labelledby="pengurusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-slate-800 dark:text-white" id="pengurusModalLabel">Tambah Pengurus</h5>
                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pengurusForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body space-y-4">
                    <input type="hidden" id="pengurus_id" name="id">
                    <input type="hidden" id="id_struktur_organisasi" name="id_struktur_organisasi" value="{{ $struktur->id }}">
                    <input type="hidden" id="parent_id" name="parent_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Ketua Umum, Sekretaris" required>
                        </div>
                        <div class="form-group">
                            <label for="id_kelompok" class="form-label">Kelompok Pengurus</label>
                            <select class="form-control" id="id_kelompok" name="id_kelompok">
                                <option value="">-- Pilih Kelompok Pengurus --</option>
                                @foreach($kelompokPengurusList as $kelompok)
                                    <option value="{{ $kelompok->id }}">{{ $kelompok->nama_kelompok }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="dr. John Doe, Sp.A" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="form-label">Keterangan / Bidang</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Bidang Organisasi & Hukum">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="no_telp" class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="urutan" class="form-label">Urutan Tampilan <span class="text-rose-500">*</span></label>
                            <input type="number" class="form-control" id="urutan" name="urutan" min="1" value="1" required>
                            <small class="text-xs text-slate-400 mt-1 block">Urutan prioritas dalam struktur bagian ini</small>
                        </div>
                        <div class="form-group">
                            <label for="status_pengurus" class="form-label">Status <span class="text-rose-500">*</span></label>
                            <select class="form-control" id="status_pengurus" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non Aktif</option>
                                <option value="mengundurkan_diri">Mengundurkan Diri</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="foto_pengurus" class="form-label">Foto Profil Pengurus</label>
                        <input type="file" class="form-control" id="foto_pengurus" name="foto_pengurus" accept="image/*">
                        <small class="text-xs text-slate-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 2MB</small>
                        <div id="preview-container" class="mt-3" style="display: none;">
                            <img id="preview-image" src="#" alt="Preview" class="h-20 w-20 rounded-xl object-cover ring-2 ring-emerald-500/20 shadow-md">
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSavePengurus">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Pengurus Confirmation Modal -->
<div class="modal fade" id="deletePengurusModal" tabindex="-1" role="dialog" aria-labelledby="deletePengurusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-slate-800 dark:text-white" id="deletePengurusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close text-slate-400 hover:text-slate-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 mx-auto flex items-center justify-center text-xl mb-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Apakah Anda yakin ingin menghapus pengurus ini?</p>
                <p class="text-xs text-rose-500 mt-1">Peringatan: Pengurus yang masih memiliki bawahan tidak dapat dihapus!</p>
            </div>
            <div class="modal-footer flex items-center justify-center gap-2 border-t border-slate-100 dark:border-slate-800">
                <input type="hidden" id="delete_pengurus_id" name="delete_pengurus_id">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnDeletePengurus">Hapus Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // File input preview
            $('#foto_pengurus').change(function() {
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#preview-image').attr('src', event.target.result);
                        $('#preview-container').show();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Add new root pengurus
            $('#btnAddPengurus').click(function() {
                $('#pengurusModal').find('.modal-title').text('Tambah Pengurus Pimpinan');
                $('#pengurusForm')[0].reset();
                $('#pengurus_id').val('');
                $('#parent_id').val('');
                $('#preview-container').hide();
                $('#pengurusModal').modal('show');
            });

            // Add sub pengurus
            $(document).on('click', '.add-sub-btn', function() {
                let parentId = $(this).data('id');
                $('#pengurusModal').find('.modal-title').text('Tambah Anggota / Bawahan');
                $('#pengurusForm')[0].reset();
                $('#pengurus_id').val('');
                $('#parent_id').val(parentId);
                $('#preview-container').hide();
                $('#pengurusModal').modal('show');
            });

            // Edit pengurus
            $(document).on('click', '.edit-pengurus-btn', function() {
                let id = $(this).data('id');
                $('#pengurusModal').find('.modal-title').text('Edit Data Pengurus');

                $.ajax({
                    url: "{{ route('pengurus.edit') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#pengurus_id').val(data.id);
                        $('#id_kelompok').val(data.id_kelompok);
                        $('#jabatan').val(data.jabatan);
                        $('#keterangan').val(data.keterangan);
                        $('#nama_lengkap').val(data.nama_lengkap);
                        $('#no_telp').val(data.no_telp);
                        $('#email').val(data.email);
                        $('#urutan').val(data.urutan);
                        $('#status_pengurus').val(data.status);
                        $('#parent_id').val(data.parent_id);

                        if (data.foto_pengurus) {
                            $('#preview-image').attr('src', "{{ asset('storage/pengurus') }}/" + data.foto_pengurus);
                            $('#preview-container').show();
                        } else {
                            $('#preview-container').hide();
                        }

                        $('#pengurusModal').modal('show');
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan saat mengambil data',
                            position: 'topRight'
                        });
                    }
                });
            });

            // Submit pengurus form
            $('#pengurusForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#btnSavePengurus').text('Menyimpan...').attr('disabled', true);

                $.ajax({
                    url: "{{ route('pengurus.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#pengurusModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: data.message,
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessages = '';
                            $.each(errors, function(key, value) {
                                errorMessages += value[0] + '<br>';
                            });
                            iziToast.error({
                                title: 'Error',
                                message: errorMessages,
                                position: 'topRight'
                            });
                        } else {
                            let message = xhr.responseJSON.message || 'Terjadi kesalahan';
                            iziToast.error({
                                title: 'Error',
                                message: message,
                                position: 'topRight'
                            });
                        }
                    },
                    complete: function() {
                        $('#btnSavePengurus').text('Simpan Data').attr('disabled', false);
                    }
                });
            });

            // Delete confirmation
            $(document).on('click', '.delete-pengurus-btn', function() {
                let id = $(this).data('id');
                $('#delete_pengurus_id').val(id);
                $('#deletePengurusModal').modal('show');
            });

            // Delete action
            $('#btnDeletePengurus').click(function() {
                let id = $('#delete_pengurus_id').val();
                $('#btnDeletePengurus').text('Menghapus...').attr('disabled', true);

                $.ajax({
                    url: "{{ route('pengurus.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#deletePengurusModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: data.message,
                            position: 'topRight'
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON.message,
                            position: 'topRight'
                        });
                    },
                    complete: function() {
                        $('#btnDeletePengurus').text('Hapus Sekarang').attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
