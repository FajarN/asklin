@extends('layouts.backend.layout')

@section('title', 'Edit Berita')
@section('page_title', 'Perbarui Berita')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('berita.index') }}" class="hover:underline">Berita & Publikasi</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Edit Data</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Perbarui Publikasi Berita
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Ubah informasi judul, kategori, thumbnail, dan konten isi artikel berita.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('berita.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('berita.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Metadata & Settings (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-sliders-h text-emerald-600 mr-1.5"></i> Informasi & Pengaturan
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Kategori Berita <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="id_kategori" required>
                                @foreach($kategori as $i)
                                    <option value="{{ $i->id }}" {{ old('id_kategori', $data->id_kategori) == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Publikasi <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" class="form-control" name="tanggal" value="{{ old('tanggal', $data->tanggal) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Judul Berita <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" name="judul" id="title" value="{{ old('judul', $data->judul) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug URL <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control font-mono text-xs" name="path" id="path" value="{{ old('path', $data->path) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Lokasi Liputan</label>
                            <input type="text" class="form-control" name="lokasi" id="lokasi" value="{{ old('lokasi', $data->lokasi) }}">
                        </div>

                        @hasanyrole('Superadmin|Manager')
                        <div class="form-group">
                            <label class="form-label">Status Terbit <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="status">
                                <option value="0" {{ old('status', $data->status) == '0' ? 'selected' : '' }}>Draft (Konsep)</option>
                                <option value="1" {{ old('status', $data->status) == '1' ? 'selected' : '' }}>Live (Terpublikasi)</option>
                            </select>
                        </div>
                        @endhasanyrole

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-full justify-center">
                                <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Media & Konten (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-file-alt text-emerald-600 mr-1.5"></i> Media & Isi Konten
                        </h4>
                    </div>
                    <div class="card-body space-y-5">
                        <!-- Thumbnail -->
                        <div class="form-group">
                            <label class="form-label">Ganti Thumbnail Utama</label>
                            <input type="file" class="form-control" name="thumb" accept="image/*">
                            @if ($data->thumb)
                                <div class="mt-3 flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                    <img src="{{ asset('assets/images/berita/thumbnails/' . $data->thumb) }}"
                                         alt="Thumbnail"
                                         class="w-24 h-16 object-cover rounded-lg shadow-sm">
                                    <span class="text-xs text-slate-500">Thumbnail yang saat ini terpasang</span>
                                </div>
                            @endif
                        </div>

                        <!-- Galeri Gambar -->
                        @if ($data->images && count($data->images) > 0)
                            <div class="form-group">
                                <label class="form-label">Galeri Gambar Terkait (Centang untuk menghapus)</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach ($data->images as $image)
                                        <div class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-center">
                                            <img src="{{ asset('assets/images/berita/' . $image->gambar) }}"
                                                 alt="Gambar" class="w-full h-24 object-cover rounded-lg mb-2">
                                            <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 font-medium cursor-pointer">
                                                <input type="checkbox" class="rounded text-rose-600 focus:ring-rose-500" name="hapus_gambar[]" value="{{ $image->id }}">
                                                <span>Hapus</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Tambah Gambar Baru ke Galeri</label>
                            <input type="file" class="form-control" name="gambar[]" multiple accept="image/*">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Isi Artikel / Konten <span class="text-rose-500">*</span></label>
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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.summernote').summernote({
                dialogsInBody: true,
                minHeight: 350,
            });

            $("#title").keyup(function() {
                var Text = $(this).val();
                Text = Text.toLowerCase();
                var regExp = /\s+/g;
                Text = Text.replace(regExp, '-').replace(/[^a-z0-9-]/g, '');
                $("#path").val(Text);
            });
        });

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Perhatian',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#059669'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#059669'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#059669'
            });
        @endif
    </script>
@endpush