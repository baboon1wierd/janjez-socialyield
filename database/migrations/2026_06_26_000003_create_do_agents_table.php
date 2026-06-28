<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('do_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('agent_name');
            $table->string('agent_type');
            $table->string('agent_id')->unique();
            $table->string('api_key')->unique();
            $table->string('api_secret');
            $table->string('agent_endpoint')->nullable();
            $table->json('agent_config')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'maintenance'])->default('active');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_heartbeat')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->json('capabilities')->nullable();
            $table->json('supported_tasks')->nullable();
            $table->json('performance_metrics')->nullable();
            $table->integer('max_concurrent_tasks')->default(10);
            $table->integer('current_tasks')->default(0);
            $table->integer('total_tasks_processed')->default(0);
            $table->json('resource_usage')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('agent_id');
            $table->index('status');
            $table->index('last_heartbeat');
        });

        Schema::create('agent_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('do_agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('task_type');
            $table->json('task_data');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['queued', 'assigned', 'processing', 'completed', 'failed'])->default('queued');
            $table->json('task_result')->nullable();
            $table->string('error_message')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index('do_agent_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_tasks');
        Schema::dropIfExists('do_agents');
    }
};