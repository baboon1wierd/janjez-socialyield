@extends('layouts.app')

@section('title', 'Edit Image - Janjez-Socio')

@section('content')
<div class="hero" style="padding:30px 0 20px;">
    <h1 class="hero-title" style="font-size:2.5rem;">Edit Image</h1>
    <p class="hero-sub">Update image details and metadata.</p>
</div>

<div style="max-width:700px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <form method="POST" action="{{ route('images.update', $image) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Title</label>
            <input type="text" name="title" value="{{ old('title', $image->title) }}" required style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Description</label>
            <textarea name="description" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;min-height:100px;">{{ old('description', $image->description) }}</textarea>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:6px;">Tags (comma separated)</label>
            <input type="text" name="tags" value="{{ old('tags', $image->tags ? implode(',', $image->tags) : '') }}" style="width:100%;padding:12px 16px;border:1px solid #e5dfdd;border-radius:12px;font-size:16px;font-family:inherit;">
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('images.show', $image) }}" class="btn-light" style="text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
