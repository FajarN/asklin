@extends('layouts.frontend.layout')
 
@section('content')
			<div class="content-wrap py-0">

				<div class="section p-0 m-0 h-100 position-absolute" style="background: url('{{ asset('images/file/bclogin.jpg') }}') center center no-repeat; background-size: cover;"></div>

				<div class="section bg-transparent min-vh-100 p-0 m-0">
					<div class="vertical-middle">
						<div class="container-fluid py-5 mx-auto">
							<div class="center">
								<a href="index.html"><img src="{{ asset('images/file/logo_asklin.png') }}" alt="Canvas Logo" width="100"></a>
							</div>
							<br>

							<div class="card mx-auto rounded-0 border-0" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
								<div class="card-body" style="padding: 40px;">
									<form id="login-form" name="login-form" class="mb-0" action="{{ route('password.email') }}" method="post">
										  @csrf
										  <?php $title = "Reset Password" ?>

										<div class="row">
                                               @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            
											<div class="col-12 form-group">
												<label for="login-form-username">Email:</label>
												 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                </div>
										

                                                <div class="col-12 form-group">
                                                <button type="submit"  class="button button-3d button-black m-0">
                                                {{ __('Send Password Reset Link') }}
                                            </button>
											</div>
										</div>
									</form>


								</div>
							</div>

						</div>
					</div>
				</div>
            </div>
            

			</div>
		</section><!-- #content end -->
@endsection