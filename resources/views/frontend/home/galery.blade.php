@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Galery</h1>
            <span>Galery ASKLIN</span>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div id="oc-news" class="owl-carousel fixed-nav top-nav carousel-widget posts-md customjs">
                    @foreach($galery as $i)
						<div class="entry mb-0" style="background: url('{{ asset("/assets/images/galery")."/".$i->foto }}') center center; background-size: cover; height: 400px;">
							<div class="bg-overlay">
								<div class="bg-overlay-content text-overlay-mask dark desc-sm align-items-end justify-content-start p-4">
									<div class="position-relative w-100">
										
										<div class="entry-title nott">
											<h3 class="fw-semibold mb-2"><a href="#" class="text-light">{{ $i->judul }}</a></h3>
										</div>
										<div class="entry-meta no-separator">
											<ul>
												<li><span>by</span> <a href="#">{{ $i->name }}</a></li>
												<li><i class="icon-time"></i><a href="#">{{ $i->created_at }}</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
	$(window).on( 'pluginCarouselReady', function(){
		$('#oc-news').owlCarousel({
			items: 1,
			margin: 20,
			dots: false,
			nav: true,
			navText: ['<i class="icon-angle-left"></i>','<i class="icon-angle-right"></i>'],
			responsive:{
				0:{ items: 1,dots: true, },
				576:{ items: 1,dots: true },
				768:{ items: 2,dots:true },
				992:{ items: 2 },
				1200:{ items: 3 }
			}
		});
	});
</script>
@endpush