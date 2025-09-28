<div class="btn-group mb-3" role="group" aria-label="Basic example">
    <a href="{{ route('anggota.detail_anggota', $id) }}" class="btn btn-icon icon-left btn-secondary text-dark">
        <i class="fas fa-eye"></i> Detail
    </a>
    <a href="{{ route('anggota.printsk', $id) }}" target="_BLANK" class="btn btn-icon icon-left btn-info text-dark">
        <i class="fas fa-print"></i> Print SK
    </a>
    <button type="button" class="btn btn-icon icon-left btn-danger text-white"
            onclick="confirmDeleteAnggota({{ $id }}, '{{ addslashes($nama_klinik ?? 'Nama tidak tersedia') }}')">
        <i class="fas fa-trash"></i> Delete
    </button>
 </div>
