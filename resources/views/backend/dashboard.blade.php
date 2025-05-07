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


            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Per Wilayah</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Propinsi</label>
                                    <select class="form-control" name="provinsi" id="provinsi">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provinsi as $i)
                                            <option value="{{ $i->code }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Kab/Kota</label>
                                    <select class="form-control" name="kota" id="kota">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <canvas id="chartWilayah" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                @foreach ($recent as $i)
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="float-right text-primary">{{ $i->updated_at->diffForHumans() }}
                                            </div>
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

            <div class="row">
                <!-- Kolom 1: Jumlah Anggota per Jenis Kepemilikan -->
                <div class="col-lg-4 col-md-4 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Jumlah Anggota per Jenis Kepemilikan</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $maxValue = $perKepemilikan->max();
                            @endphp
                            @foreach ($perKepemilikan as $jenis => $total)
                                @php
                                    $percentage = $maxValue > 0 ? ($total / $maxValue) * 100 : 0;
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $jenis != '' ? $jenis : 'Tidak Diketahui' }}</span>
                                        <span>{{ number_format($total) }}</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Jumlah Anggota per Jenis Klinik -->
                <div class="col-lg-4 col-md-4 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Jumlah Anggota per Jenis Klinik</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartJenisKlinik" width="100%" height="100%"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Jumlah Anggota per Badan Hukum -->
                <div class="col-lg-4 col-md-4 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Jumlah Anggota per Badan Hukum</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartBadanHukum" width="100%" height="100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('css')
            <link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
        @endpush

        @push('js')
            <!-- Load jQuery pertama (pastikan hanya sekali) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Load jQuery UI untuk tooltip (jika diperlukan) -->
            <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
            <!-- Load plugin lainnya -->
            <script src="{{ asset('assets/backend/modules/chart.min.js') }}"></script>
            <script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

            <script type="text/javascript">
                $(document).ready(function() {
                    // Inisialisasi nicescroll
                    if (typeof $.fn.niceScroll === 'function') {
                        $("body").niceScroll({
                            cursorcolor: "#ccc",
                            cursorwidth: "5px",
                            cursorborder: "1px solid #ccc"
                        });
                    }

                    // Inisialisasi tooltip
                    if (typeof $.fn.tooltip === 'function') {
                        $('[data-toggle="tooltip"]').tooltip();
                    }

                    // Chart Pie
                    var ctx = document.getElementById("myChart4").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: [
                                    {{ $proses }},
                                    {{ $waiting }},
                                    {{ $perbaikan }},
                                    {{ $create_dokter }},
                                    {{ $diverifikasi_cabang }},
                                    {{ $terverifikasi_cabang }},
                                    {{ $approved }},
                                    {{ $ditolak }},
                                ],
                                backgroundColor: [
                                    '#ffa426', // Proses
                                    '#fc544b', // Waiting
                                    '#f4f4f4', // Perbaikan
                                    '#9c27b0', // Belum Lengkap (create_dokter)
                                    '#6777ef', // Diverifikasi Cabang
                                    '#3abaf4', // Terverifikasi Cabang
                                    '#63ed7a', // Disetujui
                                    '#d9534f', // Ditolak
                                ],
                                label: 'Status Anggota'
                            }],
                            labels: [
                                'Proses Input',
                                'Waiting',
                                'Perlu Perbaikan',
                                'Belum Lengkap',
                                'Diverifikasi Cabang',
                                'Terverifikasi Cabang',
                                'Disetujui Pusat',
                                'Ditolak Pusat'
                            ],
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'bottom',
                            },
                        }
                    });

                    // Select2
                    $('#provinsi').select2();
                    $('#kota').select2();

                    // Event handlers
                    $('#provinsi').on('change', function() {
                        let id_provinsi = $(this).val();
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('getKota') }}',
                            data: {
                                id_provinsi: id_provinsi,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                $('#kota').html(data);
                                // Jangan trigger('change') otomatis di sini
                                loadChart(id_provinsi, '');
                            }
                        });
                    });



                    $('#kota').on('change', function() {
                        let id_provinsi = $('#provinsi').val();
                        let id_kota = $(this).val();
                        loadChart(id_provinsi, id_kota);
                    });

                    let chartWilayah;

                    function loadChart(provinsi, kota) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('getStatistikWilayah') }}',
                            data: {
                                provinsi: provinsi,
                                kota: kota,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                // Destroy chart sebelumnya jika ada
                                if (chartWilayah) {
                                    chartWilayah.destroy();
                                }

                                let labels = data.map(item => item.provinsi || item.kota);
                                let values = data.map(item => item.total);

                                let ctx = document.getElementById('chartWilayah').getContext('2d');
                                chartWilayah = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Jumlah Anggota',
                                            data: values,
                                            backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                precision: 0
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    }

                    // Initial load
                    loadChart('', '');
                });

                function loadDonutJenisKlinik() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('getStatistikJenisKlinik') }}',
                        success: function(response) {
                            let ctx = document.getElementById('chartJenisKlinik').getContext('2d');
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: response.labels,
                                    datasets: [{
                                        data: response.data,
                                        backgroundColor: ['#4e73df', '#1cc88a'],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            });
                        }
                    });
                }

                function loadPieBadanHukum() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('getStatistikBadanHukum') }}',
                        success: function(response) {
                            let ctx = document.getElementById('chartBadanHukum').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: response.labels,
                                    datasets: [{
                                        data: response.data,
                                        backgroundColor: ['#f6c23e', '#e74a3b', '#36b9cc', '#858796'],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            });
                        }
                    });
                }

                // Panggil saat halaman siap
                $(document).ready(function() {
                    loadDonutJenisKlinik();
                    loadPieBadanHukum();
                });
            </script>
        @endpush
