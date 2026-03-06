@extends('layouts.app')

@section('title', 'My Profile - OfficeOne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-person-circle"></i> My Profile</h2>
    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Edit Profile
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body py-4">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                         class="rounded-circle img-thumbnail mb-3" width="180" height="180"
                         style="object-fit: cover;" alt="{{ $user->name }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d3b66&color=fff&size=180"
                         class="rounded-circle img-thumbnail mb-3" width="180" height="180"
                         style="object-fit: cover;" alt="{{ $user->name }}">
                @endif
                <h4>{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                @if($user->role === 'admin')
                    <span class="badge bg-danger fs-6"><i class="bi bi-shield-lock"></i> Admin</span>
                @else
                    <span class="badge bg-primary fs-6"><i class="bi bi-person"></i> User</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Account Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px;"><i class="bi bi-person"></i> Full Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-envelope"></i> Email Address</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-shield"></i> Role</th>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-primary">User</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar-plus"></i> Member Since</th>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar-check"></i> Last Updated</th>
                        <td>{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
