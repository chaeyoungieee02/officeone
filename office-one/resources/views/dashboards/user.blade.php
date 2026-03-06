@extends('layouts.app')

@section('title', 'My Dashboard - OfficeOne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-house-door"></i> Welcome, {{ auth()->user()->name }}!</h2>
    <span class="badge bg-primary fs-6"><i class="bi bi-person"></i> User</span>
</div>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalProducts }}</h3>
                    <small>Available Products</small>
                </div>
            </div>
        </div>
    </div>
    @foreach($categories as $cat)
    <div class="col-md-4 mb-3">
        <div class="card text-white {{ $cat->category == 'Product' ? 'bg-success' : 'bg-info' }} shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi {{ $cat->category == 'Product' ? 'bi-box' : 'bi-gear' }}" style="font-size: 2.5rem;"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $cat->count }}</h3>
                    <small>{{ $cat->category }}s</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Featured Products -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-star"></i> Featured Products & Services</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($product->photos->count() > 0)
                        <img src="{{ asset('storage/' . $product->photos->first()->photo_path) }}"
                             class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                             style="height:180px;">
                            <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <span class="badge {{ $product->category == 'Product' ? 'bg-primary' : 'bg-info' }} mb-2">
                            {{ $product->category }}
                        </span>
                        <p class="card-text small text-muted">{{ Str::limit($product->description, 60) }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <strong class="text-success">₱{{ number_format($product->unit_price, 2) }}</strong>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="bi bi-box-seam text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2">No products available yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
