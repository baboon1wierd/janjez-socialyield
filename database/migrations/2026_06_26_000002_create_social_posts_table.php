<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform');
            $table->string('platform_post_id')->nullable();
            $table->enum('post_type', ['image', 'video', 'carousel', 'text', 'link', 'story', 'reel', 'short'])->default('image');
            $table->text('content')->nullable();
            $table->json('media_urls')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->text('caption')->nullable();
            $table->text('hashtags')->nullable();
            $table->json('mentions')->nullable();
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->json('ai_generation_data')->nullable();
            $table->string('ai_agent_id')->nullable();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('campaign_goal')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published', 'failed', 'deleted'])->default('draft');
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('reach')->default(0);
            $table->integer('impressions')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('saves')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('engagement_score', 5, 2)->default(0);
            $table->json('engagement_data')->nullable();
            $table->string('do_agent_task_id')->nullable();
            $table->json('do_agent_response')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'platform']);
            $table->index('status');
            $table->index('scheduled_for');
            $table->index('ai_agent_id');
            $table->index('do_agent_task_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_posts');
    }
};