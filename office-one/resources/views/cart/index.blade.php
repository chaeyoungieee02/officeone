@extends('layouts.app')

@section('title', 'Shopping Cart - OfficeOne')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Collection</a></li>
        <li class="breadcrumb-item active">Cart</li>
    </ol>
</nav>

@if($cartItems->count() > 0)
    {{-- Cart Header --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold">Your cart total is ₱{{ number_format($total, 2) }}</h3>
        <p class="text-muted mb-3">Free shipping and return</p>
        <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-dark px-4 py-2"
                    onclick="return confirm('Confirm your purchase of ₱{{ number_format($total, 2) }}?')">
                Check out
            </button>
        </form>
    </div>

    <hr class="mb-4">

    {{-- Cart Items --}}
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @foreach($cartItems as $item)
            <div class="row align-items-center py-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                {{-- Product Image --}}
                <div class="col-auto">
                    @if($item->product->photos->count() > 0)
                        <a href="{{ route('products.show', $item->product) }}">
                            <img src="{{ asset('storage/' . $item->product->photos->first()->photo_path) }}"
                                 class="rounded" width="100" height="100" style="object-fit:cover;"
                                 alt="{{ $item->product->name }}">
                        </a>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded"
                             style="width:100px;height:100px;">
                            <i class="bi bi-box-seam text-muted" style="font-size:2.5rem;"></i>
                        </div>
                    @endif
                </div>

                {{-- Product Info --}}
                <div class="col">
                    <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none text-dark">
                        <h5 class="fw-bold mb-1">{{ $item->product->name }}</h5>
                    </a>

                    <div class="d-flex align-items-center gap-3 mt-2">
                        {{-- Quantity --}}
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="quantity" class="form-select form-select-sm" style="width:70px;"
                                    onchange="this.form.submit()">
                                @for($q = 1; $q <= 20; $q++)
                                    <option value="{{ $q }}" {{ $item->quantity == $q ? 'selected' : '' }}>{{ $q }}</option>
                                @endfor
                            </select>
                        </form>

                        {{-- Category badge --}}
                        <span class="badge {{ $item->product->category == 'Product' ? 'bg-primary' : 'bg-info' }}">
                            {{ $item->product->category }}
                        </span>
                    </div>

                    <div class="mt-2 small text-muted">
                        <div><i class="bi bi-tag me-1"></i> {{ $item->product->item_code }}</div>
                        @if($item->product->brand)
                            <div><i class="bi bi-building me-1"></i> {{ $item->product->brand }}</div>
                        @endif
                    </div>
                </div>

                {{-- Price & Remove --}}
                <div class="col-auto text-end">
                    <div class="fw-bold fs-5 mb-2">₱{{ number_format($item->subtotal, 2) }}</div>
                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 small"
                                onclick="return confirm('Remove this item from cart?')">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            {{-- Totals --}}
            <hr class="mt-4 mb-3">
            <div class="row justify-content-end">
                <div class="col-md-5 col-lg-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">VAT</span>
                        <span>₱{{ number_format($vat, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-4 mb-4">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark w-100 py-2"
                                onclick="return confirm('Confirm your purchase of ₱{{ number_format($total, 2) }}?')">
                            Check out
                        </button>
                    </form>
                    <form action="{{ route('cart.clear') }}" method="POST" class="text-center mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none small"
                                onclick="return confirm('Clear your entire cart?')">
                            <i class="bi bi-x-circle me-1"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">Your cart is empty</h4>
        <p class="text-muted">Browse our products and add items to your cart.</p>
        <a href="{{ route('products.index') }}" class="btn btn-dark px-4">
            <i class="bi bi-box-seam me-1"></i> Browse Products
        </a>
    </div>
@endif
@endsection
