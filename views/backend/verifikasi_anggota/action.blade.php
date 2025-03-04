@hasanyrole('Admin Cabang')
    <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="has-icon"><i class="fas fa-check"></i> Verifikasi Cabang</a>
@endhasanyrole

@hasanyrole('Admin Daerah')
    <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="has-icon"><i class="fas fa-check"></i> Verifikasi Daerah</a>
@endhasanyrole

@hasanyrole('Admin Pusat|Ketua Umum')
    <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="has-icon"><i class="fas fa-check"></i> Verifikasi Ketua
        Umum</a>
@endhasanyrole

@hasanyrole('Sekjen')
    <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="has-icon"><i class="fas fa-check"></i> Verifikasi
        Sekjen</a>
@endhasanyrole

@hasanyrole('Bendahara')
    <input class="checkbox" type="checkbox" name="status_pembayaran" id="status_pembayaran" data-id="{{ $id }}"
        {{ $status_pembayaran == 1 ? ' checked' : '' }}> Lunas
@endhasanyrole

@hasanyrole('Superadmin')
    <a href="{{ route('verifikasi_anggota.verify', $id) }}" class="has-icon"><i class="fas fa-check"></i> Verifikasi</a>
    <input class="checkbox" type="checkbox" name="status_pembayaran" id="status_pembayaran" data-id="{{ $id }}"
        {{ $status_pembayaran == 1 ? ' checked' : '' }}> Lunas
@endhasanyrole
