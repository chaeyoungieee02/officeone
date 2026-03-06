@extends('layouts.app')

@section('title', 'Verify Email - OfficeOne')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0"><i class="bi bi-envelope-check"></i> Verify Your Email Address</h4>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-envelope-paper" style="font-size: 4rem; color: #0d3b66;"></i>
                </div>

                <h5 class="mb-3">Almost there!</h5>
                <p class="text-muted mb-4">
                    We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
                    <br>Please check your inbox and click the verification link to activate your account.
                </p>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <p class="text-muted small mb-3">Didn't receive the email? Check your spam folder or request a new one.</p>

                <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Resend Verification Email
                    </button>
                </form>

                <hr class="my-4">

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
