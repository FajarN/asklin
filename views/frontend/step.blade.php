<ul class="process-steps row col-mb-30 justify-content-center mb-4">
    <li class="col-md-3 col-sm-6">
        <a href="{{ route('syarat.ketentuan') }}" class="i-circled i-alt mx-auto {{ (request()->is('syarat-ketentuan')) ? 'bg-color' : '' }}">1</a>
        <h5>Syarat & Ketentuan</h5>
    </li>
    <li class="col-md-3 col-sm-6" >
         <a href="{{ route('pendaftaran.anggota') }}" class="i-circled i-alt mx-auto {{ (request()->is('pendaftaran-anggota')) ? 'bg-color' : '' }}">2</a>
        <h5>Input Data Klinik</h5>
    </li>
    <li class="col-md-3 col-sm-6">
        <a href="{{ route('pendaftaran.sdm') }}" class="i-circled i-alt mx-auto {{ (request()->is('pendaftaran-sdm')) ? 'bg-color' : '' }}">3</a>
        <h5>Input Data SDM</h5>
    </li>
    <li class="col-md-3 col-sm-6">
        <a href="#" class="i-circled i-alt mx-auto">4</a>
        <h5>Selesai</h5>
    </li>
</ul>