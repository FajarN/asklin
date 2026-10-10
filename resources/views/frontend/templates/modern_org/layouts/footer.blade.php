<!-- Modern Organization Footer -->
<footer class="mod-footer-v3">
    <div class="modern-org-container">
        <div class="mod-footer-grid">

            <!-- Column 1: Brand & Modern Mission -->
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 18px;">
                    @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                        <img src="{{ asset('storage/' . setting('logo')) }}" alt="ASKLIN Logo" style="height: 44px; width: auto; filter: brightness(0) invert(1);">
                    @else
                        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #dc2626, #991b1b); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;">A</div>
                    @endif
                    <div>
                        <h4 style="font-size: 18px; font-weight: 800; color: #fff; margin: 0; line-height: 1.2;">ASKLIN</h4>
                        <span style="font-size: 11px; color: #fca5a5;">Asosiasi Klinik Indonesia</span>
                    </div>
                </div>
                <p style="font-size: 13.5px; line-height: 1.7; color: #94a3b8; margin: 0 0 20px 0;">
                    Organisasi profesi dan wadah berhimpun fasilitas pelayanan kesehatan klinik seluruh Indonesia. Mendorong kemandirian, akselerasi transformasi digital klinik, peningkatan mutu klinis, dan keselamatan pasien.
                </p>
                <div style="display: flex; gap: 10px;">
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); color: #fca5a5; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid rgba(255,255,255,0.1);"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); color: #fca5a5; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid rgba(255,255,255,0.1);"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); color: #fca5a5; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid rgba(255,255,255,0.1);"><i class="fab fa-youtube"></i></a>
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); color: #fca5a5; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid rgba(255,255,255,0.1);"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Column 2: Profil -->
            <div>
                <h4 class="mod-footer-title">Organisasi</h4>
                <ul class="mod-footer-links">
                    <li><a href="{{ route('sejarah') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Sejarah ASKLIN</a></li>
                    <li><a href="{{ route('visimisi') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Visi, Misi &amp; Program</a></li>
                    <li><a href="{{ route('latarbelakang') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Landasan Regulasi</a></li>
                    <li><a href="{{ route('penguruspusat') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Susunan Pengurus</a></li>
                    <li><a href="{{ route('konas') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Kongres Nasional (Konas)</a></li>
                </ul>
            </div>

            <!-- Column 3: Fasilitas & Kegiatan -->
            <div>
                <h4 class="mod-footer-title">Portal &amp; Media</h4>
                <ul class="mod-footer-links">
                    <li><a href="{{ route('register') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Registrasi Keanggotaan</a></li>
                    <li><a href="{{ route('berita') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Publikasi &amp; Kabar</a></li>
                    <li><a href="{{ route('event_asklin') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Pelatihan &amp; Workshop</a></li>
                    <li><a href="{{ route('galery') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Dokumentasi Foto</a></li>
                    <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right text-[10px] text-red-400"></i> Portal Masuk Pengurus</a></li>
                </ul>
            </div>

            <!-- Column 4: Sekretariat & Layanan -->
            <div>
                <h4 class="mod-footer-title">Sekretariat</h4>
                <div style="font-size: 13px; line-height: 1.6; color: #94a3b8; space-y: 12px;">
                    <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                        <i class="fas fa-building text-red-400 mt-1"></i>
                        <span>Pengurus Pusat ASKLIN<br>Jakarta Timur, DKI Jakarta - 13560</span>
                    </div>
                    <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                        <i class="fas fa-phone-alt text-red-400 mt-1"></i>
                        <span>021-8690 1234 / 0823-1098-1637</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <i class="fas fa-envelope text-red-400 mt-1"></i>
                        <span>pp_asklin@asklin.org</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="mod-footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>. Seluruh Hak Cipta Dilindungi.
            </div>
            <div style="display: flex; gap: 18px; align-items: center;">
                <a href="{{ route('kontak') }}" style="color: #64748b; font-size: 12px;">Pusat Dukungan</a>
                <span>•</span>
                <a href="{{ route('syarat.ketentuan') }}" style="color: #64748b; font-size: 12px;">Kebijakan Privasi</a>
                <span>•</span>
                <span style="color: #fca5a5; font-size: 12px; font-weight: 700;">Template 3 — Modern Organization</span>
            </div>
        </div>
    </div>
</footer>

