@extends('layouts.frontend.layout')

@section('content')

<!-- Hero Section dengan Gradient -->
<div class="hero-section position-relative overflow-hidden">
    <div class="hero-bg"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center py-5">
            <div class="col-lg-8">
                <div class="hero-content text-white">
                    <h1 class="display-4 fw-bold mb-3 animate-fade-up">EVENT ASKLIN</h1>
                    <p class="lead mb-4 animate-fade-up" style="animation-delay: 0.2s">
                        Bergabunglah dalam berbagai event inspiratif dan edukatif dari ASKLIN
                    </p>
                    <div class="hero-stats d-flex justify-content-center gap-4 animate-fade-up" style="animation-delay: 0.4s">
                        <div class="stat-item">
                            <h3 class="mb-0">{{ App\Models\Event::where('status', '1')->count() }}</h3>
                            <small>Event Aktif</small>
                        </div>
                        <div class="stat-item">
                            <h3 class="mb-0">{{ App\Models\EventKategori::where('status', 1)->count() }}</h3>
                            <small>Kategori</small>
                        </div>
                        <div class="stat-item">
                            <h3 class="mb-0">1000+</h3>
                            <small>Peserta</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section bg-white shadow-sm sticky-top" style="top: 70px; z-index: 100;">
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-lg-10">
                <form action="{{ route('event_asklin') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted">Cari Event</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="icon-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" 
                                       placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('kategori') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="icon-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('event_asklin') }}" class="btn btn-outline-secondary">
                                    <i class="icon-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="section py-5 bg-light">
    <div class="container">
        
        <!-- Filter Tags (Active Filters) -->
        @if(request('search') || request('kategori') || request('status'))
        <div class="active-filters mb-4">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted fw-semibold">Filter aktif:</span>
                
                @if(request('search'))
                <span class="badge bg-primary">
                    Pencarian: "{{ request('search') }}"
                    <a href="{{ request()->fullUrlWithQuery(['search' => '']) }}" class="text-white ms-1">×</a>
                </span>
                @endif
                
                @if(request('kategori'))
                <span class="badge bg-info">
                    Kategori: {{ $categories->where('id', request('kategori'))->first()->nama ?? 'Unknown' }}
                    <a href="{{ request()->fullUrlWithQuery(['kategori' => '']) }}" class="text-white ms-1">×</a>
                </span>
                @endif
                
                @if(request('status'))
                <span class="badge bg-warning">
                    Status: {{ ucfirst(request('status')) }}
                    <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="text-white ms-1">×</a>
                </span>
                @endif
                
                <a href="{{ route('event_asklin') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="icon-close me-1"></i> Hapus semua filter
                </a>
            </div>
        </div>
        @endif
        
        <!-- Results Info -->
        <div class="results-info mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        Menampilkan {{ $event->count() }} dari {{ $event->total() }} event
                    </h5>
                    <p class="text-muted mb-0">Halaman {{ $event->currentPage() }} dari {{ $event->lastPage() }}</p>
                </div>
            </div>
        </div>
        
        <!-- Events Grid -->
        <div class="events-container" id="eventsGrid">
            <div class="row g-4">
                @forelse($event as $item)
                <div class="col-lg-4 col-md-6 event-item">
                    <div class="event-card h-100">
                        <!-- Card Image -->
                        <div class="event-image position-relative">
                            @if($item->gambar)
                                <img src="{{ asset('assets/images/events/' . $item->gambar) }}" 
                                     class="card-img-top" alt="{{ $item->judul }}">
                            @else
                                <div class="placeholder-image">
                                    <i class="icon-calendar"></i>
                                </div>
                            @endif
                            
                            <!-- Overlay Labels -->
                            <div class="event-labels">
                                <span class="event-category">
                                    @if($item->kategori && is_object($item->kategori))
                                        {{ $item->kategori->nama }}
                                    @else
                                        Event
                                    @endif
                                </span>
                                
                                <!-- Status Badge -->
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $mulai = \Carbon\Carbon::parse($item->mulai);
                                    $selesai = \Carbon\Carbon::parse($item->selesai);
                                @endphp
                                
                                @if($now->lt($mulai))
                                    <span class="event-status status-upcoming">Akan Datang</span>
                                @elseif($now->between($mulai, $selesai))
                                    <span class="event-status status-ongoing">Berlangsung</span>
                                @else
                                    <span class="event-status status-finished">Selesai</span>
                                @endif
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="event-actions">
                                <a href="{{ route('event_asklin.detail', $item->path) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="icon-eye me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="event-body">
                            <div class="event-meta mb-2">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="icon-calendar me-2"></i>
                                    <span>{{ $mulai->format('d M Y') }} - {{ $selesai->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="icon-clock me-2"></i>
                                    <span>{{ $mulai->format('H:i') }} - {{ $selesai->format('H:i') }}</span>
                                </div>
                            </div>
                            
                            <h5 class="event-title mb-2">
                                <a href="{{ route('event_asklin.detail', $item->path) }}">{{ $item->judul }}</a>
                            </h5>
                            
                            <p class="event-excerpt text-muted">
                                {{ $item->clean_excerpt ?? Str::limit(strip_tags($item->konten), 120) }}
                            </p>
                            
                            <!-- Event Footer -->
                            <div class="event-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="icon-user me-1"></i>
                                        {{ $item->created_at->diffForHumans() }}
                                    </small>
                                    <div class="event-level">
                                        <span class="badge badge-level-{{ strtolower($item->kategori) }}">
                                            {{ ucfirst($item->kategori) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="icon-calendar text-muted"></i>
                        </div>
                        <h4 class="mb-2">Tidak ada event ditemukan</h4>
                        @if(request('search') || request('kategori') || request('status'))
                            <p class="text-muted mb-4">Tidak ada event yang sesuai dengan kriteria pencarian Anda.</p>
                            <a href="{{ route('event_asklin') }}" class="btn btn-primary">
                                <i class="icon-refresh me-2"></i>Lihat Semua Event
                            </a>
                        @else
                            <p class="text-muted mb-4">Belum ada event yang tersedia saat ini. Silakan cek kembali nanti.</p>
                            <button class="btn btn-outline-primary" onclick="location.reload()">
                                <i class="icon-refresh me-2"></i>Refresh Halaman
                            </button>
                        @endif
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Pagination -->
        @if($event->hasPages())
        <div class="pagination-wrapper mt-5">
            <div class="d-flex justify-content-center">
                <nav aria-label="Event pagination">
                    {{ $event->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-2">Jangan Lewatkan Event Menarik Lainnya!</h3>
                <p class="mb-0">Daftarkan diri Anda untuk mendapatkan notifikasi event terbaru dari ASKLIN.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
             
            </div>
        </div>
    </div>
</div>

<style>
/* [Previous CSS styles remain the same] */
.hero-section {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    min-height: 400px;
    display: flex;
    align-items: center;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="1000,0 1000,100 0,100"/></svg>') no-repeat;
    background-size: cover;
}

.hero-stats .stat-item {
    text-align: center;
    padding: 0 20px;
}

.hero-stats .stat-item h3 {
    font-size: 2rem;
    font-weight: 700;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-up {
    animation: fadeUp 0.8s ease-out forwards;
    opacity: 0;
}

.filter-section {
    transition: all 0.3s ease;
}

.form-label {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.event-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: none;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.event-image {
    height: 240px;
    overflow: hidden;
    position: relative;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card:hover .event-image img {
    transform: scale(1.05);
}

.placeholder-image {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #9ca3af;
}

.event-labels {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.event-category {
    background: rgba(255,255,255,0.95);
    color: #667eea;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.event-status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.status-upcoming {
    background: rgba(59, 130, 246, 0.9);
    color: white;
}

.status-ongoing {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.status-finished {
    background: rgba(107, 114, 128, 0.9);
    color: white;
}

.event-actions {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.event-card:hover .event-actions {
    opacity: 1;
    transform: translateY(0);
}

.event-body {
    padding: 20px;
}

.event-title a {
    color: #1f2937;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.4;
    transition: color 0.2s ease;
}

.event-title a:hover {
    color: #667eea;
}

.event-excerpt {
    font-size: 0.9rem;
    line-height: 1.6;
}

.event-footer {
    border-top: 1px solid #f3f4f6;
    padding-top: 15px;
    margin-top: 15px;
}

.badge-level-pusat {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.badge-level-daerah {
    background: linear-gradient(45deg, #f093fb, #f5576c);
    color: white;
}

.badge-level-cabang {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    color: white;
}

.active-filters .badge {
    font-size: 0.85rem;
    padding: 8px 12px;
}

.active-filters .badge a {
    text-decoration: none;
    opacity: 0.8;
}

.active-filters .badge a:hover {
    opacity: 1;
}

.empty-state .empty-icon i {
    font-size: 4rem;
    opacity: 0.3;
}

.cta-section {
	background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

@media (max-width: 768px) {
    .hero-stats {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .hero-stats .stat-item {
        padding: 10px 0;
    }
    
    .filter-section .row > div {
        margin-bottom: 1rem;
    }
    
    .results-info {
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form on filter change
    const filterForm = document.getElementById('filterForm');
    const selects = filterForm.querySelectorAll('select');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            filterForm.submit();
        });
    });
});
</script>

@endsection