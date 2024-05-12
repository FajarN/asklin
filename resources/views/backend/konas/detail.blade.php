@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Peserta Konas</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            </div>
        </div>
    </section>

  <div class="section-body">
            <h2 class="section-title">Terimakasih telah melakukan pendaftaran peserta Konas ASKLIN KE-2 </h2>
            <p class="section-lead">
              berikut adalah detail pemesanan anda di akun peserta konas : 
            </p>
            <div class="row mt-sm-4">
              <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                  <div class="profile-widget-header">                     
                    <img alt="image" src="{{ asset("assets/images/logo-konas.png") }}" class="rounded-circle profile-widget-picture">
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                        <h2>Fajar nuralamsyah</h2>
                      </div>
                    </div>
                  </div>
                  <div class="profile-widget-description">
                    <div class="profile-widget-name">
                       <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th>NIK</th>
                            <td>10293010</td>
                        </tr>
                        <tr>
                            <th>No&nbsp;Anggota</th>
                            <td>konas-0001</td>
                        </tr>
                        <tr>
                            <th>Telp</th>
                            <td>021-032-39383</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>fajar.nuralamsyah16@gmail.com</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>Peserta</td>
                        </tr>
                        <tr>
                            <th>Komisi</th>
                            <td>Komisi 1</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                   
                  </div>
                  <div class="card-footer text-center">
                    <div class="font-weight-bold mb-2">
                        <a href="download_konas.php" class="btn btn-info">Download Invoice</a></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                      <h4>Pemesanan hotel</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table demo-table">
                        <thead>
                            <tr>
                                <th>Tanggal&nbsp;Pemesanan</th>
                                <th>Hotel</th>
                                <th>Tipe</th>
                                <th>Harga&nbsp;<small>perkamar</small></th>
                                <th>Jml&nbsp;Kamar</th>
                                <th>Harga&nbsp;Extrabed</th>
                                <th>Jml&nbsp;Extrabed</th>
                                <th>Total</th>
                                <th>Status&nbsp;Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- akhir -->

                 <div class="card-header">
                      <h4>Info Penerbangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table demo-table">
                        <thead>
                            <tr>
                                <th>Tanggal </th>
                                <th>Dari Bandara</th>
                                <th>Tujuan Bandara</th>
                                <th>Pesawat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- akhir -->
              </div>
            </div>
            </div>
          </div>
        </section>
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

</script>
@endpush