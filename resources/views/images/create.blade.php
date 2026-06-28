@extends('layouts.app')

@section('title', 'Upload Image - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Upload Image</h1>
    <p class="hero-sub">Add images to your library and generate AI visuals.</p>
</div>

<div style="max-width:700px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Description</label>
            <textarea name="description" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Image File</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Tags (comma separated)</label>
            <input type="text" name="tags" value="{{ old('tags') }}" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Upload Image</button>
            <a href="{{ route('images.index') }}" class="btn-light" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
