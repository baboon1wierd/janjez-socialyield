<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\SocialPost;
use App\Models\Video;
use App\Models\Image;

class SolutionsController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        $performance = [
            'total_reach' => SocialPost::where('user_id', auth()->id())->sum('reach'),
            'total_engagement' => SocialPost::where('user_id', auth()->id())->sum('engagement_score'),
            'total_conversions' => SocialPost::where('user_id', auth()->id())->sum('conversions'),
            'roi' => $this->calculateROI()
        ];

        return view('solutions.index', compact('campaigns', 'performance'));
    }

    public function analytics()
    {
        $data = [
            'posts_per_platform' => SocialPost::where('user_id', auth()->id())
                ->selectRaw('platform, count(*) as count')
                ->groupBy('platform')
                ->get(),
            'engagement_trend' => $this->getEngagementTrend(),
            'top_performing' => SocialPost::where('user_id', auth()->id())
                ->orderBy('engagement_score', 'desc')
                ->limit(5)
                ->get(),
            'content_types' => $this->getContentTypeDistribution()
        ];

        return view('solutions.analytics', compact('data'));
    }

    public function campaignBuilder()
    {
        return view('solutions.campaign-builder');
    }

    public function createCampaign(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'budget' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'platforms' => 'required|array',
            'target_audience' => 'nullable|array'
        ]);

        $campaign = Campaign::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'campaign_type' => $request->type,
            'budget' => $request->budget,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'platform_distribution' => $request->platforms,
            'target_audience' => $request->target_audience,
            'status' => 'draft'
        ]);

        return redirect()->route('solutions.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully!');
    }

    public function showCampaign(Campaign $campaign)
    {
        return view('solutions.campaigns.show', compact('campaign'));
    }

    private function calculateROI()
    {
        $cost = Campaign::where('user_id', auth()->id())->sum('budget_spent');
        $revenue = SocialPost::where('user_id', auth()->id())->sum('conversions') * 100;
        
        return $cost > 0 ? ($revenue - $cost) / $cost * 100 : 0;
    }

    private function getEngagementTrend()
    {
        return SocialPost::where('user_id', auth()->id())
            ->selectRaw('DATE(created_at) as date, AVG(engagement_score) as avg_engagement')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
    }

    private function getContentTypeDistribution()
    {
        return SocialPost::where('user_id', auth()->id())
            ->selectRaw('post_type, count(*) as count')
            ->groupBy('post_type')
            ->get();
    }
}