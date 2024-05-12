@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
	<section id="content">
	    <div class="content-wrap">
		    <div class="container clearfix">

<form action="" method="post">
        <section id="slider" class="slider-element min-vh-100 py-5 include-header" style="background: linear-gradient(to top, rgba(55, 55, 55, 0.922), rgba(42, 41, 41, 0.922)), url('http://asklin.org/assets/frontend/images/baru/konas.png') no-repeat center center/ cover;">
    		<div class="slider-inner">
    			<div class="row justify-content-center align-items-center h-100">
    				<div class="col-lg-4 col-sm-7 col-10 mt-sm-5">
    					<div class="form-widget">
    					    @csrf
    						<div class="tab-content" id="nav-tabContent">
    							<div class="tab-pane show active" role="tabpanel" aria-labelledby="get-started-form-select-dates">
    								<div class="row align-items-center">
    									<div class="col-12">
    										<div class="center mb-5 dark">
    											<h3 class="fw-semibold display-5 mb-4 text-white">Pendaftaran Konas Sudah ditutup</h3>
    											
                                                <a href="{{ route('konas.tour') }}" class="button button-rounded button-large tab-action-btn-next bg-danger mt-5 w-50 py-3">Kembali</a>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</section>
    </form>


@endsection

@push('css')

@endpush

@push('js')

@endpush