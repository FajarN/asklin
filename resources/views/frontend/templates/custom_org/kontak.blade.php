@extends('frontend.templates.custom_org.layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="org-page-header">
        <div class="custom-org-container">
            <div class="org-breadcrumbs">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <span style="color: #ffffff; font-weight: 700;">Hubungi Kami</span>
            </div>
            <h1 class="org-page-title">Pusat Layanan Kontak ASKLIN</h1>
            <p class="org-page-subtitle">Kantor Sekretariat Pengurus Pusat Asosiasi Klinik Indonesia (PP ASKLIN)</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="org-section">
        <div class="custom-org-container">

            <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 40px;">

                <!-- Left: Office Coordinates -->
                <div>
                    <div class="org-content-card" style="margin-bottom: 24px;">
                        <span class="org-section-eyebrow">Sekretariat Resmi</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--org-secondary); margin: 6px 0 16px 0;">
                            Kantor Pengurus Pusat
                        </h2>
                        <p style="font-size: 13.5px; color: var(--org-text-muted); line-height: 1.7; margin-bottom: 24px;">
                            Silakan menghubungi kami untuk keperluan keanggotaan klinik, informasi rekomendasi izin operasional, advokasi, atau kerjasama program kesehatan.
                        </p>

                        <div style="space-y: 18px;">
                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--org-secondary); display: block;">Alamat Kantor:</strong>
                                    <span style="font-size: 13px; color: var(--org-text-main); line-height: 1.6;">
                                        Jl. Pinang Ranti II, Kel. Pinang Ranti, Kec. Makasar,<br>
                                        Jakarta Timur, DKI Jakarta - 13560
                                    </span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--org-secondary); display: block;">WhatsApp Layanan:</strong>
                                    <a href="https://api.whatsapp.com/send?phone=6282310981637" target="_blank" style="font-size: 14px; font-weight: 700; color: var(--org-primary);">
                                        0823-1098-1637
                                    </a>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px; margin-bottom: 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--org-secondary); display: block;">Email Korespondensi:</strong>
                                    <a href="mailto:pp_asklin@asklin.org" style="font-size: 13px; font-weight: 600; color: var(--org-primary);">
                                        pp_asklin@asklin.org
                                    </a>
                                </div>
                            </div>

                            <div style="display: flex; gap: 14px;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--org-primary-soft); color: var(--org-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; shrink-0;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 13px; color: var(--org-secondary); display: block;">Jam Operasional:</strong>
                                    <span style="font-size: 13px; color: var(--org-text-main);">
                                        Senin s/d Jumat: 08.30 – 17.00 WIB
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: Contact Message Form -->
                <div>
                    <div class="org-content-card">
                        <span class="org-section-eyebrow">Kirim Pesan / Pengaduan</span>
                        <h3 style="font-size: 20px; font-weight: 800; color: var(--org-secondary); margin: 6px 0 20px 0;">
                            Formulir Pesan Sekretariat
                        </h3>

                        <form id="formKontakOrg" style="space-y: 18px;">
                            @csrf
                            <div style="margin-bottom: 18px;">
                                <label for="nama" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">
                                    Nama Lengkap / Nama Klinik <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="nama" name="nama" required placeholder="Contoh: dr. Ahmad / Klinik Sehat Prima" style="width: 100%; padding: 12px 16px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                            </div>

                            <div style="margin-bottom: 18px;">
                                <label for="email" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">
                                    Alamat Email Aktif <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="email" id="email" name="email" required placeholder="nama@email.com" style="width: 100%; padding: 12px 16px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px;">
                            </div>

                            <div style="margin-bottom: 22px;">
                                <label for="pesan" style="display: block; font-size: 12.5px; font-weight: 700; color: var(--org-secondary); margin-bottom: 6px;">
                                    Pesan / Pertanyaan / Saran <span style="color: #ef4444;">*</span>
                                </label>
                                <textarea id="pesan" name="pesan" rows="5" required placeholder="Tuliskan pesan atau kebutuhan informasi Anda secara jelas..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--org-border); border-radius: 8px; font-size: 13.5px; font-family: inherit;"></textarea>
                            </div>

                            <button type="submit" id="btnSubmitKontak" class="org-btn org-btn-primary" style="width: 100%; font-size: 14px; padding: 12px 24px;">
                                <i class="fas fa-paper-plane mr-1 text-xs"></i>
                                <span>Kirimkan Pesan Resmi</span>
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
        const form = document.getElementById('formKontakOrg');
        const btn = document.getElementById('btnSubmitKontak');

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
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-1 text-xs"></i> <span>Kirimkan Pesan Resmi</span>';

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
                            title: 'Gagal Mengirim',
                            text: data.message || 'Terjadi kesalahan sistem.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-1 text-xs"></i> <span>Kirimkan Pesan Resmi</span>';
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan koneksi saat mengirim pesan.',
                        confirmButtonColor: '#dc2626'
                    });
                });
            });
        }
    });
</script>
@endpush

