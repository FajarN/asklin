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
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-columns"></i><span>Master
                            Data</span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown @if (Request::url() == route('fasilitas.index')) active @endif"><a class="nav-link"
                                href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
                        <li class="dropdown @if (Request::url() == route('fasilitas_klinik.index')) active @endif"><a class="nav-link"
                                href="{{ route('fasilitas_klinik.index') }}">Fasilitas Klinik</a></li>
                        <li class="dropdown @if (Request::url() == route('layanan.index')) active @endif"><a class="nav-link"
                                href="{{ route('layanan.index') }}">Layanan</a></li>
                        <li class="dropdown @if (Request::url() == route('berita_kategori.index')) active @endif"><a class="nav-link"
                                href="{{ route('berita_kategori.index') }}">Kategori Berita</a></li>
                        <li class="dropdown @if (Request::url() == route('event_kategori.index')) active @endif"><a class="nav-link"
                                href="{{ route('event_kategori.index') }}">Kategori Event</a></li>
                        <li class="dropdown @if (Request::url() == route('kategori_pembayaran.index')) active @endif"><a class="nav-link"
                                href="{{ route('kategori_pembayaran.index') }}">Kategori Pembayaran</a></li>
                    </ul>
                </li>
            @endhasrole

            @hasanyrole('Superadmin|Admin Konas')
                <li class="menu-header">KONAS</li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-th-large"></i></i>
                        <span>Module Konas</span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown @if (Request::url() == route('konas_master_hotel.index')) active @endif"><a class="nav-link"
                                href="{{ route('konas_master_hotel.index') }}">Hotel</a></li>

                        <li class="dropdown @if (Request::url() == route('konas_master_tipe_hotel.index')) active @endif"><a class="nav-link"
                                href="{{ route('konas_master_tipe_hotel.index') }}">Tipe
                                Hotel</a></li>

                        <li class="dropdown @if (Request::url() == route('konas.index')) active @endif"><a class="nav-link"
                                href="{{ route('konas.index') }}">Peserta Konas</a></li>

                        <li class="dropdown @if (Request::url() == route('konas_booking.index')) active @endif"><a class="nav-link"
                                href="{{ route('konas_booking.index') }}">Booking Hotel</a></li>

                        <li class="dropdown @if (Request::url() == route('konas_penerbangan.index')) active @endif"><a class="nav-link"
                                href="{{ route('konas_penerbangan.index') }}">Data Penerbangan</a></li>

                        <li class="dropdown @if (Request::url() == route('partner.index')) active @endif"><a class="nav-link"
                                href="{{ route('partner.index') }}">Partner</a></li>
                    </ul>
                </li>
            @endhasanyrole

            @can('verifikasi-anggota')
                <li class="menu-header">Ruang Verifikasi</li>
                <li class="dropdown @if (Request::url() == route('verifikasi_anggota.index')) active @endif"><a class="nav-link"
                        href="{{ route('verifikasi_anggota.index') }}"><i class="far  fa-calendar-check"></i>
                        <span>Verifikasi Anggota</span></a></li>
            @endcan

            @can('verifikasi')
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

            @can('agenda-list')
                <li class="menu-header">Ruang Administrasi</li>
                <li class="dropdown @if (Request::url() == route('agenda.index')) active @endif"><a class="nav-link"
                        href="{{ route('agenda.index') }}"><i class="far fa-edit"></i> Agenda Kerja</a></li>
            @endcan

            @can('sertifikat')
                <li class="dropdown @if (Request::url() == route('sertifikat.index')) active @endif"><a class="nav-link"
                        href="{{ route('sertifikat.index') }}"><i class="far fa-calendar-plus"></i> Create Sertifikat</a>
                </li>
            @endcan

            @can('pembayaran')
                <li class="menu-header">Pembayaran Iuran</li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i> <span>
                            Pembayaran Cabang</span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown @if (Request::url() == route('pembayaran.pangkal')) active @endif"><a class="nav-link"
                                href="{{ route('pembayaran.pangkal') }}">Verif Uang Pangkal</a></li>
                        <li class="dropdown @if (Request::url() == route('pembayaran.index')) active @endif"><a class="nav-link"
                                href="{{ route('pembayaran.index') }}">Verif Iuran</a></li>
                    </ul>
                </li>
                @can('pembayaran-pusat')
                    <li class="dropdown @if (Request::url() == route('pembayaran_pusat.index')) active @endif"><a class="nav-link"
                            href="{{ route('pembayaran_pusat.index') }}"><i class="far  fa-file-alt"></i>
                            <span>Bukti Pembayaran Daerah/Pusat</span></a></li>
                @endcan


                @can('secret')
                    <li class="menu-header">Laporan</li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i>
                            <span>Laporan Anggota</span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown @if (Request::url() == route('laporan_pusat')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_pusat') }}">Laporan pusat</a></li>
                            <li class="dropdown @if (Request::url() == route('laporan_daerah')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_daerah') }}">Laporan daerah</a></li>
                            <li class="dropdown @if (Request::url() == route('laporan_cabang')) active @endif"><a class="nav-link"
                                    href="{{ route('laporan_cabang') }}">Laporan cabang</a></li>
                        </ul>
                    </li>
                @endcan


            @endcan
            <li class="menu-header">Content</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-folder-open"></i><span>Website</span></a>
                <ul class="dropdown-menu">
                    @can('events-list')
                        <li class="dropdown @if (Request::url() == route('events.index')) active @endif"><a
                                href="{{ route('events.index') }}">Event</a></li>
                    @endcan
                    @can('berita-list')
                        <li class="dropdown @if (Request::url() == route('berita.index')) active @endif"><a
                                href="{{ route('berita.index') }}">Berita</a></li>
                    @endcan
                    <!--<li><a href="banner.html">Banner</a></li> -->
                    @can('galery')
                        <li class="dropdown @if (Request::url() == route('galery.index')) active @endif"><a
                                href="{{ route('galery.index') }}">Gallery</a></li>
                    @endcan
                    <!--<li><a href="kontak.html">Kontak</a></li> -->
                </ul>
            </li>


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
                    </ul>
                </li>
            @endcan
        </ul>
    </aside>
</div>
