<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DOAgent;
use App\Models\AgentTask;
use App\Services\DOAgentService;

class AgentController extends Controller
{
    protected $agentService;

    public function __construct(DOAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function setup()
    {
        $agent = DOAgent::where('user_id', auth()->id())->first();
        return view('agent.setup', compact('agent'));
    }

    public function configure(Request $request)
    {
        $request->validate([
            'agent_name' => 'required|string|max:255',
            'agent_type' => 'required|string',
            'agent_endpoint' => 'required|url',
            'api_key' => 'required|string',
            'capabilities' => 'nullable|array'
        ]);

        $agent = DOAgent::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'agent_name' => $request->agent_name,
                'agent_type' => $request->agent_type,
                'agent_id' => 'agent_' . auth()->id() . '_' . time(),
                'api_key' => $request->api_key,
                'api_secret' => bin2hex(random_bytes(32)),
                'agent_endpoint' => $request->agent_endpoint,
                'capabilities' => $request->capabilities,
                'supported_tasks' => ['video_processing', 'image_generation', 'content_analysis']
            ]
        );

        return redirect()->route('videos.agent-dashboard')
            ->with('success', 'Agent configured successfully!');
    }

    public function status()
    {
        $agent = DOAgent::where('user_id', auth()->id())->first();
        
        if (!$agent) {
            return response()->json(['status' => 'not_configured']);
        }

        $health = $this->agentService->getAgentHealth($agent);
        
        return response()->json([
            'agent' => $agent,
            'health' => $health
        ]);
    }

    public function restart()
    {
        $agent = DOAgent::where('user_id', auth()->id())->first();
        
        if ($agent) {
            $agent->update(['status' => 'active']);
        }

        return back()->with('success', 'Agent restarted successfully!');
    }

    public function retryTask($taskId)
    {
        $task = AgentTask::where('user_id', auth()->id())->findOrFail($taskId);
        
        if ($task->retry()) {
            return response()->json(['success' => true, 'message' => 'Task queued for retry']);
        }

        return response()->json(['success' => false, 'message' => 'Max retries exceeded'], 400);
    }

    public function taskStatus($taskId)
    {
        $task = AgentTask::where('user_id', auth()->id())->findOrFail($taskId);
        
        return response()->json([
            'status' => $task->status,
            'result' => $task->task_result,
            'error' => $task->error_message
        ]);
    }

    public function capabilities()
    {
        $agent = DOAgent::where('user_id', auth()->id())->first();
        
        if (!$agent || !$agent->is_verified) {
            return response()->json(['capabilities' => []]);
        }

        return response()->json([
            'capabilities' => $agent->capabilities,
            'supported_tasks' => $agent->supported_tasks
        ]);
    }
}