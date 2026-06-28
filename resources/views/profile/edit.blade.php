@extends('layouts.app')

@section('title', 'Profile - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Profile</h1>
    <p class="hero-sub">Manage your account settings and preferences.</p>
</div>

<div style="max-width:700px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->full_name) }}" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Bio</label>
            <textarea name="bio" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;">{{ old('bio', auth()->user()->bio) }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Company</label>
            <input type="text" name="company" value="{{ old('company', auth()->user()->company) }}" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Website</label>
            <input type="url" name="website" value="{{ old('website', auth()->user()->website) }}" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
