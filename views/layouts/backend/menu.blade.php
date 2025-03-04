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
            <li class="dropdown active">
                <a href="{{ route('dashboard.index') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            @hasrole('Superadmin|Admin Pusat')
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-columns"></i><span>Master
                            Data</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
                        <li><a class="nav-link" href="{{ route('fasilitas_klinik.index') }}">Fasilitas Klinik</a></li>
                        <li><a class="nav-link" href="{{ route('layanan.index') }}">Layanan</a></li>
                        <li><a class="nav-link" href="{{ route('berita_kategori.index') }}">Kategori Berita</a></li>
                        <li><a class="nav-link" href="{{ route('event_kategori.index') }}">Kategori Event</a></li>
                        <li><a class="nav-link" href="{{ route('kategori_pembayaran.index') }}">Kategori Pembayaran</a>
                        </li>
                    </ul>
                </li>
            @endhasrole

            @hasanyrole('Superadmin|Admin Konas')
                <li class="menu-header">KONAS</li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-th-large"></i></i>
                        <span>Module Konas</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('konas_master_hotel.index') }}"><span>Hotel</span></a></li>
                        <li><a class="nav-link" href="{{ route('konas_master_tipe_hotel.index') }}"><span>Tipe
                                    Hotel</span></a></li>
                        <li><a class="nav-link" href="{{ route('konas.index') }}"><span>Peserta Konas</span></a></li>
                        <li><a class="nav-link" href="{{ route('konas_booking.index') }}"><span>Booking Hotel</span></a>
                        </li>
                        <li><a class="nav-link" href="{{ route('konas_penerbangan.index') }}"><span>Data
                                    Penerbangan</span></a></li>
                        <li><a class="nav-link" href="{{ route('partner.index') }}"><span>Partner</span></a></li>
                    </ul>
                </li>
            @endhasanyrole

            @can('verifikasi-anggota')
                <li class="menu-header">Ruang Verifikasi</li>
                <li><a class="nav-link" href="{{ route('verifikasi_anggota.index') }}"><i
                            class="far  fa-calendar-check"></i> <span>Verifikasi Anggota</span></a></li>
            @endcan
            @can('verifikasi')
                <li class="menu-header">Data Anggota</li>
                <li><a class="nav-link" href="{{ route('verifikasi.index') }}"><i class="far fa-user"></i> <span>Data
                            Anggota</span></a></li>
            @endcan
            @can('kerjasama-asuransi')
                <li><a class="nav-link" href="{{ route('kerjasama_asuransi.index') }}"><i class="far fa-credit-card"></i>
                        <span>Data Kerja Sama Asuransi</span></a></li>
            @endcan
            @can('agenda-list')
                <li><a class="nav-link" href="{{ route('agenda.index') }}"><i class="far fa-edit"></i> <span>Agenda
                            Kerja</span></a></li>
            @endcan

            @can('pembayaran')
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i> <span>Verifikasi
                            Pembayaran</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('pembayaran.pangkal') }}">Verif Uang Pangkal</a></li>
                        <li><a class="nav-link" href="{{ route('pembayaran.index') }}"><span>Verif Iuran</span></a></li>
                    </ul>
                </li>
                @can('pembayaran-pusat')
                    <li><a class="nav-link" href="{{ route('pembayaran_pusat.index') }}"><i class="far  fa-file-alt"></i>
                            <span>Pembayaran Daerah/Pusat</span></a></li>
                @endcan
                @can('sertifikat')
                    <li><a class="nav-link" href="{{ route('sertifikat.index') }}"><i class="far fa-calendar-plus"></i>
                            <span>Create Sertifikat</span></a></li>
                @endcan

                @can('secret')
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i>
                            <span>Laporan</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ route('laporan_pusat') }}">Laporan pusat</a></li>
                            <li><a class="nav-link" href="{{ route('laporan_daerah') }}">Laporan daerah</a></li>
                            <li><a class="nav-link" href="{{ route('laporan_cabang') }}">Laporan cabang</a></li>
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
                        <li><a href="{{ route('events.index') }}">Event</a></li>
                    @endcan
                    @can('berita-list')
                        <li><a href="{{ route('berita.index') }}">Berita</a></li>
                    @endcan
                    <!--<li><a href="banner.html">Banner</a></li> -->
                    @can('galery')
                        <li><a href="{{ route('galery.index') }}">Gallery</a></li>
                    @endcan
                    <!--<li><a href="kontak.html">Kontak</a></li> -->
                </ul>
            </li>


            @can('secret')
                <li class="dropdown">
                    <a href="javascript:void(0)" class="nav-link has-dropdown"><i class="fas fa-cogs"></i>
                        <span>Pengaturan</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('users.index') }}">User</a></li>
                        <li><a class="nav-link" href="{{ route('roles.index') }}">Group</a></li>
                        <li><a class="nav-link" href="{{ route('permissions.index') }}">Permission</a></li>
                        <li><a class="nav-link" href="{{ route('settings.index') }}">Setting</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </aside>
</div>
