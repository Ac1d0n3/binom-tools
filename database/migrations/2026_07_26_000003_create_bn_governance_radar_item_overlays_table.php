<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_governance_radar_item_overlays', function (Blueprint $table) {
            $table->string('item_id', 120)->primary();
            $table->string('updated_by_user_id', 64)->nullable()->index();
            $table->string('title_de', 500)->nullable();
            $table->text('summary_de')->nullable();
            $table->text('recommended_action_de')->nullable();
            $table->text('editorial_note')->nullable();
            $table->string('impact', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_governance_radar_item_overlays');
    }
};
