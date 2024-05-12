@extends('layouts.frontend.layout')
 
@section('content')
    @include('frontend.konas.header')
    @include('frontend.konas.menu')
    
    
   <section id="content">
        <div class="content-wrap">

            
            <div class="container clearfix">

                <div class="fancy-title topmargin title-border">
                <h4>AGENDA KONAS ASKLIN </h4>
            </div>
            
                <div class="row">
                    <object data="{{ asset('assets/frontend/ANNOUNCEMENT_BOOK.pdf') }}" width="100%" height="1200px"  type="application/pdf" ></object>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')

@endpush

@push('js')

@endpush