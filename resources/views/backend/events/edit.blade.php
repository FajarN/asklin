@extends('layouts.backend.layout')

@section('title', 'Perbarui Event')
@section('page_title', 'Edit Event')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('events.index') }}" class="hover:underline">Agenda & Event</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Edit Data</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Perbarui Agenda Kegiatan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Sesuaikan jadwal waktu pelaksanaan, lokasi wilayah, dan isi brosur pengumuman kegiatan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('events.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Event Information (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-calendar-alt text-emerald-600 mr-1.5"></i> Informasi & Jadwal Kegiatan
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Kategori Event <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="id_kategori" required>
                                @foreach($kategori as $i)
                                    <option value="{{ $i->id }}" {{ old('id_kategori', $data->id_kategori) == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Judul Event <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" name="judul" id="title" value="{{ old('judul', $data->judul) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug URL <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control font-mono text-xs" name="path" id="path" value="{{ old('path', $data->path) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ganti Brosur / Poster</label>
                            <input type="file" class="form-control" name="gambar" accept="image/*">
                            @if ($data->gambar)
                                <div class="mt-3 flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                    <img src="{{ asset('assets/images/events/' . $data->gambar) }}"
                                         alt="Banner Event"
                                         class="w-24 h-16 object-cover rounded-lg shadow-sm">
                                    <span class="text-xs text-slate-500">Poster yang terpasang</span>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Waktu Mulai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" class="form-control" name="mulai" value="{{ old('mulai', $data->mulai) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waktu Selesai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" class="form-control" name="selesai" value="{{ old('selesai', $data->selesai) }}" required>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-full justify-center">
                                <i class="fas fa-save mr-1.5"></i> Simpan Perubahan Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Wilayah & Konten (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-map-marked-alt text-emerald-600 mr-1.5"></i> Wilayah & Konten Brosur
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Provinsi</label>
                                <select class="form-control" name="id_provinsi" id="id_provinsi">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinsi as $i)
                                        <option value="{{ $i->code }}" {{ old('id_provinsi', $data->id_provinsi) == $i->code ? 'selected' : '' }}>
                                            {{ $i->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kabupaten / Kota</label>
                                <select class="form-control" name="id_kota" id="id_kota"></select>
                            </div>
                        </div>

                        @hasanyrole('Superadmin|Admin Pusat')
                        <div class="form-group">
                            <label class="form-label">Status Publikasi <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="status">
                                <option value="0" {{ old('status', $data->status) == '0' ? 'selected' : '' }}>Draft (Konsep)</option>
                                <option value="1" {{ old('status', $data->status) == '1' ? 'selected' : '' }}>Live (Terpublikasi)</option>
                            </select>
                        </div>
                        @endhasanyrole

                        <div class="form-group">
                            <label class="form-label">Deskripsi Lengkap Acara <span class="text-rose-500">*</span></label>
                            <textarea name="konten" class="summernote" required>{{ old('konten', $data->konten) }}</textarea>
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

    $('.summernote').summernote({
        dialogsInBody: true,
        minHeight: 280,
    });

    $("#id_provinsi").select2({ width: '100%' }).val("{{ $data->id_provinsi }}").trigger("change");
    $("#id_kota").select2({ width: '100%' });
    
    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        if(!id_provinsi) return;
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#id_kota').html(data);
                $("#id_kota").val("{{ $data->id_kota }}").trigger("change");
            }
        });
    });

    $("#title").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp,'-').replace(/[^a-z0-9-]/g, '');
        $("#path").val(Text);        
    });
});
</script>
@endpush