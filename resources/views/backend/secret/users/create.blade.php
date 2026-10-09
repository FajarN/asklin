@extends('layouts.backend.layout')

@section('title', 'Tambah User Baru')
@section('page_title', 'Tambah Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('users.index') }}" class="hover:underline">Manajemen Pengguna</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Tambah Baru</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Buat Akun Pengguna Sistem
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Daftarkan akun administrator baru dan tentukan hak akses peran serta wilayah teritori.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-300">
            <strong class="font-bold flex items-center gap-1.5 mb-1 text-sm">
                <i class="fas fa-exclamation-circle"></i> Perhatian: Terjadi Kesalahan Input
            </strong>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Kredensial & Role -->
            <div class="lg:col-span-6 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-user-lock text-emerald-600 mr-1.5"></i> Data Akun & Akses
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="admin@asklin.org" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Peran Pengguna (Group Role) <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="roles" required>
                                <option value="">-- Pilih Peran / Role --</option>
                                @foreach($role as $i)
                                    <option value="{{ $i->id }}" {{ old('roles') == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div class="form-group">
                                <label class="form-label">Kata Sandi <span class="text-rose-500">*</span></label>
                                <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                                <input type="password" class="form-control" name="confirm-password" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Wilayah / Teritori -->
            <div class="lg:col-span-6 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-map-marked-alt text-emerald-600 mr-1.5"></i> Teritori & Wilayah Penugasan
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Provinsi</label>
                            <select class="form-control" name="provinsi" id="provinsi">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kabupaten / Kota</label>
                            <select class="form-control" name="kota" id="kota">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <select class="form-control" name="kecamatan" id="kecamatan">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kelurahan / Desa</label>
                            <select class="form-control" name="kelurahan" id="kelurahan">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-check mr-1.5"></i> Simpan Pengguna Baru
                        </button>
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

    $('#provinsi').select2({ width: '100%' });
    $('#kota').select2({ width: '100%' });
    $('#kecamatan').select2({ width: '100%' });
    $('#kelurahan').select2({ width: '100%' });

    $('#provinsi').on('change', function(){
        let id_provinsi = $('#provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#kota').html(data);
                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
            }
        });
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
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
            }
        });
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
        });
    });
});
</script>
@endpush