@extends('layouts.frontend.layout')
 
@section('content')
<section id="page-title" class="page-title-parallax page-title-dark" style="background-image: url('{{ asset("assets/frontend/images/baru/paralax.jpg") }}'); background-size: cover; padding: 120px 0;" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">
    <div class="container clearfix">
        <h1 class="text-center">PENDAFTARAN SDM</h1>    
    </div>
</section>

<div class="line d-block d-md-none"></div>

@include('frontend.step')

<div class="container pt-3 pb-2">
    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">PENANGGUNG JAWAB KLINIK &nbsp;(<small>Wajib Diisi</small>)</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table1">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addpj()">
                    <span><i class="icon-plus"></i> Tambah Penanggungjawab</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokter</th>
                    <th>NPA IDI/PDGI</th>
                    <th>No. STR</th>
                    <th>No. SIP</th>
                    <th>Tgl. Akhir SIP</th>
                    <th>Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Penanggung Jawab -->
        <div class="modal fade" id="formPenanggungjawab" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Dokter Penanggungjawab</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-pj" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_pj" id="id_pj"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Peranan</label>
                                <div class="col-sm-9">
                                    <select class="form-select form-control h-auto" data-msg-required="DokterPenanggungJawab" name="id_kategori_sdm_pj" id="id_kategori_sdm_pj" required>
                                        <option value="1">Dokter Penanggungjawab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Dokter</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_dokter_pj" id="nama_dokter_pj" class="form-control" placeholder="Nama Lengkap Dokter Dengan Gelar" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor NPA IDI</label>
                                <div class="col-sm-9">
                                    <input type="text" name="npa_idi_pj" id="npa_idi_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor STR</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_str_pj" id="no_str_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor SIP</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_sip_pj" id="no_sip_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email_dokter_pj" id="email_dokter_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Tgl Akhir SIP</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tgl_akhir_sip_pj" id="tgl_akhir_sip_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">No Telpon</label>
                                <div class="col-sm-9">
                                    <input type="number" name="no_tlf_pj" id="no_tlf_pj" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>
                                <div class="col-sm-9">
                                    <label for="file_sip_pj" class="form-label">Upload SIP</label>
                                    <input class="form-control" type="file" id="file_sip_pj" name="file_sip_pj">
                                    <label for="file_str_pj" class="form-label">Upload STR</label>
                                    <input class="form-control" type="file" id="file_str_pj" name="file_str_pj">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-pj">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">DOKTER PRAKTEK</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table2">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="adddp()">
                    <span><i class="icon-plus"></i> Tambah Dokter Praktek</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokter</th>
                    <th>NPA IDI/PDGI</th>
                    <th>No. STR</th>
                    <th>No. SIP</th>
                    <th>Tgl. Akhir SIP</th>
                    <th>Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Dokter Praktek -->
        <div class="modal fade" id="formDokterPraktek" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Dokter Prakter</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-dp" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_dp" id="id_dp"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Peranan</label>
                                <div class="col-sm-9">
                                    <select class="form-select form-control h-auto" data-msg-required="DokterPenanggungJawab" name="id_kategori_sdm_dp" id="id_kategori_sdm_dp" required>
                                        <option value="2">Dokter Praktek</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Dokter</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_dokter_dp" id="nama_dokter_dp" class="form-control" placeholder="Nama Lengkap Dokter Dengan Gelar" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor NPA IDI</label>
                                <div class="col-sm-9">
                                    <input type="text" name="npa_idi_dp" id="npa_idi_dp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor STR</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_str_dp" id="no_str_dp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor SIP</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_sip_dp" id="no_sip_dp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email_dokter_dp" id="email_dokter_dp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Tgl Akhir SIP</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tgl_akhir_sip_dp" id="tgl_akhir_sip_dp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">No Telpon</label>
                                <div class="col-sm-9">
                                    <input type="number" name="no_tlf_dp" id="no_tlf_dp" class="form-control" />
                                </div>
                            </div>
                            <!--<div class="form-group row">-->
                            <!--    <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <label for="file_str_dp" class="form-label">Upload STR</label>-->
                            <!--        <input class="form-control" type="file" id="file_str_dp" name="file_str_dp">-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-dp">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">TENAGA KEPERAWATAN</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table3">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addtk()">
                    <span><i class="icon-plus"></i> Tambah Tenaga Keperawatan</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>No. SIB / SIK</th>
                    <th>No. STR</th>
                    <th>Tgl. Akhir STR</th>
                    <th>Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Tenaga Keperawatan -->
        <div class="modal fade" id="formTenagaKeperawatan" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Tenaga Keperawatan</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-tk" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_tk" id="id_tk"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_tk" id="nama_tk" class="form-control" placeholder="Nama Lengkap Dokter Dengan Gelar" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor SIB / SIK</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_sib_sik_tk" id="no_sib_sik_tk" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor STR</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_str_tk" id="no_str_tk" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Tgl Akhir STR</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tgl_akhir_str_tk" id="tgl_akhir_str_tk" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">No Telpon</label>
                                <div class="col-sm-9">
                                    <input type="number" name="no_tlf_tk" id="no_tlf_tk" class="form-control" />
                                </div>
                            </div>
                            <!--<div class="form-group row">-->
                            <!--    <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <label for="file_str_tk" class="form-label">Upload STR</label>-->
                            <!--        <input class="form-control" type="file" id="file_str_tk" name="file_str_tk">-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-tk">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">TENAGA KESEHATAN LAIN</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table4">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addtkl()">
                    <span><i class="icon-plus"></i> Tambah Tenaga Kesehatan Lain</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Teknik Kefarmasian / Apoteker</th>
                    <th>No. STR</th>
                    <th>Tgl. Akhir STR</th>
                    <th>No. SIPA</th>
                    <th>No. SIAA / SIK</th>
                    <th>Keterangan SIPA / SIAA / SIK</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Tenaga Kesehatan -->
        <div class="modal fade" id="formTenagaKesehatan" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Tenaga Kesehatan</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-tkl" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_tkl" id="id_tkl"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_tkl" id="nama_tkl" class="form-control" placeholder="Nama Lengkap Dokter Dengan Gelar" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Teknik Kefarmasian / Apoteker</label>
                                <div class="col-sm-9">
                                    <input type="text" name="farmasi_apoteker_tkl" id="farmasi_apoteker_tkl" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor STR</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_str_tkl" id="no_str_tkl" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Tgl Akhir STR</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tgl_akhir_str_tkl" id="tgl_akhir_str_tkl" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nomor SIPA</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_sip_tkl" id="no_sip_tkl" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Keterangan SIPA / SIA / SIK</label>
                                <div class="col-sm-9">
                                    <textarea name="ket_sib_sik_tkl" id="ket_sib_sik_tkl" class="form-control"></textarea>
                                </div>
                            </div>
                            <!--<div class="form-group row">-->
                            <!--    <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <label for="file_str_tkl" class="form-label">Upload STR</label>-->
                            <!--        <input class="form-control" type="file" id="file_str_tkl" name="file_str_tkl">-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-tkl">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">TENAGA SDM LAIN</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table5">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addlain()">
                    <span><i class="icon-plus"></i> Tambah SDM Lain</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Ijazah Terakhir</th>
                    <th>Jabatan / Pekerjaan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal SDM LAIN -->
        <div class="modal fade" id="formLain" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Tenaga Kesehatan</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-lain" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_lain" id="id_lain"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_lain" id="nama_lain" class="form-control" placeholder="Nama Lengkap Dokter Dengan Gelar" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Ijazah Terakhir</label>
                                <div class="col-sm-9">
                                    <input type="text" name="ijazah_terakhir_lain" id="ijazah_terakhir_lain" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Jabatan / Pekerjaan</label>
                                <div class="col-sm-9">
                                    <input type="text" name="jabatan_lain" id="jabatan_lain" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>
                                <div class="col-sm-9">
                                    <label for="file_str_lain" class="form-label">Upload Ijazah</label>
                                    <input class="form-control" type="file" id="file_str_tkl" name="file_str_lain">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-lain">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">Rumah Sakit Terdekat</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table6">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addrs()">
                    <span><i class="icon-plus"></i> Tambah Rumah Sakit Terdekat</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Rumah Sakit</th>
                    <th>Alamat</th>
                    <th>Jarak</th>
                    <th>Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Rumah Sakit -->
        <div class="modal fade" id="formRS" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Rumah Sakit</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-rs" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_rs" id="id_rs"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Rumah Sakit</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_rs" id="nama_rs" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat_rs" id="alamat_rs" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Jarak</label>
                                <div class="col-sm-9">
                                    <input type="text" name="jarak_rs" id="jarak_rs" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Telepon</label>
                                <div class="col-sm-9">
                                    <input type="text" name="telepon_rs" id="telepon_rs" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-rs">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">KERJASAMA DENGAN PROVIDER ASURANSI</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table7">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addas()">
                    <span><i class="icon-plus"></i> Tambah Asuransi</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Nama Kontak</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Asuransi -->
        <div class="modal fade" id="formAS" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Asuransi</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-as" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_tkl" id="id_tkl"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Perusahaan</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_as" id="nama_as" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Nama Kontak</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_kontak_as" id="nama_kontak_as" class="form-control" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat_as" id="alamat_as" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Telepon</label>
                                <div class="col-sm-9">
                                    <input type="text" name="telepon_as" id="telepon_as" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Upload Data</label>
                                <div class="col-sm-9">
                                    <label for="bukti_as" class="form-label">Upload Bukti</label>
                                    <input class="form-control" type="file" id="bukti_as" name="bukti_as">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-as">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row pt-6">
        <div class="container py-1 shop" id="shop">
            <div class="row pt-1 pb-1">
                <div class="col">
                    <h3 class="text-color-primary font-weight-bold fst-italic ls-0 text-5 mb-1 appear-animation" data-appear-animation="fadeInUpShorterPlus" data-appear-animation-delay="100" data-plugin-options="{'minWindowWidth': 0}">Foto Ruang Klinik</h3>
                    <hr>
                </div>
            </div>
        </div>

        <table class="table table-bordered" id="table8">
            <div class="col">
				<a href="javascript:void(0)" class="button button-mini button-border button-rounded" onclick="addfoto()">
                    <span><i class="icon-plus"></i> Tambah Foto</span>
                </a>
            </div>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Foto Klinik -->
        <div class="modal fade" id="formFoto" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="formModalLabel">Tambah Asuransi</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <form id="form-foto" class="mb-4" novalidate="novalidate">
                        <div class="modal-body">
                            <input type="hidden" name="id_foto" id="id_foto"/>
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Kategori</label>
                                <div class="col-sm-9">
                                    <select class="form-select form-control h-auto" name="nama_foto" id="nama_foto" required>
                                        <option value="Ruang Pendaftaran">Ruang Pendaftaran</option>
                                        <option value="Ruang Administrasi">Ruang Administrasi</option>
                                        <option value="Ruang Perawatant">Ruang Perawatan</option>
                                        <option value="Ruang Tindakan">Ruang Tindakan</option>
                                        <option value="Ruang Farmasi">Ruang Farmasi</option>
                                        <option value="Ruang Konsultasi Dokter">Ruang Konsultasi Dokter</option>
                                        <option value="Ruang Papan Nama Klinik">Ruang Papan Nama Klinik</option>
                                        <option value="Ruang Papan Nama Dokter">Ruang Papan Nama Dokter</option>
                                        <option value="Ruang Kamar Mandi">Ruang Kamar Mandi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 text-start text-sm-end mb-0">Upload Foto</label>
                                <div class="col-sm-9">
                                    <label for="bukti_as" class="form-label">Upload Ruang Berdasarkan Kategori</label>
                                    <input class="form-control" type="file" id="foto_foto" name="foto_foto">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btn-save-foto">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-auto">
            <form action="{{ route('draft') }}" method="POST">
                @csrf
                <input type="submit" class="btn btn-warning font-weight-semibold px-5 py-3 text-3" value="Simpan Draft">
            </form>
        </div>
        <div class="col-auto">
            <form action="{{ route('submit_akhir') }}" method="POST">
                @csrf
                <input type="submit" class="btn btn-success font-weight-semibold px-5 py-3 text-3" value="Submit Formulir">
            </form>
        </div>
        </div>
    </div>
