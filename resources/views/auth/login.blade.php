@extends('layouts.app')

@section('title', 'Login - Janjez-Socio')

@section('content')
<div style="max-width:420px;margin:40px auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <div style="text-align:center;margin-bottom:24px;">
        <div style="width:48px;height:48px;background:#1e1e1e;color:white;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:24px;">
            <i class="fas fa-arrow-trend-up"></i>
        </div>
        <h2 style="font-size:24px;font-weight:700;margin-top:12px;">Welcome back</h2>
        <p style="color:#4a4a4a;font-size:15px;">Log in to your Janjez-Socio account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom:16px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Email or Username</label>
            <input type="text" name="email" value="{{ old('email') }}" required autofocus style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Password</label>
            <input type="password" name="password" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Log in</button>
    </form>

    <p style="text-align:center;margin-top:16px;font-size:14px;color:#4a4a4a;">Don't have an account? <a href="{{ route('register') }}" style="color:#6c4bff;font-weight:600;text-decoration:none;">Sign up</a></p>
</div>
@endsection
