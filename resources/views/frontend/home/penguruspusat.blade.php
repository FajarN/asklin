@extends('layouts.frontend.layout')
 
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>PENGURUS PUSAT</h1>
        </div>
    </section>

    @include('frontend.home.menu')

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="row">
                    <iframe src="{{ asset('assets/frontend/sk_ppasklin.pdf') }}" width="100%" height="1200px"></iframe>
                </div>
            </div>
        </div>
    </section>
@endsection