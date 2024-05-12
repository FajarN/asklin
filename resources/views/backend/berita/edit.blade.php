@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Update Berita</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></div>
                <div class="breadcrumb-item">Edit</div>
            </div>
        </div>
    </section>
    <form action="{{ route('berita.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Kategori *</label>
                                <select class="form-control" name="id_kategori">
                                    @foreach($kategori as $i)
                                        <option value="{{ $i->id }}" {{ old('id_kategori',$data->id_kategori)==$i->id? 'selected':'' }}>{{ $i->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Judul *</label>
                                <input type="text" class="form-control" name="judul" id="title" value="{{ $data->judul }}">
                            </div>
                            <div class="form-group">
                                <label>URL *</label>
                                <input type="text" class="form-control" name="path" id="path" value="{{ $data->path }}">
                            </div>
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="file" class="form-control" name="gambar">
                                <img class="img-fluid" src="{{ asset('assets/images/berita').'/'.$data->gambar }}">
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
                                <option value="0" {{ old('status',$data->status)=="0"? 'selected':'' }}>Draft</option>
                                <option value="1" {{ old('status',$data->status)=="1"? 'selected':'' }}>Live</option>
                            </select>
                        </div>
                        @endhasanyrole
                        <div class="form-group">
                            <label>Konten *</label>
                            <textarea name="konten" class="summernote">{{ $data->konten }}</textarea>
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