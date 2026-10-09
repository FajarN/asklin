@extends('layouts.backend.layout')

@section('title', 'Laporan Data Anggota (Pusat Alternatif)')
@section('page_title', 'Laporan Pusat')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">
                <a href="{{ route('dashboard.index') }}" class="hover:underline">Dashboard</a>
                <i class="fas fa-chevron-right text-[9px] text-slate-400"></i>
                <span class="text-slate-500">Laporan Nasional</span>
            </div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Laporan Data Anggota ASKLIN Nasional
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Filter seluruh basis data klinik nasional per provinsi beserta status legalitas dan kontak faskes.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-form">
                            <select id="provinsi" class="form-control form-control">
                                <option></option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}
                                @endforeach
                            </select>
                        </div>
                        <div class="card-header-form">
                            <input type="text" class="form-control" placeholder="Search" id="search">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>No. Anggota</th>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Nama Klinik</th>
                                        <th>Jenis Klinik</th>
                                        <th>Kriteria Klinik</th>
                                        <th>Status Verifikasi</th>
                                        {{-- <th>Status Uang Pangkal</th> --}}
                                        <th>Telp 1</th>
                                        <th>Telp 2</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            var table = $('#table').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'pdf', 'print', {
                    extend: 'excel',
                    text: 'Export To Excel'
                }],
                pageLength: 30,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('laporan_pusat.list') }}",
                    data: function(d) {
                        d.search = $('#search').val(),
                            d.provinsi = $('#provinsi').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nama_klinik',
                        name: 'nama_klinik',
                        render: function(data) {
                            return data ? '<span class="nama-klinik-highlight font-bold text-red-600 dark:text-red-400">' + data + '</span>' : '<span class="text-slate-400">-</span>';
                        }
                    },
                    {
                        data: 'jenis_klinik',
                        name: 'jenis_klinik'
                    },

                    {
                        data: 'kriteria',
                        name: 'kriteria'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tlf',
                        name: 'tlf',
                        render: function(data, type, row, meta) {
                            // Periksa apakah nomor telepon tidak kosong
                            if (data) {
                                // Format nomor telepon jika perlu
                                var formattedPhoneNumber =
                                    data; // Tidak ada format yang dilakukan dalam contoh ini

                                // Buat tautan WhatsApp
                                var whatsappLink = "https://wa.me/" + formattedPhoneNumber;

                                // Kembalikan tautan sebagai string HTML
                                return '<a href="' + whatsappLink + '" target="_blank">' +
                                    formattedPhoneNumber + '</a>';
                            } else {
                                return ''; // Jika nomor telepon kosong, kembalikan string kosong
                            }
                        }
                    },
                    {
                        data: 'tlf_klinik',
                        name: 'tlf_klinik',
                        render: function(data, type, row, meta) {
                            // Periksa apakah nomor telepon tidak kosong
                            if (data) {
                                // Format nomor telepon jika perlu
                                var formattedPhoneNumber =
                                    data; // Tidak ada format yang dilakukan dalam contoh ini

                                // Buat tautan WhatsApp
                                var whatsappLink = "https://wa.me/" + formattedPhoneNumber;

                                // Kembalikan tautan sebagai string HTML
                                return '<a href="' + whatsappLink + '" target="_blank">' +
                                    formattedPhoneNumber + '</a>';
                            } else {
                                return ''; // Jika nomor telepon kosong, kembalikan string kosong
                            }
                        }
                    },
                    // {
                    //     data: 'status_pembayaran',
                    //     "render": function(data, type, row, meta) {
                    //         if (row.status_pembayaran == "0") {
                    //             return "Belum Lunas";
                    //         } else {
                    //             return "Lunas";
                    //         }
                    //     }
                    // },
                    {
                        data: 'created_on',
                        name: 'created_on'
                    }

                ]
            });

            $("#search").keyup(function() {
                table.draw();
            });
            $('#provinsi').on('change', function(e) {
                table.draw();
            });
        });
    </script>
@endpush
