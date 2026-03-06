@extends('layouts.app')

@section('title', 'Shop - OfficeOne')

@section('content')
{{-- Hero Section --}}
<div class="hero-banner text-center">
    <h1><i class="bi bi-shop"></i> OfficeOne Collection</h1>
    <p class="mb-0">Discover quality office supplies and services for your workspace</p>
</div>

{{-- Search & Filter --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search products..."
                   value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-3">
        <select id="categoryFilter" class="form-select">
            <option value="">All Categories</option>
            <option value="Product" {{ request('category') == 'Product' ? 'selected' : '' }}>Products</option>
            <option value="Service" {{ request('category') == 'Service' ? 'selected' : '' }}>Services</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="sortSelect" class="form-select">
            <option value="name_asc">Name (A-Z)</option>
            <option value="name_desc">Name (Z-A)</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
            <option value="newest">Newest First</option>
        </select>
    </div>
</div>

{{-- Products Grid --}}
<div class="row g-4" id="productsGrid">
    @forelse($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 product-card-wrapper"
         data-name="{{ strtolower($product->name) }}"
         data-category="{{ $product->category }}"
         data-price="{{ $product->unit_price }}"
         data-date="{{ $product->created_at->timestamp }}">
        <div class="card h-100 shadow-sm border-0 product-card">
            {{-- Product Image --}}
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                @if($product->photos->count() > 0)
                    <img src="{{ asset('storage/' . $product->photos->first()->photo_path) }}"
                         class="card-img-top" style="height: 200px; object-fit: cover;"
                         alt="{{ $product->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
            </a>

            <div class="card-body d-flex flex-column">
                {{-- Category Badge --}}
                <div class="mb-2">
                    <span class="badge {{ $product->category == 'Product' ? 'bg-primary' : 'bg-info' }} badge-sm">
                        {{ $product->category }}
                    </span>
                    @if($product->brand)
                        <small class="text-muted ms-1">{{ $product->brand }}</small>
                    @endif
                </div>

                {{-- Product Name --}}
                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                    <h6 class="card-title fw-bold mb-1">{{ $product->name }}</h6>
                </a>

                {{-- Rating --}}
                @if($product->reviews->count() > 0)
                    @php $avg = round($product->averageRating(), 1); @endphp
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avg))
                                <i class="bi bi-star-fill text-warning" style="font-size:0.8rem;"></i>
                            @elseif($i - $avg < 1 && $i - $avg > 0)
                                <i class="bi bi-star-half text-warning" style="font-size:0.8rem;"></i>
                            @else
                                <i class="bi bi-star text-warning" style="font-size:0.8rem;"></i>
                            @endif
                        @endfor
                        <small class="text-muted">({{ $product->reviews->count() }})</small>
                    </div>
                @else
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star text-muted" style="font-size:0.8rem;"></i>
                        @endfor
                        <small class="text-muted">(0)</small>
                    </div>
                @endif

                {{-- Price --}}
                <div class="mt-auto">
                    <span class="fs-5 fw-bold text-success">₱{{ number_format($product->unit_price, 2) }}</span>
                    @if($product->unit)
                        <small class="text-muted">/ {{ $product->unit }}</small>
                    @endif
                </div>
            </div>

            {{-- Add to Cart Footer --}}
            <div class="card-footer bg-white border-0 pb-3">
                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="99"
                           class="form-control form-control-sm" style="width:60px;">
                    <button type="submit" class="btn btn-success btn-sm flex-grow-1">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
            <h5 class="mt-3 text-muted">No products found</h5>
        </div>
    @endforelse
</div>

{{-- No Results Message (hidden by default) --}}
<div id="noResults" class="text-center py-5" style="display:none;">
    <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
    <h5 class="mt-3 text-muted">No products match your search</h5>
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
    }
    .product-card .card-img-top {
        transition: transform 0.3s;
    }
    .product-card:hover .card-img-top {
        transform: scale(1.03);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortSelect = document.getElementById('sortSelect');
    const grid = document.getElementById('productsGrid');
    const noResults = document.getElementById('noResults');

    function filterAndSort() {
        const search = searchInput.value.toLowerCase().trim();
        const category = categoryFilter.value;
        const cards = Array.from(document.querySelectorAll('.product-card-wrapper'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name;
            const cat = card.dataset.category;
            const matchesSearch = !search || name.includes(search);
            const matchesCategory = !category || cat === category;

            if (matchesSearch && matchesCategory) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort visible cards
        const sort = sortSelect.value;
        const visibleCards = cards.filter(c => c.style.display !== 'none');

        visibleCards.sort((a, b) => {
            switch(sort) {
                case 'name_asc': return a.dataset.name.localeCompare(b.dataset.name);
                case 'name_desc': return b.dataset.name.localeCompare(a.dataset.name);
                case 'price_asc': return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_desc': return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'newest': return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                default: return 0;
            }
        });

        // Re-order in DOM
        visibleCards.forEach(card => grid.appendChild(card));
        cards.filter(c => c.style.display === 'none').forEach(card => grid.appendChild(card));

        noResults.style.display = visibleCount === 0 ? '' : 'none';
    }

    searchInput.addEventListener('input', filterAndSort);
    categoryFilter.addEventListener('change', filterAndSort);
    sortSelect.addEventListener('change', filterAndSort);
});
</script>
@endpush