</div>




@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// PJ
$(function () {  
    var table1 = $('#table1').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.pjk') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_dokter', name: 'nama_dokter'},
            {data: 'npa_idi', name: 'npa_idi'},
            {data: 'no_str', name: 'no_str'},
            {data: 'no_sip', name: 'no_sip'},
            {data: 'tgl_akhir_sip', name: 'tgl_akhir_sip'},
            {data: 'no_tlf', name: 'no_tlf'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addpj(){
    $('#form-pj').trigger("reset");
    $('.modal-title-pj').html("Tambah Dokter Penanggung Jawab");
    $('#formPenanggungjawab').modal('show');
    $('#id_pj').val('');
}

$('#form-pj').submit(function(e) {
    e.preventDefault();
    $("#btn-save-pj"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.pjk.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formPenanggungjawab").modal('hide');
            $('#table1').dataTable().fnDraw(false);
            $("#btn-save-pj").html('Submit');
            $("#btn-save-pj"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-pj"). attr("disabled", false);
        }
    });
});

function deletepj(id){
    if (confirm("Hapus daftar ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pendaftaran.sdm.pjk.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table1').dataTable().fnDraw(false);
                $('#table2').dataTable().fnDraw(false);
                $('#table3').dataTable().fnDraw(false);
                $('#table4').dataTable().fnDraw(false);
                $('#table5').dataTable().fnDraw(false);
            }
        });
    }
}


