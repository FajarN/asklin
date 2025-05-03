@extends('layouts.backend.layout')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Surat Undangan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat Undangan</div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-form">
                            <form method="GET" action="{{ route('surat_undangan.index') }}" class="mb-4">
                                <div class="form-row">
                                    <div class="form-group col-md-4 mb-0">
                                        <label for="start_date">Dari Tanggal</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control"
                                            value="{{ $startDate }}">
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <label for="end_date">Sampai Tanggal</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control"
                                            value="{{ $endDate }}">
                                    </div>
                                    <div class="form-group col-md-4 mb-0 d-flex align-items-end">
                                        <div>
                                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                            <a href="{{ route('surat_undangan.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal Surat</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Hari</th>
                                        <th>Tanggal Agenda</th>
                                        <th>Waktu</th>
                                        <th>Tempat</th>
                                        <th>Cetak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suratUndangan as $item)
                                        <tr>
                                            <td>{{ $item->suratKeluar->tgl_surat }}</td>
                                            <td>{{ $item->suratKeluar->no_surat }}</td>
                                            <td>{{ $item->suratKeluar->perihal }}</td>
                                            <td>{{ $item->hari }}</td>
                                            <td>{{ $item->tgl_acara }}</td>
                                            <td>{{ $item->waktu_acara }}</td>
                                            <td>{{ $item->lokasi_acara }}</td>
                                            <td>
                                                <a href="{{ route('surat_undangan.print', $item->id_surat) }}" target="_blank"  class="btn btn-sm btn-success">
                                                    Cetak
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">
                                {{ $suratUndangan->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
