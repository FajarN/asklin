<style type="text/css">
    table {
        padding: 0;
        margin: 0;
        border: none;
    }

    td {
        padding: 0;
        margin: 0;
        border: none;
    }

    img {
        width: 10mm;
    }

    .huruf {
        position: absolute;
        top: 40%;
        left: 10%;
        z-index: 99999;
        font-size: 22px;
    }

    .berlaku {
        position: absolute;
        top: 85%;
        left: 10%;
    }

    .berlaku {
        position: absolute;
        top: 85%;
        left: 10%;
    }

    .qrcode {
        position: absolute;
        bottom: 25%;
        right: 20%;
    }

    .kode {
        position: absolute;
        top: 15%;
        left: 10%;
        font-size: 14px;
    }

    .foto {
        position: absolute;
        top: 70%;
        left: 35%;
    }
    }
</style>
<page>

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td><img src="{{ asset('/assets/images/sertifikat.jpg') }}" alt="" style="width: 290mm"></td>
        </tr>
    </table>

    <table cellpadding="100" cellspacing="10" class="huruf">
        <tr>
            <td>Nama Klinik</td>
            <td>:</td>
            <td><b>{{ $anggota->nama_klinik }}</b></td>
        </tr>
        <tr>
            <td>No. Anggota</td>
            <td>:</td>
            <td><b>{{ $anggota->no_anggota }}</b></td>
        </tr>
        <tr>
            <td>Penanggung Jawab</td>
            <td>:</td>
            <td><b>{{ $anggota->nama_kontak }}</b></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            {{-- <td><b>{{ $anggota->alamat_klinik }}<br/>RT {{ $anggota->rt.'/'.$anggota->rw }} Kel. {{ $anggota->kelurahan }}Kec. {{ $anggota->kecamatan }}<br/>{{ $anggota->kota }}{{ $anggota->provinsi }}</b></td> --}}
            <td><?php $alamat = Str::lower(substr($anggota->alamat_klinik, 0, 20));
            echo $alamat; ?>
                <br />{{ Str::lower($anggota->kota) }}
            </td>

        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" class="berlaku">
        <tr>
            <td>Berlaku :
                {{ date('d-m-Y', strtotime($anggota->dari)) . ' - ' . date('d-m-Y', strtotime($anggota->sampai)) }}
            </td>
        </tr>
    </table>



    <table cellpadding="0" cellspacing="0" class="foto">
        <tr>
            <img src="{{ asset('/assets/images/ttd.png') }}" alt="" width="300">
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" class="kode">
        <tr>
            <td>Kode : <?php $kalimat = substr($anggota->no_anggota, 0, 10);
            echo $kalimat; ?></td>
        </tr>
    </table>



    <table cellpadding="0" cellspacing="0" class="qrcode">
        <tr>
            <td>
                <qrcode value="Validasi Kunjungi Link Berikut : https://asklin.org/cekqrsertifikat/{{ $anggota->id }}"
                    ec="Q" style="width: 25mm; border: none; "></qrcode>
            </td>
        </tr>
    </table>

</page>
