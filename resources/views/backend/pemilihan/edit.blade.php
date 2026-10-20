@extends('layouts.backend.layout')

@section('title', 'Edit Agenda Pemilihan')
@section('page_title', 'Edit Agenda Pemilihan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Header Banner -->
    <div class="flex items-center justify-between bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('pemilihan.index') }}" class="btn btn-secondary btn-sm rounded-xl">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                    Edit Agenda Pemilihan Ketua
                </h2>
                <p class="text-xs text-slate-500">Perbarui jadwal, status, dan kandidat calon ketua.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pemilihan.show', $pemilihan->id) }}" class="btn btn-outline-info btn-sm rounded-xl">
                <i class="fas fa-eye mr-1"></i> Lihat Detail
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="font-bold flex items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle text-rose-600"></i> Mohon periksa kesalahan input:
            </div>
            <ul class="list-disc pl-5 text-xs space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilihan.update', $pemilihan->id) }}" method="POST" enctype="multipart/form-data" id="pemilihanForm">
        @csrf
        @method('PUT')

        <!-- Card 1: Data Umum Pemilihan -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 mb-6 space-y-5">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-b pb-3 flex items-center gap-2">
                <i class="fas fa-info-circle text-red-600"></i> 1. Informasi Umum Pemilihan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Pemilihan <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" class="form-control rounded-xl text-sm" value="{{ old('judul', $pemilihan->judul) }}" required>
                </div>

                <!-- Tingkat & Wilayah -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkatan Pemilihan</label>
                    <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $pemilihan->tingkat === 'cabang' ? 'Tingkat Cabang (Kabupaten/Kota)' : 'Tingkat Daerah (Provinsi)' }}" readonly>
                    <input type="hidden" name="tingkat" value="{{ $pemilihan->tingkat }}">
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Provinsi</label>
                    <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $pemilihan->provinsi ? $pemilihan->provinsi->name : $pemilihan->id_provinsi }}" readonly>
                    <input type="hidden" name="id_provinsi" value="{{ $pemilihan->id_provinsi }}">
                </div>

                <!-- Kota -->
                @if ($pemilihan->tingkat === 'cabang')
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kota / Kabupaten (Cabang)</label>
                        <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $pemilihan->kota ? $pemilihan->kota->name : $pemilihan->id_kota }}" readonly>
                        <input type="hidden" name="id_kota" value="{{ $pemilihan->id_kota }}">
                    </div>
                @endif

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Pemilihan <span class="text-rose-500">*</span></label>
                    <select name="status" class="form-control rounded-xl text-sm" required>
                        <option value="draft" {{ old('status', $pemilihan->status) == 'draft' ? 'selected' : '' }}>Draft (Belum Dibuka)</option>
                        <option value="aktif" {{ old('status', $pemilihan->status) == 'aktif' ? 'selected' : '' }}>Aktif (Voting Dibuka)</option>
                        <option value="selesai" {{ old('status', $pemilihan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ old('status', $pemilihan->status) == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <!-- Periode 5 Tahunan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Periode Mulai (Tahun) <span class="text-rose-500">*</span></label>
                    <input type="number" name="periode_mulai" class="form-control rounded-xl text-sm" min="2000" max="2100" value="{{ old('periode_mulai', $pemilihan->periode_mulai) }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Periode Selesai (Tahun) <span class="text-rose-500">*</span></label>
                    <input type="number" name="periode_selesai" class="form-control rounded-xl text-sm" min="2000" max="2100" value="{{ old('periode_selesai', $pemilihan->periode_selesai) }}" required>
                </div>

                <!-- Tanggal Mulai & Selesai Voting -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai Voting <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="tanggal_mulai" class="form-control rounded-xl text-sm" value="{{ old('tanggal_mulai', $pemilihan->tanggal_mulai->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai Voting <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="tanggal_selesai" class="form-control rounded-xl text-sm" value="{{ old('tanggal_selesai', $pemilihan->tanggal_selesai->format('Y-m-d\TH:i')) }}" required>
                </div>

                <!-- Deskripsi / Aturan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi & Tata Tertib Pemilihan</label>
                    <textarea name="deskripsi" rows="3" class="form-control rounded-xl text-sm">{{ old('deskripsi', $pemilihan->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 2: Manajemen Calon Kandidat -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 mb-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-users text-red-600"></i> 2. Daftar Calon Kandidat Ketua
                    </h3>
                    <p class="text-xs text-slate-400">Tambahkan atau sesuaikan informasi calon ketua.</p>
                </div>
                <button type="button" id="btnAddKandidat" class="btn btn-sm btn-outline-primary rounded-xl">
                    <i class="fas fa-user-plus mr-1"></i> Tambah Calon
                </button>
            </div>

            <div id="kandidatList" class="space-y-4">
                @foreach ($pemilihan->kandidat as $kIdx => $kand)
                    <div class="kandidat-item p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 relative" data-index="{{ $kIdx + 1 }}">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-2 mb-3">
                            <span class="badge badge-danger bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-bold">
                                Kandidat Nomor Urut <span class="nomor-urut-badge">{{ $kand->nomor_urut }}</span>
                            </span>
                            @if ($kand->suara()->count() == 0)
                                <button type="button" class="btn-remove-kandidat text-rose-500 hover:text-rose-700 text-xs font-semibold flex items-center gap-1">
                                    <i class="fas fa-trash"></i> Hapus Calon
                                </button>
                            @else
                                <span class="text-xs text-slate-400 font-medium">
                                    <i class="fas fa-lock mr-1"></i> {{ $kand->total_suara }} Suara Masuk (Terkunci)
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="kandidat[{{ $kIdx + 1 }}][id]" value="{{ $kand->id }}">
                        <input type="hidden" name="kandidat[{{ $kIdx + 1 }}][nomor_urut]" class="input-nomor-urut" value="{{ $kand->nomor_urut }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Calon <span class="text-rose-500">*</span></label>
                                <input type="text" name="kandidat[{{ $kIdx + 1 }}][nama_calon]" class="form-control rounded-xl text-xs" value="{{ $kand->nama_calon }}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Gelar (Opsional)</label>
                                <input type="text" name="kandidat[{{ $kIdx + 1 }}][gelar]" class="form-control rounded-xl text-xs" value="{{ $kand->gelar }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Asal Klinik</label>
                                <input type="text" name="kandidat[{{ $kIdx + 1 }}][asal_klinik]" class="form-control rounded-xl text-xs" value="{{ $kand->asal_klinik }}">
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Foto Calon</label>
                                @if ($kand->foto)
                                    <div class="mb-2 flex items-center gap-3">
                                        <img src="{{ asset('images/pemilihan/' . $kand->foto) }}" alt="Foto {{ $kand->nama_calon }}" class="w-14 h-14 rounded-xl object-cover border">
                                        <span class="text-xs text-slate-500">{{ $kand->foto }}</span>
                                    </div>
                                @endif
                                <input type="file" name="kandidat[{{ $kIdx + 1 }}][foto]" class="form-control rounded-xl text-xs" accept="image/*">
                                <span class="text-[10px] text-slate-400">Kosongkan jika tidak ingin mengganti foto lama.</span>
                            </div>

                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Visi</label>
                                    <textarea name="kandidat[{{ $kIdx + 1 }}][visi]" rows="3" class="form-control rounded-xl text-xs">{{ $kand->visi }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Misi</label>
                                    <textarea name="kandidat[{{ $kIdx + 1 }}][misi]" rows="3" class="form-control rounded-xl text-xs">{{ $kand->misi }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Program Kerja Unggulan</label>
                                    <textarea name="kandidat[{{ $kIdx + 1 }}][program_kerja]" rows="3" class="form-control rounded-xl text-xs">{{ $kand->program_kerja }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('pemilihan.index') }}" class="btn btn-secondary rounded-xl px-5">Batal</a>
            <button type="submit" class="btn btn-primary rounded-xl px-6 shadow-md shadow-red-600/20">
                <i class="fas fa-save mr-1.5"></i> Perbarui Agenda Pemilihan
            </button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    let kandidatCount = {{ $pemilihan->kandidat->count() }};

    function renderNewKandidat() {
        kandidatCount++;
        const html = `
        <div class="kandidat-item p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 relative" data-index="${kandidatCount}">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-2 mb-3">
                <span class="badge badge-danger bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-bold">
                    Kandidat Nomor Urut <span class="nomor-urut-badge">${kandidatCount}</span>
                </span>
                <button type="button" class="btn-remove-kandidat text-rose-500 hover:text-rose-700 text-xs font-semibold flex items-center gap-1">
                    <i class="fas fa-trash"></i> Hapus Calon
                </button>
            </div>

            <input type="hidden" name="kandidat[${kandidatCount}][nomor_urut]" class="input-nomor-urut" value="${kandidatCount}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Calon <span class="text-rose-500">*</span></label>
                    <input type="text" name="kandidat[${kandidatCount}][nama_calon]" class="form-control rounded-xl text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Gelar (Opsional)</label>
                    <input type="text" name="kandidat[${kandidatCount}][gelar]" class="form-control rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Asal Klinik</label>
                    <input type="text" name="kandidat[${kandidatCount}][asal_klinik]" class="form-control rounded-xl text-xs">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Foto Calon</label>
                    <input type="file" name="kandidat[${kandidatCount}][foto]" class="form-control rounded-xl text-xs" accept="image/*">
                </div>

                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Visi</label>
                        <textarea name="kandidat[${kandidatCount}][visi]" rows="3" class="form-control rounded-xl text-xs"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Misi</label>
                        <textarea name="kandidat[${kandidatCount}][misi]" rows="3" class="form-control rounded-xl text-xs"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Program Kerja Unggulan</label>
                        <textarea name="kandidat[${kandidatCount}][program_kerja]" rows="3" class="form-control rounded-xl text-xs"></textarea>
                    </div>
                </div>
            </div>
        </div>
        `;
        $('#kandidatList').append(html);
        refreshNomorUrut();
    }

    function refreshNomorUrut() {
        $('#kandidatList .kandidat-item').each(function(idx) {
            const no = idx + 1;
            $(this).find('.nomor-urut-badge').text(no);
            $(this).find('.input-nomor-urut').val(no);
        });
    }

    $('#btnAddKandidat').on('click', function() {
        renderNewKandidat();
    });

    $(document).on('click', '.btn-remove-kandidat', function() {
        $(this).closest('.kandidat-item').remove();
        refreshNomorUrut();
    });
});
</script>
@endpush
