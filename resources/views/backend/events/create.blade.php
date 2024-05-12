@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Events</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>
    </section>
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label>Tanggal Mulai *</label>
                                <input type="datetime-local" class="form-control" name="mulai">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Selesai *</label>
                                <input type="datetime-local" class="form-control" name="selesai">
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
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
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
<link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
<script>
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#id_provinsi').select2();
    $('#id_kota').select2();
    
    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#id_kota').html(data);
            }
        })
    });
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