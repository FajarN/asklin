@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Berita</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>
    </section>
    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Kategori *</label>
                                <select class="form-control" name="id_kategori">
                                    @foreach($kategori as $i)
                                        <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Judul *</label>
                                <input type="text" class="form-control" name="judul" id="title">
                            </div>
                            <div class="form-group">
                                <label>URL *</label>
                                <input type="text" class="form-control" name="path" id="path">
                            </div>
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                            <a href="{{ route('berita.index') }}" class="btn btn-info">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @hasanyrole('Superadmin|Admin Pusat')
                        <div class="form-group">
                            <label>Status *</label>
                            <select class="form-control" name="status">
                                <option value="0">Draft</option>
                                <option value="1">Live</option>
                            </select>
                        </div>
                        @endhasanyrole
                        <div class="form-group">
                            <label>Konten *</label>
                            <textarea name="konten" class="summernote"></textarea>
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
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script>
$("#title").keyup(function() {
  var Text = $(this).val();
  Text = Text.toLowerCase();
  var regExp = /\s+/g;
  Text = Text.replace(regExp,'-');
  $("#path").val(Text);        
});
</script>
@endpush