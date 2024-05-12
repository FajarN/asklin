<div id="page-menu" data-mobile-sticky="true">
    <div id="page-menu-wrap">
        <div class="container">
            <div class="page-menu-row">
                <div class="page-menu-title">Asosiasi Klinik Indonesia <span>{{ setting('nama_aplikasi', 'ASKLIN') }}</span></div>
                <nav class="page-menu-nav">
                    <ul class="page-menu-container">
                        <li class="page-menu-item {{ (request()->is('sejarah')) ? 'current' : '' }}"><a href="{{ route('sejarah') }}"><div>Sejarah</div></a></li>
                        <li class="page-menu-item {{ (request()->is('visi_misi')) ? 'current' : '' }}"><a href="{{ route('visimisi') }}"><div>Visi & Misi</div></a></li>
                        <li class="page-menu-item {{ (request()->is('latar_belakang')) ? 'current' : '' }}"><a href="{{ route('latarbelakang') }}"><div>Latar Belakang</div></a></li>
                        <li class="page-menu-item {{ (request()->is('pengurus_pusat')) ? 'current' : '' }}"><a href="{{ route('penguruspusat') }}"><div>Pengurus Pusat</div></a></li>
                        {{-- <li class="page-menu-item {{ (request()->is('agenda_kegiatan')) ? 'current' : '' }}"><a href="{{ route('agendakegiatan') }}"><div>Agenda Kegiatan</div></a></li> --}}
                    </ul>
                </nav>
                <div id="page-menu-trigger"><i class="icon-reorder"></i></div>
            </div>
        </div>
    </div>
</div>