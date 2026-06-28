<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Image;
use App\Models\SocialPost;

class HomeController extends Controller
{
    public function index()
    {
        $recentVideos = Video::where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        $featuredImages = Image::where('status', 'published')
            ->latest()
            ->limit(8)
            ->get();

        $trendingPosts = SocialPost::where('status', 'published')
            ->orderBy('engagement_score', 'desc')
            ->limit(4)
            ->get();

        return view('home', compact('recentVideos', 'featuredImages', 'trendingPosts'));
    }
}
