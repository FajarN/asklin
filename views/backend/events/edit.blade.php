@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Update Event</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></div>
                <div class="breadcrumb-item">Edit</div>
            </div>
        </div>
    </section>
    <form action="{{ route('events.update', $data->id) }}" method="POST" enctype="multipart/form-data">
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
                                <img class="img-fluid" src="{{ asset('assets/images/events').'/'.$data->gambar }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Mulai *</label>
                                <input type="datetime-local" class="form-control" name="mulai" value="{{ $data->mulai }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Selesai *</label>
                                <input type="datetime-local" class="form-control" name="selesai" value="{{ $data->selesai }}">
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                            <a href="{{ route('events.index') }}" class="btn btn-info">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Propinsi</label>
                            <select class="form-control" name="id_provinsi" id="id_provinsi">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}" {{ old('id_provinsi', $data->id_provinsi) == $i->code ? 'selected':'' }}>{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kab/Kota</label>
                            <select class="form-control" name="id_kota" id="id_kota"></select>
                        </div>
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
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
<script>
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#id_provinsi").select2().val({{ $data->id_provinsi }}).trigger("change");
    
    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#id_kota').html(data);
                $("#id_kota").select2().val({{ $data->id_kota }}).trigger("change");
            }
        })
    }).change();
});
$("#title").keyup(function() {
    var Text = $(this).val();
    Text = Text.toLowerCase();
    var regExp = /\s+/g;
    Text = Text.replace(regExp,'-');
    $("#path").val(Text);        
});
</script>
@endpush