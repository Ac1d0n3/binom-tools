<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bn_catalog_documents', function (Blueprint $table) {
            $table->id();
            $table->string('catalog', 64);
            $table->string('facet', 64);
            $table->string('checksum', 64);
            $table->json('payload');
            $table->timestamps();
            $table->unique(['catalog', 'facet']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bn_catalog_documents');
    }
};
