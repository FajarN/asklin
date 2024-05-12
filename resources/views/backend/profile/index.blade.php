@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Profile</div>
            </div>
        </div>
    </section>
    <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
                <div class="profile-widget-header">                     
                    <img alt="image" src="{{ asset('assets/backend/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                    <div class="profile-widget-items">
                        <div class="profile-widget-description">
                            <div class="profile-widget-name">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form action="{{ route('profile.update', Auth::user()->id) }}" method="post" class="needs-validation">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">                               
                            <div class="form-group col-md-6 col-12">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required="">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-7 col-12">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group col-md-5 col-12">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="confirm-password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection