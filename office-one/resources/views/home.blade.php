@extends('layouts.app')

@section('title', 'Welcome - OfficeOne')

@section('content')
    <!-- Hero Banner -->
    <div class="hero-banner">
        <h1><i class="bi bi-briefcase-fill"></i> Welcome to OfficeOne</h1>
        <p>Your trusted source for office supplies and furniture</p>
    </div>

    <!-- Search Bar -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                <select class="form-select" style="max-width: 200px;" name="category">
                    <option value="">All Categories</option>
                    <option value="Product">Products</option>
                    <option value="Service">Services</option>
                </select>
                <input type="text" class="form-control" name="search" placeholder="Search products...">
                <button type="submit" class="btn btn-dark px-4">
                    <i class="bi bi-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Featured Products -->
    <h3 class="mb-3">Featured Products</h3>
    <div class="row">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm card-product">
                    @if($product->photos->count() > 0)
                        <img src="{{ asset('storage/' . $product->photos->first()->photo_path) }}"
                             class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                             style="height:200px;">
                            <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted small mb-1">{{ $product->type ?? $product->category }}</p>
                        <p class="card-text small">{{ Str::limit($product->description, 80) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        <strong class="text-primary">₱{{ number_format($product->unit_price, 2) }}</strong>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2">No featured products yet. <a href="{{ route('products.create') }}">Add your first product</a>.</p>
            </div>
        @endforelse
    </div>
@endsection
