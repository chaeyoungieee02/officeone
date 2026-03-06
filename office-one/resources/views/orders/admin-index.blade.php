@extends('layouts.app')

@section('title', 'Manage Orders - OfficeOne')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Orders</li>
    </ol>
</nav>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    {{-- Total Orders --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($totalOrders) }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-bag"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-currency-exchange me-1"></i>₱{{ number_format($totalRevenue, 2) }} revenue</span>
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

    {{-- Processing --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($processingOrders) }}</div>
                        <div class="stat-label">Processing</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-truck me-1"></i>{{ $shippedOrders }} shipped</span>
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

    {{-- Delivered --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($deliveredOrders) }}</div>
                        <div class="stat-label">Delivered</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-graph-up me-1"></i>{{ $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100) : 0 }}% success rate</span>
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

    {{-- Cancelled / Returned --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($cancelledOrders) }}</div>
                        <div class="stat-label">Cancelled / Returned</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-arrow-return-left me-1"></i>{{ $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100) : 0 }}% of total</span>
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

{{-- Orders Table --}}
<div class="card border-0 shadow-sm table-card">
    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-bag me-2"></i>All Orders</h6>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>
    <div class="card-body">
        <table id="orders-table" class="table table-hover align-middle w-100">
            <thead>
                <tr class="text-muted small">
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Delivery</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
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

    /* ─── Table Card ─── */
    .table-card {
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }
    .table-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.orders.index") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'photo', name: 'photo', orderable: false, searchable: false },
            { data: 'customer', name: 'user.name' },
            { data: 'product_name', name: 'product.name' },
            { data: 'quantity', name: 'quantity' },
            { data: 'formatted_total', name: 'total_price' },
            { data: 'order_status', name: 'status', orderable: false, searchable: false },
            { data: 'delivery', name: 'delivery_status', orderable: false, searchable: false },
            { data: 'date', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        responsive: true,
        language: {
            emptyTable: "No orders found.",
            processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading...'
        }
    });
});
</script>
@endpush
