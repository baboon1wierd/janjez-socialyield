<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('campaign_type');
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('budget_spent', 10, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('platform_distribution')->nullable();
            $table->json('target_audience')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'paused'])->default('draft');
            $table->integer('total_reach')->default(0);
            $table->integer('total_engagement')->default(0);
            $table->integer('total_conversions')->default(0);
            $table->decimal('roi', 8, 2)->default(0);
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};