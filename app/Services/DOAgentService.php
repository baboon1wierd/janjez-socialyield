<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\DOAgent;
use App\Models\AgentTask;

class DOAgentService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.digitalocean.agent_url', env('DO_AGENT_URL'));
        $this->apiKey = config('services.digitalocean.api_key', env('DO_AGENT_API_KEY'));
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'timeout' => 30
        ]);
    }

    public function registerAgent(DOAgent $agent)
    {
        try {
            $response = $this->client->post('/api/agents/register', [
                'json' => [
                    'agent_id' => $agent->agent_id,
                    'api_key' => $agent->api_key,
                    'capabilities' => $agent->capabilities,
                    'config' => $agent->agent_config
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            $agent->update([
                'is_verified' => true,
                'agent_endpoint' => $data['endpoint'] ?? null,
                'last_heartbeat' => now()
            ]);

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function processTask(AgentTask $task)
    {
        try {
            $response = $this->client->post('/api/agents/process', [
                'json' => [
                    'agent_id' => $task->doAgent->agent_id,
                    'task_id' => $task->id,
                    'task_type' => $task->task_type,
                    'task_data' => $task->task_data,
                    'priority' => $task->priority
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            $task->update([
                'status' => 'processing',
                'assigned_at' => now(),
                'task_result' => $data['result'] ?? null
            ]);

            $task->doAgent->increment('current_tasks');
            $task->doAgent->increment('total_tasks_processed');
            $task->doAgent->update(['last_used_at' => now()]);

            return $data;
        } catch (\Exception $e) {
            $task->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    public function getTaskStatus(AgentTask $task)
    {
        try {
            $response = $this->client->get("/api/agents/tasks/{$task->id}/status", [
                'query' => ['agent_id' => $task->doAgent->agent_id]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if ($data['status'] === 'completed') {
                $task->markAsCompleted($data['result']);
                $task->doAgent->decrement('current_tasks');
            } elseif ($data['status'] === 'failed') {
                $task->markAsFailed($data['error'] ?? 'Unknown error');
                $task->doAgent->decrement('current_tasks');
            }

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getAgentHealth(DOAgent $agent)
    {
        try {
            $response = $this->client->get("/api/agents/{$agent->agent_id}/health");
            $data = json_decode($response->getBody(), true);
            
            $agent->update([
                'last_heartbeat' => now(),
                'resource_usage' => $data['resources'] ?? null,
                'performance_metrics' => $data['metrics'] ?? null
            ]);

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function cancelTask(AgentTask $task)
    {
        try {
            $response = $this->client->post("/api/agents/tasks/{$task->id}/cancel", [
                'json' => ['agent_id' => $task->doAgent->agent_id]
            ]);

            $data = json_decode($response->getBody(), true);
            
            $task->update(['status' => 'cancelled']);
            $task->doAgent->decrement('current_tasks');

            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}