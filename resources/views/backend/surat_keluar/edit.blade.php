@extends('layouts.backend.layout')

@section('content')


    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Input Surat Keluar</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('surat_keluar.index') }}">Surat Keluar</a></div>
                    <div class="breadcrumb-item">Create</div>
                </div>
            </div>
        </section>
        <form action="{{ route('surat_keluar.update', $suratKeluar->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">

                              <input type="hidden" id="no_surat" name="no_surat" value="{{ $suratKeluar->no_surat }}">

                                <div class="form-group">
                                    <label for="id_jenis_surat">Jenis Surat</label>
                                    <select class="form-control" id="id_jenis_surat" name="id_jenis_surat" required>
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($jenisSurat as $js)
                                            <option value="{{ $js->id }}"
                                                {{ $suratKeluar->id_jenis_surat == $js->id ? 'selected' : '' }}>
                                                {{ $js->nama_jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tgl_surat">Tanggal Surat</label>
                                    <input type="date" class="form-control" id="tgl_surat" name="tgl_surat"
                                        value="{{ \Carbon\Carbon::parse($suratKeluar->tgl_surat)->format('Y-m-d') }}"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="perihal">Perihal</label>
                                    <input type="text" class="form-control" id="perihal" name="perihal"
                                        value="{{ $suratKeluar->perihal }}" required>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('surat_keluar.index') }}" class="btn btn-info">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="form_surat_tugas"
                                style="display: {{ stripos($suratKeluar->jenisSurat->nama_jenis, 'tugas') !== false ? 'block' : 'none' }};">
                                <hr>
                                <h5>Detail Surat Tugas</h5>

                                <div class="form-group">
                                    <label for="asal_surat">Asal Surat</label>
                                    <input type="text" class="form-control" id="asal_surat" name="asal_surat"
                                        value="{{ $suratKeluar->suratTugas->asal_surat ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_asal_surat">Nomor Asal Surat</label>
                                    <input type="text" class="form-control" id="nomor_asal_surat" name="nomor_asal_surat"
                                        value="{{ $suratKeluar->suratTugas->nomor_asal_surat ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="agenda">Agenda</label>
                                    <input type="text" class="form-control" id="agenda" name="agenda"
                                        value="{{ $suratKeluar->suratTugas->agenda ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="hari">Hari</label>
                                    <input type="text" class="form-control" id="hari" name="hari"
                                        value="{{ $suratKeluar->suratTugas->hari ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="tgl_agenda">Tanggal Agenda</label>
                                    <input type="date" class="form-control" id="tgl_agenda" name="tgl_agenda"
                                        value="{{ isset($suratKeluar->suratTugas->tgl_agenda) ? \Carbon\Carbon::parse($suratKeluar->suratTugas->tgl_agenda)->format('Y-m-d') : '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="waktu_agenda">Waktu</label>
                                    <input type="text" class="form-control" id="waktu_agenda" name="waktu_agenda"
                                        placeholder="Contoh: 08.00 - 12.00 WIB"
                                        value="{{ $suratKeluar->suratTugas->waktu_agenda ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="tempat_agenda">Tempat</label>
                                    <input type="text" class="form-control" id="tempat_agenda" name="tempat_agenda"
                                        value="{{ $suratKeluar->suratTugas->tempat_agenda ?? '' }}">
                                </div>

                                <h6>Daftar Petugas</h6>
                                <div id="petugas_container">
                                    @if (isset($suratKeluar->suratTugas) && $suratKeluar->suratTugas->details->count() > 0)
                                        @foreach ($suratKeluar->suratTugas->details as $index => $detail)
                                            <div class="row form-group petugas-item">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="nama_pengurus[]"
                                                        placeholder="Nama Pengurus" value="{{ $detail->nama_pengurus }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="jabatan[]"
                                                        placeholder="Jabatan" value="{{ $detail->jabatan }}">
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($index == 0)
                                                        <button type="button" class="btn btn-info" id="btn_add_petugas">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-danger btn-remove-petugas">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row form-group petugas-item">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="nama_pengurus[]"
                                                    placeholder="Nama Pengurus">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="jabatan[]"
                                                    placeholder="Jabatan">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-info" id="btn_add_petugas">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- akhir --}}

                            <div id="form_surat_undangan"
                                style="display: {{ stripos($suratKeluar->jenisSurat->nama_jenis, 'undangan') !== false ? 'block' : 'none' }};">
                                <hr>
                                <h5>Detail Surat Undangan</h5>

                                <div class="form-group">
                                    <label for="nama_penerima">Nama Penerima</label>
                                    <input type="text" class="form-control" id="nama_penerima" name="nama_penerima"
                                        value="{{ $suratKeluar->suratUndangan->nama_penerima ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="alamat_penerima">Alamat Penerima</label>
                                    <textarea class="form-control" id="alamat_penerima" name="alamat_penerima" rows="2">{{ $suratKeluar->suratUndangan->alamat_penerima ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="salam_pembuka">Salam Pembuka</label>
                                    <input type="text" class="form-control" id="salam_pembuka" name="salam_pembuka"
                                        placeholder="Contoh: Dengan hormat,"
                                        value="{{ $suratKeluar->suratUndangan->salam_pembuka ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="isi_surat">Isi Surat</label>
                                    <textarea class="form-control" id="isi_surat" name="isi_surat" rows="3">{{ $suratKeluar->suratUndangan->isi_surat ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="judul_acara">Judul Acara</label>
                                    <input type="text" class="form-control" id="judul_acara" name="judul_acara"
                                        value="{{ $suratKeluar->suratUndangan->judul_acara ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="tujuan_acara">Tujuan Acara</label>
                                    <textarea class="form-control" id="tujuan_acara" name="tujuan_acara" rows="2">{{ $suratKeluar->suratUndangan->tujuan_acara ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="waktu_tgl_acara">Waktu dan Tanggal Acara</label>
                                    <input type="text" class="form-control" id="waktu_tgl_acara"
                                        name="waktu_tgl_acara"
                                        value="{{ $suratKeluar->suratUndangan->waktu_tgl_acara ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_acara">Lokasi Acara</label>
                                    <textarea class="form-control" id="lokasi_acara" name="lokasi_acara" rows="2">{{ $suratKeluar->suratUndangan->lokasi_acara ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="agenda_acara">Agenda Acara</label>
                                    <textarea class="form-control" id="agenda_acara" name="agenda_acara" rows="3">{{ $suratKeluar->suratUndangan->agenda_acara ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="informasi_tambahan">Informasi Tambahan</label>
                                    <textarea class="form-control" id="informasi_tambahan" name="informasi_tambahan" rows="2">{{ $suratKeluar->suratUndangan->informasi_tambahan ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="salam_penutup">Salam Penutup</label>
                                    <input type="text" class="form-control" id="salam_penutup" name="salam_penutup"
                                        placeholder="Contoh: Hormat Kami,"
                                        value="{{ $suratKeluar->suratUndangan->salam_penutup ?? '' }}">
                                </div>
                            </div>
                            {{-- akhir --}}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Tampilkan form khusus sesuai jenis surat yang dipilih
            $('#id_jenis_surat').change(function() {
                showSpecificForm($(this).find('option:selected').text());
            });

            // Tambah petugas di surat tugas
            $('#btn_add_petugas').click(function() {
                var newPetugas = $('.petugas-item').first().clone();
                newPetugas.find('input').val('');
                newPetugas.find('button').removeClass('btn-info').addClass('btn-danger')
                    .html('<i class="fas fa-times"></i>')
                    .removeAttr('id').addClass('btn-remove-petugas');
                $('#petugas_container').append(newPetugas);
            });

            // Hapus petugas di surat tugas
            $(document).on('click', '.btn-remove-petugas', function() {
                $(this).closest('.petugas-item').remove();
            });

            // Fungsi untuk menampilkan form khusus sesuai jenis surat
            function showSpecificForm(jenisSuratText) {
                // Reset tampilan form khusus
                $('#form_surat_tugas, #form_surat_undangan').hide();

                // Tampilkan form sesuai jenis surat
                if (jenisSuratText.toLowerCase().includes('tugas')) {
                    $('#form_surat_tugas').show();
                } else if (jenisSuratText.toLowerCase().includes('undangan')) {
                    $('#form_surat_undangan').show();
                }
            }
        });
    </script>
@endpush
