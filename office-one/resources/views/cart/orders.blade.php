@extends('layouts.app')

@section('title', 'My Orders - OfficeOne')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Shop</a></li>
        <li class="breadcrumb-item active">My Orders</li>
    </ol>
</nav>

@php
    $totalSpent = $orders->where('status', 'completed')->sum('total_price');
    $deliveredCount = $orders->where('delivery_status', 'delivered')->count();
    $processingCount = $orders->whereIn('delivery_status', ['processing', 'shipped'])->count();
    $orderCount = $orders->count();
@endphp

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ $orderCount }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-bag"></i></div>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" points="0,35 30,28 60,32 90,20 120,25 150,15 180,18 200,10"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none" points="0,40 0,35 30,28 60,32 90,20 120,25 150,15 180,18 200,10 200,40"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">₱{{ number_format($totalSpent, 0) }}</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" points="0,30 30,25 60,28 90,15 120,20 150,10 180,12 200,5"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none" points="0,40 0,30 30,25 60,28 90,15 120,20 150,10 180,12 200,5 200,40"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ $deliveredCount }}</div>
                        <div class="stat-label">Delivered</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" points="0,25 30,30 60,22 90,28 120,18 150,22 180,15 200,20"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none" points="0,40 0,25 30,30 60,22 90,28 120,18 150,22 180,15 200,20 200,40"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ $processingCount }}</div>
                        <div class="stat-label">In Transit</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-truck"></i></div>
                </div>
            </div>
            <div class="stat-card-sparkline">
                <svg viewBox="0 0 200 40" preserveAspectRatio="none">
                    <polyline fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" points="0,20 30,15 60,25 90,10 120,30 150,12 180,22 200,8"/>
                    <polyline fill="rgba(255,255,255,0.1)" stroke="none" points="0,40 0,20 30,15 60,25 90,10 120,30 150,12 180,22 200,8 200,40"/>
                </svg>
            </div>
        </div>
    </div>
</div>

