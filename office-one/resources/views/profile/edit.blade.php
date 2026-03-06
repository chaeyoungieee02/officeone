@extends('layouts.app')

@section('title', 'Edit Profile - OfficeOne')

@push('styles')
<style>
    @keyframes editFadeInUp {
        from { opacity: 0; transform: translateY(25px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .edit-animate { animation: editFadeInUp .55s ease both; }
    .edit-animate-delay-1 { animation-delay: .1s; }
    .edit-animate-delay-2 { animation-delay: .2s; }

    .edit-hero {
        background: linear-gradient(135deg, #0d3b66 0%, #1a5fa8 50%, #2980b9 100%);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }
    .edit-hero::before {
        content: '';
        position: absolute;
        top: -30%; right: -20%;
        width: 350px; height: 350px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }

    .avatar-upload-wrapper {
        position: relative;
        display: inline-block;
        transition: transform .3s ease;
    }
    .avatar-upload-wrapper:hover { transform: scale(1.04); }
    .avatar-upload-ring {
        width: 150px; height: 150px;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, #ffd166, #f77f00, #d62828);
        display: inline-block;
    }
    .avatar-upload-ring img {
        width: 100%; height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
    }
    .avatar-overlay {
        position: absolute;
        bottom: 8px; right: 8px;
        background: #0d3b66;
        color: #fff;
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        border: 3px solid #fff;
        cursor: pointer;
        transition: background .25s ease, transform .25s ease;
    }
    .avatar-overlay:hover { background: #1a5fa8; transform: scale(1.1); }

    .edit-card {
        border: none;
        border-radius: 16px;
        transition: box-shadow .3s ease;
    }
    .edit-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,.08) !important; }

    .form-control, .form-select {
        border-radius: 10px;
        padding: .6rem 1rem;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .form-control:focus {
        border-color: #1a5fa8;
        box-shadow: 0 0 0 .2rem rgba(26,95,168,.15);
    }
    .form-label { font-weight: 600; font-size: .88rem; color: #344054; }

    .section-divider {
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%; left: 0;
        width: 100%; height: 1px;
        background: #dee2e6;
    }
    .section-divider span {
        position: relative;
        background: #fff;
        padding: 0 1rem;
        font-weight: 600;
        color: #6c757d;
        font-size: .9rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #0d3b66, #1a5fa8);
        border: none;
        border-radius: 10px;
        padding: .65rem 2rem;
        font-weight: 600;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(13,59,102,.3);
    }
</style>
@endpush

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3 edit-animate">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" class="text-decoration-none">My Profile</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

{{-- ════════ HERO HEADER ════════ --}}
<div class="edit-hero text-white p-4 mb-4 shadow edit-animate">
    <div class="d-flex align-items-center flex-wrap gap-3">
        <div class="avatar-upload-wrapper">
            <div class="avatar-upload-ring">
                @if($user->profile_photo)
                    <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                @else
                    <img id="photo-preview"
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d3b66&color=fff&size=150"
                         alt="{{ $user->name }}">
                @endif
            </div>
            <label for="profile_photo" class="avatar-overlay" title="Change photo">
                <i class="bi bi-camera-fill"></i>
            </label>
        </div>
        <div>
            <h4 class="fw-bold mb-1">Edit Your Profile</h4>
            <p class="mb-0 opacity-75">Update your personal information and photo</p>
        </div>
        <div class="ms-auto d-none d-md-block">
            <a href="{{ route('profile.show') }}" class="btn btn-outline-light rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </div>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- ════════ PERSONAL INFO ════════ --}}
        <div class="col-lg-7 edit-animate edit-animate-delay-1">
            <div class="card edit-card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-vcard text-primary me-2"></i>Personal Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="bi bi-person me-1"></i>Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="section-divider">
                        <span><i class="bi bi-lock me-1"></i>Change Password</span>
                    </div>
                    <p class="text-muted small text-center mb-3">Leave blank if you don't want to change your password.</p>

                    <div class="mb-3">
                        <label for="current_password" class="form-label"><i class="bi bi-key me-1"></i>Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" placeholder="Enter current password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="New password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label"><i class="bi bi-lock-fill me-1"></i>Confirm Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════ PHOTO & ACTIONS ════════ --}}
        <div class="col-lg-5 edit-animate edit-animate-delay-2">
            <div class="card edit-card shadow-sm mb-3">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3"><i class="bi bi-camera text-primary me-2"></i>Profile Photo</h5>
                    <p class="text-muted small mb-3">Click the camera icon on the avatar or use the button below</p>
                    <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                           id="profile_photo" name="profile_photo" accept="image/*">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">Max 2MB &middot; JPG, PNG, GIF</small>

                    @if($user->profile_photo)
                        <div class="form-check mt-3 d-inline-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" id="remove_photo" name="remove_photo" value="1">
                            <label class="form-check-label text-danger small" for="remove_photo">
                                <i class="bi bi-trash me-1"></i> Remove current photo
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card edit-card shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Tips</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Use a clear, recent photo</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Use a valid email you can access</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Choose a strong password (8+ chars)</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Keep your info up to date</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════ SAVE BAR ════════ --}}
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3 edit-animate edit-animate-delay-2">
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4 d-md-none">
            <i class="bi bi-arrow-left me-1"></i> Cancel
        </a>
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4 d-none d-md-inline-block">
            Cancel
        </a>
        <button type="submit" class="btn btn-primary btn-save text-white">
            <i class="bi bi-check-lg me-1"></i> Save Changes
        </button>
    </div>
</form>
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
