@extends('layouts.app')

@section('title', 'Register | HappyStem')

@section('content')
    <style>
        .hs-register-wrap { max-width: 1000px; margin: 40px auto 80px; padding: 0 20px; }
        .hs-register-split { display: flex; border-radius: 18px; overflow: hidden; box-shadow: 0 12px 40px rgba(90, 74, 74, 0.15); background: #fff; }

        .hs-register-brand { flex: 0 0 42%; padding: 45px 38px; color: #fff; display: flex; flex-direction: column; justify-content: center; gap: 22px; position: relative; overflow: hidden; }
        .hs-register-brand::after { content: ""; position: absolute; right: -60px; bottom: -60px; width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,0.12); }
        .hs-register-brand::before { content: ""; position: absolute; left: -40px; top: -40px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,0.1); }
        .hs-register-logo { display: flex; align-items: center; gap: 14px; }
        .hs-register-logo img { width: 74px; height: 74px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.85); box-shadow: 0 4px 14px rgba(0,0,0,0.15); }
        .hs-register-logo h1 { font-size: 1.7rem; margin: 0; line-height: 1.1; }
        .hs-register-logo p { margin: 0; font-size: 0.85rem; opacity: 0.92; }
        .hs-register-brand h2 { font-size: 1.5rem; margin: 0; line-height: 1.35; }
        .hs-register-brand .hs-register-tagline { font-size: 0.98rem; line-height: 1.6; opacity: 0.95; }
        .hs-register-perks { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 13px; }
        .hs-register-perks li { display: flex; align-items: center; gap: 12px; font-size: 0.93rem; }
        .hs-register-perks li i { width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }

        .hs-register-form { flex: 1; padding: 45px 48px; }
        .hs-register-form h3 { color: var(--secondary); font-size: 1.6rem; margin: 0 0 4px; }
        .hs-register-form .hs-sub { color: var(--dark); font-size: 0.92rem; margin: 0 0 24px; }

        .hs-field { margin-bottom: 15px; display: flex; flex-direction: column; }
        .hs-field label { color: var(--dark); font-weight: 600; font-size: 0.88rem; margin-bottom: 6px; }
        .hs-field input,
        .hs-field select { width: 100%; padding: 12px 14px; border: 1px solid #e5e5e5; border-radius: 10px; font-size: 0.95rem; background: #f9f9fb; color: var(--dark); transition: border-color 0.2s, box-shadow 0.2s; }
        .hs-field input:focus,
        .hs-field select:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(209, 123, 136, 0.15); }
        .hs-field input::placeholder { color: #b5b0b0; }

        .hs-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .hs-terms { display: flex; align-items: flex-start; gap: 9px; margin: 18px 0 20px; font-size: 0.86rem; color: var(--dark); }
        .hs-terms input { margin-top: 3px; accent-color: var(--accent); width: 15px; height: 15px; flex-shrink: 0; }
        .hs-terms a { color: var(--accent); font-weight: 600; text-decoration: none; }

        .hs-register-btn { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: linear-gradient(120deg, var(--accent), #c96a78); color: #fff; border: none; padding: 14px; border-radius: 30px; font-size: 1rem; font-weight: 700; cursor: pointer; box-shadow: 0 6px 18px rgba(209, 123, 136, 0.4); transition: var(--transition); }
        .hs-register-btn:hover { background: var(--secondary); transform: translateY(-2px); box-shadow: 0 8px 22px rgba(138, 155, 110, 0.4); }

        .hs-register-links { margin-top: 20px; text-align: center; font-size: 0.92rem; color: var(--dark); }
        .hs-register-links a { color: var(--accent); font-weight: 700; text-decoration: none; }
        .hs-register-links a:hover { text-decoration: underline; }

        .hs-brand-theme { background: linear-gradient(160deg, #e8b4bc 0%, #d17b88 55%, #8a9b6e 130%); }

        @media (max-width: 820px) {
            .hs-register-split { flex-direction: column; }
            .hs-register-brand { flex: none; padding: 32px 28px; }
            .hs-register-form { padding: 32px 26px; }
            .hs-row { grid-template-columns: 1fr; }
        }
    </style>

    <section class="hs-register-wrap">
        <div class="hs-register-split">
            <div class="hs-register-brand hs-brand-theme">
                <div class="hs-register-logo">
                    <img src="{{ asset('images/qqq.png') }}" alt="HappyStem Logo">
                    <div>
                        <h1>HappyStem</h1>
                        <p>by Carmencita</p>
                    </div>
                </div>
                <div>
                    <h2>Beautiful flowers, delivered with love.</h2>
                    <p class="hs-register-tagline">Join HappyStem and order handcrafted bouquets from the heart of Bangued, Abra.</p>
                </div>
                <ul class="hs-register-perks">
                    <li><i class="fas fa-truck-fast"></i> Same-day delivery across Abra</li>
                    <li><i class="fas fa-seedling"></i> Fresh, handcrafted bouquets</li>
                    <li><i class="fas fa-wallet"></i> Easy GCash payment</li>
                    <li><i class="fas fa-heart"></i> Made with love by Carmencita</li>
                </ul>
            </div>

            <div class="hs-register-form">
                <h3>Create Your Account</h3>
                <p class="hs-sub">Fill in your details to start your HappyStem journey.</p>

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
                    <div class="hs-row">
                        <div class="hs-field">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required>
                        </div>
                        <div class="hs-field">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" required>
                        </div>
                    </div>
                    <div class="hs-field">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                    </div>
                    <div class="hs-row">
                        <div class="hs-field">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="At least 6 characters" required>
                        </div>
                        <div class="hs-field">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                        </div>
                    </div>

                    <label class="hs-terms">
                        <input type="checkbox" name="terms" value="1" required>
                        <span>I agree to the <a href="#" onclick="return false;">Terms of Service</a> and <a href="#" onclick="return false;">Privacy Policy</a>.</span>
                    </label>

                    <button type="submit" class="hs-register-btn"><i class="fas fa-user-plus"></i> Create Account</button>
                </form>

                <p class="hs-register-links">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </p>
            </div>
        </div>
    </section>
@endsection
