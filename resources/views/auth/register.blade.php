@extends('layouts.app')

@section('title', 'Register | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Create Your Account</h2>
        <p>Join HappyStem to order and track your flower deliveries.</p>
    </section>

    <section class="auth-wrap">
        <div class="auth-box">
            <h3>Register</h3>
            <p class="sub">Fill in your details to get started.</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Juan Dela Cruz" required>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0917-123-4567" required>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Municipality</label>
                    <select name="municipality" required>
                        <option value="">-- Select municipality --</option>
                        @foreach ($municipalities as $muni)
                            <option value="{{ $muni }}" @selected(old('municipality') === $muni)>{{ $muni }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Barangay &amp; Street Address</label>
                    <input type="text" name="street" value="{{ old('street') }}" placeholder="e.g. Zone 2, Brgy. Zone 1" required>
                </div>
                <div class="form-group" style="margin-bottom:15px;">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="At least 6 characters" required>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn" style="width:100%;text-align:center;"><i class="fas fa-user-plus"></i> Create Account</button>
            </form>

            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </div>
    </section>
@endsection
