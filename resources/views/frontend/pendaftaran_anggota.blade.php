@extends('layouts.frontend.layout')
 
@section('content')
<section id="page-title" class="page-title-parallax page-title-dark" style="background-image: url('{{ asset("assets/frontend/images/baru/paralax.jpg") }}'); background-size: cover; padding: 120px 0;" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">
    <div class="container clearfix">
        <h1 class="text-center">PENDAFTARAN ANGGOTA ASKLIN</h1>    
    </div>
</section>

<div class="line d-block d-md-none"></div>

@include('frontend.step')

<div class="container pt-3 pb-2">
    <form action="{{ route('update.anggota', $anggota->id) }}" method="POST">
        @CSRF
        @method('PUT')
        <div class="row pt-6">
            <div class="col-md-6 col-lg-6 mb-5 mb-lg-0">
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Nama Klinik</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="text" name="nama_klinik" value="{{ $anggota->nama_klinik }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Nama Kontak/ penganggung jawab</strong></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="text" name="nama_kontak" value="{{ $anggota->nama_kontak }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Email</strong></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="email" name="email" value="{{ $anggota->email }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>No telpon</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="number" name="tlf" value="{{ $anggota->tlf }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>File SIO</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="file" name="sio" value="{{ $anggota->sio }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Status Pendaftar</strong></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Status Pendaftar" name="status_pendaftar" required>
                            <option value="Pemilik" {{ ( 'Pemilik' == $anggota->status_pendaftar) ? 'selected' : '' }}>Pemilik</option>
                            <option value="Penanggunjawab" {{ ( 'Penanggunjawab' == $anggota->status_pendaftar) ? 'selected' : '' }}>Penanggungjawab</option>
                            <option value="Pengelola" {{ ( 'Pengelola' == $anggota->status_pendaftar) ? 'selected' : '' }}>Pengelola</option>
                            <option value="Pemilik dan Penanggungjawab" {{ ( 'Pemilik dan Penanggungjawab' == $anggota->status_pendaftar) ? 'selected' : '' }}>Pemilik dan Penanggungjawab</option>
                            <option value="Pengelola dan penanggungjawab" {{ ( 'Pengelola dan penanggungjawab' == $anggota->status_pendaftar) ? 'selected' : '' }}>Pengelola dan Penanggungjawab</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>No Ijin Klinik <b style="color:red; font-size:17px">*</b></strong></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="text" name="no_ijin" value="{{ $anggota->no_ijin }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Tgl.Terbit Ijin</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="date" name="tgl_ijin" value="{{ $anggota->tgl_ijin }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>izin Berakhir</strong></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="date" name="tgl_akhir_ijin" value="{{ $anggota->tgl_akhir_ijin }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-6">
            <div class="container py-5 shop" id="shop">
                <div class="row pt-1 pb-1">
                    <div class="col">
                        <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-3 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">ALAMAT & FASILITAS KLINIK</h3>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 mb-5 mb-lg-0">
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Jenis Klinik</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Jenis Klinik" name="jenis_klinik" id="jenis_klinik" required>
                                <option value="Utama" {{ ( 'Utama' == $anggota->jenis_klinik) ? 'selected' : '' }}>Utama</option>
                                <option value="Pratama" {{ ( 'Pratama' == $anggota->jenis_klinik) ? 'selected' : '' }}>Pratama</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Status Kepemilikan Klinik</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Status Kepemilikan Klinik" name="status_kepemilikan" id="status_kepemilikan_klinik" required>
                            <option value="Perorangan" {{ ( 'Perorangan' == $anggota->status_kepemilikan) ? 'selected' : '' }}>Perorangan</option>
                            <option value="Badan Usaha" {{ ( 'Badan Usaha' == $anggota->status_kepemilikan) ? 'selected' : '' }}>Badan Usaha</option>
                            <option value="Badan Hukum" {{ ( 'Badan Hukum' == $anggota->status_kepemilikan) ? 'selected' : '' }}>Badan Hukum</option>
                            <option value="Pemerintah" {{ ( 'Pemerintah' == $anggota->status_kepemilikan) ? 'selected' : '' }}>Pemerintah</option>
                            <option value="POLRI" {{ ( 'POLRI' == $anggota->status_kepemilikan) ? 'selected' : '' }}>POLRI</option>
                            <option value="TNI" {{ ( 'TNI' == $anggota->status_kepemilikan) ? 'selected' : '' }}>TNI</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="bbu">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Bentuk Badan Usaha</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" name="bentuk_badan_usaha">
                            <option value="">--Pilih--</option>
                            <option value="CV" {{ ( 'CV' == $anggota->bentuk_badan_usaha) ? 'selected' : '' }}>CV</option>
                        </select>
                    </div>
                </div>
                  <div class="form-group row" id="perorangan">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Nama pemilik klinik</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="text" name="nama_pemilik_klinik" value="{{ $anggota->nama_pemilik_klinik }}">
                    </div>
                </div>
                <div class="form-group row" id="bbh">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Bentuk Badan Hukum</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-="bentuk_badan_hukum" name="bentuk_badan_hukum">
                            <option value="PT" {{ $anggota->bentuk_badan_hukum == 'PT'  ? 'selected' : '' }}>PT</option>
                            <option value="Yayasan" {{ $anggota->bentuk_badan_hukum == 'Yayasan'  ? 'selected' : '' }}>Yayasan</option>
                            <option value="Koperasi" {{ $anggota->bentuk_badan_hukum == 'Koperasi'  ? 'selected' : '' }}>Koperasi</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="nbhu">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Nama Badan Hukum/Usaha</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input type="text" class="form-control text-3 h-auto py-2" name="nama_badan_usaha" value="{{ $anggota->nama_badan_usaha }}">
                    </div>
                </div>
                <div class="form-group row">
                        <legend class="col-form-label col-sm-3 pt-0"><strong>Alamat</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-12">
                        <textarea data-msg-required="Please enter your address" rows="4" class="form-control text-3 h-auto py-2" name="alamat_klinik" required>{{ $anggota->alamat_klinik }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <legend class="col-form-label col-sm-3 pt-0"><strong>RT</strong></legend>
                        <input type="text" data-msg-required="rt" class="form-control text-3 h-auto py-2" name="rt" value="{{ $anggota->rt }}" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <legend class="col-form-label col-sm-3 pt-0"><strong>RW</strong></legend>
                        <input type="text" data-msg-required="rw" class="form-control text-3 h-auto py-2" name="rw" value="{{ $anggota->rw }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Kode Pos</strong></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="number" name="kode_pos" value="{{ $anggota->kode_pos }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Telpon Klinik</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <input class="form-control text-3 h-auto py-2" type="text" name="tlf_klinik" value="{{ $anggota->tlf_klinik }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Propinsi</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Provinsi" name="id_provinsi" id="provinsi" required>
                            <option>==Pilih Provinsi==</option>
                            @foreach ($provinsi as $i)
                                <option value="{{ $i->code }}" {{ $anggota->id_provinsi == $i->code  ? 'selected' : '' }}>{{ $i->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Kabupaten / Kota</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Kabupaten/kota" name="id_kota" id="kota" required></select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Kecamatan</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Kecamatan" name="id_kecamatan" id="kecamatan" required></select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-form-label col-sm-3 pt-0"><strong>Kelurahan</strong> <b style="color:red; font-size:17px">*</b></legend>
                    <div class="col-lg-9">
                        <select class="form-select form-control h-auto" data-msg-required="Kelurahan" name="id_kelurahan" id="kelurahan" required></select>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="overflow-hidden mb-1">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-3 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">KRITERIA KLINIK <b style="color:red; font-size:17px">*</b></h3> 
                </div>

                <div class="row">
                    @foreach($fasilitas_klinik as $k)
                        <div class="form-group col-lg-6">
                            <div class="custom-checkbox-1">
                                <input type="checkbox" name="fasilitas_klinik[]" value="{{ $k->id }}" {{ (in_array($k->id, explode(",", $anggota->fasilitas_klinik)) ? 'checked' : '')}}>
                                <label class="custom-control-label">{{ $k->nama }}</label>
                            </div>
                        </div>
                    @endforeach

                    <div class="overflow-hidden mb-1">
                        <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-3 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">FASILITAS KLINIK <small style="color:blue; font-size:10px">Di Isi Yang Ada</small></h3>
                    </div>

                    <div class="row">
                        @foreach($fasilitas as $k)
                            <div class="form-group col-lg-6">
                                <div class="custom-checkbox-1">
                                    <input type="checkbox" name="id_fasilitas[]" value="{{ $k->id }}" {{ (in_array($k->id, explode(",", $anggota->id_fasilitas)) ? 'checked' : '')}}>
                                    <label class="custom-control-label">{{ $k->nama }}</label>
                                </div>
                            </div>
                        @endforeach

                        <div class="overflow-hidden mb-1">
                            <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-3 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">JENIS LAYANAN</h3>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <div class="custom-checkbox-1" id="c1">
                                    <input id="Akupuntur" type="checkbox" name="id_layanan[]" value="1" {{ (in_array(1, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Akupuntur">Akupuntur</label>
                                </div>
                                <div class="custom-checkbox-1" id="c2">
                                    <input id="Bedah" type="checkbox" name="id_layanan[]" value="2" {{ (in_array(2, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Bedah">Bedah</label>
                                </div>
                                    <div class="custom-checkbox-1" id="c3">
                                    <input id="Gigi" type="checkbox" name="id_layanan[]" value="3" {{ (in_array(3, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Gigi">Gigi</label>
                                </div>
                                    <div class="custom-checkbox-1" id="c4">
                                    <input id="Kebidanan" type="checkbox" name="id_layanan[]" value="4" {{ (in_array(4, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Kebidanan">Kebidanan</label>
                                </div>
                                    <div class="custom-checkbox-1" id="c5">
                                    <input id="PenyakitDalam" type="checkbox" name="id_layanan[]" value="5" {{ (in_array(5, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="PenyakitDalam">Penyakit Dalam</label>
                                </div>
                                    <div class="custom-checkbox-1" id="c6">
                                    <input id="RehabilitasiMedik" type="checkbox" name="id_layanan[]" value="6" {{ (in_array(6, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="RehabilitasiMedik">Rehabilitasi Medik</label>
                                </div>
                                    <div class="custom-checkbox-1" id="c7">
                                    <input id="Umum" type="checkbox" name="id_layanan[]" value="7" {{ (in_array(7, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Umum">Umum</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-6">
                                <div class="custom-checkbox-1" id="c8">
                                    <input id="Anak" type="checkbox" name="id_layanan[]" value="8" {{ (in_array(8, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Anak">Anak</label>
                                </div>
                                <div class="custom-checkbox-1" id="c9">
                                    <input id="Estetika" type="checkbox" name="id_layanan[]" value="9" {{ (in_array(9, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Estetika">Estetika</label>
                                </div>
                                <div class="custom-checkbox-1" id="c10">
                                    <input id="Hemodialisa" type="checkbox" name="id_layanan[]" value="10" {{ (in_array(10, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Hemodialisa">Hemodialisa</label>
                                </div>
                                <div class="custom-checkbox-1" id="c11">
                                    <input id="Mata" type="checkbox" name="id_layanan[]" value="11" {{ (in_array(11, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Mata">Mata</label>
                                </div>
                                <div class="custom-checkbox-1" id="c12">
                                    <input id="Persalinan" type="checkbox" name="id_layanan[]" value="12" {{ (in_array(12, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="Persalinan">Persalinan 24 Jam</label>
                                </div>
                                <div class="custom-checkbox-1" id="c13">
                                    <input id="THT" type="checkbox" name="id_layanan[]" value="13" {{ (in_array(13, explode(",", $anggota->id_layanan)) ? 'checked' : '')}}/>
                                    <label for="THT">THT</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group col-lg-9"></div>
            <div class="form-group col-lg-3">
                <button type="submit" class="button button-rounded button-reveal button-large button-border text-end button-red"><i class="icon-line-arrow-right ms-2"></i><span>SELANJUTNYA</span></button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
$(function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#provinsi").select2().val({{ $anggota->id_provinsi }}).trigger("change");

    $('#provinsi').on('change', function(){
        let id_provinsi = $('#provinsi').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKota') }}',
            data: {id_provinsi:id_provinsi},
            cache: false,
            success: function(data){
                $('#kota').html(data);
                $("#kota").select2().val({{ $anggota->id_kota }}).trigger("change");
                $('#kecamatan').html();
                $('#kelurahan').html();
            }
        })
    }).change();

    $('#kota').on('change', function(){
        let id_kota = $('#kota').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKecamatan') }}',
            data: {id_kota:id_kota},
            cache: false,
            success: function(data){
                $('#kecamatan').html(data);
                $("#kecamatan").select2().val({{ $anggota->id_kecamatan }}).trigger("change");
                $('#kelurahan').html();
            }
        })
    }).change();

    $('#kecamatan').on('change', function(){
        let id_kecamatan = $('#kecamatan').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('getKelurahan') }}',
            data: {id_kecamatan:id_kecamatan},
            cache: false,
            success: function(data){
                $('#kelurahan').html(data);
                $("#kelurahan").select2().val({{ $anggota->id_kelurahan }}).trigger("change");
            }
        })
    }).change();
});

$("#status_kepemilikan_klinik").change(function() {
    $('#perorangan').hide();
    if ($(this).val() == "Perorangan") {
        $('#bbu').hide();
        $('#bbh').hide();
        $('#nbhu').hide();
        $('#perorangan').show();
    } else if ($(this).val() == "Badan Usaha") {
        $('#perorangan').hide();
        $('#bbu').show();
        $('#bbh').hide();
        $('#nbhu').show();
    } else if ($(this).val() == "Badan Hukum") {
        $('#bbu').hide();
         $('#perorangan').hide();
        $('#bbh').show();
        $('#nbhu').show();
    } else if ($(this).val() == "Pemerintah") {
        $('#bbu').hide();
        $('#bbh').hide();
        $('#nbhu').hide();
        $('#perorangan').show();
   } else if ($(this).val() == "TNI") {
        $('#bbu').hide();
        $('#bbh').hide();
        $('#nbhu').hide();
        $('#perorangan').show();
   } else if ($(this).val() == "POLRI") {
        $('#bbu').hide();
        $('#bbh').hide();
        $('#nbhu').hide();
        $('#perorangan').show();
    } else{
        $('#bbu').hide();
        $('#bbh').hide();
        $('#nbhu').hide();
        $('#perorangan').hide();
    }
});
$("#status_kepemilikan_klinik").trigger("change");

$("#jenis_klinik").change(function() {
    if ($(this).val() == "Utama") {
        $('#c1, #c2, #c3, #c4, #c5, #c6, #c7, #c8, #c9, #c10, #c11, #c12, #c13').show();
    } else if ($(this).val() == "Pratama") {
        $('#c8, #c3, #c7, #c12, #c9, #c4, #c6').show();
        $('#c1, #c2, #c5, #c10, #c11, #c13').hide();
    }
});
$("#jenis_klinik").trigger("change");
</script>
@endpush



