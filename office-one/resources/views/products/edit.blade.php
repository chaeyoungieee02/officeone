@extends('layouts.app')

@section('title', 'Edit Product/Service - OfficeOne')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Product / Service</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="item_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('item_code') is-invalid @enderror"
                                   id="item_code" name="item_code" value="{{ old('item_code', $product->item_code) }}" required>
                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror"
                                    id="category" name="category" required>
                                <option value="">-- Select Category --</option>
                                <option value="Product" {{ old('category', $product->category) == 'Product' ? 'selected' : '' }}>Product</option>
                                <option value="Service" {{ old('category', $product->category) == 'Service' ? 'selected' : '' }}>Service</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                   id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror"
                                   id="type" name="type" value="{{ old('type', $product->type) }}"
                                   placeholder="e.g. Office Supplies">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                   id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                                   placeholder="e.g. pcs, box, ream">
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="unit_price" class="form-label">Unit Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" min="0"
                                       class="form-control @error('unit_price') is-invalid @enderror"
                                       id="unit_price" name="unit_price"
                                       value="{{ old('unit_price', $product->unit_price) }}" required>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Existing Photos -->
                    @if($product->photos->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Current Photos</label>
                        <div class="row">
                            @foreach($product->photos as $photo)
                            <div class="col-3 mb-2 text-center position-relative" id="photo-{{ $photo->id }}">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                     class="img-thumbnail" style="height:120px;object-fit:cover;width:100%;">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox"
                                           name="delete_photos[]" value="{{ $photo->id }}"
                                           id="del-photo-{{ $photo->id }}">
                                    <label class="form-check-label text-danger small" for="del-photo-{{ $photo->id }}">
                                        <i class="bi bi-trash"></i> Remove
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="photos" class="form-label">Add More Photos</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                               id="photos" name="photos[]" multiple accept="image/*">
                        <small class="text-muted">You can select multiple images. Max 2MB each (JPEG, PNG, GIF, WebP).</small>
                        @error('photos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Image Preview -->
                    <div id="image-preview" class="row mb-3"></div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active"
                                   name="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('photos').addEventListener('change', function (e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function (event) {
                const col = document.createElement('div');
                col.className = 'col-3 mb-2';
                col.innerHTML = '<img src="' + event.target.result + '" class="img-thumbnail" style="height:120px;object-fit:cover;width:100%;">';
                preview.appendChild(col);
            };
            reader.readAsDataURL(files[i]);
        }
    });
</script>
@endpush
