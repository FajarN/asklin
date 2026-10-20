@extends('layouts.backend.layout')

@section('title', 'Buat Agenda Pemilihan Baru')
@section('page_title', 'Buat Agenda Pemilihan')

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
                    Buat Agenda Pemilihan Ketua
                </h2>
                <p class="text-xs text-slate-500">Konfigurasikan jadwal, wilayah, dan kandidat calon ketua.</p>
            </div>
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

    <form action="{{ route('pemilihan.store') }}" method="POST" enctype="multipart/form-data" id="pemilihanForm">
        @csrf

        <!-- Card 1: Data Umum Pemilihan -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 mb-6 space-y-5">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-b pb-3 flex items-center gap-2">
                <i class="fas fa-info-circle text-red-600"></i> 1. Informasi Umum Pemilihan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Pemilihan <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" class="form-control rounded-xl text-sm" placeholder="Contoh: Pemilihan Ketua Cabang ASKLIN Jakarta Selatan Periode 2023 - 2028" value="{{ old('judul') }}" required>
                </div>

                <!-- Tingkat & Wilayah -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkatan Pemilihan <span class="text-rose-500">*</span></label>
                    @if ($isCabang)
                        <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="Tingkat Cabang (Kabupaten/Kota)" readonly>
                        <input type="hidden" name="tingkat" value="cabang">
                    @elseif ($isDaerah)
                        <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="Tingkat Daerah (Provinsi)" readonly>
                        <input type="hidden" name="tingkat" value="daerah">
                    @else
                        <select name="tingkat" id="tingkatSelect" class="form-control rounded-xl text-sm" required>
                            <option value="cabang" {{ old('tingkat') == 'cabang' ? 'selected' : '' }}>Tingkat Cabang (Kabupaten/Kota)</option>
                            <option value="daerah" {{ old('tingkat') == 'daerah' ? 'selected' : '' }}>Tingkat Daerah (Provinsi)</option>
                        </select>
                    @endif
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                    @if ($isCabang || $isDaerah)
                        <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $userProvince ? $userProvince->name : Auth::user()->provinsi }}" readonly>
                        <input type="hidden" name="id_provinsi" value="{{ $userProvince ? $userProvince->code : Auth::user()->provinsi }}">
                    @else
                        <select name="id_provinsi" id="provinsiSelect" class="form-control rounded-xl text-sm" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinces as $prov)
                                <option value="{{ $prov->code }}" {{ old('id_provinsi') == $prov->code ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Kota / Cabang -->
                <div id="kotaContainer" class="{{ ($isDaerah || old('tingkat') == 'daerah') ? 'hidden' : '' }}">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kota / Kabupaten (Cabang) <span class="text-rose-500">*</span></label>
                    @if ($isCabang)
                        <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $userCity ? $userCity->name : Auth::user()->kota }}" readonly>
                        <input type="hidden" name="id_kota" value="{{ $userCity ? $userCity->code : Auth::user()->kota }}">
                    @elseif (!$isDaerah)
                        <select name="id_kota" id="kotaSelect" class="form-control rounded-xl text-sm">
                            <option value="">-- Pilih Kota / Kabupaten --</option>
                        </select>
                    @endif
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Pemilihan <span class="text-rose-500">*</span></label>
                    <select name="status" class="form-control rounded-xl text-sm" required>
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft (Belum Dibuka)</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif (Voting Dibuka)</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ old('status') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <!-- Periode 5 Tahunan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Periode Mulai (Tahun) <span class="text-rose-500">*</span></label>
                    <input type="number" name="periode_mulai" class="form-control rounded-xl text-sm" min="2000" max="2100" value="{{ old('periode_mulai', date('Y')) }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Periode Selesai (Tahun) <span class="text-rose-500">*</span></label>
                    <input type="number" name="periode_selesai" class="form-control rounded-xl text-sm" min="2000" max="2100" value="{{ old('periode_selesai', date('Y') + 5) }}" required>
                </div>

                <!-- Tanggal Mulai & Selesai Voting -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai Voting <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="tanggal_mulai" class="form-control rounded-xl text-sm" value="{{ old('tanggal_mulai', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai Voting <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="tanggal_selesai" class="form-control rounded-xl text-sm" value="{{ old('tanggal_selesai', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <!-- Deskripsi / Aturan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi & Tata Tertib Pemilihan</label>
                    <textarea name="deskripsi" rows="3" class="form-control rounded-xl text-sm" placeholder="Aturan pemilihan, hak suara 1 klinik 1 suara, dll.">{{ old('deskripsi') }}</textarea>
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
                    <p class="text-xs text-slate-400">Tambahkan calon nomor urut 1, 2, 3, dan seterusnya secara dinamis.</p>
                </div>
                <button type="button" id="btnAddKandidat" class="btn btn-sm btn-outline-primary rounded-xl">
                    <i class="fas fa-user-plus mr-1"></i> Tambah Calon
                </button>
            </div>

            <div id="kandidatList" class="space-y-4">
                <!-- Template item inserted via JS or PHP loop -->
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('pemilihan.index') }}" class="btn btn-secondary rounded-xl px-5">Batal</a>
            <button type="submit" class="btn btn-primary rounded-xl px-6 shadow-md shadow-red-600/20">
                <i class="fas fa-save mr-1.5"></i> Simpan Agenda Pemilihan
            </button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    let kandidatCount = 0;

    function renderKandidatItem(data = {}) {
        kandidatCount++;
        const noUrut = data.nomor_urut || kandidatCount;
        const html = `
        <div class="kandidat-item p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 relative" data-index="${kandidatCount}">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-2 mb-3">
                <span class="badge badge-danger bg-red-600 text-white px-2.5 py-1 rounded-md text-xs font-bold">
                    Kandidat Nomor Urut <span class="nomor-urut-badge">${noUrut}</span>
                </span>
                ${kandidatCount > 1 ? `
                <button type="button" class="btn-remove-kandidat text-rose-500 hover:text-rose-700 text-xs font-semibold flex items-center gap-1">
                    <i class="fas fa-trash"></i> Hapus Calon
                </button>` : ''}
            </div>

            <input type="hidden" name="kandidat[${kandidatCount}][nomor_urut]" class="input-nomor-urut" value="${noUrut}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Calon <span class="text-rose-500">*</span></label>
                    <input type="text" name="kandidat[${kandidatCount}][nama_calon]" class="form-control rounded-xl text-xs" placeholder="Nama lengkap tanpa gelar" value="${data.nama_calon || ''}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Gelar (Opsional)</label>
                    <input type="text" name="kandidat[${kandidatCount}][gelar]" class="form-control rounded-xl text-xs" placeholder="dr. / MARS / Sp.KKLP" value="${data.gelar || ''}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Asal Klinik</label>
                    <input type="text" name="kandidat[${kandidatCount}][asal_klinik]" class="form-control rounded-xl text-xs" placeholder="Contoh: Klinik Sehat Utama" value="${data.asal_klinik || ''}">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Foto Calon (Maksimal 2MB)</label>
                    <input type="file" name="kandidat[${kandidatCount}][foto]" class="form-control rounded-xl text-xs" accept="image/*">
                </div>

                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Visi</label>
                        <textarea name="kandidat[${kandidatCount}][visi]" rows="3" class="form-control rounded-xl text-xs" placeholder="Visi calon...">${data.visi || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Misi</label>
                        <textarea name="kandidat[${kandidatCount}][misi]" rows="3" class="form-control rounded-xl text-xs" placeholder="Misi calon...">${data.misi || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Program Kerja Unggulan</label>
                        <textarea name="kandidat[${kandidatCount}][program_kerja]" rows="3" class="form-control rounded-xl text-xs" placeholder="Program kerja...">${data.program_kerja || ''}</textarea>
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

    // Default 2 candidate slots for initial load
    renderKandidatItem();
    renderKandidatItem();

    $('#btnAddKandidat').on('click', function() {
        renderKandidatItem();
    });

    $(document).on('click', '.btn-remove-kandidat', function() {
        $(this).closest('.kandidat-item').remove();
        refreshNomorUrut();
    });

    // Cascading dropdown for Provinsi -> Kota
    $('#provinsiSelect').on('change', function() {
        const provCode = $(this).val();
        const kotaSelect = $('#kotaSelect');
        kotaSelect.html('<option value="">-- Memuat Kota... --</option>');

        if (provCode) {
            $.ajax({
                url: "{{ route('pemilihan.get_cities') }}",
                data: { province_code: provCode },
                dataType: 'json',
                success: function(cities) {
                    kotaSelect.html('<option value="">-- Pilih Kota / Kabupaten --</option>');
                    $.each(cities, function(i, city) {
                        kotaSelect.append('<option value="' + city.code + '">' + city.name + '</option>');
                    });
                }
            });
        } else {
            kotaSelect.html('<option value="">-- Pilih Kota / Kabupaten --</option>');
        }
    });

    // Toggle tingkat cabang vs daerah
    $('#tingkatSelect').on('change', function() {
        if ($(this).val() === 'daerah') {
            $('#kotaContainer').addClass('hidden');
            $('#kotaSelect').prop('required', false);
        } else {
            $('#kotaContainer').removeClass('hidden');
            $('#kotaSelect').prop('required', true);
        }
    });
});
</script>
@endpush
