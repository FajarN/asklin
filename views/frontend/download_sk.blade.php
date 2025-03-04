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

    .header {
        position: absolute;
        top: 14%;
        left: 37%;

    }

    .isi {
        position: absolute;
        top: 19%;
        left: 10%;
        font-size: 12px;
    }

    .huruf {
        position: absolute;
        top: 22%;
        left: 10%;
        z-index: 99999;
        font-size: 13px;
    }

    .bungkus {
        position: absolute;
        top: 46%;
        left: 10%;
        font-size: 12px;
        line-height: 20px;
    }

    .noanggota {
        font-size: 28px;
        padding: 1px;
        text-align: center;
        margin-left: 5px;
        margin-top: 70px;
    }

    p .khusus {
        text-indent: 10px;
        margin-top: -20px;
        margin-bottom: -2px;
    }

    ol.bag1 {
        list-style-type: lower-alpha;
        line-height: 100px;

    }

    .container {
        position: absolute;
        top: 55%;
        left: 11%;
        font-size: 12px;
        line-height: 20px;
    }

    .penutup {
        position: absolute;
        bottom: 25%;
        margin-left: 80px;
        font-size: 12px;
    }

    .footer {
        position: absolute;
        bottom: 7%;
        margin-left: 80px;
        font-family: arial;
        font-weight: bold;
        font-size: 14px;
    }

    .khusus3 {
        font-weight: bold;
        padding: 2px;
        font-size: 18px;
        font-family: 'Aladin', cursive;
        letter-spacing: 1.2px;
    }

    .qrcode {
        position: absolute;
        bottom: 15.1%;
        right: 22%;
    }

    }
</style>

