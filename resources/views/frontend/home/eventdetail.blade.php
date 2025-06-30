@extends('layouts.frontend.layout')

@section('content')

<!-- Breadcrumb -->
<div class="section bg-light py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event_asklin') }}">Event</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($event->judul, 50) }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Event Detail -->
<div class="section py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Event Header -->
                <div class="event-header mb-4">
                    @if($event->kategori && is_object($event->kategori))
                        <span class="badge bg-danger mb-3">{{ $event->kategori->nama }}</span>
                    @else
                        <span class="badge bg-danger mb-3">Event</span>
                    @endif
                    
                    <h1 class="event-title mb-3">{{ $event->judul }}</h1>
                    
                    @php
                        $now = \Carbon\Carbon::now();
                        $mulai = \Carbon\Carbon::parse($event->mulai);
                        $selesai = \Carbon\Carbon::parse($event->selesai);
                    @endphp
                    
                    <div class="event-meta mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="meta-item">
                                    <i class="icon-calendar text-danger me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Mulai</small>
                                        <strong>{{ $mulai->format('d M Y') }}</strong>
                                        <small class="text-muted">({{ $mulai->format('H:i') }} WIB)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="meta-item">
                                    <i class="icon-clock text-danger me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Selesai</small>
                                        <strong>{{ $selesai->format('d M Y') }}</strong>
                                        <small class="text-muted">({{ $selesai->format('H:i') }} WIB)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="meta-item">
                                    <i class="icon-tag text-danger me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        @if($now->lt($mulai))
                                            <span class="badge bg-warning">Akan Datang</span>
                                        @elseif($now->between($mulai, $selesai))
                                            <span class="badge bg-success">Sedang Berlangsung</span>
                                        @else
                                            <span class="badge bg-secondary">Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Image -->
                @if($event->gambar)
                <div class="event-image mb-4">
                    <img src="{{ asset('assets/images/events/'.$event->gambar) }}" 
                         class="img-fluid rounded shadow" alt="{{ $event->judul }}"
                         style="width: 100%; max-height: 400px; object-fit: cover;">
                </div>
                @endif

                <!-- Event Content -->
                <div class="event-content">
                    @if(isset($event->formatted_content) && is_array($event->formatted_content))
                        <!-- Formatted Content Structure -->
                        @foreach($event->formatted_content as $section)
                            @if($section['type'] == 'header')
                                <div class="content-section header-section mb-4">
                                    <h4 class="section-title text-danger">
                                        <i class="icon-info-circle me-2"></i>{{ $section['content'] }}
                                    </h4>
                                </div>
                            @elseif($section['type'] == 'title')
                                <div class="content-section title-section mb-3">
                                    <h5 class="text-dark fw-bold">{{ $section['content'] }}</h5>
                                </div>
                            @elseif($section['type'] == 'schedule')
                                <div class="content-section schedule-section mb-3">
                                    <div class="info-card bg-light p-3 rounded">
                                        <h6 class="text-danger mb-2">
                                            <i class="icon-calendar me-2"></i>Jadwal Pelaksanaan
                                        </h6>
                                        <p class="mb-0">{{ $section['content'] }}</p>
                                    </div>
                                </div>
                            @elseif($section['type'] == 'target')
                                <div class="content-section target-section mb-3">
                                    <div class="info-card bg-info bg-opacity-10 p-3 rounded">
                                        <h6 class="text-info mb-2">
                                            <i class="icon-users me-2"></i>Target Peserta
                                        </h6>
                                        <p class="mb-0">{{ $section['content'] }}</p>
                                    </div>
                                </div>
                            @elseif($section['type'] == 'price')
                                <div class="content-section price-section mb-3">
                                    <div class="info-card bg-success bg-opacity-10 p-3 rounded">
                                        <h6 class="text-success mb-2">
                                            <i class="icon-money me-2"></i>Biaya Pendaftaran
                                        </h6>
                                        <p class="mb-0 fw-bold text-success">{{ $section['content'] }}</p>
                                    </div>
                                </div>
                            @elseif($section['type'] == 'registration')
                                <div class="content-section registration-section mb-3">
                                    <div class="info-card bg-warning bg-opacity-10 p-3 rounded">
                                        <h6 class="text-warning mb-2">
                                            <i class="icon-external-link me-2"></i>Link Pendaftaran
                                        </h6>
                                        @php
                                            $content = $section['content'];
                                            if (preg_match('/https?:\/\/[^\s]+/', $content, $matches)) {
                                                $link = $matches[0];
                                                $content = str_replace($link, '<a href="'.$link.'" target="_blank" class="btn btn-warning btn-sm ms-2">Daftar Sekarang <i class="icon-external-link ms-1"></i></a>', $content);
                                            }
                                        @endphp
                                        <p class="mb-0">{!! $content !!}</p>
                                    </div>
                                </div>
                            @elseif($section['type'] == 'contact')
                                <div class="content-section contact-section mb-3">
                                    <div class="info-card bg-danger bg-opacity-10 p-3 rounded">
                                        <h6 class="text-danger mb-2">
                                            <i class="icon-phone me-2"></i>Kontak Person
                                        </h6>
                                        @php
                                            $content = $section['content'];
                                            // Extract phone numbers and make them clickable
                                            $content = preg_replace('/(\d{10,15})/', '<a href="tel:$1" class="text-danger fw-bold">$1</a>', $content);
                                            // Extract WhatsApp numbers
                                            $content = preg_replace('/(\d{4}\s?\d{4}\s?\d{4,6})/', '<a href="https://wa.me/62$1" target="_blank" class="text-success fw-bold">$1 <i class="icon-whatsapp ms-1"></i></a>', $content);
                                        @endphp
                                        <p class="mb-0">{!! $content !!}</p>
                                    </div>
                                </div>
                            @else
                                <div class="content-section text-section mb-3">
                                    <p>{{ $section['content'] }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <!-- Fallback to original content -->
                        <div class="content-body">
                            @php
                                $content = $event->konten;
                                // Clean up content
                                $content = str_replace(['Website asklinkabbekasi.org<br><br>', 'Website asklinkabbekasi.org'], '', $content);
                                
                                // Format links
                                $content = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" class="btn btn-danger btn-sm">Daftar Sekarang <i class="icon-external-link ms-1"></i></a>', $content);
                                
                                // Format phone numbers
                                $content = preg_replace('/(\d{10,15})/', '<a href="tel:$1" class="text-danger fw-bold">$1</a>', $content);
                                
                                // Format sections with better styling
                                $content = str_replace('⏰', '<i class="icon-clock text-danger"></i>', $content);
                                $content = str_replace('👩‍⚕️', '<i class="icon-users text-info"></i>', $content);
                                $content = str_replace('💵', '<i class="icon-money text-success"></i>', $content);
                                $content = str_replace('🌐', '<i class="icon-globe text-warning"></i>', $content);
                                $content = str_replace('☎️', '<i class="icon-phone text-danger"></i>', $content);
                                
                                // Better line breaks
                                $content = str_replace('<br><br>', '</p><p>', $content);
                                if (!str_starts_with($content, '<p>')) {
                                    $content = '<p>' . $content;
                                }
                                if (!str_ends_with($content, '</p>')) {
                                    $content = $content . '</p>';
                                }
                            @endphp
                            
                            <div class="formatted-content">
                                {!! $content !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Share Section -->
                <div class="share-section mt-5 pt-4 border-top">
                    <h6 class="mb-3">Bagikan Event Ini:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" class="btn btn-outline-danger btn-sm">
                            <i class="icon-facebook me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($event->judul) }}" 
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="icon-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($event->judul . ' - ' . request()->fullUrl()) }}" 
                           target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="icon-whatsapp me-1"></i> WhatsApp
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                            <i class="icon-link me-1"></i> Salin Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Event Info Card -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="icon-info me-2"></i>Informasi Event</h6>
                        </div>
                        <div class="card-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <strong>Kategori:</strong>
                                    @if($event->kategori && is_object($event->kategori))
                                        <span class="badge bg-light text-dark ms-2">{{ $event->kategori->nama }}</span>
                                    @else
                                        <span class="badge bg-light text-dark ms-2">Event</span>
                                    @endif
                                </div>
                                <div class="info-item">
                                    <strong>Tingkat:</strong>
                                    <span class="ms-2">{{ ucfirst($event->kategori) }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Dipublikasi:</strong>
                                    <span class="ms-2">{{ $event->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Durasi:</strong>
                                    <span class="ms-2">{{ $mulai->diffInDays($selesai) }} hari</span>
                                </div>
                                <div class="info-item">
                                    <strong>Status:</strong>
                                    <span class="ms-2">
                                        @if($event->status == '1')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if($now->lte($selesai))
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="card-title">Tertarik mengikuti event ini?</h6>
                            <p class="text-muted small">Hubungi panitia untuk informasi pendaftaran</p>
                         
                        </div>
                    </div>
                    @endif

                    <!-- Related Events -->
                    @if(isset($relatedEvents) && $relatedEvents->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="icon-calendar me-2"></i>Event Terkait</h6>
                        </div>
                        <div class="card-body">
                            @foreach($relatedEvents as $related)
                            <div class="related-event mb-3">
                                <div class="d-flex">
                                    @if($related->gambar)
                                    <img src="{{ asset('assets/images/events/'.$related->gambar) }}" 
                                         class="related-image me-3" alt="{{ $related->judul }}">
                                    @else
                                    <div class="related-placeholder me-3">
                                        <i class="icon-calendar"></i>
                                    </div>
                                    @endif
                                    <div class="related-content">
                                        <h6 class="related-title mb-1">
                                            <a href="{{ route('event_asklin.detail', $related->path) }}" 
                                               class="text-decoration-none">
                                                {{ Str::limit($related->judul, 50) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($related->mulai)->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="section bg-light py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('event_asklin') }}" class="btn btn-outline-danger">
                <i class="icon-arrow-left me-2"></i>Kembali ke Daftar Event
            </a>
            <small class="text-muted">
                Terakhir diupdate: {{ $event->updated_at->format('d M Y H:i') }}
            </small>
        </div>
    </div>
</div>

<style>
/* Event Header */
.event-title {
    color: #2c3e50;
    font-weight: 600;
    line-height: 1.4;
}

.event-meta .meta-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.event-meta .meta-item i {
    margin-top: 2px;
    flex-shrink: 0;
}

/* Content Sections */
.content-section {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.3rem;
    margin-bottom: 1rem;
}

.info-card {
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.info-card h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-card p {
    font-size: 0.95rem;
    line-height: 1.6;
}

.info-card a {
    text-decoration: none;
}

.info-card .btn {
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 20px;
}

/* Formatted Content */
.formatted-content p {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 1.2rem;
    color: #495057;
}

.formatted-content i {
    font-size: 1.2rem;
    margin-right: 8px;
}

/* Event Content */
.event-content .content-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #495057;
}

.event-content .content-body img {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
    border-radius: 8px;
}

.event-content .content-body p {
    margin-bottom: 1.2rem;
}

.event-content .content-body h2,
.event-content .content-body h3,
.event-content .content-body h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
    font-weight: 600;
}

/* Sidebar */
.sidebar .card {
    border: none;
    border-radius: 12px;
}

.sidebar .card-header {
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}

.info-list .info-item {
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f4;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-list .info-item:last-child {
    border-bottom: none;
}

/* Related Events */
.related-image {
    width: 60px;
    height: 45px;
    border-radius: 6px;
    object-fit: cover;
}

.related-placeholder {
    width: 60px;
    height: 45px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.related-title a {
    color: #495057;
    font-size: 0.9rem;
    font-weight: 500;
    line-height: 1.3;
}

.related-title a:hover {
    color: #007bff;
}

.related-event {
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f3f4;
}

.related-event:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

/* Share Section */
.share-section .btn {
    border-radius: 20px;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .event-meta .row {
        text-align: center;
    }
    
    .event-meta .meta-item {
        flex-direction: column;
        text-align: center;
    }
    
    .event-meta .meta-item i {
        margin-bottom: 8px;
        margin-top: 0;
    }
    
    .share-section .d-flex {
        justify-content: center;
    }
    
    .sidebar {
        margin-top: 30px;
    }
    
    .info-item {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 5px;
    }
    
    .info-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .event-title {
        font-size: 1.5rem;
    }
    
    .share-section .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .info-card h6 {
        font-size: 0.9rem;
    }
    
    .info-card p {
        font-size: 0.85rem;
    }
}

/* Print Styles */
@media print {
    .share-section,
    .sidebar,
    .breadcrumb,
    .section.bg-light {
        display: none !important;
    }
    
    .container {
        max-width: 100%;
    }
    
    .event-content {
        page-break-inside: avoid;
    }
    
    .info-card {
        border: 1px solid #ccc !important;
        break-inside: avoid;
    }
}
</style>

<script>
// Copy to Clipboard Function
function copyToClipboard() {
    const url = window.location.href;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showAlert('Link berhasil disalin!', 'success');
        }).catch(() => {
            fallbackCopyToClipboard(url);
        });
    } else {
        fallbackCopyToClipboard(url);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showAlert('Link berhasil disalin!', 'success');
    } catch (err) {
        showAlert('Gagal menyalin link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Simple Alert Function
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show custom-alert`;
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 3000);
}

// Smooth scroll for back button
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

@endsection