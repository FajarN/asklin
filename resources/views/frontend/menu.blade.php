<div id="page-menu" data-mobile-sticky="true">
    <div id="page-menu-wrap">
        <div class="container">
            <div class="page-menu-row">
                <div class="page-menu-title" style="color:black;">Asosiasi Klinik Indonesia <span>ASKLIN</span></div>
                <nav class="page-menu-nav">
                    <ul class="page-menu-container">
                        <li class="page-menu-item {{ (strpos(Route::currentRouteName(), 'user.profile') == 0) ? 'current' : '' }}"><a href="{{ route('user.profile') }}"><div>Profil Klinik</div></a></li>
                         <li  class="page-menu-item {{ (strpos(Route::currentRouteName(), 'tagihan') == 0) ? 'current' : '' }}"><a href="{{ route('tagihan') }}"><div>Pembayaran Iuran</div></a></li>
                        @if($anggota->status == 'approved')
                            <li class="page-menu-item {{ (strpos(Route::currentRouteName(), 'sertifikat') == 0) ? 'current' : '' }}"><a href="{{ route('sertifikat') }}">Cetak Sertifikat<div></div></a></li>
                        @endif
                        {{-- <li class="page-menu-item"><a href="{{ route('agendakegiatan') }}"><div>Agenda & Kegiatan</div></a></li> --}}
                    </ul>
                </nav>
                <div id="page-menu-trigger"><i class="icon-reorder"></i></div>
            </div>
        </div>
    </div>
</div>