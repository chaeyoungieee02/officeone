@extends('layouts.app')

@section('title', $product->name . ' - OfficeOne')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Collection</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-5">
    {{-- Product Image Gallery --}}
    <div class="col-lg-6">
        <div class="position-relative">
            @if($product->photos->count() > 0)
                <div class="mb-3">
                    <img id="mainProductImage"
                         src="{{ asset('storage/' . $product->photos->first()->photo_path) }}"
                         class="img-fluid rounded shadow-sm w-100"
                         style="max-height: 450px; object-fit: cover; cursor: zoom-in;"
                         alt="{{ $product->name }}"
                         onclick="window.open(this.src, '_blank')">
                </div>
                @if($product->photos->count() > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($product->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                             class="rounded border thumbnail-img {{ $loop->first ? 'border-primary border-2' : '' }}"
                             style="width: 75px; height: 75px; object-fit: cover; cursor: pointer;"
                             alt="{{ $product->name }}"
                             onclick="selectThumbnail(this, '{{ asset('storage/' . $photo->photo_path) }}')">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm"
                     style="height: 400px;">
                    <div class="text-center text-muted">
                        <i class="bi bi-image" style="font-size: 5rem;"></i>
                        <p class="mt-2">No photos available</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Product Info --}}
    <div class="col-lg-6">
        <div class="mb-2">
            <span class="badge {{ $product->category == 'Product' ? 'bg-primary' : 'bg-info' }} mb-2">
                {{ $product->category }}
            </span>
            @if($product->brand)
                <span class="text-primary fw-semibold">{{ $product->brand }}</span>
            @endif
        </div>

        <h2 class="fw-bold mb-1">{{ $product->name }}</h2>

        @if($product->type)
            <p class="text-muted mb-3">{{ $product->type }}</p>
        @endif

        {{-- Star Ratings --}}
        @if($product->reviews->count() > 0)
            @php $avg = round($product->averageRating(), 1); @endphp
            <div class="d-flex align-items-center gap-2 mb-3">
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($avg))
                            <i class="bi bi-star-fill text-warning"></i>
                        @elseif($i - $avg < 1 && $i - $avg > 0)
                            <i class="bi bi-star-half text-warning"></i>
                        @else
                            <i class="bi bi-star text-warning"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-muted">{{ $avg }}/5</span>
                <a href="#reviews" class="text-decoration-none small">See {{ $product->reviews->count() }} review{{ $product->reviews->count() > 1 ? 's' : '' }}</a>
            </div>
        @endif

        {{-- Price --}}
        <div class="mb-4">
            <span class="fs-2 fw-bold text-success">₱{{ number_format($product->unit_price, 2) }}</span>
            @if($product->unit)
                <span class="text-muted ms-2">per {{ $product->unit }}</span>
            @endif
        </div>

        {{-- Status --}}
        @if(!$product->is_active)
            <div class="alert alert-warning py-2 mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i> This product is currently unavailable.
            </div>
        @endif

        {{-- Description --}}
        @if($product->description)
            <div class="mb-4">
                <p class="text-muted lh-lg">{{ $product->description }}</p>
            </div>
        @endif

        <hr>

        {{-- Item Code / Details --}}
        <div class="mb-4">
            <div class="row g-2">
                <div class="col-6">
                    <small class="text-muted d-block">Item Code</small>
                    <span class="fw-semibold">{{ $product->item_code }}</span>
                </div>
                @if($product->brand)
                <div class="col-6">
                    <small class="text-muted d-block">Brand</small>
                    <span class="fw-semibold">{{ $product->brand }}</span>
                </div>
                @endif
                @if($product->unit)
                <div class="col-6">
                    <small class="text-muted d-block">Unit</small>
                    <span class="fw-semibold">{{ $product->unit }}</span>
                </div>
                @endif
                <div class="col-6">
                    <small class="text-muted d-block">Category</small>
                    <span class="fw-semibold">{{ $product->category }}</span>
                </div>
            </div>
        </div>

        {{-- Add to Cart --}}
        @if($product->is_active)
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <div class="d-flex align-items-end gap-3 mb-3">
                    <div>
                        <label for="quantity" class="form-label small text-muted mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="99"
                               class="form-control" style="width: 90px;">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg flex-grow-1 py-2">
                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                    </button>
                </div>
            </form>
        @else
            <button class="btn btn-secondary btn-lg w-100 py-2" disabled>
                <i class="bi bi-cart-x me-2"></i> Unavailable
            </button>
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">
            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
        </a>
    </div>
</div>

