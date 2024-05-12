@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>AGENDA & KEGIATAN</h1>
            <span>Kegiatan Asklin</span>
        </div>
    </section>

    @include('frontend.home.menu')

    <section id="content">
			<div class="content-wrap">
				<div class="container clearfix">

					<div class="row">

						<div class="entry event col-12">
							<div class="grid-inner row align-items-center g-0 p-4">
								<div class="col-md-4 mb-md-0">
									<a href="#" class="entry-image">
										<img src="images/events/thumbs/1.jpg" alt="Inventore voluptates velit totam ipsa tenetur">
										<div class="entry-date">10<span>Apr</span></div>
									</a>
								</div>
								<div class="col-md-8 ps-md-4">
									<div class="entry-title title-sm">
										<h2><a href="#">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eligendi, officiis.</a></h2>
									</div>
									<div class="entry-meta">
										<ul>
											<li><span class="badge bg-warning text-dark px-1 py-1">Private</span></li>
											<li><a href="#"><i class="icon-time"></i> 11:00 - 19:00</a></li>
											<li><a href="#"><i class="icon-map-marker2"></i> Melbourne, Australia</a></li>
										</ul>
									</div>
									<div class="entry-content">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem blanditiis enim culpa reiciendis et explicabo tenetur voluptate rerum molestiae eaque possimus exercitationem eligendi fuga.</p>
									 <a href="#" class="btn btn-danger">Read More</a>
									</div>
								</div>
							</div>
						</div>

						<div class="entry event col-12">
							<div class="grid-inner row align-items-center g-0 p-4">
								<div class="col-md-4 mb-md-0">
									<a href="#" class="entry-image">
										<img src="images/events/thumbs/2.jpg" alt="Nemo quaerat nam beatae iusto minima vel">
										<div class="entry-date">16<span>Apr</span></div>
									</a>
								</div>
								<div class="col-md-8 ps-md-4">
									<div class="entry-title title-sm">
										<h2><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit.</a></h2>
									</div>
									<div class="entry-meta">
										<ul>
											<li><span class="badge bg-danger px-1 py-1">Urgent</span></li>
											<li><a href="#"><i class="icon-time"></i> 11:00 - 19:00</a></li>
											<li><a href="#"><i class="icon-map-marker2"></i> Perth, Australia</a></li>
										</ul>
									</div>
									<div class="entry-content">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, voluptatem, dolorem animi nisi autem blanditiis enim culpa reiciendis et explicabo tenetur voluptate rerum molestiae eaque possimus exercitationem eligendi fuga.</p>
										 <a href="#" class="btn btn-danger">Read More</a>
									</div>
								</div>
							</div>
						</div>

						

					</div>

					<!-- Pager
					============================================= -->
					<div class="d-flex justify-content-between">
						<a href="#" class="btn btn-outline-secondary">&larr; Older</a>
						<a href="#" class="btn btn-outline-dark">Newer &rarr;</a>
					</div>
					<!-- .pager end -->

				</div>
			</div>
		</section>
@endsection