@extends('frontend.templates.modern_org.layouts.app')

@section('content')

    <!-- Inner Header -->
    <div class="mod-inner-header">
        <div class="modern-org-container">
            <div class="mod-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #fff; font-weight: 700;">Hubungi Kami</span>
            </div>
            <h1 class="mod-inner-title">Pusat Layanan Kontak &amp; Dukungan</h1>
            <p class="mod-inner-sub">Sekretariat Pengurus Pusat Asosiasi Klinik Indonesia (PP ASKLIN)</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mod-section">
        <div class="modern-org-container">

            <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 40px;">

                <!-- Office Contact Information -->
                <div>
                    <div class="mod-card" style="margin-bottom: 24px;">
                        <span class="mod-tag-pill">Pusat Informasi</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--mod-text-main); margin: 6px 0 16px 0;">
                            Sekretariat PP ASKLIN
                        </h2>
                        <p style="font-size: 14px; color: var(--mod-text-muted); line-height: 1.7; margin-bottom: 24px;">
                            Pusat koordinasi advokasi perlindungan klinik, penerbitan rekomendasi keanggotaan, dan layanan kemitraan kesehatan.
                        </p>

                        <div style="space-y: 18px;">
                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-main); display: block;">Alamat Sekretariat:</strong>
                                    <span style="font-size: 13px; color: var(--mod-text-muted); line-height: 1.6;">
                                        Jl. Pinang Ranti II, Kel. Pinang Ranti, Kec. Makasar,<br>
                                        Jakarta Timur, DKI Jakarta - 13560
                                    </span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-main); display: block;">WhatsApp Layanan:</strong>
                                    <a href="https://api.whatsapp.com/send?phone=6282310981637" target="_blank" style="font-size: 14px; font-weight: 700; color: var(--mod-primary);">
                                        0823-1098-1637
                                    </a>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-main); display: block;">Email Korespondensi:</strong>
                                    <a href="mailto:pp_asklin@asklin.org" style="font-size: 13px; font-weight: 600; color: var(--mod-primary);">
                                        pp_asklin@asklin.org
                                    </a>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--mod-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--mod-text-main); display: block;">Jam Kerja Pelayanan:</strong>
                                    <span style="font-size: 13px; color: var(--mod-text-muted);">
                                        Senin – Jumat: 08.30 – 17.00 WIB
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Contact Message Form -->
                <div>
                    <div class="mod-card">
                        <span class="mod-tag-pill">Tanya Kami</span>
                        <h3 style="font-size: 20px; font-weight: 800; color: var(--mod-text-main); margin: 6px 0 20px 0;">
                            Kirimkan Pesan atau Aspirasi
                        </h3>

                        <form id="formKontakMod">
                            @csrf
                            <div style="margin-bottom: 18px;">
                                <label for="nama" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">
                                    Nama Lengkap / Instansi Klinik <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="nama" name="nama" required placeholder="Nama Anda atau nama klinik" style="width: 100%; padding: 12px 18px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                            </div>

                            <div style="margin-bottom: 18px;">
                                <label for="email" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">
                                    Email Aktif <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="email" id="email" name="email" required placeholder="nama@email.com" style="width: 100%; padding: 12px 18px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px;">
                            </div>

                            <div style="margin-bottom: 22px;">
                                <label for="pesan" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--mod-text-main); margin-bottom: 6px;">
                                    Isi Pesan / Pertanyaan <span style="color: #ef4444;">*</span>
                                </label>
                                <textarea id="pesan" name="pesan" rows="5" required placeholder="Tuliskan pesan pertanyaan atau kebutuhan pendampingan Anda..." style="width: 100%; padding: 12px 18px; border: 1px solid var(--mod-border); border-radius: 12px; font-size: 13.5px; font-family: inherit;"></textarea>
                            </div>

                            <button type="submit" id="btnSubmitKontakMod" class="mod-btn mod-btn-primary" style="width: 100%; font-size: 14px; padding: 13px 24px;">
                                <i class="fas fa-paper-plane mr-1 text-xs"></i>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('formKontakMod');
        const btn = document.getElementById('btnSubmitKontakMod');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sedang Mengirim...';

                const formData = new FormData(form);

                fetch("{{ route('storeKontak') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-1 text-xs"></i> <span>Kirim Pesan Sekarang</span>';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesan Terkirim!',
                            text: data.message,
                            confirmButtonColor: '#dc2626'
                        });
                        form.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-1 text-xs"></i> <span>Kirim Pesan Sekarang</span>';
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kendala saat mengirim data.',
                        confirmButtonColor: '#dc2626'
                    });
                });
            });
        }
    });
</script>
@endpush

