@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pengaturan Sistem</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Settings Management</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            @include('app_settings::_settings')
        </div>
    </div>
</div>
@endsection
