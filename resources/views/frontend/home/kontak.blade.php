@extends('layouts.frontend.layout')


@section('content')
    <section id="content">

        <div class="content-wrap">

            <div class="container clearfix">

                <div class="row justify-content-around">
                    <div class="col-lg-5">
                        <h4 class="display-3 color fw-normal font-display mb-5">Hubungi Kami</h4>

                        <div class="clear"></div>

                        <div class="feature-box fbox-sm mb-3">
                            <div class="fbox-icon">
                                <i class="bg-color-50 color icon-call"></i>
                            </div>
                            <div class="fbox-content">
                                <h3 class="nott text-larger fw-normal mb-2 color">
                                    <strong class="mb-2 h6 d-block text-dark">Kantor Pusat Asklin:</strong>
                                    Jl. Pinang Ranti II No.1A Kel.Pinang Ranti Kec. Makasar<br>
                                    Jakarta Timur<br>
                                    Kode Pos : 13560
                                </h3>
                            </div>
                        </div>
                        <div class="feature-box fbox-sm mb-3">
                            <div class="fbox-icon">
                                <i class="bg-color-50 color icon-call"></i>
                            </div>
                            <div class="fbox-content">Telp :
                                <h3 class="nott text-larger fw-normal mb-2"><a href="tel:+0218098928">
                                        (021)8098928</a></h3>
                            </div>
                        </div>
                        <div class="feature-box fbox-sm mb-3">
                            <div class="fbox-icon">
                                <i class="bg-color-50 color icon-call"></i>
                            </div>
                            <div class="fbox-content">Whatsapp :
                                <h3 class="nott text-larger fw-normal mb-2"><a
                                        href="https://api.whatsapp.com/send?phone=6282310981637">
                                    </a>082310981637</h3>
                            </div>
                        </div>
                        <div class="feature-box fbox-sm mb-5">
                            <div class="fbox-icon">
                                <i class="bg-color-50 color icon-email3"></i>
                            </div>
                            <div class="fbox-content">
                                <h3 class="nott text-larger fw-normal mb-2"><a
                                        href="mailto:pp_asklin@asklin.org">pp_asklin@asklin.org</a></h3>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-5">
                        <div class="form-widget">

                            <div class="form-result"></div>

                            <form class="mb-0" id="formKontak">
                                @csrf

                                <div class="form-process">
                                    <div class="css3-spinner">
                                        <div class="css3-spinner-scaler"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group mb-4">
                                        <label class="nott ls0" for="nama">Nama Lengkap
                                            <small>*</small></label>
                                        <input type="text" id="nama" name="nama" value=""
                                            class="rounded-pill form-control required" />
                                    </div>

                                    <div class="col-12 form-group mb-4">
                                        <label class="nott ls0" for="email">Email
                                            <small>*</small></label>
                                        <input type="email" id="email" name="email" value=""
                                            class="required email rounded-pill form-control" />
                                    </div>

                                    <div class="col-12 form-group mb-4">
                                        <label class="nott ls0" for="pesan">Pesan
                                            <small>*</small></label>
                                        <textarea class="required rounded-5 form-control" id="pesan" name="pesan" rows="6" cols="30"></textarea>
                                    </div>

                                    <div class="col-12 form-group mb-4 d-none">
                                        <input type="text" id="template-contactform-botcheck"
                                            name="template-contactform-botcheck" value=""
                                            class="rounded-pill form-control" />
                                    </div>

                                    <div class="col-12 form-group mb-4">
                                        <button
                                            class="button button-large rounded-pill bg-color px-4 py-2 h-op-09 op-ts m-0"
                                            type="submit" value="submit">Kirim Pesan</button>
                                    </div>
                                </div>

                                <input type="hidden" name="prefix" value="template-contactform-">

                            </form>
                        </div>
                    </div>
                </div>

                <div class="card bg-color-2 border-0 mt-5"
                    style="background: url(demos/covid-care/images/illustration/bg-pattern.svg) no-repeat center left / auto 300%;">
                    <div class="card-body p-5">
                        <div class="row justify-content-between col-mb-30">
                            <div class="col-md-4">
                                <h3 class="mb-1 color">Customer Support</h3>
                                <a href="tel:+021809892" class="text-dark"><u>082310981637</u></a><br>
                                <a href="https://api.whatsapp.com/send?phone=6282310981637" class="text-dark"><u></u></a>
                            </div>

                            <div class="col-md-4">
                                <h3 class="mb-1 color">Contact us via Email</h3>
                                <a href="#" class="text-dark"><u>pp_asklin@asklin.org</u></a><br>
                                <a href="#" class="text-dark"><u>admin@asklin.org</u></a>
                            </div>

                            <div class="col-md-4">
                                <h3 class="mb-1 color">Live chat with Us</h3>
                                <a href="https://api.whatsapp.com/send?phone=6282310981637" class="text-dark"><u>Click
                                        here</u><i class="icon-line-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section @endsection @push('css') @endpush @push('js') <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('formKontak');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                fetch("{{ route('storeKontak') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message
                            });
                            form.reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim data.'
                        });
                    });
            });
        });
    </script>
@endpush
