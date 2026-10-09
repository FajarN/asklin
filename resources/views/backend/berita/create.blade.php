@extends('layouts.backend.layout')

@section('title', 'Tulis Berita Baru')
@section('page_title', 'Tulis Berita')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('berita.index') }}" class="hover:underline">Berita & Publikasi</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Tulis Baru</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Buat Publikasi Berita Baru
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Tulis artikel, berita kegiatan, atau pengumuman resmi organisasi ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('berita.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Metadata & Status (4 cols) -->
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
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $i)
                                    <option value="{{ $i->id }}" {{ old('id_kategori') == $i->id ? 'selected' : '' }}>{{ $i->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Publikasi <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" class="form-control" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Judul Berita <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" name="judul" id="title" value="{{ old('judul') }}" placeholder="Judul artikel berita" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug URL <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control font-mono text-xs" name="path" id="path" value="{{ old('path') }}" placeholder="slug-url-otomatis" required>
                            <small class="text-xs text-slate-400 mt-1 block">Terbuat otomatis dari judul berita</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Lokasi Liputan</label>
                            <input type="text" class="form-control" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Jakarta Pusat">
                        </div>

                        @hasanyrole('Superadmin|Manager')
                        <div class="form-group">
                            <label class="form-label">Status Terbit <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="status">
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft (Konsep)</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Live (Terpublikasi)</option>
                            </select>
                        </div>
                        @endhasanyrole

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-full justify-center">
                                <i class="fas fa-paper-plane mr-1.5"></i> Simpan & Terbitkan
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Gambar Thumbnail Utama</label>
                                <input type="file" class="form-control" name="thumb" accept="image/*">
                                <small class="text-xs text-slate-400 mt-1 block">Format: JPG, PNG, WEBP (Maks 2MB)</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Galeri Foto Tambahan</label>
                                <input type="file" class="form-control" name="gambar[]" multiple accept="image/*">
                                <small class="text-xs text-slate-400 mt-1 block">Dapat memilih multiple foto sekaligus</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Isi Artikel / Konten <span class="text-rose-500">*</span></label>
                            <textarea name="konten" class="summernote" required>{{ old('konten') }}</textarea>
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
