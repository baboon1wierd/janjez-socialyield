@extends('layouts.app')

@section('title', $post->platform . ' Post - Janjez-Socio')

@section('content')
<div style="max-width:800px;margin:0 auto;">
    <div style="background:white;border-radius:28px;overflow:hidden;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
        <div style="padding:32px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
                <span style="font-size:14px;padding:6px 14px;border-radius:20px;background:#f0edec;font-weight:600;">{{ ucfirst($post->platform) }}</span>
                <span style="font-size:14px;padding:6px 14px;border-radius:20px;background:#f0edec;font-weight:600;">{{ ucfirst($post->post_type) }}</span>
            </div>

            <h1 style="font-size:24px;font-weight:700;margin-bottom:12px;">{{ $post->caption ?: $post->content }}</h1>

            @if($post->media_urls)
                <div style="margin:20px 0;">
                    @foreach($post->media_urls as $media)
                        <img src="{{ $media }}" alt="media" style="max-width:100%;border-radius:12px;margin-bottom:12px;">
                    @endforeach
                </div>
            @endif

            <div style="display:flex;flex-wrap:wrap;gap:16px;margin:20px 0;font-size:14px;color:#4a4a4a;">
                <span><i class="fas fa-eye"></i> {{ $post->reach }}</span>
                <span><i class="fas fa-heart"></i> {{ $post->likes }}</span>
                <span><i class="fas fa-comment"></i> {{ $post->comments }}</span>
                <span><i class="fas fa-share"></i> {{ $post->shares }}</span>
                <span><i class="fas fa-bookmark"></i> {{ $post->saves }}</span>
                <span><i class="fas fa-mouse-pointer"></i> {{ $post->clicks }}</span>
            </div>

            @if($post->scheduled_for)
                <p style="font-size:14px;color:#b45309;"><i class="fas fa-clock"></i> Scheduled for {{ $post->scheduled_for->format('M d, Y H:i') }}</p>
            @elseif($post->published_at)
                <p style="font-size:14px;color:#166534;"><i class="fas fa-check"></i> Published {{ $post->published_at->diffForHumans() }}</p>
            @else
                <p style="font-size:14px;color:#4a4a4a;"><i class="fas fa-pen"></i> Draft</p>
            @endif

            <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                @if($post->status === 'draft')
                    <form action="{{ route('social-creative.publish', $post) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-primary">Publish Now</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