// PJ
$(function () {  
    var table2 = $('#table2').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.dp') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_dokter', name: 'nama_dokter'},
            {data: 'npa_idi', name: 'npa_idi'},
            {data: 'no_str', name: 'no_str'},
            {data: 'no_sip', name: 'no_sip'},
            {data: 'tgl_akhir_sip', name: 'tgl_akhir_sip'},
            {data: 'no_tlf', name: 'no_tlf'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function adddp(){
    $('#form-dp').trigger("reset");
    $('.modal-title-dp').html("Tambah Dokter Praktek");
    $('#formDokterPraktek').modal('show');
    $('#id_dp').val('');
}

$('#form-dp').submit(function(e) {
    e.preventDefault();
    $("#btn-save-dp"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.dp.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formDokterPraktek").modal('hide');
            $('#table2').dataTable().fnDraw(false);
            $("#btn-save-dp").html('Submit');
            $("#btn-save-dp"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-dp"). attr("disabled", false);
        }
    });
});

// TK
$(function () {  
    var table3 = $('#table3').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.tk') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'no_sib_sik', name: 'no_sib_sik'},
            {data: 'no_str', name: 'no_str'},
            {data: 'tgl_akhir_str', name: 'tgl_akhir_str'},
            {data: 'no_tlf', name: 'no_tlf'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addtk(){
    $('#form-tk').trigger("reset");
    $('.modal-title-tl').html("Tambah Tenaga Keperawatan");
    $('#formTenagaKeperawatan').modal('show');
    $('#id_tk').val('');
}

$('#form-tk').submit(function(e) {
    e.preventDefault();
    $("#btn-save-tk"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.tk.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formTenagaKeperawatan").modal('hide');
            $('#table3').dataTable().fnDraw(false);
            $("#btn-save-tk").html('Submit');
            $("#btn-save-tk"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-tk"). attr("disabled", false);
        }
    });
});

// TKL
$(function () {  
    var table4 = $('#table4').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.tkl') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'farmasi_apoteker', name: 'farmasi_apoteker'},
            {data: 'no_str', name: 'no_str'},
            {data: 'tgl_akhir_str', name: 'tgl_akhir_str'},
            {data: 'no_sip', name: 'no_sip'},
            {data: 'no_sib_sik', name: 'no_sib_sik'},
            {data: 'ket_sib_sik', name: 'ket_sib_sik'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addtkl(){
    $('#form-tk').trigger("reset");
    $('.modal-title-tkl').html("Tambah Kesehatan Lain");
    $('#formTenagaKesehatan').modal('show');
    $('#id_tkl').val('');
}

$('#form-tkl').submit(function(e) {
    e.preventDefault();
    $("#btn-save-tkl"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.tkl.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formTenagaKesehatan").modal('hide');
            $('#table4').dataTable().fnDraw(false);
            $("#btn-save-tkl").html('Submit');
            $("#btn-save-tkl"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-tkl"). attr("disabled", false);
        }
    });
});

// SDM Lain
$(function () {  
    var table5 = $('#table5').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.lain') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'ijazah_terakhir', name: 'ijazah_terakhir'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addlain(){
    $('#form-lain').trigger("reset");
    $('.modal-title-lain').html("Tambah SDM Lain");
    $('#formLain').modal('show');
    $('#id_lain').val('');
}

$('#form-lain').submit(function(e) {
    e.preventDefault();
    $("#btn-save-lain"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.lain.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formLain").modal('hide');
            $('#table5').dataTable().fnDraw(false);
            $("#btn-save-lain").html('Submit');
            $("#btn-save-lain"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-lain"). attr("disabled", false);
        }
    });
});

function deletelain(id){
    if (confirm("Hapus daftar ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pendaftaran.sdm.lain.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table5').dataTable().fnDraw(false);
            }
        });
    }
}

// SDM Rumah Sakit
$(function () {  
    var table6 = $('#table6').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.rumah_sakit') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'alamat', name: 'alamat'},
            {data: 'jarak', name: 'jarak'},
            {data: 'telepon', name: 'telepon'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addrs(){
    $('#form-rs').trigger("reset");
    $('.modal-title-lain').html("Tambah Rumah Sakit");
    $('#formRS').modal('show');
    $('#id_rs').val('');
}

$('#form-rs').submit(function(e) {
    e.preventDefault();
    $("#btn-save-rs"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.rumah_sakit.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formRS").modal('hide');
            $('#table6').dataTable().fnDraw(false);
            $("#btn-save-rs").html('Submit');
            $("#btn-save-rs"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-rs"). attr("disabled", false);
        }
    });
});

function deleters(id){
    if (confirm("Hapus daftar ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pendaftaran.sdm.rumah_sakit.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table6').dataTable().fnDraw(false);
            }
        });
    }
}

// Asurasi
$(function () {  
    var table6 = $('#table7').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.asuransi') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'nama_kontak', name: 'nama_kontak'},
            {data: 'alamat', name: 'alamat'},
            {data: 'telepon', name: 'telepon'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addas(){
    $('#form-as').trigger("reset");
    $('.modal-title-lain').html("Tambah Asuransi");
    $('#formAS').modal('show');
    $('#id_as').val('');
}

$('#form-as').submit(function(e) {
    e.preventDefault();
    $("#btn-save-as"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.asuransi.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formAS").modal('hide');
            $('#table7').dataTable().fnDraw(false);
            $("#btn-save-as").html('Submit');
            $("#btn-save-as"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-as"). attr("disabled", false);
        }
    });
});

function deleteas(id){
    if (confirm("Hapus daftar ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pendaftaran.sdm.asuransi.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table7').dataTable().fnDraw(false);
            }
        });
    }
}

// Foto Klinik
$(function () {  
    var table6 = $('#table8').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: { 
            url: "{{ route('pendaftaran.sdm.foto') }}"
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: "foto" ,
                "render": function (  data, type, row, meta ) {
                return '<img src="{{ asset("images/file") }}/' + row.foto + '" width="90px">';}
            },
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

function addfoto(){
    $('#form-foto').trigger("reset");
    $('.modal-title-lain').html("Tambah Foto");
    $('#formFoto').modal('show');
    $('#id_foto').val('');
}

$('#form-foto').submit(function(e) {
    e.preventDefault();
    $("#btn-save-foto"). attr("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: '{{ route('pendaftaran.sdm.foto.add') }}',
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#formFoto").modal('hide');
            $('#table8').dataTable().fnDraw(false);
            $("#btn-save-foto").html('Submit');
            $("#btn-save-foto"). attr("disabled", false);
        },
        error: function(data){
            $("#btn-save-foto"). attr("disabled", false);
        }
    });
});

function deletefoto(id){
    if (confirm("Hapus daftar ini?") == true) {
        var id = id;
        $.ajax({
            type:"POST",
            url: "{{ route('pendaftaran.sdm.foto.delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#table8').dataTable().fnDraw(false);
            }
        });
    }
}
</script>
@endpush