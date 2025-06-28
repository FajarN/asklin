@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Data Anggota ASKLIN</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan Cabang</div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-form d-flex align-items-center">
                        <button type="button" class="btn btn-success mr-2" id="exportBtn">
                            <i class="fas fa-download"></i> Export Excel
                        </button>
                        <input type="text" class="form-control" placeholder="Search" id="search">
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Klinik</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Nama Pemilik</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Provinsi</th>
                                    <th>Kab/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
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
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {  
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        scrollX: true, // Enable horizontal scroll untuk banyak kolom
        ajax: { 
            url: "{{ route('laporan_cabang.list') }}",
            data: function (d) {
                d.search = $('#search').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'jenis_klinik', name: 'jenis_klinik'},
            {data: 'no_anggota', name: 'no_anggota'},
            {data: 'nama_klinik', name: 'nama_klinik'},
            {data: 'nama_pemilik_klinik', name: 'nama_pemilik_klinik'},
            {data: 'email', name: 'email'},
            {data: 'tlf', name: 'tlf'},
            {data: 'alamat_klinik', name: 'alamat_klinik'},
            {data: 'provinsi_name', name: 'provinsi_name'},
            {data: 'kota_name', name: 'kota_name'},
            {data: 'kecamatan_name', name: 'kecamatan_name'},
            {data: 'kelurahan_name', name: 'kelurahan_name'}
        ]
    });

    $("#search").keyup(function(){
        table.draw();
    });

    // Export Excel functionality
    $("#exportBtn").click(function(){
        var searchValue = $('#search').val();
        var exportUrl = "{{ route('laporan_cabang.export') }}";
        
        if(searchValue) {
            exportUrl += '?search=' + encodeURIComponent(searchValue);
        }
        
        console.log('Export URL:', exportUrl); // Debug
        
        // Show loading state
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Exporting...');
        
        // Use window.location for download
        window.location.href = exportUrl;
        
        // Reset button after a short delay
        setTimeout(() => {
            $btn.prop('disabled', false).html('<i class="fas fa-download"></i> Export Excel');
        }, 3000);
    });
});
</script>
@endpush