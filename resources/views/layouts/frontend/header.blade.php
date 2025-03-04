<header id="header" class="full-header transparent-header semi-transparent" data-sticky-logo-height="74"
    data-menu-padding="32">
    <div id="header-wrap">
        <div class="container">
            <div class="header-row">

                <div id="logo">
                    <a href="{{ route('home') }}" class="standard-logo"
                        data-dark-logo="/storage/{{ setting('logo') }}"><img src="/storage/{{ setting('logo') }}"
                            alt="ASKLIN"></a>
                    <a href="{{ route('home') }}" class="retina-logo"
                        data-dark-logo="/storage/{{ setting('logo') }}"><img src="/storage/{{ setting('logo') }}"
                            alt="ASKLIN"></a>
                </div>

                <div class="header-misc">
                    <div id="top-search" class="header-misc-icon">
                        <a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i
                                class="icon-line-cross"></i></a>
                    </div>
                </div>

                <div id="primary-menu-trigger">
                    <svg class="svg-trigger" viewBox="0 0 100 100">
                        <path
                            d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
                        </path>
                        <path d="m 30,50 h 40"></path>
                        <path
                            d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
                        </path>
                    </svg>
                </div>

                <nav class="primary-menu sub-title">
                    <ul class="menu-container">
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('home') }}">
                                <div>Home</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="#">
                                <div>Tentang Kami</div>
                            </a>
                            <ul class="sub-menu-container">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('sejarah') }}">
                                        <div>Sejarah</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('visimisi') }}">
                                        <div>Visi & Misi</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('latarbelakang') }}">
                                        <div>Latar Belakang</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('penguruspusat') }}">
                                        <div>Pengurus Pusat</div>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="menu-item">
                            @guest
                                <a class="menu-link" href="{{ route('register') }}">
                                    <div>Pendaftaran Anggota</div>
                                </a>
                            @else
                                @hasrole('Superadmin|Admin Cabang|Admin Daerah|Admin Pusat|Sekjen|Ketua Umum|Bendahara|Admin
                                    Konas')
                                    <a class="menu-link" href="{{ route('dashboard.index') }}">
                                        <div>Ruang Pengurus</div>
                                    </a>
                                @endhasrole
                                @hasrole('Klinik')
                                    <a class="menu-link" href="{{ route('user.profile') }}">
                                        <div>Ruang Entry Data</div>
                                    </a>
                                @endhasrole
                                @hasrole('Peserta')
                                    <a class="menu-link" href="{{ route('konas.akun') }}">
                                        <div>Akun Konas</div>
                                    </a>
                                @endhasrole
                            @endguest
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('berita') }}">
                                <div>Berita</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('event_asklin') }}">
                                <div>Event</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('galery') }}">
                                <div>Galery</div>
                            </a>
                        </li>
                          <li class="menu-item">
                            <a class="menu-link" href="{{ route('rakernas') }}">
                                <div>Rakernas</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('konas') }}">
                                <div>Konas</div>
                            </a>
                            <ul class="sub-menu-container">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('konas.pendahuluan') }}">
                                        <div>Pendahuluan</div>
                                    </a>
                                </li>
                                {{-- <li class="menu-item">
                                    <a class="menu-link" href="{{ route('konas.sambutan_gubernur_aceh') }}"><div>Sambutan Gubernur Aceh</div></a>
                                </li> --}}
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('konas.sambutan_ketum') }}">
                                        <div>Sambutan Ketua Umum</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('konas.sambutan_ketua_aceh') }}">
                                        <div>Sambutan Ketua Wilayah Aceh</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('konas.sambutan_ketua_panitia') }}">
                                        <div>Sambutan Ketua Panitia</div>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link" href="{{ route('kontak') }}">
                                <div>Kontak</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            @guest
                                <a class="menu-link" href="{{ route('login') }}">
                                    <div>Login</div>
                                </a>
                            @else
                                <a class="menu-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>


                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @endguest
                        </li>
                    </ul>
                </nav>

                <form class="top-search-form" action="search.html" method="get">
                    <input type="text" name="q" class="form-control" value=""
                        placeholder="Type &amp; Hit Enter.." autocomplete="off">
                </form>
            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header>
