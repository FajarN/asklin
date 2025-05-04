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
                                    <label>Tanggal *</label>
                                    <input type="datetime-local" class="form-control" name="tanggal">
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
                                    <label>Lokasi</label>
                                    <input type="text" class="form-control" name="lokasi" id="lokasi">
                                </div>

                                @hasanyrole('Superadmin|Manager')
                                <div class="form-group">
                                    <label>Status *</label>
                                    <select class="form-control" name="status">
                                        <option value="0">Draft</option>
                                        <option value="1">Live</option>
                                    </select>
                                </div>
                                @endhasanyrole

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-success">Submit</button>
                                <a href="{{ route('berita.index') }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8">

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                    <label>Thumbnail</label>
                                    <input type="file" class="form-control" name="thumb">
                            </div>

                            <div class="form-group">
                            <label>Upload Foto (Bisa unggah banyak gambar sekaligus)</label>
                            <input type="file" class="form-control" name="gambar[]" multiple>
                            </div>

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
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $("#title").keyup(function() {
            var Text = $(this).val();
            Text = Text.toLowerCase();
            var regExp = /\s+/g;
            Text = Text.replace(regExp, '-');
            $("#path").val(Text);
        });
    </script>
@endpush

@push('js')
    <script>
        // Check for validation errors from flash message
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Check for success message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Check for error message
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif
    </script>
@endpush
