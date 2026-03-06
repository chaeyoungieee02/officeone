@extends('layouts.app')

@section('title', 'Edit Profile - OfficeOne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-pencil-square"></i> Edit Profile</h2>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Profile
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3"><i class="bi bi-lock"></i> Change Password</h5>
                    <p class="text-muted small">Leave blank if you don't want to change your password.</p>

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Profile Photo -->
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <div class="text-center mb-2">
                            @if($user->profile_photo)
                                <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                     class="rounded-circle img-thumbnail" width="150" height="150"
                                     style="object-fit: cover;" alt="{{ $user->name }}">
                            @else
                                <img id="photo-preview"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d3b66&color=fff&size=150"
                                     class="rounded-circle img-thumbnail" width="150" height="150"
                                     style="object-fit: cover;" alt="{{ $user->name }}">
                            @endif
                        </div>
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                               id="profile_photo" name="profile_photo" accept="image/*">
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max 2MB. Formats: JPG, PNG, GIF</small>

                        @if($user->profile_photo)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_photo" name="remove_photo" value="1">
                                <label class="form-check-label text-danger" for="remove_photo">
                                    <i class="bi bi-trash"></i> Remove current photo
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr>
            <div class="text-end">
                <a href="{{ route('profile.show') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
