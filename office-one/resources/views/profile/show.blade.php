@extends('layouts.app')

@section('title', 'My Profile - OfficeOne')

@push('styles')
<style>
    /* ── animations ── */
    @keyframes profileFadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes profilePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(13,59,102,.35); }
        50%      { box-shadow: 0 0 0 14px rgba(13,59,102,0); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .profile-animate { animation: profileFadeInUp .6s ease both; }
    .profile-animate-delay-1 { animation-delay: .12s; }
    .profile-animate-delay-2 { animation-delay: .24s; }
    .profile-animate-delay-3 { animation-delay: .36s; }

    /* ── hero card ── */
    .profile-hero {
        background: linear-gradient(135deg, #0d3b66 0%, #1a5fa8 50%, #2980b9 100%);
        border-radius: 18px;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
        animation: shimmer 6s linear infinite;
        background-size: 200% 100%;
    }
    .profile-avatar-ring {
        width: 160px; height: 160px;
        border-radius: 50%;
        padding: 5px;
        background: linear-gradient(135deg, #ffd166, #f77f00, #d62828);
        display: inline-block;
        animation: profilePulse 2.5s ease-in-out infinite;
        transition: transform .35s ease;
    }
    .profile-avatar-ring:hover { transform: scale(1.06); }
    .profile-avatar-ring img {
        width: 100%; height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
    }
    .profile-role-badge {
        font-size: .85rem;
        padding: .45em 1em;
        border-radius: 50px;
        letter-spacing: .5px;
        backdrop-filter: blur(4px);
    }
    .profile-role-admin { background: rgba(220,53,69,.85); color: #fff; }
    .profile-role-user  { background: rgba(255,255,255,.2); color: #fff; border: 1px solid rgba(255,255,255,.35); }

    /* ── info cards ── */
    .info-card {
        border: none;
        border-radius: 14px;
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,.1) !important;
    }
    .info-icon-box {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        transition: transform .25s ease;
    }
    .info-card:hover .info-icon-box { transform: scale(1.12); }
    .info-label { font-size: .78rem; text-transform: uppercase; letter-spacing: .6px; color: #6c757d; margin-bottom: 2px; }
    .info-value { font-size: 1.05rem; font-weight: 600; color: #212529; }

    /* ── quick-stat pills ── */
    .quick-stat {
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        border: none;
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .quick-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,.1) !important;
    }
    .quick-stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
    }
</style>
<style>
    /* Ensure Edit Profile button is always clickable */
    .profile-edit-btn { z-index: 9999 !important; pointer-events: auto !important; position: relative; }
</style>
@endpush

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3 profile-animate">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active">My Profile</li>
    </ol>
</nav>

{{-- ════════ HERO CARD ════════ --}}
<div class="profile-hero text-white text-center p-5 mb-4 shadow profile-animate">
    <div class="position-relative d-inline-block mb-3">
        <div class="profile-avatar-ring">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d3b66&color=fff&size=180" alt="{{ $user->name }}">
            @endif
        </div>
    </div>
    <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
    <p class="mb-3 opacity-75"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
    @if($user->role === 'admin')
        <span class="profile-role-badge profile-role-admin"><i class="bi bi-shield-lock me-1"></i>Administrator</span>
    @else
        <span class="profile-role-badge profile-role-user"><i class="bi bi-person me-1"></i>Member</span>
    @endif
    <div class="mt-4">
        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm profile-edit-btn" style="z-index:9999; pointer-events:auto; position:relative;">
            <i class="bi bi-pencil-square me-1"></i> Edit Profile
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- ════════ ACCOUNT INFO ════════ --}}
    <div class="col-lg-7 profile-animate profile-animate-delay-1">
        <div class="card info-card shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Account Information</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background:#f0f4f8;">
                    <div class="info-icon-box bg-primary text-white me-3">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background:#f0f4f8;">
                    <div class="info-icon-box bg-success text-white me-3">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background:#f0f4f8;">
                    <div class="info-icon-box {{ $user->role === 'admin' ? 'bg-danger' : 'bg-info' }} text-white me-3">
                        <i class="bi bi-shield-fill"></i>
                    </div>
                    <div>
                        <div class="info-label">Role</div>
                        <div class="info-value">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger rounded-pill px-3">Admin</span>
                            @else
                                <span class="badge bg-primary rounded-pill px-3">User</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background:#f0f4f8;">
                    <div class="info-icon-box bg-warning text-white me-3">
                        <i class="bi bi-calendar-plus-fill"></i>
                    </div>
                    <div>
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center p-3 rounded-3" style="background:#f0f4f8;">
                    <div class="info-icon-box text-white me-3" style="background:#6f42c1;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ $user->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ════════ QUICK STATS ════════ --}}
    <div class="col-lg-5 profile-animate profile-animate-delay-2">
        @if($user->role !== 'admin')
        <div class="row g-3">
            <div class="col-6">
                <div class="card quick-stat shadow-sm text-center">
                    <div class="quick-stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->orders()->count() }}</h4>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card quick-stat shadow-sm text-center">
                    <div class="quick-stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->orders()->where('status', 'completed')->count() }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card quick-stat shadow-sm text-center">
                    <div class="quick-stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->reviews()->count() }}</h4>
                    <small class="text-muted">Reviews</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card quick-stat shadow-sm text-center">
                    <div class="quick-stat-icon bg-info bg-opacity-10 text-info mx-auto mb-2">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $user->cartItems()->sum('quantity') }}</h4>
                    <small class="text-muted">In Cart</small>
                </div>
            </div>
        </div>

        {{-- Activity snapshot --}}
        <div class="card info-card shadow-sm mt-3 profile-animate profile-animate-delay-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-activity text-primary me-2"></i>Activity</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Account age</span>
                    <span class="fw-semibold">{{ $user->created_at->diffForHumans(null, true) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Email verified</span>
                    <span class="fw-semibold">
                        @if($user->email_verified_at)
                            <i class="bi bi-patch-check-fill text-success"></i> {{ $user->email_verified_at->format('M d, Y') }}
                        @else
                            <i class="bi bi-x-circle-fill text-danger"></i> Not verified
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Status</span>
                    <span class="badge bg-success rounded-pill px-3"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Active</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
