<!-- Official Footer -->
<footer class="org-footer">
    <div class="custom-org-container">
        <div class="org-footer-grid">

            <!-- Column 1: Organization Identity -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                        <img src="{{ asset('storage/' . setting('logo')) }}" alt="ASKLIN Logo" style="height: 48px; width: auto; filter: brightness(0) invert(1);">
                    @else
                        <div style="width: 44px; height: 44px; border-radius: 8px; background: #dc2626; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;">A</div>
                    @endif
                    <div>
                        <h4 style="font-size: 18px; font-weight: 800; color: #fff; margin: 0; line-height: 1.2;">ASKLIN</h4>
                        <span style="font-size: 11px; color: #cbd5e1;">Asosiasi Klinik Indonesia</span>
                    </div>
                </div>
                <p class="org-footer-desc">
                    Wadah resmi berhimpunnya fasilitas kesehatan klinik di seluruh wilayah Negara Kesatuan Republik Indonesia. Mewujudkan klinik yang mandiri, bermutu tinggi, berdaya saing, dan berorientasi pada keselamatan pasien.
                </p>
                <div style="display: flex; gap: 8px; margin-top: 18px;">
                    <a href="#" style="width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px;" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px;" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px;" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" style="width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px;" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Column 2: Profil & Legalitas -->
            <div>
                <h4 class="org-footer-title">Profil &amp; Struktur</h4>
                <ul class="org-footer-links">
                    <li><a href="{{ route('sejarah') }}"><i class="fas fa-angle-right"></i> Sejarah Pendirian ASKLIN</a></li>
                    <li><a href="{{ route('visimisi') }}"><i class="fas fa-angle-right"></i> Visi, Misi &amp; Sasaran</a></li>
                    <li><a href="{{ route('latarbelakang') }}"><i class="fas fa-angle-right"></i> Latar Belakang &amp; Landasan Hukum</a></li>
                    <li><a href="{{ route('penguruspusat') }}"><i class="fas fa-angle-right"></i> Susunan Pengurus Pusat</a></li>
                    <li><a href="{{ route('konas') }}"><i class="fas fa-angle-right"></i> Konferensi Nasional (Konas)</a></li>
                </ul>
            </div>

            <!-- Column 3: Layanan & Keanggotaan -->
            <div>
                <h4 class="org-footer-title">Layanan Klinik</h4>
                <ul class="org-footer-links">
                    <li><a href="{{ route('register') }}"><i class="fas fa-angle-right"></i> Registrasi Anggota Baru</a></li>
                    <li><a href="{{ route('syarat.ketentuan') }}"><i class="fas fa-angle-right"></i> Pedoman &amp; Ketentuan</a></li>
                    <li><a href="{{ route('berita') }}"><i class="fas fa-angle-right"></i> Warta &amp; Berita Terbaru</a></li>
                    <li><a href="{{ route('event_asklin') }}"><i class="fas fa-angle-right"></i> Agenda Pelatihan / Seminar</a></li>
                    <li><a href="{{ route('galery') }}"><i class="fas fa-angle-right"></i> Dokumentasi Kegiatan</a></li>
                </ul>
            </div>

            <!-- Column 4: Kontak Sekretariat -->
            <div>
                <h4 class="org-footer-title">Sekretariat Pusat</h4>
                <div class="org-footer-contact-item">
                    <i class="fas fa-building"></i>
                    <span>Pengurus Pusat Asosiasi Klinik Indonesia (PP ASKLIN)<br>Jakarta Timur, Daerah Khusus Ibukota Jakarta</span>
                </div>
                <div class="org-footer-contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>Telepon / WhatsApp: 021-8690 1234 / 0812-3456-7890</span>
                </div>
                <div class="org-footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>ppasklin.indonesia@gmail.com</span>
                </div>
                <div class="org-footer-contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Senin - Jumat: 08.30 - 17.00 WIB</span>
                </div>
            </div>

        </div>

        <div class="org-footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>. Seluruh Hak Cipta Dilindungi Undang-Undang.
            </div>
            <div style="display: flex; gap: 16px;">
                <a href="{{ route('syarat.ketentuan') }}" style="color: #6ee7b7; font-size: 11px;">Ketentuan Layanan</a>
                <span>•</span>
                <a href="{{ route('kontak') }}" style="color: #6ee7b7; font-size: 11px;">Pusat Kontak Bantuan</a>
                <span>•</span>
                <span style="color: #34d399; font-size: 11px; font-weight: 700;">Template: Custom Organization</span>
            </div>
        </div>
    </div>
</footer>

