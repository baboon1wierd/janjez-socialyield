@extends('layouts.app')

@section('title', 'Analytics - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Analytics</h1>
    <p class="hero-sub">Deep insights into your social media performance.</p>
</div>

<div class="grid-2" style="margin-top:0;">
    <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
        <h3 style="font-weight:700;margin-bottom:16px;">Posts per Platform</h3>
        @foreach($data['posts_per_platform'] as $item)
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0edec;">
                <span style="text-transform:capitalize;">{{ $item->platform }}</span>
                <span style="font-weight:600;">{{ $item->count }}</span>
            </div>
        @endforeach
    </div>
    <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
        <h3 style="font-weight:700;margin-bottom:16px;">Top Performing</h3>
        @foreach($data['top_performing'] as $post)
            <div style="padding:12px 0;border-bottom:1px solid #f0edec;">
                <p style="font-weight:600;">Score: {{ $post->engagement_score }}</p>
                <p style="font-size:14px;color:#4a4a4a;">{{ $post->platform }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
