<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomic_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->unsignedTinyInteger('level');
            $table->timestamps();
        });

        Schema::create('taxa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('taxa')->nullOnDelete();
            $table->foreignId('rank_id')->constrained('taxonomic_ranks');
            $table->string('scientific_name')->index();
            $table->string('author_citation')->nullable();
            $table->smallInteger('year')->nullable();
            $table->string('nomenclatural_status', 30)->default('accepted');
            $table->boolean('is_hybrid')->default(false);
            $table->boolean('is_cultivar')->default(false);
            $table->string('status', 20)->default('active')->index();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('taxon_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxon_id')->constrained()->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('status', 20)->default('synonym');
            $table->string('author')->nullable();
            $table->smallInteger('year')->nullable();
            $table->foreignId('source_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('taxon_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxon_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 20);
            $table->string('identifier', 120);
            $table->timestamps();
            $table->unique(['provider', 'identifier']);
        });

        Schema::create('taxon_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_taxon_id')->constrained('taxa')->cascadeOnDelete();
            $table->foreignId('to_taxon_id')->constrained('taxa')->cascadeOnDelete();
            $table->string('type', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxon_relationships');
        Schema::dropIfExists('taxon_identifiers');
        Schema::dropIfExists('taxon_names');
        Schema::dropIfExists('taxa');
        Schema::dropIfExists('taxonomic_ranks');
    }
};
