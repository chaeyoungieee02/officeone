@extends('layouts.app')

@section('title', 'Review Management - OfficeOne')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Reviews</li>
    </ol>
</nav>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    {{-- Total Reviews --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($totalReviews) }}</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-clock me-1"></i>{{ $recentCount }} this month</span>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"
                        points="0,35 30,28 60,32 90,20 120,25 150,15 180,18 200,10"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none"
                        points="0,40 0,35 30,28 60,32 90,20 120,25 150,15 180,18 200,10 200,40"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Average Rating --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ $averageRating }} <small style="font-size:0.6em">/5</small></div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($averageRating))
                            <i class="bi bi-star-fill" style="color:rgba(255,255,255,0.8)"></i>
                        @else
                            <i class="bi bi-star" style="color:rgba(255,255,255,0.4)"></i>
                        @endif
                    @endfor
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"
                        points="0,25 30,30 60,22 90,28 120,18 150,22 180,15 200,20"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none"
                        points="0,40 0,25 30,30 60,22 90,28 120,18 150,22 180,15 200,20 200,40"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- 5-Star Reviews --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($fiveStarCount) }}</div>
                        <div class="stat-label">5-Star Reviews</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-emoji-laughing"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-graph-up me-1"></i>{{ $totalReviews > 0 ? round(($fiveStarCount / $totalReviews) * 100) : 0 }}% of total</span>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"
                        points="0,30 30,25 60,28 90,15 120,20 150,10 180,12 200,5"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none"
                        points="0,40 0,30 30,25 60,28 90,15 120,20 150,10 180,12 200,5 200,40"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rating Distribution --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ $recentCount }}</div>
                        <div class="stat-label">Recent (30 days)</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-activity me-1"></i>Active feedback</span>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"
                        points="0,20 30,15 60,25 90,10 120,30 150,12 180,22 200,8"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none"
                        points="0,40 0,20 30,15 60,25 90,10 120,30 150,12 180,22 200,8 200,40"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Rating Distribution + Table --}}
<div class="row g-3 mb-4">
    {{-- Rating Breakdown --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm info-card h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2"></i>Rating Distribution</h6>
            </div>
            <div class="card-body">
                @for($i = 5; $i >= 1; $i--)
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2" style="width:55px;">
                            @for($s = 1; $s <= $i; $s++)
                                <i class="bi bi-star-fill text-warning" style="font-size:0.7rem"></i>
                            @endfor
                        </div>
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $i >= 4 ? 'bg-success' : ($i == 3 ? 'bg-warning' : 'bg-danger') }}" 
                                     style="width: {{ $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="ms-2 text-muted small" style="width:30px;text-align:right;">
                            {{ $ratingDistribution[$i] }}
                        </div>
                    </div>
                @endfor

                <hr class="my-3">
                <div class="text-center">
                    <div class="display-5 fw-bold text-warning">{{ $averageRating }}</div>
                    <div class="mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($averageRating))
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">Based on {{ $totalReviews }} review{{ $totalReviews !== 1 ? 's' : '' }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews Table --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm table-card">
            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-star-half me-2"></i>All Reviews</h6>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>
            <div class="card-body">
                <table id="reviews-table" class="table table-hover align-middle w-100">
                    <thead>
                        <tr class="text-muted small">
                            <th>User</th>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ─── Stat Cards (CoreUI style) ─── */
    .stat-card {
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }
    .stat-card-primary { background: linear-gradient(135deg, #321fdb 0%, #1b2e8a 100%); color: #fff; }
    .stat-card-success { background: linear-gradient(135deg, #2eb85c 0%, #1b9e3e 100%); color: #fff; }
    .stat-card-warning { background: linear-gradient(135deg, #f9b115 0%, #e8950a 100%); color: #fff; }
    .stat-card-danger  { background: linear-gradient(135deg, #e55353 0%, #c42c2c 100%); color: #fff; }

    .stat-card-body {
        padding: 1.25rem 1.25rem 0.5rem;
        position: relative;
        z-index: 2;
    }
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.85rem;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.3;
        transition: opacity 0.3s, transform 0.3s;
    }
    .stat-card:hover .stat-icon {
        opacity: 0.6;
        transform: scale(1.15) rotate(-5deg);
    }
    .stat-footer {
        margin-top: 0.75rem;
        font-size: 0.8rem;
    }
    .stat-card-sparkline {
        height: 50px;
        position: relative;
        z-index: 1;
    }
    .stat-card-sparkline svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* ─── Info & Table Cards ─── */
    .info-card, .table-card {
        border-radius: 8px;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
    }
    .info-card:hover, .table-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* ─── Smooth progress bars ─── */
    .progress-bar {
        transition: width 1.5s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        $('#reviews-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.reviews.index") }}',
            columns: [
                { data: 'user_name', name: 'user.name' },
                { data: 'product_name', name: 'product.name' },
                { data: 'stars', name: 'rating', searchable: false },
                { data: 'short_comment', name: 'comment' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[4, 'desc']],
            responsive: true,
            language: {
                emptyTable: "No reviews found.",
                processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading...'
            }
        });
    });
</script>
@endpush
