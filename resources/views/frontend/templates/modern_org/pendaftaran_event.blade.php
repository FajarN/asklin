@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('event_asklin') }}">Event</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Pendaftaran Event</span>
            </div>
            <h1 class="mod-inner-title">Formulir Registrasi Event &amp; Pelatihan</h1>
            <p class="mod-inner-sub">Pendaftaran Peserta / Delegasi Klinik untuk Kegiatan Resmi ASKLIN</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 40px;">

                <!-- Registration Form -->
                <div class="mod-card">
                    <span class="mod-tag-pill">Registrasi Online</span>
                    <h2 style="font-size: 20px; font-weight: 800; color: var(--mod-text-main); margin: 6px 0 22px 0;">
                        Lengkapi Data Delegasi Klinik
                    </h2>

                    <form action="{{ route('pendaftaran_event.daftar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Nama Peserta <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="nama_peserta" required placeholder="Nama lengkap beserta gelar dokter/tenaga medis" style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Status Keanggotaan <span style="color: #ef4444;">*</span></label>
                            <select name="keanggotaaan" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                                <option value="">-- Pilih Keanggotaan --</option>
                                <option value="0">Anggota ASKLIN</option>
                                <option value="1">Bukan Anggota</option>
                                <option value="2">Perseorangan / Umum</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Nama Fasilitas Klinik <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="nama_klinik" required placeholder="Contoh: Klinik Pratama Sehat Prima" style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Alamat Klinik <span style="color: #ef4444;">*</span></label>
                            <textarea name="alamat" required rows="3" placeholder="Alamat lengkap lokasi klinik" style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px; font-family: inherit;"></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Provinsi <span style="color: #ef4444;">*</span></label>
                                <select name="id_provinsi" id="id_provinsi" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsi as $i)
                                        <option value="{{ $i->code }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Kabupaten / Kota <span style="color: #ef4444;">*</span></label>
                                <select name="id_kota" id="id_kota" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;"></select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Kecamatan <span style="color: #ef4444;">*</span></label>
                                <select name="id_kecamatan" id="id_kecamatan" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;"></select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Kelurahan <span style="color: #ef4444;">*</span></label>
                                <select name="id_kelurahan" id="id_kelurahan" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;"></select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Telepon / WA <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="tlf" id="tlf" required placeholder="08xxxxxxxxxx" style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">Email <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" id="email" required placeholder="email@klinik.com" style="width: 100%; padding: 12px 16px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                            </div>
                        </div>

                        <div style="padding: 18px; background: #f8fafc; border-radius: 14px; border: 1px solid var(--mod-border); margin-bottom: 22px;">
                            <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--mod-primary); display: block; margin-bottom: 10px;">
                                <i class="fas fa-hotel mr-1"></i> Pemesanan Akomodasi Hotel (Opsional)
                            </span>
                            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 4px;">Pilihan Hotel</label>
                                    <select name="nama_hotel" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--mod-border); border-radius: 10px; font-size: 13px;">
                                        <option value="">-- Pilih Hotel --</option>
                                        <option value="Hotel A">Hotel A</option>
                                        <option value="Hotel B">Hotel B</option>
                                        <option value="Hotel C">Hotel C</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 4px;">Jumlah Kamar</label>
                                    <input type="number" name="jumlah_kamar" required value="1" min="1" style="width: 100%; padding: 10px 14px; border: 1px solid var(--mod-border); border-radius: 10px; font-size: 13px;">
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 4px;">Tanggal Masuk (Check-in)</label>
                                    <input type="date" name="tgl_masuk" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--mod-border); border-radius: 10px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--mod-text-main); margin-bottom: 4px;">Tanggal Keluar (Check-out)</label>
                                    <input type="date" name="tgl_keluar" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--mod-border); border-radius: 10px; font-size: 13px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="mod-btn mod-btn-primary" style="width: 100%; padding: 14px 28px; font-size: 14px;">
                            <i class="fas fa-check-circle mr-1 text-xs"></i>
                            <span>Kirim Pendaftaran Delegasi</span>
                        </button>
                    </form>
                </div>

                <!-- Info Card -->
                <div>
                    <div class="mod-card" style="padding: 26px; margin-bottom: 24px;">
                        <span class="mod-tag-pill">Panduan Delegasi</span>
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--mod-text-main); margin: 6px 0 16px 0;">
                            Ketentuan Pendaftaran
                        </h3>

                        <div style="space-y: 14px; font-size: 13px; color: var(--mod-text-main); line-height: 1.7;">
                            <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                                <i class="fas fa-check-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Pastikan kontak delegasi yang dicantumkan aktif untuk penerimaan barcode absensi dan e-certificate SKP resmi.</span>
                            </div>
                            <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                                <i class="fas fa-check-circle text-red-600 mt-1 shrink-0"></i>
                                <span>Anggota klinik terverifikasi ASKLIN berhak atas kuota khusus dan benefit kepesertaan komprehensif.</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: var(--mod-radius-lg); padding: 26px; color: #fff; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <h4 style="font-size: 15px; font-weight: 800; color: #fca5a5; margin: 0 0 10px 0;">
                            Bantuan Pendaftaran?
                        </h4>
                        <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin-bottom: 16px;">
                            Hubungi panitia helpdesk kami via WhatsApp jika memerlukan bantuan registrasi delegasi atau akomodasi hotel.
                        </p>
                        <a href="https://api.whatsapp.com/send?phone=6282310981637" target="_blank" class="mod-btn mod-btn-primary" style="width: 100%; font-size: 13px;">
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

