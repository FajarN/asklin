@extends('layouts.frontend.layout')

@section('content')

    <section id="page-title">

        <div class="container clearfix">
            <h1>Event Asklin</h1>
            <span>{{ Str::limit($event->judul, 50) }}</span>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event_asklin') }}">Event</a></li>
            </ol>
        </div>

    </section>


    <div class="section py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Event Header -->
                    <div class="event-header mb-4">
                        @if ($event->kategori && is_object($event->kategori))
                            <span class="badge bg-primary mb-3">{{ $event->kategori->nama }}</span>
                        @else
                            <span class="badge bg-primary mb-3">Event</span>
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
                                        <i class="icon-calendar text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Tanggal Mulai</small>
                                            <strong>{{ $mulai->format('d M Y') }}</strong>
                                            <small class="text-muted">({{ $mulai->format('H:i') }} WIB)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="meta-item">
                                        <i class="icon-clock text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Tanggal Selesai</small>
                                            <strong>{{ $selesai->format('d M Y') }}</strong>
                                            <small class="text-muted">({{ $selesai->format('H:i') }} WIB)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="meta-item">
                                        <i class="icon-tag text-primary me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Status</small>
                                            @if ($now->lt($mulai))
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

                    <!-- Event Image dan Content dalam Row -->
                    <div class="row mb-4">
                        <!-- Image Column - Kiri -->
                        <div class="col-lg-5">
                            @if ($event->gambar)
                                <div class="portfolio-single-image masonry-thumbs grid-container" data-big="1"
                                    data-lightbox="gallery">
                                    <img src="{{ asset('assets/images/events/' . $event->gambar) }}"
                                        class="img-fluid rounded shadow" alt="{{ $event->judul }}">
                                    <div class="image-badge">
                                        @if ($event->kategori && is_object($event->kategori))
                                            {{ $event->kategori->nama }}
                                        @else
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="icon-calendar"></i>
                                    <span>No Image Available</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content Column - Kanan -->
                        <div class="col-lg-7">
                            <div class="event-content-text">
                                @php
                                    // Simple processing - hanya decode dan cleanup
                                    $content = html_entity_decode($event->konten, ENT_QUOTES, 'UTF-8');
                                    $content = preg_replace('/^<p>Website\s+[\w\.]+<\/p>/', '', $content);

                                    // Format phone numbers
                                    $content = preg_replace(
                                        '/(\d{10,15})/',
                                        '<a href="tel:+62$1" class="phone-link">$1</a>',
                                        $content,
                                    );

                                    // Format speaker names
                                    $content = preg_replace(
                                        '/(\b(?:Dr\.|Prof\.|dr\.|Ir\.)\s+[^<\n,]+)/i',
                                        '<span class="speaker-highlight">$1</span>',
                                        $content,
                                    );

                                    // Format hashtags
                                    $content = preg_replace(
                                        '/(#\w+)/',
                                        '<span class="hashtag-style">$1</span>',
                                        $content,
                                    );
                                @endphp

                                <div class="content-text">
                                    {!! $content !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="share-section mt-5 pt-4 border-top">
                        <h6 class="mb-3">Bagikan Event Ini:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                target="_blank" class="btn btn-outline-primary btn-sm">
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
                <div class="col-lg-3">
                    <div class="sidebar">
                        <!-- Quick Actions -->
                        @if ($now->lte($selesai))
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Tertarik mengikuti event ini?</h6>
                                    <p class="text-muted small">Hubungi panitia untuk informasi pendaftaran</p>
                                    <div class="d-grid gap-2">
                                        <a href="https://wa.me/6281219486667" target="_blank"
                                            class="btn btn-outline-success">
                                            <i class="icon-whatsapp me-2"></i>WhatsApp Bu Ellen
                                        </a>
                                        <a href="https://wa.me/6282310981637" target="_blank"
                                            class="btn btn-outline-success">
                                            <i class="icon-whatsapp me-2"></i>WhatsApp Hasyim
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Related Events -->
                        @if (isset($relatedEvents) && $relatedEvents->count() > 0)
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="icon-calendar me-2"></i>Event Terkait</h6>
                                </div>
                                <div class="card-body">
                                    @foreach ($relatedEvents as $related)
                                        <div class="related-event mb-3">
                                            <div class="d-flex">
                                                @if ($related->gambar)
                                                    <img src="{{ asset('assets/images/events/' . $related->gambar) }}"
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
                <a href="{{ route('event_asklin') }}" class="btn btn-outline-primary">
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


        /* Content Text Styling */
        .event-content-text {
            height: 100%;
        }

        .content-text {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        /* Remove scrollbar styling since no scroll needed */

        /* Content Paragraph Styling */
        .content-text p {
            margin-bottom: 16px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .content-text p:hover {
            background: #e9ecef;
            border-left-color: #007bff;
        }

        /* Enhanced Elements */
        .phone-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            background: rgba(0, 123, 255, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .phone-link:hover {
            background: rgba(0, 123, 255, 0.2);
            text-decoration: none;
        }

        .register-btn {
            display: inline-block;
            color: white;
            text-decoration: none;
            font-weight: 600;
            background: linear-gradient(45deg, #007bff, #0056b3);
            padding: 8px 16px;
            border-radius: 20px;
            margin: 4px 2px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            text-decoration: none;
            color: white;
        }

        .register-btn i {
            margin-right: 6px;
        }

        .speaker-highlight {
            background: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
            margin: 1px;
        }

        .hashtag-style {
            color: #e91e63;
            background: rgba(233, 30, 99, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
            margin: 0 2px;
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
        @media (max-width: 991px) {
            .event-main-image {
                height: 250px;
                margin-bottom: 20px;
            }

            .no-image-placeholder {
                height: 250px;
                margin-bottom: 20px;
            }
        }

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

            .content-text {
                font-size: 15px;
                margin-top: 20px;
            }

            .content-text p {
                padding: 10px;
            }

            .event-main-image {
                height: 200px;
            }

            .no-image-placeholder {
                height: 200px;
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

            .content-text {
                font-size: 14px;
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

            .content-text {
                height: auto !important;
                overflow: visible !important;
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
            alert.className =
                `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show custom-alert`;
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
