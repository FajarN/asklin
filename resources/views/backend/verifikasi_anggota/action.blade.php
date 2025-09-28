<div class="btn-group" role="group">
    @hasanyrole('Admin Cabang')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-check"></i> Verifikasi Cabang
        </a>
    @endhasanyrole

    @hasanyrole('Admin Daerah')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-check"></i> Verifikasi Daerah
        </a>
    @endhasanyrole

    @hasanyrole('Admin Pusat|Ketua Umum')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-check"></i> Verifikasi Ketua Umum
        </a>
    @endhasanyrole

    @hasanyrole('Sekjen')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-check"></i> Verifikasi Sekjen
        </a>
    @endhasanyrole

    @hasanyrole('Bendahara')
        <div class="form-check form-check-inline">
            <input class="form-check-input checkbox" type="checkbox" name="status_pembayaran" id="status_pembayaran_{{ $id }}" data-id="{{ $id }}"
                {{ $status_pembayaran == 1 ? ' checked' : '' }}>
            <label class="form-check-label" for="status_pembayaran_{{ $id }}">
                Lunas
            </label>
        </div>
    @endhasanyrole

    @hasanyrole('Superadmin')
        <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-check"></i> Verifikasi
        </a>
        <div class="form-check form-check-inline ml-2">
            <input class="form-check-input checkbox" type="checkbox" name="status_pembayaran" id="status_pembayaran_{{ $id }}" data-id="{{ $id }}"
                {{ $status_pembayaran == 1 ? ' checked' : '' }}>
            <label class="form-check-label" for="status_pembayaran_{{ $id }}">
                Lunas
            </label>
        </div>
    @endhasanyrole

    {{-- Tombol Delete untuk semua role yang memiliki akses --}}
    @hasanyrole('Admin Cabang|Admin Daerah|Admin Pusat|Ketua Umum|Sekjen|Superadmin')
        <button type="button" class="btn btn-sm btn-danger ml-1"
                onclick="confirmDeleteVerifikasi({{ $id }}, '{{ addslashes($nama_klinik ?? 'Nama tidak tersedia') }}', '{{ $status }}')">
            <i class="fas fa-trash"></i> Delete
        </button>
    @endhasanyrole
</div>
