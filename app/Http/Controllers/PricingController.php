<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'features' => ['5 posts/month', 'Basic templates', 'Community support'],
                'limitations' => ['Limited platforms', 'No AI generation']
            ],
            [
                'name' => 'Pro',
                'price' => 29,
                'features' => ['Unlimited posts', 'All platforms', 'AI generation', 'Priority support'],
                'popular' => true
            ],
            [
                'name' => 'Business',
                'price' => 99,
                'features' => ['Team collaboration', 'Analytics', 'API access', 'Dedicated agent'],
                'limitations' => []
            ]
        ];
        
        return view('pricing.index', compact('plans'));
    }
}