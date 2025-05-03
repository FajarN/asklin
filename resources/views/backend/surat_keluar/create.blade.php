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
    <form action="{{ route('surat_keluar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="id_jenis_surat">Jenis Surat</label>
                                <select class="form-control" id="id_jenis_surat" name="id_jenis_surat" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    @foreach ($jenisSurat as $js)
                                        <option value="{{ $js->id }}">{{ $js->nama_jenis }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tgl_surat">Tanggal Surat</label>
                                <input type="date" class="form-control" id="tgl_surat" name="tgl_surat" required>
                            </div>

                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="no_surat" id="no_surat" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-warning" type="button" id="generate-btn" onclick="generateNomor()">
                                            Generate
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="perihal">Perihal</label>
                                <input type="text" class="form-control" id="perihal" name="perihal" required>
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
                        <div id="form_surat_tugas" style="display: none;">
                            <hr>
                            <h5>Detail Surat Tugas</h5>

                            <div class="form-group">
                                <label for="asal_surat">Asal Surat</label>
                                <input type="text" class="form-control" id="asal_surat" name="asal_surat">
                            </div>
                            <div class="form-group">
                                <label for="nomor_asal_surat">Nomor Asal Surat</label>
                                <input type="text" class="form-control" id="nomor_asal_surat" name="nomor_asal_surat">
                            </div>
                            <div class="form-group">
                                <label for="agenda">Agenda</label>
                                <input type="text" class="form-control" id="agenda" name="agenda">
                            </div>

                            <div class="form-group">
                                <label for="hari">Hari</label>
                                <input type="text" class="form-control" id="hari" name="hari">
                            </div>

                            <div class="form-group">
                                <label for="tgl_agenda">Tanggal Agenda</label>
                                <input type="date" class="form-control" id="tgl_agenda" name="tgl_agenda">
                            </div>

                            <div class="form-group">
                                <label for="waktu_agenda">Waktu</label>
                                <input type="text" class="form-control" id="waktu_agenda" name="waktu_agenda"
                                    placeholder="Contoh: 08.00 - 12.00 WIB">
                            </div>

                            <div class="form-group">
                                <label for="tempat_agenda">Tempat</label>
                                <input type="text" class="form-control" id="tempat_agenda" name="tempat_agenda">
                            </div>

                            <h6>Daftar Petugas</h6>
                            <div id="petugas_container">
                                <div class="row form-group petugas-item">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="nama_pengurus[]"
                                            placeholder="Nama Pengurus">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="jabatan[]" placeholder="Jabatan">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-remove-petugas"><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info btn-sm mb-3" id="btn_add_petugas">
                                <i class="fas fa-plus"></i> Tambah Petugas
                            </button>
                        </div>

                        <div id="form_surat_undangan" style="display: none;">
                            <hr>
                            <h5>Detail Surat Undangan</h5>

                             <div class="form-group">
                            <label>Template Undangan:</label>
                            <div class="btn-group mb-3" role="group">
                                <button type="button" class="btn btn-primary" id="btn_template_pengurus" onclick="loadTemplate('pengurus')">
                                    <i class="fas fa-users"></i> Rapat Pengurus Pusat
                                </button>
                                <button type="button" class="btn btn-success" id="btn_template_global" onclick="loadTemplate('global')">
                                    <i class="fas fa-globe"></i> Rapat Global
                                </button>
                            </div>
                            <div class="progress mb-3" id="loading_progress" style="display: none; height: 5px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>

                            <div class="form-group">
                                <label for="nama_penerima">Nama Penerima</label>
                                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima">
                            </div>

                            <div class="form-group">
                                <label for="alamat_penerima">Alamat Penerima</label>
                                <textarea class="form-control" id="alamat_penerima" name="alamat_penerima" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="salam_pembuka">Salam Pembuka</label>
                                <input type="text" class="form-control" id="salam_pembuka" name="salam_pembuka"
                                    placeholder="Contoh: Dengan hormat,">
                            </div>

                            <div class="form-group">
                                <label for="isi_surat">Isi Surat</label>
                                <textarea class="form-control" id="isi_surat" name="isi_surat" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="judul_acara">Judul Acara</label>
                                <input type="text" class="form-control" id="judul_acara" name="judul_acara">
                            </div>

                            <div class="form-group">
                                <label for="tujuan_acara">Tujuan Acara</label>
                                <textarea class="form-control" id="tujuan_acara" name="tujuan_acara" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="waktu_tgl_acara">Waktu dan Tanggal Acara</label>
                                <input type="text" class="form-control" id="waktu_tgl_acara" name="waktu_tgl_acara">
                            </div>

                            <div class="form-group">
                                <label for="lokasi_acara">Lokasi Acara</label>
                                <textarea class="form-control" id="lokasi_acara" name="lokasi_acara" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="agenda_acara">Agenda Acara</label>
                                <textarea class="form-control" id="agenda_acara" name="agenda_acara" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="informasi_tambahan">Informasi Tambahan</label>
                                <textarea class="form-control" id="informasi_tambahan" name="informasi_tambahan" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="salam_penutup">Salam Penutup</label>
                                <input type="text" class="form-control" id="salam_penutup" name="salam_penutup"
                                    placeholder="Contoh: Hormat Kami,">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@push('js')
  <!-- Pastikan jQuery dimuat pertama -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Kemudian Select2 -->
    <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>

    <!-- Script lainnya -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script>
    $(document).ready(function() {

        $('#id_jenis_surat').change(function() {
            showSpecificForm($(this).find('option:selected').text());
            $('#no_surat').val('');
        });

        $('#btn_add_petugas').click(function() {
            var newPetugas = $('.petugas-item').first().clone();
            newPetugas.find('input').val('');
            $('#petugas_container').append(newPetugas);
        });

        $(document).on('click', '.btn-remove-petugas', function() {
            if ($('.petugas-item').length > 1) {
                $(this).closest('.petugas-item').remove();
            } else {
                $('.petugas-item').find('input').val('');
            }
        });

        function showSpecificForm(jenisSuratText) {
            $('#form_surat_tugas, #form_surat_undangan').hide();

            if (jenisSuratText.toLowerCase().includes('tugas')) {
                $('#form_surat_tugas').show();
            } else if (jenisSuratText.toLowerCase().includes('undangan')) {
                $('#form_surat_undangan').show();
            }
        }

        $('#tgl_surat').change(function() {
            if ($('#id_jenis_surat').val()) {
                generateNomor();
            }
        });
    });

  
function generateNomor() {
    const jenisSuratId = document.getElementById('id_jenis_surat').value;
    const tglSurat = document.getElementById('tgl_surat').value;
    const token = document.querySelector('form input[name="_token"]').value;

    if (!jenisSuratId) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Silakan pilih jenis surat terlebih dahulu'
        });
        return;
    }

    if (!tglSurat) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Silakan isi tanggal surat terlebih dahulu'
        });
        return;
    }

    document.getElementById('generate-btn').disabled = true;
    document.getElementById('generate-btn').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch('{{ route('surat_keluar.generate-nomor') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id_jenis_surat: jenisSuratId,
            tgl_surat: tglSurat,
            _token: token
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('generate-btn').disabled = false;
        document.getElementById('generate-btn').innerHTML = 'Generate';

        if (data.success) {
            document.getElementById('no_surat').value = data.no_surat;
            
            console.log('Debug info from server:', data.debug);
        } else {
            console.error('Error from server:', data.error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Gagal generate nomor surat'
            });
        }
    })
    .catch(error => {
        document.getElementById('generate-btn').disabled = false;
        document.getElementById('generate-btn').innerHTML = 'Generate';

        console.error('Fetch error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal generate nomor surat: ' + error
        });
    });
}


