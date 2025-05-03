@extends('layouts.backend.layout')

@section('title', 'Detail Surat')

@section('css')
<style>
    .table-striped td,
    .table-striped th {
        padding-top: 0.35rem;
        padding-bottom: 0.35rem;
    }
</style>
@endsection

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Surat</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('surat_keluar.index') }}">Struktur Surat</a>
                    </div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>
        </section>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Surat Keluar</h4>
                            <div class="card-header-action">
                                <div class="section-header-button ml-auto">
                                    <a href="{{ route('surat_keluar.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <a href="{{ route('surat_keluar.edit', $suratKeluar->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('surat_keluar.print', $suratKeluar->id) }}"
                                        class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="120">Jenis Surat</th>
                                            <td>: {{ $suratKeluar->jenisSurat->nama_jenis }}</td>
                                        </tr>
                                        <tr>
                                            <th>No Surat</th>
                                            <td>: {{ $suratKeluar->no_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Surat</th>
                                            <td>: {{ \Carbon\Carbon::parse($suratKeluar->tgl_surat)->format('d-m-Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="120">Perihal</th>
                                            <td width="230">
                                                : {{ $suratKeluar->perihal }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>: 
                                                @php
                                                    $badges = [
                                                        'draft' => 'badge-warning',
                                                        'disetujui' => 'badge-success',
                                                        'ditolak' => 'badge-danger',
                                                        'terkirim' => 'badge-info',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge {{ $badges[$suratKeluar->status] ?? 'badge-secondary' }}">
                                                    {{ ucfirst($suratKeluar->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
            @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'tugas') !== false && $suratKeluar->suratTugas)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="section-title-inline">Surat Tugas</h4>
                                <div class="card-header-action">
                                    <div class="btn-group">
                                        <div class="card-header-action">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="org-chart">
                                   <table class="table table-sm table-striped table-md col-md-6 mb-0" >
                                        <tr>
                                            <th width="150">Asal Surat</th>
                                            <td>: {{ $suratKeluar->suratTugas->asal_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th width="150">Nomor Asal Surat</th>
                                            <td>: {{ $suratKeluar->suratTugas->nomor_asal_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th width="150">Agenda</th>
                                            <td>: {{ $suratKeluar->suratTugas->agenda }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hari</th>
                                            <td>: {{ $suratKeluar->suratTugas->hari }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Agenda</th>
                                            <td>: {{ \Carbon\Carbon::parse($suratKeluar->suratTugas->tgl_agenda)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Waktu</th>
                                            <td>: {{ $suratKeluar->suratTugas->waktu_agenda }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat</th>
                                            <td>: {{ $suratKeluar->suratTugas->tempat_agenda }}</td>
                                        </tr>
                                    </table>

                                    <h6 class="ml-3 mt-3">Daftar Pengurus</h6>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengurus</th>
                                                <th>Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratKeluar->suratTugas->details as $index => $detail)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $detail->nama_pengurus }}</td>
                                                    <td>{{ $detail->jabatan }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        @endif

        @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'undangan') !== false && $suratKeluar->suratUndangan)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Surat Undangan</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <tr>
                                    <th width="200">Nama Penerima</th>
                                    <td>{{ $suratKeluar->suratUndangan->nama_penerima }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Penerima</th>
                                    <td>{{ $suratKeluar->suratUndangan->alamat_penerima }}</td>
                                </tr>
                                <tr>
                                    <th>Salam Pembuka</th>
                                    <td>{{ $suratKeluar->suratUndangan->salam_pembuka }}</td>
                                </tr>
                                <tr>
                                    <th>Isi Surat</th>
                                    <td>{{ $suratKeluar->suratUndangan->isi_surat }}</td>
                                </tr>
                                <tr>
                                    <th>Judul Acara</th>
                                    <td>{{ $suratKeluar->suratUndangan->judul_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Tujuan Acara</th>
                                    <td>{{ $suratKeluar->suratUndangan->tujuan_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu dan Tanggal Acara</th>
                                    <td>{{ $suratKeluar->suratUndangan->waktu_tgl_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi Acara</th>
                                    <td>{{ $suratKeluar->suratUndangan->lokasi_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Agenda Acara</th>
                                    <td>{{ $suratKeluar->suratUndangan->agenda_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Informasi Tambahan</th>
                                    <td>{{ $suratKeluar->suratUndangan->informasi_tambahan }}</td>
                                </tr>
                                <tr>
                                    <th>Salam Penutup</th>
                                    <td>{{ $suratKeluar->suratUndangan->salam_penutup }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </section>
</div>
@endsection
