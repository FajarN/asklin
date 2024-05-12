@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Agenda</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('agenda.index') }}">Agenda</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>
    </section>
    <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Jenis Agenda *</label>
                                <select class="form-control" name="jenis">
                                    <option value="Rapat Pleno">Rapat Pleno</option>
                                    <option value="Rapat Pleno Diperluas">Rapat Pleno Diperluas</option>
                                    <option value="Rapat Pengurus Harian">Rapat Pengurus Harian</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal / Jam *</label>
                                <input type="text" class="form-control datetimepicker" name="tanggal">
                            </div>
                            <div class="form-group">
                                <label>Perihal *</label>
                                <input type="text" class="form-control" name="perihal">
                            </div>
                            <div class="form-group">
                                <label>Tempat / Ruang *</label>
                                <textarea class="form-control" name="tempat"></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                            <a href="{{ route('agenda.index') }}" class="btn btn-info">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Pimpinan Rapat *</label>
                            <input type="text" class="form-control" name="pimpinan">
                        </div>
                        <div class="form-group">
                            <label>Notulen Rapat *</label>
                            <input type="text" class="form-control" name="notulen">
                        </div>
                        <div class="form-group">
                            <label>Keputusan *</label>
                            <textarea name="keputusan" class="summernote"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
@endpush