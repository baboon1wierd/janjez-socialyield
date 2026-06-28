@extends('layouts.app')

@section('title', 'Upload Video - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Upload Video</h1>
    <p class="hero-sub">Upload a video and optionally process it with a DigitalOcean AI agent.</p>
</div>

<div style="max-width:700px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
            @error('title')<p style="color:#dc3545;font-size:14px;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Description</label>
            <textarea name="description" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Video File</label>
            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
            @error('video')<p style="color:#dc3545;font-size:14px;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">DigitalOcean Agent (Optional)</label>
            <select name="agent_id" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
                <option value="">None — Process manually later</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->agent_name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Upload Video</button>
            <a href="{{ route('videos.index') }}" class="btn-light" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
