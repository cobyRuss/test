@extends('layouts.app')

@section('title', 'Reset Password | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Reset Password</h2>
    </section>

    <section class="auth-wrap">
        <div class="auth-box">
            @if ($error)
                <div class="alert alert-error">{{ $error }}</div>
                <div class="auth-links">
                    <a href="{{ route('forgot') }}">Request a new reset link</a>
                </div>
            @else
                <h3>Set New Password</h3>
                @if ($customer)
                    <p class="sub">Hello {{ $customer->full_name }}! Choose a new password for your account.</p>
                @endif

                @if ($success)
                    <div class="alert alert-success">{{ $success }}</div>
                    <div class="auth-links">
                        <a href="{{ route('login') }}" class="btn" style="text-decoration:none;"><i class="fas fa-sign-in-alt"></i> Login Now</a>
                    </div>
                @else
                    <form action="{{ route('reset.submit', ['token' => $token]) }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom:15px;">
                            <label>New Password</label>
                            <input type="password" name="password" placeholder="At least 6 characters" required autofocus>
                        </div>
                        <div class="form-group" style="margin-bottom:20px;">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Repeat new password" required>
                        </div>
                        <button type="submit" class="btn" style="width:100%;text-align:center;">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </section>
@endsection
