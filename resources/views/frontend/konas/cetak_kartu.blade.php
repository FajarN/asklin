<style type="text/css">

table
{
    padding: 0;
    margin: 0;
    border: none;
}
td
{
    padding: 0;
    margin: 0;
    border: none;
}

img
{
    width: 10mm;
}
.header{
 position:absolute;
 top:14%;
 left:37%; 
 
}

.foto{
     position:absolute;
    font-weight:bold;
    float:left;
    top:33%;
    left:7%;
    font-size:25px; 
}

.qrcode{
    position:absolute;
     float:left;
    top:70%;
    left:23%;
}

.status {
    margin: auto;
    width: 640px; 
    padding: 50px;
    color: #fff;  
    text-align:center;
    position:absolute;
    top:12%;
    font-size:40px;
    font-weight:bold;
}
.nama {
    position:absolute;
    font-weight:bold;
    float:left;
    top:37%;
    left:45%;
    font-size:25px;
}

.noanggota {
    position:absolute;
    float:left;
    top:50%;
    left:45%;
    font-size:35px;
}

.komisi {
    position:absolute;
    float:left;
    top:64%;
    left:45%;
    font-size:30px;
}


}



</style>
<page>

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td><img src="{{ asset('assets/images/imgkonas.jpg') }}" alt="" style="width:100%"></td>
        </tr>
    </table>

    <div class="foto"><img src="{{ asset('images/konas').'/'.$peserta->foto }}" alt="" style="width:60mm"></div>

    <div class="status">{{ $peserta->status }}</div>

    <div class="nama">{{ $peserta->nama_sertifikat }}</div>

    <div class="noanggota"><b>{{ $peserta->no_anggota }}</b></div>

    <div class="komisi">{{ $peserta->komisi }}</div>

    
    <table cellpadding="0" cellspacing="0" class="qrcode">
        <tr>
            <td>
           <qrcode value="<?php echo 'Nomor Kartu : '.$peserta->no_anggota."\n" ?>" ec="Q" style="width: 40mm; border: none;" ></qrcode>
       </td>
    </tr>
   </table>
   
</page>