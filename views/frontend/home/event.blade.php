@extends('layouts.frontend.layout')
 
@section('content')

				<div class="section mt-0 overflow-visible">
					<div class="container">
						<div class="row justify-content-center center">
							<div class="col-lg-7">
								<div class="heading-block border-bottom-0 mb-4">
									<h2 class="mb-3">EVENT ASKLIN</h2>
									
								</div>
								<div class="input-group input-group-lg mb-4">
									<input type="text" class="form-control w-auto" aria-label="Text input with dropdown button" placeholder="Search..">
									<select class="form-select col col-4">
										<option selected value="All">All</option>
										<option value="Business">Kongres</option>
										<option value="Design">MUSDA</option>
										<option value="Tech">MUSCAB</option>
										<option value="Fashion">Seminar</option>
										<option value="Music">Simposium</option>
										<option value="Software">Workshop</option>
									</select>
									<button class="btn bg-color text-white border-0" type="button"><i class="icon-search"></i></button>
								</div>
								<a class="button button-rounded" href="#" role="button">Submit</a>
							</div>
						</div>
					</div>
				</div>
                <div class="container">
					<div class="row">

                    @foreach($event as $i)
                    	
                        <div class="col-lg-4 col-sm-6 mb-4">
							<div class="i-products">
								<div class="products-image">
									<a href=""{{ route('event_asklin.detail', $i->path) }}">
										<img src="{{ asset('assets/images/events').'/'.$i->gambar }}" alt="Image 1">
										<span class="badge">{{ $i->nama }}</span>
									</a>
								</div>
								<div class="products-desc">
									<h3><a href=""{{ route('event_asklin.detail', $i->path) }}">{{ $i->judul }}</a></h3>
									<p>{{ $i->konten }}</p>
									<div class="clear"></div>
									
									<div class="products-hoverlays">
										{{-- <span class="products-location"><i class="icon-map-marker1"></i> Jakarta</span> --}}
										{{-- <ul class="list-group-flush my-3 mb-0">
											<li class="list-group-item"><strong>4</strong> Narasumber</li>
											<li class="list-group-item"><strong>500</strong> Peserta</li>
											<li class="list-group-item"><strong>20</strong> Hari Tersisa</li>
										</ul> --}}
										<div class="product-user d-flex align-items-center mt-3">
											<a href="{{ route('event_asklin.detail', $i->path) }}">{{ $i->created_at->diffForHumans() }}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
                          @endforeach
                </div>

                <div class="d-flex justify-content-between">
                    {!! $event->links() !!}
                </div>
				
            </div>
        </div>
    </section>
@endsection