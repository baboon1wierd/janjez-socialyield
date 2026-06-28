<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('platform')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->json('tags')->nullable();
            $table->json('categories')->nullable();
            $table->json('colors')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->string('ai_model')->nullable();
            $table->json('ai_generation_data')->nullable();
            $table->string('ai_agent_id')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('platform');
            $table->index('status');
            $table->index('ai_agent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
};