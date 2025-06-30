@extends('layouts.backend.layout')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Events</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></div>
                <div class="breadcrumb-item">Create</div>
            </div>
        </div>
    </section>
    
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-sm-4">
            <!-- Left Column - Event Info -->
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Event</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kategori *</label>
                            <select class="form-control" name="id_kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $i)
                                    <option value="{{ $i->id }}">{{ $i->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Judul *</label>
                            <input type="text" class="form-control" name="judul" id="title" required 
                                   placeholder="Contoh: Webinar Update Standar Akreditasi 2025">
                        </div>
                        
                        <div class="form-group">
                            <label>URL/Slug *</label>
                            <input type="text" class="form-control" name="path" id="path" required>
                            <small class="form-text text-muted">URL akan otomatis dibuat dari judul</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Thumbnail Event</label>
                            <input type="file" class="form-control" name="gambar" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Tanggal & Waktu Mulai *</label>
                            <input type="datetime-local" class="form-control" name="mulai" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tanggal & Waktu Selesai *</label>
                            <input type="datetime-local" class="form-control" name="selesai" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tingkat Event *</label>
                            <select class="form-control" name="kategori" required>
                                <option value="">Pilih Tingkat</option>
                                <option value="Pusat">Pusat</option>
                                <option value="Daerah">Daerah</option>
                                <option value="Cabang">Cabang</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Location Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Lokasi Event</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Provinsi</label>
                            <select class="form-control" name="id_provinsi" id="id_provinsi">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Kab/Kota</label>
                            <select class="form-control" name="id_kota" id="id_kota">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                        
                        @hasanyrole('Superadmin|Admin Pusat')
                        <div class="form-group">
                            <label>Status Publikasi *</label>
                            <select class="form-control" name="status" required>
                                <option value="0">Draft</option>
                                <option value="1">Publish</option>
                            </select>
                        </div>
                        @endhasanyrole
                    </div>
                    
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Simpan Event
                        </button>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Content Builder -->
            <div class="col-12 col-md-12 col-lg-8">
                <!-- Template Builder -->
                <div class="card">
                    <div class="card-header">
                        <h4>Template Builder Konten</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-info btn-sm" id="useTemplate">
                                <i class="fas fa-magic"></i> Gunakan Template
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="previewContent">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Template Form -->
                        <div id="templateBuilder" class="template-builder">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target Peserta</label>
                                        <input type="text" class="form-control" id="targetPeserta" 
                                               placeholder="Contoh: Bapak/Ibu Pimpinan Klinik Anggota ASKLIN">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Jenis Event</label>
                                        <select class="form-control" id="jenisEvent">
                                            <option value="Webinar">Webinar</option>
                                            <option value="Seminar">Seminar</option>
                                            <option value="Workshop">Workshop</option>
                                            <option value="Pelatihan">Pelatihan</option>
                                            <option value="Kongres">Kongres</option>
                                            <option value="Simposium">Simposium</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Tema/Topik Event</label>
                                        <textarea class="form-control" id="temaEvent" rows="2" 
                                                  placeholder="Contoh: Kiat Mencapai KBK 100% - Sosialisasi Web ASKLIN"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Deskripsi Target Peserta</label>
                                        <textarea class="form-control" id="deskripsiTarget" rows="2" 
                                                  placeholder="Contoh: Seluruh Klinik Anggota ASKLIN Kab Bekasi"></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status Biaya</label>
                                        <select class="form-control" id="statusBiaya">
                                            <option value="FREE">GRATIS</option>
                                            <option value="BERBAYAR">BERBAYAR</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" id="nominalBiayaGroup" style="display: none;">
                                        <label>Nominal Biaya</label>
                                        <input type="text" class="form-control" id="nominalBiaya" 
                                               placeholder="Contoh: Rp 150.000">
                                        <small class="form-text text-muted">Include apa saja (sertifikat, konsumsi, dll)</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Link Pendaftaran</label>
                                        <input type="url" class="form-control" id="linkPendaftaran" 
                                               placeholder="https://daftar.asklin.org/event">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Kontak Person 1</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="namaKontak1" 
                                                       placeholder="Nama">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="hpKontak1" 
                                                       placeholder="08123456789">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Kontak Person 2 (Opsional)</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="namaKontak2" 
                                                       placeholder="Nama">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="hpKontak2" 
                                                       placeholder="08123456789">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Pesan Ajakan</label>
                                        <input type="text" class="form-control" id="pesanAjakan" 
                                               placeholder="Mari Bapak Ibu, Daftarkan Segera!"
                                               value="Mari Bapak Ibu, Daftarkan Segera!">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Editor -->
                <div class="card">
                    <div class="card-header">
                        <h4>Konten Event</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-warning btn-sm" id="clearContent">
                                <i class="fas fa-trash"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea name="konten" id="konten" class="summernote"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Modal -->
                <div class="modal fade" id="previewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Preview Event</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="previewResult"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.template-builder {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.template-builder .form-group label {
    font-weight: 600;
    color: #495057;
}

.template-preview {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    margin-top: 15px;
}

.preview-section {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px;
}

.preview-header { background: #e3f2fd; border-left: 4px solid #2196f3; }
.preview-schedule { background: #e8f5e8; border-left: 4px solid #4caf50; }
.preview-target { background: #fff3e0; border-left: 4px solid #ff9800; }
.preview-price { background: #f3e5f5; border-left: 4px solid #9c27b0; }
.preview-registration { background: #ffebee; border-left: 4px solid #f44336; }
.preview-contact { background: #e0f2f1; border-left: 4px solid #009688; }

.card-header-action .btn {
    margin-left: 5px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    .template-builder .row {
        margin: 0;
    }
    
    .template-builder .col-md-6 {
        padding: 5px;
    }
}
</style>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/backend/modules/select2/dist/js/select2.min.js') }}"></script>

<script>
$(function(e) {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize Select2
    $('#id_provinsi').select2({
        placeholder: "Pilih Provinsi"
    });
    $('#id_kota').select2({
        placeholder: "Pilih Kota"
    });
    
    // Initialize Summernote
    $('.summernote').summernote({
        height: 400,
        placeholder: 'Tulis konten event di sini atau gunakan template builder di atas...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    
    // Province-City Dependency
    $('#id_provinsi').on('change', function(){
        let id_provinsi = $('#id_provinsi').val();
        $('#id_kota').html('<option value="">Loading...</option>');
        
        if(id_provinsi) {
            $.ajax({
                type: 'POST',
                url: '{{ route('getKota') }}',
                data: {id_provinsi: id_provinsi},
                cache: false,
                success: function(data){
                    $('#id_kota').html('<option value="">Pilih Kota</option>' + data);
                },
                error: function() {
                    $('#id_kota').html('<option value="">Error loading data</option>');
                }
            });
        } else {
            $('#id_kota').html('<option value="">Pilih Kota</option>');
        }
    });
    
    // Auto-generate slug from title
    $("#title").keyup(function() {
        var text = $(this).val();
        text = text.toLowerCase();
        text = text.replace(/[^a-z0-9\s-]/g, ''); // Remove special characters
        text = text.replace(/\s+/g, '-'); // Replace spaces with hyphens
        text = text.replace(/-+/g, '-'); // Replace multiple hyphens with single
        text = text.replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
        $("#path").val(text);
    });
    
    // Show/Hide nominal biaya
    $('#statusBiaya').on('change', function() {
        if($(this).val() === 'BERBAYAR') {
            $('#nominalBiayaGroup').show();
        } else {
            $('#nominalBiayaGroup').hide();
        }
    });
    
    // Generate Template
    $('#useTemplate').on('click', function() {
        generateTemplate();
    });
    
    // Preview Content
    $('#previewContent').on('click', function() {
        var content = $('.summernote').summernote('code');
        if(content.trim() === '' || content === '<p><br></p>') {
            alert('Konten masih kosong. Silakan isi konten terlebih dahulu.');
            return;
        }
        
        $('#previewResult').html(formatPreview(content));
        $('#previewModal').modal('show');
    });
    
    // Clear Content
    $('#clearContent').on('click', function() {
        if(confirm('Yakin ingin menghapus semua konten?')) {
            $('.summernote').summernote('code', '');
            // Clear template fields
            $('#templateBuilder input, #templateBuilder textarea, #templateBuilder select').val('');
            $('#statusBiaya').val('FREE');
            $('#nominalBiayaGroup').hide();
        }
    });
    
    // Generate Template Function
    function generateTemplate() {
        // Get all template values
        var targetPeserta = $('#targetPeserta').val() || 'Yth Bapak/Ibu';
        var jenisEvent = $('#jenisEvent').val() || 'Event';
        var temaEvent = $('#temaEvent').val() || 'Tema Event';
        var deskripsiTarget = $('#deskripsiTarget').val() || 'Target Peserta';
        var statusBiaya = $('#statusBiaya').val();
        var nominalBiaya = $('#nominalBiaya').val() || 'Rp XXX.XXX';
        var linkPendaftaran = $('#linkPendaftaran').val() || 'https://link-pendaftaran.com';
        var namaKontak1 = $('#namaKontak1').val() || 'Admin';
        var hpKontak1 = $('#hpKontak1').val() || '081234567890';
        var namaKontak2 = $('#namaKontak2').val();
        var hpKontak2 = $('#hpKontak2').val();
        var pesanAjakan = $('#pesanAjakan').val() || 'Mari Bapak Ibu, Daftarkan Segera!';
        
        // Build template
        var template = '<p>Undangan Kepada ' + targetPeserta + '<br><br>';
        template += jenisEvent + ' Tema: ' + temaEvent + '<br><br>';
        template += '⏰ Waktu Pelaksanaan<br>';
        template += '- Tgl: [Akan diisi otomatis dari tanggal event]<br>';
        template += '- Pukul: [Akan diisi otomatis dari waktu event] WIB<br><br>';
        template += '👩‍⚕️ TARGET PESERTA<br>';
        template += deskripsiTarget + '<br><br>';
        
        // Biaya section
        if(statusBiaya === 'FREE') {
            template += '💵 FREE<br><br>';
        } else {
            template += '💵 ' + nominalBiaya + '<br><br>';
        }
        
        // Link pendaftaran
        template += '🌐 LINK PENDAFTARAN<br>';
        template += 'Link: ' + linkPendaftaran + '<br><br>';
        
        // Kontak person
        template += '☎️ KONTAK PERSON<br>';
        template += namaKontak1 + ': ' + hpKontak1 + '<br>';
        if(namaKontak2 && hpKontak2) {
            template += namaKontak2 + ': ' + hpKontak2 + '<br>';
        }
        template += '<br>' + pesanAjakan + ' 😊</p>';
        
        // Set to summernote
        $('.summernote').summernote('code', template);
        
        // Show success message
        showNotification('Template berhasil dibuat!', 'success');
    }
    
    // Format Preview Function
    function formatPreview(content) {
        var formatted = content;
        
        // Add preview styling for different sections
        formatted = formatted.replace(/Undangan Kepada[^<]*/g, '<div class="preview-section preview-header"><strong>$&</strong></div>');
        formatted = formatted.replace(/⏰ Waktu Pelaksanaan[\s\S]*?WIB/g, '<div class="preview-section preview-schedule">$&</div>');
        formatted = formatted.replace(/👩‍⚕️ TARGET PESERTA[\s\S]*?(?=💵|🌐|☎️|$)/g, '<div class="preview-section preview-target">$&</div>');
        formatted = formatted.replace(/💵[^<]*<br>/g, '<div class="preview-section preview-price">$&</div>');
        formatted = formatted.replace(/🌐 LINK PENDAFTARAN[\s\S]*?(?=☎️|$)/g, '<div class="preview-section preview-registration">$&</div>');
        formatted = formatted.replace(/☎️ KONTAK PERSON[\s\S]*?(?=Mari|$)/g, '<div class="preview-section preview-contact">$&</div>');
        
        return '<div class="template-preview">' + formatted + '</div>';
    }
    
    // Show Notification Function
    function showNotification(message, type = 'info') {
        var alertClass = 'alert-' + (type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info');
        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert">' +
            '<span>&times;</span></button></div>');
        
        // Add to top of form
        $('.main-content .section').after(notification);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            notification.fadeOut();
        }, 3000);
    }
    
    // Form validation before submit
    $('form').on('submit', function(e) {
        var judul = $('#title').val().trim();
        var mulai = $('input[name="mulai"]').val();
        var selesai = $('input[name="selesai"]').val();
        var konten = $('.summernote').summernote('code').trim();
        
        if(!judul) {
            e.preventDefault();
            showNotification('Judul event wajib diisi!', 'error');
            $('#title').focus();
            return false;
        }
        
        if(!mulai || !selesai) {
            e.preventDefault();
            showNotification('Tanggal mulai dan selesai wajib diisi!', 'error');
            return false;
        }
        
        if(konten === '' || konten === '<p><br></p>') {
            e.preventDefault();
            showNotification('Konten event wajib diisi!', 'error');
            return false;
        }
        
        // Show loading
        $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
    });
});
</script>
@endpush