{{-- Reviews Section --}}
<div class="card border-0 shadow-sm mt-5" id="reviews">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Reviews</h4>

        @php
            $reviewsList = $product->reviews()->with('user')->latest()->get();
            $reviewCount = $reviewsList->count();
            $avg = $reviewCount > 0 ? round($product->averageRating(), 1) : 0;
            $ratingDist = [];
            for ($r = 5; $r >= 1; $r--) {
                $ratingDist[$r] = $reviewsList->where('rating', $r)->count();
            }
        @endphp

        @if($reviewCount > 0)
        {{-- Rating Overview Panel --}}
        <div class="row g-4 mb-4 pb-4 border-bottom">
            {{-- Big average rating --}}
            <div class="col-md-3 text-center text-md-start">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <span class="display-3 fw-bold lh-1">{{ $avg }}</span>
                    <div class="mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avg))
                                <i class="bi bi-star-fill" style="color: #7c3aed;"></i>
                            @elseif($i - $avg < 1 && $i - $avg > 0)
                                <i class="bi bi-star-half" style="color: #7c3aed;"></i>
                            @else
                                <i class="bi bi-star" style="color: #7c3aed;"></i>
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted mt-1">{{ $reviewCount }} rating{{ $reviewCount !== 1 ? 's' : '' }}</small>
                </div>
            </div>

            {{-- Rating distribution bars --}}
            <div class="col-md-5">
                @for($r = 5; $r >= 1; $r--)
                <div class="d-flex align-items-center mb-1">
                    <span class="text-muted me-2" style="width: 24px; font-size: 0.85rem;">{{ $r }}.0</span>
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 10px; border-radius: 5px; background: #e9ecef;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $reviewCount > 0 ? ($ratingDist[$r] / $reviewCount * 100) : 0 }}%; background: #7c3aed; border-radius: 5px;">
                            </div>
                        </div>
                    </div>
                    <span class="text-muted ms-2 small" style="width: 75px;">{{ number_format($ratingDist[$r]) }} review{{ $ratingDist[$r] !== 1 ? 's' : '' }}</span>
                </div>
                @endfor
            </div>
        </div>
        @endif

        {{-- Review Form --}}
        @auth
            @php
                $existingReview = auth()->user()->reviews()->where('product_id', $product->id)->first();
                $hasDelivered = auth()->user()->hasDeliveredOrderFor($product->id);
            @endphp

            @if($hasDelivered && !$existingReview)
                <div class="rounded-3 border p-4 mb-4" style="background: #fafafa;">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Write a Review</h6>
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted">Rating <span class="text-danger">*</span></label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}"
                                           {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label small text-muted">Comment <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                      name="comment" id="comment" rows="3" minlength="10" maxlength="1000"
                                      placeholder="Share your experience with this product..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min 10 characters. Profanity will be filtered.</small>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i> Submit Review
                        </button>
                    </form>
                </div>
            @elseif($existingReview)
                <div class="rounded-3 border border-primary p-4 mb-4" style="background: #f5f3ff;">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Update Your Review</h6>
                    <form action="{{ route('reviews.update', $existingReview) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small text-muted">Rating <span class="text-danger">*</span></label>
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="edit-star{{ $i }}"
                                           {{ old('rating', $existingReview->rating) == $i ? 'checked' : '' }} required>
                                    <label for="edit-star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-comment" class="form-label small text-muted">Comment <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                      name="comment" id="edit-comment" rows="3" minlength="10" maxlength="1000"
                                      required>{{ old('comment', $existingReview->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min 10 characters. Profanity will be filtered.</small>
                        </div>
                        <button type="submit" class="btn text-white px-4" style="background: #7c3aed;">
                            <i class="bi bi-check-lg me-1"></i> Update Review
                        </button>
                    </form>
                </div>
            @elseif(!$hasDelivered)
                <div class="alert alert-light border mb-4">
                    <i class="bi bi-info-circle me-1 text-primary"></i> You can only review this product once your order has been delivered.
                </div>
            @endif
        @endauth

        {{-- Individual Reviews --}}
        @forelse($reviewsList as $review)
            <div class="review-item d-flex gap-3 {{ !$loop->first ? 'pt-3' : '' }} {{ !$loop->last ? 'pb-3 border-bottom' : 'pb-1' }}">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    @if($review->user->profile_photo)
                        <img src="{{ asset('storage/' . $review->user->profile_photo) }}"
                             class="rounded-circle shadow-sm" width="42" height="42" style="object-fit:cover;"
                             alt="{{ $review->user->name }}">
                    @else
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm"
                              style="width:42px; height:42px; font-size:1rem; background: #7c3aed;">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Review Content --}}
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                        <div>
                            <span class="fw-semibold">{{ $review->user->name }}</span>
                            @if(auth()->id() === $review->user_id)
                                <span class="badge rounded-pill text-white ms-1" style="background: #7c3aed; font-size: 0.7rem;">You</span>
                            @endif
                            <small class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-1" style="color: #7c3aed;">{{ $review->rating }}.0</span>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 0.8rem; color: {{ $i <= $review->rating ? '#7c3aed' : '#d1d5db' }};"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-secondary" style="line-height: 1.6;">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-chat-left" style="font-size: 2.5rem; color: #d1d5db;"></i>
                <p class="text-muted mt-2 mb-0">No reviews yet. Be the first to review this product!</p>
            </div>
        @endforelse

        {{-- Read all reviews link --}}
        @if($reviewCount > 5)
            <div class="mt-3">
                <a href="#" class="text-decoration-none fw-semibold" style="color: #7c3aed;">
                    Read all reviews <i class="bi bi-chevron-down"></i>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 2px;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.15s;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }
    .thumbnail-img {
        opacity: 0.6;
        transition: opacity 0.2s, border-color 0.2s;
    }
    .thumbnail-img:hover,
    .thumbnail-img.active {
        opacity: 1;
        border-color: #0d6efd !important;
        border-width: 2px !important;
    }
    .review-item {
        transition: background 0.2s ease;
        border-radius: 8px;
        padding-left: 8px;
        padding-right: 8px;
    }
    .review-item:hover {
        background: #f9fafb;
    }
    .progress-bar {
        transition: width 1s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
function selectThumbnail(el, src) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active', 'border-primary', 'border-2');
        img.style.opacity = '0.6';
    });
    el.classList.add('active', 'border-primary', 'border-2');
    el.style.opacity = '1';
}
</script>
@endpush
