<div class="card">
    <div class="col-12 col-sm-12 col-lg-7">
        <div class="card author-box card-primary">
            <div class="card-body">
                <div class="author-box-left">
                    @if ($pengurus->foto_pengurus)
                        <img src="{{ asset('storage/pengurus/' . $pengurus->foto_pengurus) }}"
                            alt="{{ $pengurus->nama_lengkap }}" class="rounded-circle author-box-picture">
                    @else
                        <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="{{ $pengurus->nama_lengkap }}"
                            class="rounded-circle author-box-picture">
                    @endif
                    <div class="clearfix"></div>
                </div>
                <div class="author-box-details">
                    <div class="author-box-name">
                        <h6 class="mb-1">{{ $pengurus->nama_lengkap }}</h6>
                    </div>
                    <div class="author-box-job">{{ $pengurus->jabatan }}</div>
                    <div class="author-box-description">
                        @php
                            $statusClasses = [
                                'aktif' => 'status-aktif',
                                'nonaktif' => 'status-nonaktif',
                                'mengundurkan_diri' => 'status-mengundurkan_diri',
                            ];
                        @endphp
                        <span
                            class="member-status {{ $statusClasses[$pengurus->status] }}">{{ ucfirst(str_replace('_', ' ', $pengurus->status)) }}</span>

                        @if ($pengurus->no_telp || $pengurus->email)
                            <div class="mt-2">
                                @if ($pengurus->no_telp)
                                    <small class="mr-3"><i class="fas fa-phone"></i> {{ $pengurus->no_telp }}</small>
                                @endif

                                @if ($pengurus->email)
                                    <small><i class="fas fa-envelope"></i> {{ $pengurus->email }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="pengurus-actions">
                        <button class="btn btn-sm btn-success add-sub-btn" data-id="{{ $pengurus->id }}">
                            <i class="fas fa-user-plus"></i> Tambah Anggota
                        </button>
                        <button class="btn btn-sm btn-primary edit-pengurus-btn" data-id="{{ $pengurus->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-pengurus-btn" data-id="{{ $pengurus->id }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="w-100 d-sm-none"></div>
                </div>
            </div>
        </div>


    </div>

    @php
        $children = App\Models\StrukturPengurus::where('parent_id', $pengurus->id)->orderBy('urutan', 'asc')->get();
    @endphp

    @if (count($children) > 0)
        <div class="child-pengurus">
            @foreach ($children as $child)
                @include('backend.struktur_organisasi.components.pengurus_card', ['pengurus' => $child])
            @endforeach
        </div>
    @endif
