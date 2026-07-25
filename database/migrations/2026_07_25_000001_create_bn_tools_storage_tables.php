<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_users', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('email', 190)->unique();
            $table->string('display_name', 120);
            $table->string('password_hash', 255);
            $table->json('team_ids')->nullable();
            $table->boolean('can_manage_users')->default(false);
            $table->boolean('can_manage_teams')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('pending_approval')->default(false);
            $table->string('short_name', 16)->default('');
            $table->string('color_token', 32)->default('accent-1');
            $table->string('avatar_icon', 64)->default('');
            $table->boolean('must_change_password')->default(false);
            $table->timestamps();
        });

        Schema::create('bn_teams', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('member_ids')->nullable();
            $table->json('member_roles')->nullable();
            $table->boolean('archived')->default(false);
            $table->string('short_name', 16)->default('');
            $table->string('color_token', 32)->default('accent-1');
            $table->string('avatar_icon', 64)->default('');
            $table->timestamps();
        });

        Schema::create('bn_story_acl', function (Blueprint $table) {
            $table->string('slug', 190)->primary();
            $table->string('visibility', 32)->default('public');
            $table->json('user_ids')->nullable();
            $table->json('team_ids')->nullable();
            $table->timestamps();
        });

        Schema::create('bn_plans', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('owner_user_id', 64)->index();
            $table->string('template_slug', 190)->nullable()->index();
            $table->json('payload');
            $table->timestamps();
        });

        Schema::create('bn_plan_history', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('plan_id', 64)->index();
            $table->string('actor_user_id', 64)->nullable();
            $table->string('actor_label', 190)->nullable();
            $table->string('action', 64)->default('update');
            $table->string('summary', 255)->nullable();
            $table->json('snapshot');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('bn_plan_attachments', function (Blueprint $table) {
            $table->string('id', 64);
            $table->string('plan_id', 64);
            $table->json('meta');
            $table->timestamps();
            $table->primary(['plan_id', 'id']);
            $table->index('plan_id');
        });

        Schema::create('bn_user_templates', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('owner_user_id', 64)->index();
            $table->json('payload');
            $table->timestamps();
        });

        Schema::create('bn_read_state', function (Blueprint $table) {
            $table->string('user_id', 64);
            $table->string('slug', 190);
            $table->unsignedBigInteger('read_at');
            $table->primary(['user_id', 'slug']);
            $table->index('user_id');
        });

        Schema::create('bn_prompt_studio_library', function (Blueprint $table) {
            $table->string('owner_user_id', 64)->primary();
            $table->json('payload');
            $table->timestamps();
        });

        Schema::create('bn_playbook_stats', function (Blueprint $table) {
            $table->string('slug', 190)->primary();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_playbook_stats');
        Schema::dropIfExists('bn_prompt_studio_library');
        Schema::dropIfExists('bn_read_state');
        Schema::dropIfExists('bn_user_templates');
        Schema::dropIfExists('bn_plan_attachments');
        Schema::dropIfExists('bn_plan_history');
        Schema::dropIfExists('bn_plans');
        Schema::dropIfExists('bn_story_acl');
        Schema::dropIfExists('bn_teams');
        Schema::dropIfExists('bn_users');
    }
};
