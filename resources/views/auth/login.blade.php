@extends('layouts.frontend.layout')
 
@section('content')
			<div class="content-wrap py-0">

				<div class="section p-0 m-0 h-100 position-absolute" style="background: url('{{ asset('images/file/bclogin.jpg') }}') center center no-repeat; background-size: cover;"></div>

				<div class="section bg-transparent min-vh-100 p-0 m-0">
					<div class="vertical-middle">
						<div class="container-fluid py-5 mx-auto">
							<div class="center">
								<a class="d-none d-none d-sm-block" href="{{ route('konas') }}"><img src="{{ asset('images/file/logo_asklin.png') }}" alt="Canvas Logo" width="100"></a>
							</div>
							<br>

							<div class="card mx-auto rounded-0 border-0" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
								<div class="card-body" style="padding: 40px;">
									<form id="login-form" name="login-form" class="mb-0" action="#" method="post">
										  @csrf
										<h3>{{ $title = "Login" }}</h3>

										<div class="row">
											<div class="col-12 form-group">
												<label for="login-form-username">Email:</label>
												 <input id="email" type="email" class="form-control fw-semibold not-dark @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                </div>

											<div class="col-12 form-group">
												<label for="login-form-password">Password:</label>
												<div class="input-group">
												<input id="password" type="password" class="form-control fw-semibold not-dark @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
													<span class="input-group-text bg-white"><i id="show-password" class="icon-eye-open"></i></span>
												</div>
												 @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
											</div>
											
											{{-- <div class="col-12 form-group">
												<label for="login-form-password">Password:</label>
												<input id="password" type="password" class="form-control fw-semibold not-dark @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
												
                                            </div> --}}

									<div class="col-12 form-group">
                                         <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label nott ls0 mb-0 fw-semibold" for="inlineCheckbox2">Remember me</label>
                                       </div>
												
                                        @if (Route::has('password.request'))
                                         <a class="float-end" href="{{ route('password.request') }}">Lupa password?</a>
                                         @endif
                                         <input  type="submit" class="button button-3d button-black m-0" id="login-form-submit" value="login">
											</div>
										</div>
									</form>

									{{-- <div class="line line-sm"></div> --}}

									 <div class="w-100 text-center">
										<h4 style="margin-bottom: 15px;">Belum Punya Akun ?</h4>
										<a href="{{ route('register') }}" class="button button-rounded  si-colored">Register</a>
										
									</div> 
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</section><!-- #content end -->
@endsection