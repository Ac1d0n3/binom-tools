<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bn_users', function (Blueprint $table) {
            $table->boolean('can_manage_content')->default(false)->after('can_manage_teams');
            $table->json('content_areas')->nullable()->after('can_manage_content');
        });

        $allAreas = json_encode([
            'stories' => true,
            'planTemplates' => true,
            'vendorsSources' => true,
            'news' => true,
            'glossary' => true,
        ], JSON_THROW_ON_ERROR);

        // Backfill: previous user-admins become content admins with all areas.
        DB::table('bn_users')
            ->where('can_manage_users', true)
            ->update([
                'can_manage_content' => true,
                'content_areas' => $allAreas,
            ]);
    }

    public function down(): void
    {
        Schema::table('bn_users', function (Blueprint $table) {
            $table->dropColumn(['can_manage_content', 'content_areas']);
        });
    }
};
