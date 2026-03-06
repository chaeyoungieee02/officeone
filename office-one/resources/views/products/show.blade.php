@extends('layouts.app')

@section('title', $product->name . ' - OfficeOne')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-eye"></i> Product / Service Details</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width:140px;">Item Code</th>
                                <td><strong>{{ $product->item_code }}</strong></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Name</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Category</th>
                                <td>
                                    <span class="badge {{ $product->category == 'Product' ? 'bg-primary' : 'bg-info' }}">
                                        {{ $product->category }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Brand</th>
                                <td>{{ $product->brand ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Type</th>
                                <td>{{ $product->type ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width:140px;">Unit</th>
                                <td>{{ $product->unit ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Unit Price</th>
                                <td><strong class="text-success">₱{{ number_format($product->unit_price, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Created</th>
                                <td>{{ $product->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Updated</th>
                                <td>{{ $product->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($product->description)
                <div class="mb-4">
                    <h6 class="text-muted">Description</h6>
                    <p>{{ $product->description }}</p>
                </div>
                @endif

                <!-- Product Photos -->
                @if($product->photos->count() > 0)
                <div class="mb-4">
                    <h6 class="text-muted">Photos ({{ $product->photos->count() }})</h6>
                    <div class="row">
                        @foreach($product->photos as $photo)
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                     class="img-thumbnail w-100" style="height:160px;object-fit:cover;"
                                     alt="{{ $product->name }}">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center text-muted mb-4">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p>No photos uploaded for this product.</p>
                </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="card shadow-sm mt-4" id="reviews">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text"></i> Reviews
                    @if($product->reviews->count() > 0)
                        <span class="badge bg-light text-dark ms-1">{{ $product->reviews->count() }}</span>
                    @endif
                </h5>
                @if($product->reviews->count() > 0)
                    <div>
                        @php $avg = round($product->averageRating(), 1); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avg))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - $avg < 1 && $i - $avg > 0)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="text-white ms-1">{{ $avg }}/5</span>
                    </div>
                @endif
            </div>
            <div class="card-body">
                {{-- Review Form: only for users who purchased and haven't reviewed yet --}}
                @auth
                    @php
                        $existingReview = auth()->user()->reviews()->where('product_id', $product->id)->first();
                        $hasDelivered = auth()->user()->hasDeliveredOrderFor($product->id);
                    @endphp

                    @if($hasDelivered && !$existingReview)
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6><i class="bi bi-pencil-square"></i> Write a Review</h6>
                                <form action="{{ route('reviews.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Rating <span class="text-danger">*</span></label>
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
                                        <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('comment') is-invalid @enderror"
                                                  name="comment" id="comment" rows="3" minlength="10" maxlength="1000"
                                                  placeholder="Share your experience with this product..." required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Min 10 characters. Profanity will be filtered.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Submit Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($existingReview)
                        {{-- Edit existing review --}}
                        <div class="card bg-light mb-4 border-primary">
                            <div class="card-body">
                                <h6><i class="bi bi-pencil-square"></i> Update Your Review</h6>
                                <form action="{{ route('reviews.update', $existingReview) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Rating <span class="text-danger">*</span></label>
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
                                        <label for="edit-comment" class="form-label">Comment <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('comment') is-invalid @enderror"
                                                  name="comment" id="edit-comment" rows="3" minlength="10" maxlength="1000"
                                                  required>{{ old('comment', $existingReview->comment) }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Min 10 characters. Profanity will be filtered.</small>
                                    </div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-check-lg"></i> Update Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif(!$hasDelivered && !auth()->user()->isAdmin())
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-1"></i> You can only review this product once your order has been delivered.
                        </div>
                    @endif
                @endauth

                {{-- List of Reviews --}}
                @forelse($product->reviews()->with('user')->latest()->get() as $review)
                    <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="me-3">
                            @if($review->user->profile_photo)
                                <img src="{{ asset('storage/' . $review->user->profile_photo) }}"
                                     class="rounded-circle" width="45" height="45" style="object-fit:cover;"
                                     alt="{{ $review->user->name }}">
                            @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary text-white"
                                      style="width:45px;height:45px;font-size:1.1rem;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    @if(auth()->id() === $review->user_id)
                                        <span class="badge bg-info ms-1">You</span>
                                    @endif
                                    <br>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}" style="font-size:0.85rem;"></i>
                                    @endfor
                                    <small class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $review->user_id)
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('Delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Review">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <p class="mt-1 mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-chat-left" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endforelse
            </div>
        </div>
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
</style>
@endpush
