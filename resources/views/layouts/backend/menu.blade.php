<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.index') }}"><img src="{{ asset('storage/' . setting('logo')) }}" alt=""
                    width="80"></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard.index') }}"><img src="{{ asset('storage/' . setting('logo')) }}" alt=""
                    width="40"></a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header"></li>
            <li class="dropdown @if (Request::url() == route('dashboard.index')) active @endif">
                <a href="{{ route('dashboard.index') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            @hasrole('Superadmin|Admin Pusat')
                @canany(['fasilitas-list', 'fasilitas-klinik--list', 'layanan-list', 'kategori-berita-list',
                    'kategori-event-list', 'kategori-pembayaran-list'])
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-columns"></i><span>Master
                                Data</span></a>
                        <ul class="dropdown-menu">
                            @can('fasilitas-list')
                                <li class="dropdown @if (Request::url() == route('fasilitas.index')) active @endif"><a class="nav-link"
                                        href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
                            @endcan
                            @can('fasilitas-klinik-list')
                                <li class="dropdown @if (Request::url() == route('fasilitas_klinik.index')) active @endif"><a class="nav-link"
                                        href="{{ route('fasilitas_klinik.index') }}">Fasilitas Klinik</a></li>
                            @endcan
                            @can('layanan-list')
                                <li class="dropdown @if (Request::url() == route('layanan.index')) active @endif"><a class="nav-link"
                                        href="{{ route('layanan.index') }}">Layanan</a></li>
                            @endcan
                            @can('kategori-berita-list')
                                <li class="dropdown @if (Request::url() == route('berita_kategori.index')) active @endif"><a class="nav-link"
                                        href="{{ route('berita_kategori.index') }}">Kategori Berita</a></li>
                            @endcan
                            @can('kategori-event-list')
                                <li class="dropdown @if (Request::url() == route('event_kategori.index')) active @endif"><a class="nav-link"
                                        href="{{ route('event_kategori.index') }}">Kategori Event</a></li>
                            @endcan
                            @can('kategori-pembayaran-list')
                                <li class="dropdown @if (Request::url() == route('kategori_pembayaran.index')) active @endif"><a class="nav-link"
                                        href="{{ route('kategori_pembayaran.index') }}">Kategori Pembayaran</a></li>
                            @endcan
                            @if(auth()->check() && (auth()->user()->can('master-ketua-umum-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Sekjen', 'Ketua Umum'])))
                                <li class="dropdown @if (Request::url() == route('master_ketua_umum.index')) active @endif"><a class="nav-link"
                                        href="{{ route('master_ketua_umum.index') }}">Master Ketua Umum</a></li>
                            @endif
                        </ul>
                    </li>
                @endcanany
            @endhasrole

            @can('verifikasi-anggota')
                <li class="menu-header">Ruang Verifikasi</li>
                <li class="dropdown @if (Request::url() == route('verifikasi_anggota.index')) active @endif"><a class="nav-link"
                        href="{{ route('verifikasi_anggota.index') }}"><i class="far  fa-calendar-check"></i>
                        <span>Verifikasi Anggota</span></a></li>
            @endcan

            @can('data-anggota')
                <li class="menu-header">Data Terverifikasi</li>
                <li class="dropdown @if (Request::url() == route('anggota.index')) active @endif"><a class="nav-link"
                        href="{{ route('anggota.index') }}"><i class="far fa-user"></i><span>Data Anggota</span></a>
                </li>
            @endcan

            @can('kerjasama-asuransi')
                <li class="dropdown @if (Request::url() == route('kerjasama_asuransi.index')) active @endif"><a class="nav-link"
                        href="{{ route('kerjasama_asuransi.index') }}"><i class="far fa-credit-card"></i>Asuransi
                        Anggota</a></li>
            @endcan

            @can('expired-sio')
                <li class="dropdown @if (Request::url() == route('expired_sio.index')) active @endif"><a class="nav-link"
                        href="{{ route('expired_sio.index') }}"><i class="fas fa-calendar-times"></i>Expired SIO</a></li>
            @endcan

            @can('agenda-list')
                <li class="menu-header">Ruang Administrasi</li>
                <li class="dropdown @if (Request::url() == route('agenda.index')) active @endif"><a class="nav-link"
                        href="{{ route('agenda.index') }}"><i class="far fa-edit"></i> Agenda Kerja</a></li>
            @endcan

            @can('sertifikat-list')
                <li class="dropdown @if (Request::url() == route('sertifikat.index')) active @endif"><a class="nav-link"
                        href="{{ route('sertifikat.index') }}"><i class="far fa-calendar-plus"></i> Create Sertifikat</a>
                </li>
            @endcan

            @can('expired-sertifikat')
                <li class="dropdown @if (Request::url() == route('expired_sertifikat.index')) active @endif"><a class="nav-link"
                        href="{{ route('expired_sertifikat.index') }}"><i class="fas fa-calendar-times"></i>Expired
                        Sertifikat</a></li>
            @endcan

            @canany(['pembayaran-list', 'pembayaran-pusat-list'])
                <li class="menu-header">Pembayaran Iuran Anggota</li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i> <span>
                            Verifikasi Iuran</span></a>
                    <ul class="dropdown-menu">
                        @can('pembayaran-list')
                            <li class="dropdown @if (Request::url() == route('pembayaran.pangkal')) active @endif"><a class="nav-link"
                                    href="{{ route('pembayaran.pangkal') }}">Verif Uang Pangkal</a></li>
                        @endcan
                        @can('pembayaran-list')
                            <li class="dropdown @if (Request::url() == route('pembayaran.index')) active @endif"><a class="nav-link"
                                    href="{{ route('pembayaran.index') }}">Verif Iuran</a></li>
                        @endcan
                    </ul>
                </li>

                @can('pembayaran-pusat-list')
                    <li class="dropdown @if (Request::url() == route('pembayaran_pusat.index')) active @endif"><a class="nav-link"
                            href="{{ route('pembayaran_pusat.index') }}"><i class="far  fa-file-alt"></i>
                            <span>Bukti Pembayaran Daerah/Pusat</span></a></li>
                @endcan
            @endcanany


            @if(auth()->check() && (auth()->user()->canany(['jenis-surat-list', 'penomoran-pusat-list', 'surat-keluar-list', 'surat-tugas-list', 'surat-undangan-list', 'struktur-organisasi-list', 'master-ketua-umum-list', 'sk-pengurus-list']) || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Daerah', 'Admin Cabang', 'Sekjen', 'Ketua Umum'])))
                <li class="menu-header">Ruang Pengurus</li>
                @if(auth()->user()->can('struktur-organisasi-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Daerah', 'Admin Cabang', 'Sekjen', 'Ketua Umum']))
                    <li class="dropdown @if (Request::url() == route('struktur_organisasi.index')) active @endif"><a class="nav-link"
                            href="{{ route('struktur_organisasi.index') }}"><i class="fas fa-user-md"></i> Struktur
                            Organisasi</a></li>
                @endif
                @if(auth()->user()->can('master-ketua-umum-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Sekjen', 'Ketua Umum']))
                    <li class="dropdown @if (Request::url() == route('master_ketua_umum.index')) active @endif"><a class="nav-link"
                            href="{{ route('master_ketua_umum.index') }}"><i class="fas fa-user-tie"></i> Master Ketua Umum</a></li>
                @endif
                @if(auth()->user()->can('sk-pengurus-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Daerah', 'Admin Cabang', 'Sekjen', 'Ketua Umum']))
                    <li class="dropdown @if (Request::url() == route('sk_pengurus.index')) active @endif"><a class="nav-link"
                            href="{{ route('sk_pengurus.index') }}"><i class="fas fa-file-signature"></i> Surat Keputusan (SK)</a></li>
                @endif
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-regular fa-envelope"></i>
                        <span>Surat</span></a>
                    <ul class="dropdown-menu">
                        @if(auth()->user()->can('sk-pengurus-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Daerah', 'Admin Cabang', 'Sekjen', 'Ketua Umum']))
                            <li class="dropdown @if (Request::url() == route('sk_pengurus.index')) active @endif"><a class="nav-link"
                                    href="{{ route('sk_pengurus.index') }}">Surat Keputusan (SK)</a></li>
                        @endif
                        @can('jenis-surat-list')
                            <li class="dropdown @if (Request::url() == route('jenis_surat.index')) active @endif"><a class="nav-link"
                                    href="{{ route('jenis_surat.index') }}">Jenis Surat</a></li>
                        @endcan
                        @can('penomoran-surat-list')
                            <li class="dropdown @if (Request::url() == route('surat_penomoran.index')) active @endif"><a class="nav-link"
                                    href="{{ route('surat_penomoran.index') }}">Penomoran Surat</a></li>
                        @endcan
                        @can('surat-keluar-list')
                            <li class="dropdown @if (Request::url() == route('surat_keluar.index')) active @endif"><a class="nav-link"
                                    href="{{ route('surat_keluar.index') }}">Surat Keluar</a></li>
                        @endcan
                        @can('surat-tugas-list')
                            <li class="dropdown @if (Request::url() == route('surat_tugas.index')) active @endif"><a class="nav-link"
                                    href="{{ route('surat_tugas.index') }}">Surat Tugas</a></li>
                        @endcan
                        @can('surat-undangan-list')
                            <li class="dropdown @if (Request::url() == route('surat_undangan.index')) active @endif"><a class="nav-link"
                                    href="{{ route('surat_undangan.index') }}">Surat Undangan</a></li>
                        @endcan
                    </ul>
                </li>
            @endif

            @canany(['laporan-pusat', 'laporan-daerah', 'laporan-cabang'])
                <li class="menu-header">Laporan</li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i>
                        <span>Laporan Anggota</span></a>
                    <ul class="dropdown-menu">
                        @can('laporan-pusat')
                            <li class="dropdown @if (Request::url() == route('laporan_pusat.index')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_pusat.index') }}">Laporan pusat</a></li>
                        @endcan
                        @can('laporan-daerah')
                            <li class="dropdown @if (Request::url() == route('laporan_daerah')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_daerah') }}">Laporan daerah</a></li>
                        @endcan
                        @can('laporan-cabang')
                            <li class="dropdown @if (Request::url() == route('laporan_cabang')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_cabang') }}">Laporan cabang</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            @php
                $isKonasActive = request()->routeIs('konas_admin.*') || request()->is('backend/konas*') || request()->is('backend/peserta-konas*') || request()->is('backend/booking-hotel*') || request()->is('backend/data-penerbangan*') || request()->is('backend/konas-master-hotel*') || request()->is('backend/konas-master-tipe-hotel*') || request()->is('backend/partner*');
                $isEventActive = request()->routeIs('events.*');
                $isWebsiteActive = request()->routeIs('slider.*') || $isEventActive || $isKonasActive || request()->routeIs('berita.*') || request()->routeIs('galery.*') || request()->routeIs('kontak.*');
            @endphp
            @if(auth()->check() && (auth()->user()->canany(['slider-list', 'berita-list', 'gallery-list', 'kontak-list']) || auth()->user()->hasRole(['Superadmin', 'Admin Pusat'])))
                <li class="menu-header">Content</li>
                <li class="dropdown @if (request()->routeIs('slider.*') || request()->routeIs('berita.*') || request()->routeIs('galery.*') || request()->routeIs('kontak.*')) active @endif">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i
                            class="fas fa-folder-open"></i><span>Website</span></a>
                    <ul class="dropdown-menu">
                        @can('slider-list')
                            <li class="dropdown @if (Request::url() == route('slider.index')) active @endif"><a
                                    href="{{ route('slider.index') }}">Slider</a></li>
                        @endcan
                        @can('berita-list')
                            <li class="dropdown @if (Request::url() == route('berita.index')) active @endif"><a
                                    href="{{ route('berita.index') }}">Berita</a></li>
                        @endcan
                        @can('gallery-list')
                            <li class="dropdown @if (Request::url() == route('galery.index')) active @endif"><a
                                    href="{{ route('galery.index') }}">Gallery</a></li>
                        @endcan
                        @can('kontak-list')
                            <li class="dropdown @if (Request::url() == route('kontak.index')) active @endif"><a
                                    href="{{ route('kontak.index') }}">Kontak</a></li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->check() && (auth()->user()->can('event-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Konas']) || auth()->user()->canany(['konas-master-hotel-list', 'konas-tipe-hotel-list', 'konas-peserta-list', 'konas-booking-hotel-list', 'konas-data-penerbangan-list', 'konas-partner-list'])))
                <li class="dropdown @if ($isEventActive || $isKonasActive) active @endif">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i
                            class="fas fa-calendar-alt"></i><span>Agenda Event</span></a>
                    <ul class="dropdown-menu">
                        @can('event-list')
                            <li class="@if ($isEventActive) active @endif"><a class="nav-link"
                                    href="{{ route('events.index') }}">Semua Event</a></li>
                        @endcan
                        @if(auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Konas']) || auth()->user()->canany(['konas-master-hotel-list', 'konas-tipe-hotel-list', 'konas-peserta-list', 'konas-booking-hotel-list', 'konas-data-penerbangan-list', 'konas-partner-list']))
                            <li class="@if ($isKonasActive) active @endif"><a class="nav-link"
                                    href="{{ route('konas_admin.index') }}">Event Konas</a></li>
                        <li class="@if (request()->routeIs('rakernas_admin.*') || request()->is('backend/rakernas*')) active @endif"><a class="nav-link"
                                href="{{ route('rakernas_admin.index') }}">Event Rakernas</a></li>
                    </ul>
                </li>
            @endif


            @can('secret')
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-cogs"></i>
                        <span>Pengaturan</span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown @if (Request::url() == route('users.index')) active @endif"><a class="nav-link"
                                href="{{ route('users.index') }}">User</a></li>
                        <li class="dropdown @if (Request::url() == route('roles.index')) active @endif"><a class="nav-link"
                                href="{{ route('roles.index') }}">Group</a></li>
                        <li class="dropdown @if (Request::url() == route('permissions.index')) active @endif"><a class="nav-link"
                                href="{{ route('permissions.index') }}">Permission</a></li>
                        <li class="dropdown @if (Request::url() == route('settings.index')) active @endif"><a class="nav-link"
                                href="{{ route('settings.index') }}">Setting</a></li>
                        <li class="dropdown @if (Request::url() == route('smtp_settings.index')) active @endif"><a class="nav-link"
                                href="{{ route('smtp_settings.index') }}">Setting Email SMTP</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </aside>
</div>
