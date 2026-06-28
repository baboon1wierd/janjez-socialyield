<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
    }
};
