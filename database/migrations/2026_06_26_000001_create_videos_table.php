<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('platform')->nullable();
            $table->string('platform_video_id')->nullable();
            $table->integer('duration')->nullable();
            $table->string('resolution')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->json('tags')->nullable();
            $table->json('categories')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->string('ai_model')->nullable();
            $table->json('ai_generation_data')->nullable();
            $table->string('ai_agent_id')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->json('engagement_data')->nullable();
            $table->enum('status', ['draft', 'processing', 'published', 'failed'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('do_agent_task_id')->nullable();
            $table->json('do_agent_response')->nullable();
            $table->timestamp('do_agent_processed_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('platform');
            $table->index('status');
            $table->index('ai_agent_id');
            $table->index('do_agent_task_id');
        });

        Schema::create('video_processing_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->string('task_type');
            $table->json('task_data');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('attempts')->default(0);
            $table->string('do_agent_id')->nullable();
            $table->json('do_agent_result')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('do_agent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_processing_queue');
        Schema::dropIfExists('videos');
    }
};