@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users Management</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>
    </section>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label>Group</label>
                                <select class="form-control" name="roles">
                                    <option></option>
                                    @foreach($role as $i)
                                        <option value="{{ $i->id }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" class="form-control" name="confirm-password">
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                            <a href="{{ route('users.index') }}" class="btn btn-info">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Propinsi</label>
                            <select class="form-control" name="provinsi" id="provinsi">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kab/Kota</label>
                            <select class="form-control" name="kota" id="kota">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select class="form-control" name="kecamatan" id="kecamatan">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kelurahan</label>
                            <select class="form-control" name="kelurahan" id="kelurahan">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#provinsi').select2();
    $('#kota').select2();
    $('#kecamatan').select2();
    $('#kelurahan').select2();

    $('#provinsi').on('change', function(){
        let id_provinsi = $('#provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#kota').html(data);
                $('#kecamatan').html();
                $('#kelurahan').html();
            }
        })
    });

    $('#kota').on('change', function(){
        let id_kota = $('#kota').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKecamatan') }}',
            data: {id_kota:id_kota},
            cache: false,
            success: function(data){
                $('#kecamatan').html(data);
                $('#kelurahan').html();
            }
        })
    });

    $('#kecamatan').on('change', function(){
        let id_kecamatan = $('#kecamatan').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKelurahan') }}',
            data: {id_kecamatan:id_kecamatan},
            cache: false,
            success: function(data){
                $('#kelurahan').html(data);
            }
        })
    });
});
</script>
@endpush