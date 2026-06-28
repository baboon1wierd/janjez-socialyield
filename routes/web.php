<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SocialCreativeController;
use App\Http\Controllers\SolutionsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::middleware(['auth'])->group(function () {
    Route::resource('images', ImageController::class);
    Route::post('/images/ai-generate', [ImageController::class, 'aiGenerate'])->name('images.ai-generate');
    
    Route::resource('videos', VideoController::class);
    Route::post('/videos/{video}/process-agent', [VideoController::class, 'processWithAgent'])->name('videos.process-agent');
    Route::get('/videos/{video}/agent-status', [VideoController::class, 'getAgentStatus'])->name('videos.agent-status');
    Route::get('/agent-dashboard', [VideoController::class, 'agentDashboard'])->name('videos.agent-dashboard');
    
    Route::resource('social-creative', SocialCreativeController::class)->parameters(['social-creative' => 'socialPost']);
    Route::post('/social-creative/{socialPost}/publish', [SocialCreativeController::class, 'publish'])->name('social-creative.publish');
    Route::post('/social-creative/{socialPost}/schedule', [SocialCreativeController::class, 'schedule'])->name('social-creative.schedule');
    Route::post('/social-creative/ai-generate', [SocialCreativeController::class, 'aiGeneratePost'])->name('social-creative.ai-generate');
    
    Route::get('/solutions', [SolutionsController::class, 'index'])->name('solutions.index');
    Route::get('/solutions/analytics', [SolutionsController::class, 'analytics'])->name('solutions.analytics');
    Route::get('/solutions/campaign-builder', [SolutionsController::class, 'campaignBuilder'])->name('solutions.campaign-builder');
    Route::post('/solutions/campaigns', [SolutionsController::class, 'createCampaign'])->name('solutions.campaigns.store');
    Route::get('/solutions/campaigns/{campaign}', [SolutionsController::class, 'showCampaign'])->name('solutions.campaigns.show');
    
    Route::get('/agent/setup', [AgentController::class, 'setup'])->name('agent.setup');
    Route::post('/agent/configure', [AgentController::class, 'configure'])->name('agent.configure');
    Route::get('/agent/status', [AgentController::class, 'status'])->name('agent.status');
    Route::post('/agent/restart', [AgentController::class, 'restart'])->name('agent.restart');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::post('/agent-tasks/{task}/retry', [AgentController::class, 'retryTask']);
    Route::get('/agent-tasks/{task}/status', [AgentController::class, 'taskStatus']);
    Route::get('/agent-capabilities', [AgentController::class, 'capabilities']);
});

require __DIR__.'/auth.php';