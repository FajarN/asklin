@extends('layouts.backend.layout')

@section('title', 'Tambah Agenda Baru')
@section('page_title', 'Buat Agenda')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('agenda.index') }}" class="hover:underline">Agenda Organisasi</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Tambah Baru</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Buat Agenda Rapat & Notulensi
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Catat jadwal sidang pengurus, notulensi pimpinan, serta lembar ketetapan hasil rapat.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Waktu & Lokasi (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-clock text-emerald-600 mr-1.5"></i> Waktu & Ruang Rapat
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">Jenis Agenda Rapat <span class="text-rose-500">*</span></label>
                            <select class="form-control" name="jenis" required>
                                <option value="Rapat Pleno">Rapat Pleno</option>
                                <option value="Rapat Pleno Diperluas">Rapat Pleno Diperluas</option>
                                <option value="Rapat Pengurus Harian">Rapat Pengurus Harian</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal & Jam Pelaksanaan <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control datetimepicker" name="tanggal" placeholder="YYYY-MM-DD HH:mm" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Perihal / Agenda Rapat <span class="text-rose-500">*</span></label>
                            <input type="text" class="form-control" name="perihal" placeholder="Contoh: Evaluasi Iuran Semester I" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tempat / Ruang / Link Virtual <span class="text-rose-500">*</span></label>
                            <textarea class="form-control" name="tempat" rows="3" placeholder="Contoh: Ruang Rapat Utama Sekretariat ASKLIN / Zoom Meeting" required></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-full justify-center">
                                <i class="fas fa-save mr-1.5"></i> Simpan Agenda Rapat
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Pimpinan & Notulensi (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            <i class="fas fa-file-signature text-emerald-600 mr-1.5"></i> Personil & Keputusan Rapat
                        </h4>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Pimpinan Rapat <span class="text-rose-500">*</span></label>
                                <input type="text" class="form-control" name="pimpinan" placeholder="Nama pimpinan sidang/rapat" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Notulen Rapat <span class="text-rose-500">*</span></label>
                                <input type="text" class="form-control" name="notulen" placeholder="Nama pencatat notulensi" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keputusan & Poin Kesimpulan Rapat <span class="text-rose-500">*</span></label>
                            <textarea name="keputusan" class="summernote" required></textarea>
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
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/backend/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script>
    $(function() {
        $('.summernote').summernote({
            dialogsInBody: true,
            minHeight: 280,
        });

        if ($.fn.daterangepicker) {
            $('.datetimepicker').daterangepicker({
                locale: { format: 'YYYY-MM-DD HH:mm:ss' },
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            });
        }
    });
</script>
@endpush