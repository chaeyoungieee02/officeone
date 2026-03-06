@extends('layouts.app')

@section('title', 'Admin Dashboard - OfficeOne')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</nav>

{{-- Top Stat Cards --}}
<div class="row g-3 mb-4">
    {{-- Total Products --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($totalProducts) }}</div>
                        <div class="stat-label">Total Products</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-check-circle me-1"></i>{{ $activeProducts }} active</span>
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

    {{-- Revenue --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
                        <div class="stat-label">Revenue</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-currency-exchange"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-bag-check me-1"></i>{{ $totalOrders }} orders</span>
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

    {{-- Users --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($totalUsers) }}</div>
                        <div class="stat-label">Users</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-shield-lock me-1"></i>{{ $totalAdmins }} admin(s)</span>
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

    {{-- Reviews --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($totalReviews) }}</div>
                        <div class="stat-label">Reviews</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-star-half"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="text-white-50"><i class="bi bi-truck me-1"></i>{{ $pendingDeliveries }} pending deliveries</span>
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

{{-- Revenue Chart --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 chart-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Revenue Overview</h5>
                        <small class="text-muted">{{ now()->subMonths(5)->format('F') }} - {{ now()->format('F Y') }}</small>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-secondary active" disabled>Monthly</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bottom Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm info-card h-100">
            <div class="card-body text-center py-4">
                <div class="info-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-bag-check"></i>
                </div>
                <h4 class="fw-bold mt-3 mb-1">{{ number_format($totalOrders) }}</h4>
                <small class="text-muted">Total Orders</small>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: {{ $totalOrders > 0 ? min(($deliveredOrders / $totalOrders) * 100, 100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $deliveredOrders }} delivered ({{ $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100) : 0 }}%)</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm info-card h-100">
            <div class="card-body text-center py-4">
                <div class="info-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h4 class="fw-bold mt-3 mb-1">{{ $activeProducts }}</h4>
                <small class="text-muted">Active Products</small>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $totalProducts > 0 ? ($activeProducts / $totalProducts * 100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $inactiveProducts }} inactive, {{ $trashedProducts }} trashed</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm info-card h-100">
            <div class="card-body text-center py-4">
                <div class="info-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-box"></i>
                </div>
                <h4 class="fw-bold mt-3 mb-1">{{ $productCount }}</h4>
                <small class="text-muted">Products</small>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-info" style="width: {{ $totalProducts > 0 ? ($productCount / $totalProducts * 100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $serviceCount }} services</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm info-card h-100">
            <div class="card-body text-center py-4">
                <div class="info-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-truck"></i>
                </div>
                <h4 class="fw-bold mt-3 mb-1">{{ $pendingDeliveries }}</h4>
                <small class="text-muted">Pending Deliveries</small>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: {{ $totalOrders > 0 ? min(($pendingDeliveries / $totalOrders) * 100, 100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $deliveredOrders }} delivered</small>
            </div>
        </div>
    </div>
</div>

{{-- Recent Products & Recent Orders --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm table-card">
            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Recent Products</h6>
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-3">Item Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $product)
                        <tr>
                            <td class="ps-3"><code>{{ $product->item_code }}</code></td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none fw-semibold">{{ $product->name }}</a>
                            </td>
                            <td>
                                <span class="badge {{ $product->category == 'Product' ? 'bg-primary' : 'bg-info' }}">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td>₱{{ number_format($product->unit_price, 2) }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No products yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm table-card">
            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-bag me-2"></i>Recent Orders</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-3">Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="ps-3"><code>#{{ $order->id }}</code></td>
                            <td class="small">{{ $order->user->name ?? 'Deleted' }}</td>
                            <td class="fw-semibold">₱{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @switch($order->delivery_status)
                                    @case('delivered')
                                        <span class="badge bg-success">Delivered</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge bg-info">Shipped</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @case('returned')
                                        <span class="badge bg-warning text-dark">Returned</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Processing</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-primary action-btn">
                        <i class="bi bi-plus-circle me-1"></i> Add Product
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary action-btn">
                        <i class="bi bi-table me-1"></i> Products
                    </a>
                    <a href="{{ route('users.create') }}" class="btn btn-success action-btn">
                        <i class="bi bi-person-plus me-1"></i> Add User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-success action-btn">
                        <i class="bi bi-people me-1"></i> Users
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-info action-btn">
                        <i class="bi bi-truck me-1"></i> Orders
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-warning action-btn">
                        <i class="bi bi-star-half me-1"></i> Reviews
                    </a>
                    <a href="{{ route('products.template') }}" class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-download me-1"></i> Import Template
                    </a>
                </div>
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

    /* ─── Info Cards ─── */
    .info-card {
        border-radius: 8px;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .info-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto;
        transition: transform 0.3s;
    }
    .info-card:hover .info-icon {
        transform: scale(1.15) rotate(-5deg);
    }

    /* ─── Chart Card ─── */
    .chart-card {
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }
    .chart-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .chart-container {
        position: relative;
        height: 260px;
    }

    /* ─── Table Cards ─── */
    .table-card {
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .table-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* ─── Action Buttons ─── */
    .action-btn {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* ─── Smooth progress bars ─── */
    .progress-bar {
        transition: width 1.5s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(50, 31, 219, 0.25)');
    gradient.addColorStop(1, 'rgba(50, 31, 219, 0.02)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($monthlyRevenue)->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode(collect($monthlyRevenue)->pluck('revenue')) !!},
                borderColor: '#321fdb',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#321fdb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#321fdb',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#303c54',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return '₱' + Number(context.parsed.y).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#8f9bb3' }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        color: '#8f9bb3',
                        callback: function(value) {
                            return '₱' + Number(value).toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
