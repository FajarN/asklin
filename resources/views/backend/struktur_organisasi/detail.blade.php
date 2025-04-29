@extends('layouts.backend.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Detail Struktur Organisasi')

@section('css')

@endsection

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Struktur Organisasi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('struktur_organisasi.index') }}">Struktur Organisasi</a>
                    </div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>
        </section>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Struktur</h4>
                            <div class="card-header-action">
                                <a href="{{ route('struktur_organisasi.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="200">Nama Struktur</th>
                                            <td>{{ $struktur->nama_struktur }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tingkatan</th>
                                            <td>{{ $struktur->tingkatanPengurus->nama_tingkatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Periode</th>
                                            <td>{{ $struktur->periode }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="200">Propinsi</th>
                                            <td>
                                                {{ $struktur->provinsi_nama }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200">Kab/Kota</th>
                                            <td>
                                                {{ $struktur->kota_nama }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Muscab</th>
                                            <td>{{ date('d-m-Y', strtotime($struktur->tgl_muscab)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'draft' => 'badge-warning',
                                                        'aktif' => 'badge-success',
                                                        'selesai' => 'badge-info',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge {{ $statusClass[$struktur->status] }}">{{ ucfirst($struktur->status) }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="section-title-inline">Susunan Pengurus</h4>
                            <button class="btn btn-sm btn-success" id="btnAddPengurus">
                                <i class="fas fa-plus"></i> Tambah Pengurus
                            </button>
                            <div class="card-header-action">
                                <div class="btn-group">
                                <div class="card-header-action">
                                        <div class="btn-group">
                                            <a href="{{ route('struktur_organisasi.print', $struktur->id) }}" class="btn btn-primary" target="_blank">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="org-chart">
                                @if (count($pengurus) > 0)
                                    @foreach ($pengurus as $item)
                                        @include('backend.struktur_organisasi.components.pengurus_card', [
                                            'pengurus' => $item,
                                        ])
                                    @endforeach
                                @else
                                    <div class="alert alert-info">
                                        Belum ada data pengurus. Silahkan tambahkan pengurus dengan klik tombol "Tambah
                                        Pengurus" di atas.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>

        <!-- Modal for Add/Edit Pengurus -->
        <div class="modal fade" id="pengurusModal" tabindex="-1" role="dialog" aria-labelledby="pengurusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pengurusModalLabel">Tambah Pengurus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="pengurusForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="pengurus_id" name="id">
                            <input type="hidden" id="id_struktur_organisasi" name="id_struktur_organisasi"
                                value="{{ $struktur->id }}">
                            <input type="hidden" id="parent_id" name="parent_id">

                            <div class="form-group">
                                <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan">
                            </div>

                            <div class="form-group">
                                <label for="id_kelompok">Kelompok Pengurus</label>
                                <select class="form-control" id="id_kelompok" name="id_kelompok">
                                    <option value="">-- Pilih Kelompok Pengurus --</option>
                                    @foreach($kelompokPengurusList as $kelompok)
                                        <option value="{{ $kelompok->id }}">{{ $kelompok->nama_kelompok }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telp">Nomor Telepon</label>
                                        <input type="text" class="form-control" id="no_telp" name="no_telp">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="urutan">Urutan <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="urutan" name="urutan"
                                            min="1" value="1" required>
                                        <small class="text-muted">Angka urutan untuk mengurutkan tampilan pengurus</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status_pengurus" name="status" required>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Non Aktif</option>
                                            <option value="mengundurkan_diri">Mengundurkan Diri</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="foto_pengurus">Foto Pengurus</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_pengurus"
                                        name="foto_pengurus" accept="image/*">
                                    <label class="custom-file-label" for="foto_pengurus">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format gambar: JPG, PNG. Maks 2MB</small>
                                <div id="preview-container" class="mt-2" style="display: none;">
                                    <img id="preview-image" src="#" alt="Preview"
                                        style="max-height:100px; max-width: 40%;">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSavePengurus">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Pengurus Confirmation Modal -->
        <div class="modal fade" id="deletePengurusModal" tabindex="-1" role="dialog"
            aria-labelledby="deletePengurusModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePengurusModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus pengurus ini?</p>
                        <p class="text-danger">Peringatan: Pengurus dengan bawahan tidak dapat dihapus!</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="delete_pengurus_id" name="delete_pengurus_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="btnDeletePengurus">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>


        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // File input change event to show preview
                $('#foto_pengurus').change(function() {
                    let file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(event) {
                            $('#preview-image').attr('src', event.target.result);
                            $('#preview-container').show();
                        }
                        reader.readAsDataURL(file);

                        // Update label
                        $('.custom-file-label').text(file.name);
                    }
                });

                // Add new pengurus
                $('#btnAddPengurus').click(function() {
                    $('#pengurusModal').find('.modal-title').text('Tambah Pengurus');
                    $('#pengurusForm')[0].reset();
                    $('#pengurus_id').val('');
                    $('#parent_id').val('');
                    $('#preview-container').hide();
                    $('.custom-file-label').text('Pilih file...');
                    $('#pengurusModal').modal('show');
                });

                // Add sub pengurus
                $(document).on('click', '.add-sub-btn', function() {
                    let parentId = $(this).data('id');
                    $('#pengurusModal').find('.modal-title').text('Tambah Anggota Bawahan');
                    $('#pengurusForm')[0].reset();
                    $('#pengurus_id').val('');
                    $('#parent_id').val(parentId);
                    $('#preview-container').hide();
                    $('.custom-file-label').text('Pilih file...');
                    $('#pengurusModal').modal('show');
                });

                // Edit pengurus
                $(document).on('click', '.edit-pengurus-btn', function() {
                    let id = $(this).data('id');
                    $('#pengurusModal').find('.modal-title').text('Edit Pengurus');

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

                            // Show image preview if exists
                            if (data.foto_pengurus) {
                                $('#preview-image').attr('src',
                                    "{{ asset('storage/pengurus') }}/" + data.foto_pengurus);
                                $('#preview-container').show();
                                $('.custom-file-label').text('Foto telah diupload');
                            } else {
                                $('#preview-container').hide();
                                $('.custom-file-label').text('Pilih file...');
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
                            }, 1000);
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
                                if (message === 'CSRF token mismatch.') {
                                    message =
                                        'Sesi Anda telah habis, silakan refresh halaman dan coba lagi';
                                }
                                iziToast.error({
                                    title: 'Error',
                                    message: message,
                                    position: 'topRight'
                                });
                            }
                        },
                        complete: function() {
                            $('#btnSavePengurus').text('Simpan').attr('disabled', false);
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

                            // Reload page to refresh the structure
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Error',
                                message: xhr.responseJSON.message,
                                position: 'topRight'
                            });
                        },
                        complete: function() {
                            $('#btnDeletePengurus').text('Hapus').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
    @endpush
