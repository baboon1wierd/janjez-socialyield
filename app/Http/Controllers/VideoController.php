<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\DOAgent;
use App\Models\AgentTask;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);
            
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        $agents = DOAgent::where('user_id', auth()->id())
            ->where('status', 'active')
            ->get();
            
        return view('videos.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,avi|max:512000',
            'agent_id' => 'nullable|exists:do_agents,id'
        ]);

        $path = $request->file('video')->store('videos', 'public');
        
        $video = Video::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => Storage::url($path),
            'status' => 'draft'
        ]);

        if ($request->agent_id) {
            $agent = DOAgent::find($request->agent_id);
            $task = $agent->assignTask('video_processing', [
                'video_id' => $video->id,
                'video_url' => $video->video_url,
                'actions' => ['enhance', 'transcribe', 'generate_thumbnails']
            ]);
            
            $video->update([
                'do_agent_task_id' => $task->id,
                'ai_agent_id' => $agent->id,
                'status' => 'processing'
            ]);
        }

        return redirect()->route('videos.show', $video)
            ->with('success', 'Video uploaded successfully!');
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    public function processWithAgent(Video $video, Request $request)
    {
        $agent = DOAgent::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (!$agent) {
            return back()->with('error', 'No active agent found. Please setup your DigitalOcean agent.');
        }

        $task = $agent->assignTask('video_enhancement', [
            'video_id' => $video->id,
            'video_url' => $video->video_url,
            'enhancements' => $request->enhancements ?? ['resolution', 'audio', 'color']
        ]);

        $video->update([
            'do_agent_task_id' => $task->id,
            'status' => 'processing'
        ]);

        return back()->with('success', 'Video sent for processing with DigitalOcean agent.');
    }

    public function getAgentStatus(Video $video)
    {
        if (!$video->do_agent_task_id) {
            return response()->json(['status' => 'not_processed']);
        }

        $task = AgentTask::find($video->do_agent_task_id);
        
        return response()->json([
            'status' => $task->status,
            'progress' => $task->task_data['progress'] ?? 0,
            'result' => $task->task_result,
            'error' => $task->error_message
        ]);
    }

    public function agentDashboard()
    {
        $agent = DOAgent::where('user_id', auth()->id())->first();
        $tasks = AgentTask::where('user_id', auth()->id())
            ->latest()
            ->limit(20)
            ->get();
            
        $stats = [
            'total' => AgentTask::where('user_id', auth()->id())->count(),
            'processing' => AgentTask::where('user_id', auth()->id())
                ->whereIn('status', ['queued', 'assigned', 'processing'])->count(),
            'completed' => AgentTask::where('user_id', auth()->id())
                ->where('status', 'completed')->count(),
            'failed' => AgentTask::where('user_id', auth()->id())
                ->where('status', 'failed')->count()
        ];

        return view('videos.agent-dashboard', compact('agent', 'tasks', 'stats'));
    }
}