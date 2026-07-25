<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_calendar_holiday_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type', 32);
            $table->string('country', 8)->nullable();
            $table->string('region', 16)->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sync_interval_hours')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('bn_calendar_holidays', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('source_id')->nullable()->constrained('bn_calendar_holiday_sources')->nullOnDelete();
            $table->string('name');
            $table->date('date');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('country', 8)->nullable();
            $table->string('region', 16)->nullable();
            $table->string('type', 32);
            $table->boolean('all_day')->default(true);
            $table->string('imported_uid')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['date', 'type']);
            $table->index(['imported_uid', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_calendar_holidays');
        Schema::dropIfExists('bn_calendar_holiday_sources');
    }
};
