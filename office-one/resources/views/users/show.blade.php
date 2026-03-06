@extends('layouts.app')

@section('title', $user->name . ' - OfficeOne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-person"></i> User Details</h2>
    <div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>
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
                @if($user->is_active)
                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Active</span>
                @else
                    <span class="badge bg-secondary fs-6"><i class="bi bi-x-circle"></i> Inactive</span>
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
                        <th><i class="bi bi-toggle-on"></i> Status</th>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar-plus"></i> Created</th>
                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar-check"></i> Last Updated</th>
                        <td>{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @if($user->email_verified_at)
                    <tr>
                        <th><i class="bi bi-patch-check"></i> Email Verified</th>
                        <td>{{ $user->email_verified_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if(auth()->id() !== $user->id)
        <div class="card shadow-sm mt-3 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Danger Zone</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <p class="mb-2">Permanently delete this user account and all associated data.</p>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
