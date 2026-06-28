<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('full_name');
            $table->string('display_name')->nullable();
            $table->string('avatar_url')->default('/assets/default-avatar.png');
            $table->string('cover_image')->nullable();
            $table->text('bio')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_say'])->default('prefer_not_say');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_suspended')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->string('verification_token')->nullable();
            $table->timestamp('verification_token_expiry')->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expiry')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->json('two_factor_backup_codes')->nullable();
            $table->timestamp('last_password_change')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('account_locked_until')->nullable();
            $table->enum('role', ['user', 'premium', 'business', 'admin', 'super_admin'])->default('user');
            $table->json('permissions')->nullable();
            $table->string('do_agent_id')->nullable()->unique();
            $table->string('do_agent_token')->nullable();
            $table->json('do_agent_config')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->string('last_ip')->nullable();
            $table->text('last_user_agent')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->integer('referral_count')->default(0);
            $table->timestamps();
            $table->index(['email', 'username']);
            $table->index('verification_token');
            $table->index('is_active');
            $table->index('do_agent_id');
            $table->foreign('referred_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};