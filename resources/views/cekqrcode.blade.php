<!DOCTYPE HTML>
<html>
	<head>
	<title>Cek Nomor Anggota</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Phudu:wght@800&display=swap" rel="stylesheet">
	</head>
	<body>
    <style>
        :root {
    font-size: 20px;
    box-sizing: inherit;
    }

    *,
    *:before,
    *:after {
    box-sizing: inherit;
    }

    p {
    margin: 0;
    }

    p:not(:last-child) {
    margin-bottom: 1.5em;
    }

    body {
    font: 1em/1.618 Inter, sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 30px;
    color: #224;
    background:
        url({{ asset("assets/images/qrcode.jpg") }})
        center / cover no-repeat fixed;
    }

    .card {
    max-width: 350px;
    min-height: 550px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    max-width: 350px;
    height: 550px;
    padding: 35px;
    border: 1px solid rgba(255, 255, 255, .25);
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.45);
    box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(15px);
    }
    .card p{
        text-align: center;
    }
    .card strong {
        font-family: 'Phudu', cursive;
    }

    .card-footer {
    font-size: 0.65em;
    color: #446;
    }

    @media screen and (max-width: 600px) {
    body {
        font-size: 16px;
    }
    }
</style>
<body>

  <div class="card">
  <p>Mengesahkan<br>
    <strong>{{ $anggota->nama_klinik }}</strong><br>
    Nomor Anggota <br>
    <strong>{{ $anggota->no_anggota }}</strong><br>
    Alamat <br>
    {{ $anggota->alamat_klinik }}
   </p>

     <p style="margin-top:-80px;">Pengurus Pusat <br>
    ASOSIASI KLINIK INDONESIA <br>
    Ketua Umum <br>
    <img src="{{ asset('/assets/images/ttd.png') }}" alt="" width="300">
 </p>
 <p style="margin-top:-120px;">dr. Eddi Junaidi, SpOG.,SH.,M.Kes </p>
  <p class="card-footer"><?php $mytime = Carbon\Carbon::now();
                            echo $mytime->toDateTimeString(); ?></p>
</div>
</body>
</html>