@if($orders->count() > 0)
    {{-- Orders List --}}
    @foreach($orders as $order)
    <div class="order-card card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="row g-0 align-items-center">
                {{-- Product Image --}}
                <div class="col-auto">
                    <div class="p-3">
                        @if($order->product && $order->product->photos->count() > 0)
                            <img src="{{ asset('storage/' . $order->product->photos->first()->photo_path) }}"
                                 class="rounded-3 shadow-sm" width="80" height="80" style="object-fit:cover;"
                                 alt="{{ $order->product->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-light"
                                 style="width:80px; height:80px;">
                                <i class="bi bi-box-seam text-muted" style="font-size: 1.8rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="col">
                    <div class="p-3 ps-0">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-primary fw-bold small">Order #{{ $order->id }}</span>
                                    <span class="text-muted small">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                @if($order->product)
                                    <a href="{{ route('products.show', $order->product) }}" class="text-decoration-none fw-semibold fs-6 d-block text-dark order-product-link">
                                        {{ $order->product->name }}
                                    </a>
                                @else
                                    <span class="text-muted fs-6">Product deleted</span>
                                @endif
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <span class="text-muted small"><i class="bi bi-x-diamond me-1"></i>Qty: {{ $order->quantity }}</span>
                                    <span class="fw-bold" style="color: #0d3b66;">₱{{ number_format($order->total_price, 2) }}</span>
                                </div>
                            </div>

                            {{-- Status & Delivery Badges --}}
                            <div class="d-flex flex-column align-items-end gap-2">
                                <div class="d-flex gap-2 flex-wrap justify-content-end">
                                    @if($order->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>Completed
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i>Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Cancelled
                                        </span>
                                    @endif

                                    @if($order->delivery_status === 'delivered')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-check2-all me-1"></i>Delivered
                                        </span>
                                    @elseif($order->delivery_status === 'shipped')
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="bi bi-truck me-1"></i>Shipped
                                        </span>
                                    @elseif($order->delivery_status === 'cancelled')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Cancelled
                                        </span>
                                    @elseif($order->delivery_status === 'returned')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="bi bi-arrow-return-left me-1"></i>Returned
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>Processing
                                        </span>
                                    @endif
                                </div>

                                {{-- Review Action --}}
                                @if($order->status === 'completed' && $order->delivery_status === 'delivered' && $order->product)
                                    @if(auth()->user()->hasReviewedProduct($order->product_id))
                                        <a href="{{ route('products.show', $order->product) }}#reviews"
                                           class="btn btn-sm btn-outline-success review-btn px-3">
                                            <i class="bi bi-star-fill me-1"></i> Reviewed
                                        </a>
                                    @else
                                        <a href="{{ route('products.show', $order->product) }}#reviews"
                                           class="btn btn-sm btn-warning review-btn px-3 text-white">
                                            <i class="bi bi-star me-1"></i> Write Review
                                        </a>
                                    @endif
                                @elseif($order->status === 'completed' && $order->delivery_status !== 'delivered')
                                    <span class="text-muted small"><i class="bi bi-clock me-1"></i>Awaiting delivery</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Delivery Progress Bar --}}
            @if($order->status !== 'cancelled' && !in_array($order->delivery_status, ['cancelled', 'returned']))
            <div class="px-3 pb-3">
                @php
                    $steps = ['processing' => 1, 'shipped' => 2, 'delivered' => 3];
                    $currentStep = $steps[$order->delivery_status] ?? 0;
                @endphp
                <div class="delivery-tracker d-flex align-items-center">
                    <div class="tracker-step {{ $currentStep >= 1 ? 'active' : '' }}">
                        <div class="tracker-dot"><i class="bi bi-clipboard-check"></i></div>
                        <small>Processing</small>
                    </div>
                    <div class="tracker-line {{ $currentStep >= 2 ? 'active' : '' }}"></div>
                    <div class="tracker-step {{ $currentStep >= 2 ? 'active' : '' }}">
                        <div class="tracker-dot"><i class="bi bi-truck"></i></div>
                        <small>Shipped</small>
                    </div>
                    <div class="tracker-line {{ $currentStep >= 3 ? 'active' : '' }}"></div>
                    <div class="tracker-step {{ $currentStep >= 3 ? 'active' : '' }}">
                        <div class="tracker-dot"><i class="bi bi-house-check"></i></div>
                        <small>Delivered</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="bi bi-bag-x" style="font-size: 4rem; color: #d1d5db;"></i>
            </div>
            <h4 class="fw-bold text-muted">No orders yet</h4>
            <p class="text-muted mb-4">Start shopping to see your orders here.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                <i class="bi bi-box-seam me-1"></i> Browse Products
            </a>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    /* ─── Stat Cards ─── */
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
    .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.2; }
    .stat-label { font-size: 0.8rem; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
    .stat-icon { font-size: 1.8rem; opacity: 0.3; transition: opacity 0.3s, transform 0.3s; }
    .stat-card:hover .stat-icon { opacity: 0.6; transform: scale(1.15) rotate(-5deg); }
    .stat-card-sparkline { height: 45px; position: relative; z-index: 1; }
    .stat-card-sparkline svg { width: 100%; height: 100%; display: block; }

    /* ─── Order Cards ─── */
    .order-card {
        border-radius: 12px;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
        overflow: hidden;
    }
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .order-product-link {
        transition: color 0.2s;
    }
    .order-product-link:hover {
        color: #321fdb !important;
    }

    /* ─── Review Button ─── */
    .review-btn {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .review-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* ─── Delivery Tracker ─── */
    .delivery-tracker {
        justify-content: center;
        gap: 0;
    }
    .tracker-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    .tracker-step small {
        font-size: 0.7rem;
        color: #adb5bd;
        transition: color 0.3s;
    }
    .tracker-step.active small {
        color: #2eb85c;
        font-weight: 600;
    }
    .tracker-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e9ecef;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .tracker-step.active .tracker-dot {
        background: #2eb85c;
        color: #fff;
        box-shadow: 0 2px 8px rgba(46, 184, 92, 0.3);
    }
    .tracker-line {
        flex: 1;
        height: 3px;
        background: #e9ecef;
        margin: 0 4px;
        margin-bottom: 20px;
        border-radius: 2px;
        transition: background 0.3s ease;
    }
    .tracker-line.active {
        background: #2eb85c;
    }
</style>
@endpush
