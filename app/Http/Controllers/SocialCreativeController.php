<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialPost;
use App\Models\Campaign;
use App\Models\Video;
use App\Models\Image;

class SocialCreativeController extends Controller
{
    public function index()
    {
        $posts = SocialPost::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
            
        $stats = [
            'total' => SocialPost::where('user_id', auth()->id())->count(),
            'published' => SocialPost::where('user_id', auth()->id())
                ->where('status', 'published')->count(),
            'scheduled' => SocialPost::where('user_id', auth()->id())
                ->where('status', 'scheduled')->count(),
            'engagement' => SocialPost::where('user_id', auth()->id())
                ->sum('engagement_score')
        ];

        return view('social-creative.index', compact('posts', 'stats'));
    }

    public function create()
    {
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        $videos = Video::where('user_id', auth()->id())->get();
        $images = Image::where('user_id', auth()->id())->get();
        
        return view('social-creative.create', compact('campaigns', 'videos', 'images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'content' => 'required|string',
            'caption' => 'nullable|string',
            'media' => 'nullable|array',
            'scheduled_for' => 'nullable|date',
            'campaign_id' => 'nullable|exists:campaigns,id'
        ]);

        $post = SocialPost::create([
            'user_id' => auth()->id(),
            'platform' => $request->platform,
            'content' => $request->content,
            'caption' => $request->caption,
            'media_urls' => $request->media,
            'campaign_id' => $request->campaign_id,
            'status' => $request->scheduled_for ? 'scheduled' : 'draft',
            'scheduled_for' => $request->scheduled_for,
            'is_ai_generated' => $request->has('ai_generated')
        ]);

        return redirect()->route('social-creative.show', $post)
            ->with('success', 'Post created successfully!');
    }

    public function show(SocialPost $socialPost)
    {
        return view('social-creative.show', compact('socialPost'));
    }

    public function publish(SocialPost $socialPost)
    {
        $socialPost->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return back()->with('success', 'Post published successfully!');
    }

    public function schedule(Request $request, SocialPost $socialPost)
    {
        $request->validate([
            'scheduled_for' => 'required|date|after:now'
        ]);

        $socialPost->update([
            'scheduled_for' => $request->scheduled_for,
            'status' => 'scheduled'
        ]);

        return back()->with('success', 'Post scheduled successfully!');
    }

    public function aiGeneratePost(Request $request)
    {
        $request->validate([
            'goal' => 'required|string',
            'platform' => 'required|string',
            'tone' => 'nullable|string',
            'topic' => 'required|string'
        ]);

        $generated = [
            'content' => 'Generated post content here...',
            'caption' => 'Generated caption here...',
            'hashtags' => ['#ai', '#socialmedia', '#growth']
        ];

        return response()->json([
            'success' => true,
            'generated' => $generated
        ]);
    }
}