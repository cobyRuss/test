@extends('layouts.app')

@section('title', 'Forgot Password | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Reset Password</h2>
    </section>

    <section class="auth-wrap">
        <div class="auth-box">
            @if ($step === 1)
                <h3>Forgot Password</h3>
                <p class="sub">Enter your email address to receive a verification code.</p>

                @if ($error)
                    <div class="alert alert-error">{{ $error }}</div>
                @endif
                @if ($success)
                    <div class="alert alert-success">{{ $success }}</div>
                @endif

                <form action="{{ route('forgot') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="you@email.com" required autofocus>
                    </div>
                    <button type="submit" name="send_code" value="1" class="btn" style="width:100%;text-align:center;">
                        <i class="fas fa-paper-plane"></i> Send Verification Code
                    </button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('login') }}">&larr; Back to login</a>
                </div>

            @elseif ($step === 2)
                <h3>Enter Verification Code</h3>
                <p class="sub">A 6-digit code was sent to your email.</p>

                @if ($error)
                    <div class="alert alert-error">{{ $error }}</div>
                @endif
                @if ($success)
                    <div class="alert alert-success">{{ $success }}</div>
                @endif

                <form action="{{ route('forgot') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>Verification Code</label>
                        <input type="text" name="code" maxlength="6" placeholder="000000" required autofocus>
                    </div>
                    @if ($resetCode)
                        <div class="alert alert-info">Demo mode: your code is <strong>{{ $resetCode }}</strong></div>
                    @endif
                    <button type="submit" name="verify_code" value="1" class="btn" style="width:100%;text-align:center;">
                        <i class="fas fa-check"></i> Verify Code
                    </button>
                </form>

            @elseif ($step === 3)
                <h3>Set New Password</h3>
                @if ($resetName)
                    <p class="sub">Hello {{ $resetName }}! Choose a new password for your account.</p>
                @endif

                @if ($error)
                    <div class="alert alert-error">{{ $error }}</div>
                @endif

                <form action="{{ route('forgot') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>New Password</label>
                        <input type="password" name="password" placeholder="At least 6 characters" required autofocus>
                    </div>
                    <div class="form-group" style="margin-bottom:20px;">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Repeat new password" required>
                    </div>
                    <button type="submit" name="reset_password" value="1" class="btn" style="width:100%;text-align:center;">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>

            @else
                <h3>Password Reset Successful</h3>
                @if ($success)
                    <div class="alert alert-success">{{ $success }}</div>
                @endif
                <div class="auth-links">
                    <a href="{{ route('login') }}" class="btn" style="text-decoration:none;"><i class="fas fa-sign-in-alt"></i> Login Now</a>
                </div>
            @endif
        </div>
    </section>
@endsection