<page onLoad="makeCode()">



    <table cellpadding="0" cellspacing="0">
        <tr>
            <td><img src="{{ asset('/assets/images/skanggota.jpg') }}" alt="" style="width:208mm"></td>
        </tr>
    </table>

    <div class="header">
        <h5>SURAT KEPUTUSAN ANGGOTA ASKLIN<br><br>
              @php
            $createdOn = !empty($anggota->created_on)
                ? \Carbon\Carbon::createFromTimestamp($anggota->created_on)->format('Y')
                : ($anggota->created_at
                    ? \Carbon\Carbon::parse($anggota->created_at)->format('Y')
                    : \Carbon\Carbon::parse($anggota->verifikasi_pusat)->format('Y'));  @endphp

            &nbsp;&nbsp;&nbsp;&nbsp; <?php $kalimat = $anggota->no_anggota;
            $sub_kalimat = substr($kalimat, 13, 3); ?>
            {{ $sub_kalimat }}/SK-Anggota/PP-Asklin/{{ $createdOn }}</h5>
    </div>
    </div>

    <div class="isi">
        <p>Dengan ini menyatakan bahwa Pengurus Pusat Asosiasi Klinik Indonesia yang berkedudukan di Jakarta memberikan
            <br>hak keanggotaan kepada :
        </p>
    </div>

    <table cellpadding="100" cellspacing="15" class="huruf">
        <tr>
            <td width="138">1. Nama Klinik </td>
            <td>: 
                <?php
                $nama = Str::lower($anggota->nama_klinik);
                $nama = ucwords($nama);
                echo $nama;
                ?>
                </td>
        </tr>

        <tr>
            <td>2. Jenis Klinik </td>
            <td>: {{ $anggota->jenis_klinik }}</td>
        </tr>

        <tr>
            <td>3. Kriteria Klinik </td>
            <td>: {{ $anggota->nama_fasilitas_klinik }}</td>
        </tr>

        <tr>
            <td>4. Alamat</td>
            <td>:
                <?php
                $alamat = Str::lower(substr($anggota->alamat_klinik, 0, 100));
                $alamat = ucwords($alamat);
                echo $alamat;
                ?>
            </td>
        </tr>

        <tr>
            <td>5. RT/RW</td>
            <td>: {{ $anggota->rt . '/' . $anggota->rw }}</td>
        </tr>

        <tr>
            <td>6. Kabupaten/Kota </td>
            <td>:  
                <?php
                $kota = Str::lower($anggota->kota);
                $kota = ucwords($kota);
                echo $kota;
                ?></td>
        </tr>

        <tr>
            <td>7. Propinsi</td>
            <td>: <?php
                $provinsi = Str::lower($anggota->provinsi);
                $provinsi = ucwords($provinsi);
                echo $provinsi;
                ?></td>
        </tr>

        <tr>
            <td>8. No.Telp</td>
            <td>: {{ $anggota->tlf_klinik }}</td>
        </tr>

        <tr>
            <td>9. Pemilik</td>
            <td></td>
        </tr>
        <tr>
            <td><span style="text-indent: 30px;">Nama Perorangan<b> / <b>Perusahaan </b></b></span></td>
            <td>
                @if ($anggota->status_kepemilikan == 'Perorangan')
                    : {{ $anggota->nama_pemilik_klinik }}
                @else
                    : {{ $anggota->nama_badan_usaha }}
                @endif
            </td>
        </tr>
    </table>

    <div class="bungkus">
        <p>Dari hasil verifikasi dan survey lapangan yang dilakukan oleh Pengurus / Staff Asosiasi Klinik Indonesia
            (ASKLIN) <br>Cabang / Daerah / Pusat maka berhak disyahkan menjadi anggota Asosiasi Klinik Indonesia
            (ASKLIN)<br>dengan NOMOR ANGGOTA :</p>
    </div>
    <div class="noanggota"> {{ $anggota->no_anggota }}</div>

    <div class="container">
        <p class="khusus">1. Keanggotaan dapat dibekukan apabila :</p>
        <span>a. Tidak mematuhi peraturan dan ketentuan Perundang-undangan Negara Republik Indonesia yang
            berlaku</span><br>
        <span>b. Tidak mematuhi peraturan organisasi Asosiasi Klinik Indonesia (ASKLIN)</span><br>
        <span>c. Alamat dan kepemilikan tidak sesuai dengan yang dicantumkan</span><br>
        <span>d. Jika klinik tutup sementara atau permanen</span><br>
        <span>e. Tidak memenuhi kewajiban sebagai Anggota Asosiasi Klinik Indonesia (ASKLIN)</span><br>
        <span>f. Pembekuan ini dapat diaktifkan kembali apabila mengajukan permohonan kembali dan memenuhi
            <br>&nbsp;&nbsp;&nbsp;&nbsp;kewajiban - kewajibannya sebagai anggota</span>

        <p class="khusus">2. Keanggotaan dapat diberhentikan atau dicabut nomor keanggotaannya apabila :</p>
        <ul style="margin-top:-4mm">
            <li>Menjadi dan / atau terdaftar anggota organisasi lain yang sejenis bentuk tujuan dan kegiatannya </li>
        </ul>
    </div>

    <p class="penutup">Demikian surat keputusan ini untuk dapat dipergunakan sebagai anggota Asosiasi Klinik Indonesia.
    </p>

    <div class="footer">
        {{-- <p class="ttd">Jakarta, 21 Februari 2023</p> --}}
        <p style="margin-top:-10px;">PENGURUS PUSAT ASOSIASI KLINIK INDONESIA</p>
        <p style="margin-top:-10px;"class="ttd">KETUA UMUM</p>
        <img src="{{ asset('/assets/images/ttd.png') }}" alt="" width="220">
        <p class="ttd">dr. Eddi Junaidi, SpOG.,SH.,MKes</p>
    </div>

    {{-- <div id="qrcode" style="width:100px; height:100px; margin-top:15px;"></div> --}}

    <table cellpadding="0" cellspacing="0" class="qrcode">
        <tr>
            <td>
                <qrcode value="Validasi Kunjungi Link Berikut : https://asklin.org/cekqrcode/{{ $anggota->id }}"
                    ec="Q" style="width: 25mm; border: none; "></qrcode>
            </td>
        </tr>
    </table>

</page>
