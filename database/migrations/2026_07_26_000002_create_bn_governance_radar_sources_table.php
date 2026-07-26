<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_governance_radar_sources', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('owner_user_id', 64)->index();
            $table->string('name', 190);
            $table->string('feed_url', 500);
            $table->string('source_url', 500)->nullable();
            $table->string('type', 64)->default('Custom');
            $table->string('region', 64)->default('Global');
            $table->string('language', 16)->default('de');
            $table->string('cadence', 64)->default('rss');
            $table->json('topics')->nullable();
            $table->text('note')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_governance_radar_sources');
    }
};
