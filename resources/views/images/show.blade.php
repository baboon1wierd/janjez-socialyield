@extends('layouts.app')

@section('title', $image->title . ' - Janjez-Socio')

@section('content')
<div style="max-width:1000px;margin:0 auto;">
    <div style="background:white;border-radius:28px;overflow:hidden;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
        <div style="padding:40px;text-align:center;">
            <img src="{{ $image->image_url }}" alt="{{ $image->title }}" style="max-width:100%;max-height:500px;object-fit:contain;border-radius:16px;">
        </div>
        <div style="padding:32px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">{{ $image->title }}</h1>
            <p style="color:#4a4a4a;font-size:16px;margin-bottom:16px;">{{ $image->description }}</p>

            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:20px;font-size:14px;color:#4a4a4a;">
                <span><i class="fas fa-eye"></i> {{ $image->views }} views</span>
                <span><i class="fas fa-heart"></i> {{ $image->likes }}</span>
                <span><i class="fas fa-comment"></i> {{ $image->comments }}</span>
            </div>

            <div style="margin-bottom:20px;">
                <span style="font-size:12px;padding:4px 12px;border-radius:20px;background:#f0edec;">{{ ucfirst($image->status) }}</span>
                @if($image->platform)
                    <span style="font-size:12px;padding:4px 12px;border-radius:20px;background:#e8f4fd;color:#0c5460;">{{ ucfirst($image->platform) }}</span>
                @endif
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('images.edit', $image) }}" class="btn-primary">Edit Image</a>
                <form action="{{ route('images.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-light" style="border-color:#dc3545;color:#dc3545;">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
