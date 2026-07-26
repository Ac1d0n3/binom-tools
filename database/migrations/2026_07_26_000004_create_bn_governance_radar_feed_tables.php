<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_governance_radar_feed_items', function (Blueprint $table) {
            $table->id();
            $table->string('source_id', 64)->index();
            $table->string('guid', 500);
            $table->string('title', 500);
            $table->text('summary')->nullable();
            $table->string('url', 1000)->nullable();
            $table->dateTime('published_at')->nullable()->index();
            $table->string('language', 16)->default('en');
            $table->json('raw_topics')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            $table->unique(['source_id', 'guid']);
            $table->index(['source_id', 'published_at']);
        });

        Schema::create('bn_governance_radar_feed_syncs', function (Blueprint $table) {
            $table->string('source_id', 64)->primary();
            $table->string('feed_url', 1000);
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_status', 32)->default('pending');
            $table->text('last_error')->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_governance_radar_feed_items');
        Schema::dropIfExists('bn_governance_radar_feed_syncs');
    }
};
