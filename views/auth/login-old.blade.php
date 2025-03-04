@extends('layouts.frontend.layout')
 
@section('content')
<div class="container pt-4 pb-5">
    <div class="fancy-title title-border">
        <h3>Tatacara Pendaftaran</h3>
    </div>
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-5 col-md-6 mb-5 mb-md-0">
            <div class="feature-box fbox-plain bottommargin-sm">
                <div class="fbox-icon">
                    <i class="icon-line-circle-check text-info"></i>
                </div>
                <div class="fbox-content">
                    <h3 class="fw-normal nott">Pendaftaran Akun</h3>
                    <p>Sebelum mendaftar, Calon Anggota asklin wajib membuat akun pendaftaran terlebih dahulu. Pembuatan akun pendaftaran menggunakan email aktif dan selanjutnya membuat password</p>
                </div>
            </div>
            <div class="feature-box fbox-plain bottommargin-sm">
                <div class="fbox-icon">
                    <i class="icon-line-circle-check text-info"></i>
                </div>
                <div class="fbox-content">
                    <h3 class="fw-normal nott">{{ $title = "Login" }}</h3>
                    <p> Setelah membuat akun pendaftaran, silahkan login ke akun pendaftaran dengan memasukkan email, password</p>
                </div>
            </div>
            <div class="feature-box fbox-plain bottommargin-sm">
                <div class="fbox-icon">
                    <i class="icon-line-circle-check text-info"></i>
                </div>
                <div class="fbox-content">
                    <h3 class="fw-normal nott">Input Data Klinik</h3>
                    <p>Setelah login berhasil, calon pendaftar akan diarahkan ke halaman inpu data klinik yang berisikan informasi data klinik, Calon anggota asklin diwajibkan untuk mengunggah SIO.</p>
                </div>
            </div>
            <div class="feature-box fbox-plain  bottommargin-sm">
                <div class="fbox-icon">
                    <i class="icon-line-circle-check text-warning"></i>
                </div>
                <div class="fbox-content">
                    <h3 class="fw-normal nott">Verifikasi Data</h3>
                    <p>Setelah input data klinik akan dilakukan verifikasi oleh pengurus cabang serta pengurus daerah</p>
                </div>
            </div>
            <div class="feature-box fbox-plain">
                    <div class="fbox-icon">
                        <i class="icon-line-circle-check text-primary"></i>
                    </div>
                    <div class="fbox-content">
                        <h3 class="fw-normal nott">Cetak <i>E-SK ANGGOTA</i> dan <i>E-Sertifikat</i></h3>
                        <p>Anda dapat mendownload SK anggota dan sertifikat Anggota Secara Online</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="card pricing border-0 shadow bg-color dark">
                    <div class="card-body rounded pb-0 px-4 px-lg-5 pt-4 pt-lg-5">
                        <div class="d-block d-lg-flex flex-row justify-content-lg-between align-items-lg-center">
                            <h3 class="h6 fw-bolder mb-2 mb-lg-0 text-white">Apakah Anda sudah mempunyai Akun?</h3>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="button button-small button-light button-white button-rounded m-0">Pendaftaran Akun</a>
                            @endif
                        </div>
                        <div class="line line-sm"></div>
                        <h3 class="h5 fw-bolder mb-3 text-white">Login</h3>
                        <p class="text-smaller mb-0" style="line-height: 1.5;">
                            Password adalah kunci yang dipergunakan untuk login pendaftaran, diisi
                            sesuai yang diisikan saat registrasi/pembuatan akun.
                        </p>
                        <form class="mb-0 row" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="col-12 form-group">
                                <label class="nott ls0 font-body mb-1 fw-normal" for="email">Email:</label>
                                <input id="email" type="email" class="form-control fw-semibold not-dark @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="clear"></div>
                            <div class="col-12 form-group">
                                <label class="nott ls0 font-body mb-1 fw-normal" for="password">Password:</label>
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control fw-semibold not-dark @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-between">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label nott ls0 mb-0 fw-semibold" for="inlineCheckbox2">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-smaller fw-medium text-light" href="{{ route('password.request') }}">Lupa password?</a>
                                @endif
                            </div>
                            <div class="col-12 text-center">
                                <input type="submit" class="btn btn rounded bg-dark text-white text-uppercase fw-semibold ls1 py-2 px-5" value="login">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
    </div>
</div>
@endsection