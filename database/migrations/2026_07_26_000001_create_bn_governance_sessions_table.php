<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_governance_sessions', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('owner_user_id', 64)->index();
            $table->string('title', 190);
            $table->string('company_name', 190)->nullable();
            $table->string('project_name', 190)->nullable();
            $table->string('scenario', 32)->default('new')->index();
            $table->string('status', 32)->default('draft')->index();
            $table->string('current_step', 64)->default('advisor')->index();
            $table->json('payload');
            $table->json('validation_summary')->nullable();
            $table->json('report_snapshot')->nullable();
            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_governance_sessions');
    }
};
