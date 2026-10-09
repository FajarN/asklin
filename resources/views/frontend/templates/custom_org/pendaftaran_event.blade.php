@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('event_asklin') }}">Event</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Pendaftaran Event</span>
            </div>
            <h1 class="org-page-title">Pendaftaran Peserta Event ASKLIN</h1>
            <p class="org-page-subtitle">Silakan isi formulir registrasi delegasi klinik untuk mengikuti kegiatan resmi ASKLIN</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 40px;">

                <!-- Registration Form -->
                <div class="org-content-card">
                    <span class="org-section-eyebrow">Formulir Resmi</span>
                    <h2 style="font-size: 20px; font-weight: 800; color: var(--org-secondary); margin: 6px 0 20px 0;">
                        Data Pendaftaran Peserta
                    </h2>

                    <form action="{{ route('pendaftaran_event.daftar') }}" method="POST" enctype="multipart/form-data" style="space-y: 16px;">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Nama Peserta <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="nama_peserta" required placeholder="Nama lengkap peserta beserta gelar" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Status Keanggotaan <span style="color: #ef4444;">*</span></label>
                            <select name="keanggotaaan" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                                <option value="">-- Pilih Status --</option>
                                <option value="0">Anggota ASKLIN</option>
                                <option value="1">Bukan Anggota</option>
                                <option value="2">Perseorangan / Umum</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Nama Fasilitas Klinik <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="nama_klinik" required placeholder="Contoh: Klinik Pratama Sehat Bersama" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Alamat Klinik <span style="color: #ef4444;">*</span></label>
                            <textarea name="alamat" required rows="3" placeholder="Alamat lengkap klinik" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px; font-family: inherit;"></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Provinsi <span style="color: #ef4444;">*</span></label>
                                <select name="id_provinsi" id="id_provinsi" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsi as $i)
                                        <option value="{{ $i->code }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Kabupaten / Kota <span style="color: #ef4444;">*</span></label>
                                <select name="id_kota" id="id_kota" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;"></select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Kecamatan <span style="color: #ef4444;">*</span></label>
                                <select name="id_kecamatan" id="id_kecamatan" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;"></select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Kelurahan <span style="color: #ef4444;">*</span></label>
                                <select name="id_kelurahan" id="id_kelurahan" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;"></select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Nomor Telepon / WhatsApp <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="tlf" id="tlf" required placeholder="08xxxxxxxxxx" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">Email Peserta <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" id="email" required placeholder="email@klinik.com" style="width: 100%; padding: 10px 14px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                            </div>
                        </div>

                        <div style="padding: 16px; background: var(--org-bg-body); border-radius: 8px; border: 1px solid var(--org-border); margin-bottom: 20px;">
                            <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--org-primary-dark); display: block; margin-bottom: 10px;">
                                <i class="fas fa-hotel mr-1"></i> Akomodasi &amp; Penginapan (Opsional)
                            </span>
                            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--org-secondary); margin-bottom: 4px;">Pilihan Hotel</label>
                                    <select name="nama_hotel" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--org-border); border-radius: 6px; font-size: 13px;">
                                        <option value="">-- Pilih Hotel --</option>
                                        <option value="Hotel A">Hotel A</option>
                                        <option value="Hotel B">Hotel B</option>
                                        <option value="Hotel C">Hotel C</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--org-secondary); margin-bottom: 4px;">Jumlah Kamar</label>
                                    <input type="number" name="jumlah_kamar" required value="1" min="1" style="width: 100%; padding: 8px 12px; border: 1px solid var(--org-border); border-radius: 6px; font-size: 13px;">
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--org-secondary); margin-bottom: 4px;">Tanggal Masuk (Check-in)</label>
                                    <input type="date" name="tgl_masuk" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--org-border); border-radius: 6px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--org-secondary); margin-bottom: 4px;">Tanggal Keluar (Check-out)</label>
                                    <input type="date" name="tgl_keluar" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--org-border); border-radius: 6px; font-size: 13px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="org-btn org-btn-primary" style="width: 100%; padding: 12px 24px; font-size: 14px;">
                            <i class="fas fa-check-circle mr-1 text-xs"></i>
                            <span>Kirim Pendaftaran Event</span>
                        </button>
                    </form>
                </div>

                <!-- Information Box -->
                <div>
                    <div class="org-content-card" style="padding: 24px; margin-bottom: 24px;">
                        <span class="org-section-eyebrow">Panduan Peserta</span>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--org-secondary); margin: 6px 0 16px 0;">
                            Ketentuan Registrasi
                        </h3>

                        <div style="space-y: 14px; font-size: 13px; color: var(--org-text-main); line-height: 1.7;">
                            <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                                <i class="fas fa-info-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Pastikan data nama peserta dan nomor kontak yang didaftarkan aktif untuk konfirmasi tiket dan sertifikat kegiatan.</span>
                            </div>
                            <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                                <i class="fas fa-info-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Anggota klinik resmi ASKLIN berhak mendapatkan harga registrasi khusus delegasi sesuai ketentuan panitia pelaksana.</span>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <i class="fas fa-info-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Konfirmasi pembayaran dan e-ticket akan dikirimkan melalui WhatsApp / Email yang didaftarkan.</span>
                            </div>
                        </div>
                    </div>

                    <div class="org-content-card" style="padding: 24px; background: var(--org-primary-soft); border-color: #fecaca;">
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--org-primary-dark); margin: 0 0 10px 0;">
                            Butuh Bantuan Pendaftaran?
                        </h4>
                        <p style="font-size: 12.5px; color: var(--org-secondary); line-height: 1.6; margin-bottom: 14px;">
                            Hubungi narahubung panitia acara kami jika mengalami kendala saat mengisi formulir pendaftaran.
                        </p>
                        <a href="https://api.whatsapp.com/send?phone=6282310981637" target="_blank" class="org-btn org-btn-primary" style="width: 100%; font-size: 12.5px;">
                            <i class="fab fa-whatsapp"></i>
                            <span>Chat WhatsApp Panitia</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('js')
<script>
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#id_provinsi').on('change', function() {
        let id_provinsi = $(this).val();
        if (id_provinsi) {
            $.ajax({
                type: 'POST',
                url: '{{ route("getKota") }}',
                data: {id_provinsi: id_provinsi},
                success: function(data) {
                    $('#id_kota').html(data);
                    $('#id_kota').trigger('change');
                }
            });
        }
    });

    $('#id_kota').on('change', function() {
        let id_kota = $(this).val();
        if (id_kota) {
            $.ajax({
                type: 'POST',
                url: '{{ route("getKecamatan") }}',
                data: {id_kota: id_kota},
                success: function(data) {
                    $('#id_kecamatan').html(data);
                    $('#id_kecamatan').trigger('change');
                }
            });
        }
    });

    $('#id_kecamatan').on('change', function() {
        let id_kecamatan = $(this).val();
        if (id_kecamatan) {
            $.ajax({
                type: 'POST',
                url: '{{ route("getKelurahan") }}',
                data: {id_kecamatan: id_kecamatan},
                success: function(data) {
                    $('#id_kelurahan').html(data);
                }
            });
        }
    });
});
</script>
@endpush

