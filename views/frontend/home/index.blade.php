@extends('layouts.frontend.layout')
 
@section('content')
	<div id='map'  style="height: 450px; margin-top: 0px; position: relative; outline: medium none; z-index: -1;"></div>

    <div class="row bottommargin-lg align-items-stretch">
        <div class="col-lg-4 dark col-padding overflow-hidden text-center" style="background-color: #f00404;">
            <div>
                <h3 class="text-uppercase " style="font-weight: 600;">EVENT YANG LALU</h3>
                {{-- <p style="line-height: 1.8;">Lorem ipsum dolor, sit, amet consectetur adipisicing elit. Rerum, nesciunt?</p> --}}
                <a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
                <i class="icon-calendar bgicon"></i>
            </div>
        </div>

        <div class="col-lg-4 dark col-padding overflow-hidden text-center" style="background-color: #ccc;">
            <div>
                <h3 class="text-uppercase" style="font-weight: 600;">EVENT SEDANG BERLANGSUNG</h3>
                {{-- <p style="line-height: 1.8;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, dolor!</p> --}}
                <a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
                <i class="icon-beaker bgicon"></i>
            </div>
        </div>

        <div class="col-lg-4 dark col-padding overflow-hidden text-center" style="background-color: #f00404;">
            <div>
                <h3 class="text-uppercase" style="font-weight: 600;">EVENT AKAN DATANG</h3>
                {{-- <p style="line-height: 1.8;">Lorem ipsum, dolor, sit amet consectetur adipisicing elit. Doloremque, placeat!</p> --}}
                <a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
                <i class="icon-thumbs-up bgicon"></i>
            </div>
        </div>
    </div>

    <div class="section topmargin-lg parallax" style="padding: 80px 0 60px; background-image: url('{{ asset("assets/frontend/demos/course/images/icon-pattern.jpg") }}'); background-size: auto; background-repeat: repeat"  data-bottom-top="background-position:0px 100px;" data-top-bottom="background-position:0px -500px;">
        <div class="wave-top" style="position: absolute; top: 0; left: 0; width: 100%; background-image: url('{{ asset("assets/frontend/demos/course/images/wave-3.svg") }}'); height: 12px; z-index: 2; background-repeat: repeat-x;"></div>

        <div class="heading-block border-bottom-0 mb-5 center">
			<h3>KEPENGURUSAN ASKLIN</h3>
		</div>

        <div class="row mt-2">
            <div class="col-md-4 mb-5">
				<div class="card course-card hover-effect border-0">
					<a href="#"><img class="card-img-top" src="{{ asset('assets/frontend/images/baru/penguruspusat.JPG') }}" alt="Card image cap"></a>
					<div class="card-body">
						<h4 class="card-title fw-bold mb-2"><a href="#">PENGURUS PUSAT</a></h4>
						{{-- <p class="card-text text-black-50 mb-1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nam!</p> --}}
					</div>
					<div class="card-footer py-3 d-flex justify-content-between align-items-center bg-white text-muted">
						<a href="#" class="text-dark position-relative"><i class="icon-line2-user"></i> <span class="author-number">1</span></a>
					</div>
				</div>
			</div>
            <div class="col-md-4 mb-5">
				<div class="card course-card hover-effect border-0">
					<a href="#"><img class="card-img-top" src="{{ asset('assets/frontend/images/baru/daerahpengurus.JPG') }}" alt="Card image cap"></a>
					<div class="card-body">
						<h4 class="card-title fw-bold mb-2"><a href="#">PENGURUS DAERAH</a></h4>
						{{-- <p class="card-text text-black-50 mb-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, corporis.</p> --}}
					</div>
					<div class="card-footer py-3 d-flex justify-content-between align-items-center bg-white text-muted">
						<a href="#" class="text-dark position-relative"><i class="icon-line2-user"></i> <span class="author-number">1</span></a>
					</div>
				</div>
			</div>
            <div class="col-md-4 mb-5">
				<div class="card course-card hover-effect border-0">
					<a href="#"><img class="card-img-top" src="{{ asset('assets/frontend/images/baru/cabangpengurus.JPG') }}" alt="Card image cap"></a>
					<div class="card-body">
						<h4 class="card-title fw-bold mb-2"><a href="#">PENGURUS CABANG</a></h4>
						{{-- <p class="card-text text-black-50 mb-1">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat, harum.</p> --}}
					</div>
					<div class="card-footer py-3 d-flex justify-content-between align-items-center bg-white text-muted">
						<a href="#" class="text-dark position-relative"><i class="icon-line2-user"></i> <span class="author-number">1</span></a>
					</div>
				</div>
			</div>
        </div>
    </div>
@endsection
 
@push('js')
<script>
    var map = L.map('map').setView([-0.789275,113.921327], 5);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var greenIcon = L.icon({
        iconUrl: '{{ asset("assets/frontend/marker.png") }}',iconSize:     [38, 38],});
    var pusaticon = L.icon({
        iconUrl: '{{ asset("assets/frontend/p.png") }}',iconSize:     [38, 38],});
    
    L.marker([-6.289076, 106.882895], {icon: pusaticon}).bindPopup("ASOSIASI KLINIK INDONESIA (Pusat) <br>www.asklin.org<br><i class=\"fa fa-envelope\"></i> pp_asklin@asklin.org").addTo(map);
    L.marker([-6.182671, 106.86799], {icon: greenIcon}).bindPopup("ASOSIASI KLINIK INDONESIA (DKI) <br>www.asklin.org<br><i class=\"fa fa-envelope\"></i> dki@asklin.org").addTo(map);
    
    L.marker([5.548290, 95.323756], {icon: greenIcon}).bindPopup("ASKLIN ACEH <br>www.asklin.org/web/aceh<br><i class=\"fa fa-envelope\"></i> aceh@asklin.org ").addTo(map); 
    L.marker([3.595196, 98.672223], {icon: greenIcon}).bindPopup("ASKLIN SUMATRA UTARA <br>www.asklin.org/web/sumut<br><i class=\"fa fa-envelope\"></i> sumut@asklin.org").addTo(map); 
    L.marker([-0.947083, 100.417181], {icon: greenIcon}).bindPopup("ASKLIN SUMATRA BARAT <br>www.asklin.org/web/sumbar<br><i class=\"fa fa-envelope\"></i> sumbar@asklin.org").addTo(map); 
    L.marker([0.293347, 101.706829], {icon: greenIcon}).bindPopup("ASKLIN RIAU <br>www.asklin.org/web/riau<br><i class=\"fa fa-envelope\"></i> riau@asklin.org").addTo(map); 
    L.marker([-3.577847, 102.346387], {icon: greenIcon}).bindPopup("ASKLIN BENGKULU <br>www.asklin.org/web/bengkulu<br><i class=\"fa fa-envelope\"></i> bengkulu@asklin.org").addTo(map); 
    L.marker([-1.610123, 103.61312], {icon: greenIcon}).bindPopup("ASKLIN JAMBI <br>www.asklin.org/web/jambi<br><i class=\"fa fa-envelope\"></i> jambi@asklin.org").addTo(map); 
    L.marker([-2.976074, 104.775431], {icon: greenIcon}).bindPopup("ASKLIN SUMATRA SELATAN <br>www.asklin.org/web/sumsel<br><i class=\"fa fa-envelope\"></i> sumsel@asklin.org").addTo(map); 
    L.marker([1.148847, 104.020091], {icon: greenIcon}).bindPopup("ASKLIN KEPULAUAN RIAU <br>www.asklin.org/web/kepri<br>jl.Budi Kemuliaan no.1 Seraya - Batam<br>08117010980<br><i class=\"fa fa-envelope\"></i> kepri@asklin.org").addTo(map); 
    L.marker([-5.376902, 105.273523], {icon: greenIcon}).bindPopup("ASKLIN LAMPUNG <br>www.asklin.org/web/lampung<br>Jl.Raja Basa Raya Blok T-21 Way Halim <i class=\"fa fa-envelope\"></i> lampung@asklin.org").addTo(map); 
    L.marker([-2.112719, 106.103495], {icon: greenIcon}).bindPopup("ASKLIN BANGKA BELITUNG <br>www.asklin.org/web/babel<br> jl.Bukit Baru Pangkalpinan<br>(0717 ) 421091 <br><i class=\"fa fa-envelope\"></i> babel@asklin.org").addTo(map); 
    L.marker([-6.128428, 106.148894], {icon: greenIcon}).bindPopup("ASKLIN BANTEN <br>www.asklin.org/web/banten<br>Jl.TB.SUWANDI PURI RAYA No 17 Serang <br>0254 220422<br><i class=\"fa fa-envelope\"></i> banten@asklin.org").addTo(map); 
    L.marker([-6.896009, 107.619798], {icon: greenIcon}).bindPopup("ASKLIN JAWA BARAT <br>asklinjabar.org<br>Jl.Titiran no.16 <br> 022 -2507293 <br> <i class=\"fa fa-envelope\"></i> jabar@asklin.org").addTo(map); 
    L.marker([-6.986126, 110.46038], {icon: greenIcon}).bindPopup("ASKLIN JAWA TENGAH <br>www.asklin.org/web/jateng<br>jl.Ratu Ratih 2/28 Tlogosari Semarang <br>024-6717721,0812 1299 0039<i class=\"fa fa-envelope\"></i> jateng@asklin.org").addTo(map); 
    L.marker([-7.257472, 112.752088], {icon: greenIcon}).bindPopup("ASKLIN JAWA TIMUR <br>www.asklin.org/web/jatim<br><i class=\"fa fa-envelope\"></i> jatim@asklin.org").addTo(map); 
    L.marker([-7.795580, 110.36949], {icon: greenIcon}).bindPopup("ASKLIN JOGJAKARTA <br>www.asklin.org/web/jogja<br><i class=\"fa fa-envelope\"></i> jogja@asklin.org").addTo(map); 
    L.marker([1.488881, 124.846642], {icon: greenIcon}).bindPopup("ASKLIN SULAWESI UTARA <br>www.asklin.org/web/sulut<br>Jln Sudirman No.78 <br>085388888816 <br><i class=\"fa fa-envelope\"></i> sulut@asklin.org").addTo(map); 
    L.marker([-5.149946, 119.464253], {icon: greenIcon}).bindPopup("ASKLIN SULAWESI SELATAN <br>www.asklin.org/web/sulsel<br>jl Abdullah Dg Sirua No 360 C Makassar <br>0811417541<i class=\"fa fa-envelope\"></i> sulsel@asklin.org").addTo(map); 
    L.marker([0.699937, 122.446724], {icon: greenIcon}).bindPopup("ASKLIN GORONTALO <br>www.asklin.org/web/gorontalo<br><i class=\"fa fa-envelope\"></i> gorontalo@asklin.org").addTo(map); 
    L.marker([-8.652933, 117.361648], {icon: greenIcon}).bindPopup("ASKLIN NTB <br>www.asklin.org/web/ntb<br><i class=\"fa fa-envelope\"></i> ntb@asklin.org").addTo(map); 
    L.marker([-0.079467, 109.38106], {icon: greenIcon}).bindPopup("ASKLIN KALIMANTAN BARAT <br>www.asklin.org/web/kalbar<br>Adi Sucipto gg Bumi Raya No 33-35 Kubu - Raya Pontianak <br> (0561) 721993,081258137260<br><i class=\"fa fa-envelope\"></i> kalbar@asklin.org").addTo(map); 
    L.marker([-0.494823, 117.143615], {icon: greenIcon}).bindPopup("ASKLIN KALIMANTAN TIMUR <br>www.asklin.org/web/kaltim<br><i class=\"fa fa-envelope\"></i> kaltim@asklin.org").addTo(map); 
    L.marker([-1.430025, 121.445618], {icon: greenIcon}).bindPopup("ASKLIN SULAWESI TENGAH <br>www.asklin.org/web/sulteng<br><i class=\"fa fa-envelope\"></i> sulteng@asklin.org").addTo(map); 
    L.marker([-3.238462, 130.145273], {icon: greenIcon}).bindPopup("ASKLIN MALUKU <br>www.asklin.org/web/maluku<br><i class=\"fa fa-envelope\"></i> maluku@asklin.org").addTo(map); 
</script>
<script>
    (function () {
        var revapi;
        revapi = jQuery('.tp-banner').revolution(
        {
            delay:9000,
            startwidth:1170,
            startheight:682,
            hideThumbs:200,
            fullWidth:"on",
            forceFullWidth:"on"
        });
    })();
</script>
@endpush