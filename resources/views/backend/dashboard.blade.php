@extends('layouts.backend.layout')

@section('content')
   <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>

          <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
             <div class="card card-primary">
                  <div class="card-header">
                    <h4>Proses Input</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $proses }}</h2>
                  </div>
                </div>
              </div>

           <div class="col-12 col-md-6 col-lg-3">
             <div class="card card-warning">
                  <div class="card-header">
                    <h4>Waiting</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $waiting }}</h2>
                  </div>
                </div>
              </div>

             <div class="col-12 col-md-6 col-lg-3">
             <div class="card card-secondary">
                  <div class="card-header">
                    <h4>Perbaikan</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $perbaikan }}</h2>
                  </div>
                </div>
              </div>

              
             <div class="col-12 col-md-6 col-lg-3">
               <div class="card card-danger">
                  <div class="card-header">
                    <h4>Belum Lengkap</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $create_dokter }}</h2>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-lg-3">
               <div class="card card-secondary">
                  <div class="card-header">
                    <h4>Verifikasi Cabang</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $diverifikasi_cabang }}</h2>
                  </div>
                </div>
              </div>

              
              <div class="col-12 col-md-6 col-lg-3">
               <div class="card card-info">
                  <div class="card-header">
                    <h4>Terverifikasi Cabang</h4>
                  </div>
                  <div class="card-body">
                    <h2> {{ $terverifikasi_cabang }}</h2>
                  </div>
                </div>
              </div>

               
              <div class="col-12 col-md-6 col-lg-3">
               <div class="card card-success">
                  <div class="card-header">
                    <h4>Disetujui Pusat</h4>
                  </div>
                  <div class="card-body">
                    <h2> {{ $approved }}</h2>
                  </div>
                </div>
              </div>

               
              <div class="col-12 col-md-6 col-lg-3">
               <div class="card card-success">
                  <div class="card-header">
                    <h4>Ditolak Pusat</h4>
                  </div>
                  <div class="card-body">
                    <h2>{{ $ditolak }}</h2>
                  </div>
                </div>
              </div>
              
          </div>


          <!-- ======== -->
            <div class="row">
              <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pie Chart</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="myChart4"></canvas>
                  </div>
               </div>
           </div>
    
            
             <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Recent Activities</h4>
                </div>
                <div class="card-body">             
                  <ul class="list-unstyled list-unstyled-border">
                    @foreach($recent as $i)
                    <li class="media">
                      <img class="mr-3 rounded-circle" width="50" src="{{ asset('images/file').'/'.$i->logo }}" alt="avatar">
                      <div class="media-body">
                        <div class="float-right text-primary">{{ $i->updated_at->diffForHumans() }}</div>
                        <div class="media-title">{{ $i->nama_klinik }}</div>
                        <span class="text-small text-muted">{{ $i->status }}</span>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                  <div class="text-center pt-1 pb-1">
                    <a href="{{ route('verifikasi_anggota.index') }}" class="btn btn-primary btn-lg btn-round">
                      View All
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          

@endsection
{{-- 
@push('css')

@endpush --}}

@push('js')
<script src="{{ asset('assets/backend/modules/chart.min.js') }} "></script>
    <script type="text/javascript">

        var ctx = document.getElementById("myChart4").getContext('2d');
        var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
            data: [
                50,
                40,
                30,
                100,
            ],
            backgroundColor: [
                '#63ed7a',
                '#ffa426',
                '#fc544b',
                '#6777ef',
            ],
            label: 'Dataset 1'
            }],
            labels: [
            'Disetujui',
            'Waiting',
            'Belum Lengkap',
            'Verifikasi'
            ],
        },
        options: {
            responsive: true,
            legend: {
            position: 'bottom',
            },
        }
        });
        </script>
@endpush