const templates = {
    pengurus: {
        nama_penerima: "Pengurus Pusat Asosiasi Klinik Indonesia",
        alamat_penerima: "Di Tempat",
        salam_pembuka: "Dengan hormat,",
        isi_surat: "Dalam rangka agenda rapat rutin pengurus pusat Asosiasi Klinik Indonesia. Dengan ini Saya mengundang Bapak/Ibu Pengurus Pusat Asklin untuk hadir pada::",
        judul_acara: "Rapat Koordinasi Pengurus Pusat",
        tujuan_acara: "Untuk membahas perkembangan organisasi dan program kerja periode ini.",
        waktu_tgl_acara: "Hari/tanggal: Sabtu, 15 Juni 2024\nPukul: 09.00 - 12.00 WIB",
        lokasi_acara: "Ruang Rapat Klinik Utama Taradita 48 \n Jl. Pinang Ranti II No.1A \n Kec. Makasar Jakarta Timur",
        agenda_acara: "1. Pembukaan\n2. Laporan Ketua Umum\n3. Evaluasi Program\n4. Pembahasan Rencana Kerja\n5. Lain-lain\n6. Penutup",
        informasi_tambahan: "Konfirmasi kehadiran dapat dilakukan melalui sekretariat atau WhatsApp ke nomor +62 838-1191-7367.",
        salam_penutup: "Demikian undangan ini kami sampaikan, mengingat pentingnya acara ini maka kami harapkan kehadiran Bapak/ibu. Atas perhatian dan kerjasamanya kami ucapkan terima kasih."
    },
    global: {
        nama_penerima: "Yth. Seluruh Anggota Organisasi",
        alamat_penerima: "Di Tempat",
        salam_pembuka: "Dengan hormat,",
        isi_surat: "Kami mengundang seluruh anggota organisasi untuk menghadiri rapat global yang akan membahas perkembangan terbaru organisasi kita.",
        judul_acara: "Rapat Global Tahunan Organisasi",
        tujuan_acara: "Menyampaikan laporan tahunan dan membahas rencana strategis organisasi untuk tahun depan.",
        waktu_tgl_acara: "Hari/tanggal: Minggu, 30 Juni 2024\nPukul: 13.00 - 16.00 WIB",
        lokasi_acara: "Aula Utama\nGedung Serbaguna Organisasi\nJl. Contoh No. 123, Jakarta",
        agenda_acara: "1. Pembukaan\n2. Sambutan Ketua Umum\n3. Laporan Tahunan\n4. Diskusi Strategis\n5. Penutup",
        informasi_tambahan: "Diharapkan kehadiran seluruh anggota. Konfirmasi kehadiran paling lambat 3 hari sebelum acara.",
        salam_penutup: "Hormat kami,"
    }
};

function loadTemplate(templateType) {
    const progressBar = document.getElementById('loading_progress');
    progressBar.style.display = 'block';
    
    document.getElementById('btn_template_pengurus').disabled = true;
    document.getElementById('btn_template_global').disabled = true;
    
    setTimeout(() => {
        const template = templates[templateType];
        
        for (const [field, value] of Object.entries(template)) {
            const element = document.getElementById(field);
            if (element) {
                if (element.tagName === 'TEXTAREA') {
                    element.value = value.replace(/\\n/g, '\n');
                } else {
                    element.value = value;
                }
            }
        }
        
        progressBar.style.display = 'none';
        
        document.getElementById('btn_template_pengurus').disabled = false;
        document.getElementById('btn_template_global').disabled = false;
        
        iziToast.success({
            title: 'Success',
            message: 'Template berhasil dimuat',
            position: 'topRight'
        });
    }, 1000); 
}

</script>
<script>

</script>
@endpush