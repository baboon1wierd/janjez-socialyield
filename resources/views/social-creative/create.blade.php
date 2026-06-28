@extends('layouts.app')

@section('title', 'Create Post - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Create Social Post</h1>
    <p class="hero-sub">Design and schedule posts across multiple platforms.</p>
</div>

<div style="max-width:900px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('social-creative.store') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">Platform</label>
                <select name="platform" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                    <option value="tiktok">TikTok</option>
                    <option value="youtube">YouTube</option>
                    <option value="x">X (Twitter)</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">Campaign (Optional)</label>
                <select name="campaign_id" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
                    <option value="">Select campaign</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Content</label>
            <textarea name="content" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:120px;">{{ old('content') }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Caption</label>
            <textarea name="caption" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;">{{ old('caption') }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Scheduled For (Optional)</label>
            <input type="datetime-local" name="scheduled_for" value="{{ old('scheduled_for') }}" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;display:flex;align-items:center;gap:8px;">
            <input type="checkbox" name="ai_generated" id="ai_generated" style="width:18px;height:18px;accent-color:#6c4bff;">
            <label for="ai_generated" style="font-weight:600;">AI Generated</label>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Create Post</button>
            <a href="{{ route('social-creative.index') }}" class="btn-light" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
