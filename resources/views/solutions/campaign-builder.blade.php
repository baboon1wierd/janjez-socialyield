@extends('layouts.app')

@section('title', 'Campaign Builder - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Campaign Builder</h1>
    <p class="hero-sub">Design and launch your next social media campaign.</p>
</div>

<div style="max-width:700px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('solutions.campaigns.store') }}">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Campaign Name</label>
            <input type="text" name="name" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Description</label>
            <textarea name="description" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;"></textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Type</label>
            <select name="type" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
                <option value="showcase">Showcase</option>
                <option value="launch">Launch</option>
                <option value="promo">Promo</option>
            </select>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Budget</label>
            <input type="number" name="budget" step="0.01" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">Start Date</label>
                <input type="date" name="start_date" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
            </div>
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">End Date</label>
                <input type="date" name="end_date" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
            </div>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Create Campaign</button>
            <a href="{{ route('solutions.index') }}" class="btn-light" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
