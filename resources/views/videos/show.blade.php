@extends('layouts.app')

@section('title', $video->title . ' - Janjez-Socio')

@section('content')
<div style="max-width:1000px;margin:0 auto;">
    <div style="background:white;border-radius:28px;overflow:hidden;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
        <div style="position:relative;">
            <video src="{{ $video->video_url }}" controls style="width:100%;max-height:600px;background:black;"></video>
        </div>
        <div style="padding:32px;">
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">{{ $video->title }}</h1>
            <p style="color:#4a4a4a;font-size:16px;margin-bottom:16px;">{{ $video->description }}</p>

            <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:20px;font-size:14px;color:#4a4a4a;">
                <span><i class="fas fa-eye"></i> {{ $video->views }} views</span>
                <span><i class="fas fa-heart"></i> {{ $video->likes }} likes</span>
                <span><i class="fas fa-comment"></i> {{ $video->comments }} comments</span>
                <span><i class="fas fa-share"></i> {{ $video->shares }} shares</span>
            </div>

            <div style="margin-bottom:20px;">
                <span style="font-size:12px;padding:4px 12px;border-radius:20px;background:#f0edec;">{{ ucfirst($video->status) }}</span>
                @if($video->platform)
                    <span style="font-size:12px;padding:4px 12px;border-radius:20px;background:#e8f4fd;color:#0c5460;">{{ ucfirst($video->platform) }}</span>
                @endif
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('videos.process-agent', $video) }}" class="btn-primary" onclick="event.preventDefault();document.getElementById('process-form-{{ $video->id }}').submit();">
                    <i class="fas fa-robot"></i> Process with Agent
                </a>
                <form id="process-form-{{ $video->id }}" action="{{ route('videos.process-agent', $video) }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
