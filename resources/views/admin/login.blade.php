<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login | HappyStem</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e8b4bc, #8a9b6e); }
        .admin-login-box { background: #fff; border-radius: 14px; padding: 40px; width: 92%; max-width: 400px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); }
        .admin-login-box h2 { color: var(--secondary); margin-bottom: 6px; }
        .admin-login-box .sub { color: var(--dark); font-size: 0.9rem; margin-bottom: 22px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.92rem; }
        .alert-error { background: #fdecea; color: #b3261e; border: 1px solid #f5c6c2; }
        .admin-login-box input { width: 100%; }
        .admin-login-box label { color: var(--dark); }
    </style>
</head>
<body>
    <div class="admin-login-box">
        <div style="text-align:center;margin-bottom:20px;">
            <img src="{{ asset('images/logo.jpg') }}" alt="HappyStem" style="width:70px;height:70px;border-radius:50%;object-fit:cover;">
            <h2>HappyStem Admin</h2>
            <p class="sub">Sign in to manage the flower shop.</p>
        </div>

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

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:15px;">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="admin" required autofocus>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;text-align:center;"><i class="fas fa-lock"></i> Sign In</button>
        </form>

        <p style="text-align:center;margin-top:18px;font-size:0.85rem;color:var(--secondary);">
            <a href="{{ route('home') }}" style="color:var(--accent);text-decoration:none;">&larr; Back to store</a>
        </p>
    </div>
</body>
</html>
