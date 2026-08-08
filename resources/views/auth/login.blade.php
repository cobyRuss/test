@extends('layouts.app')

@section('title', 'Login | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Welcome Back</h2>
        <p>Login to your HappyStem account.</p>
    </section>

    <section class="auth-wrap">
        <div class="auth-box">
            <h3>Login</h3>
            <p class="sub">Enter your credentials to continue.</p>

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required autofocus>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Your password" required>
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-size:0.88rem;margin-bottom:20px;color:var(--dark);">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
                <button type="submit" class="btn" style="width:100%;text-align:center;"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('forgot') }}">Forgot password?</a><br>
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        </div>
    </section>
@endsection
