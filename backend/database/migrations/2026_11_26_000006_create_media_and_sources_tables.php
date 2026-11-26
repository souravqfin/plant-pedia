<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->string('url')->nullable();
            $table->boolean('requires_attribution')->default(true);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 30)->default('public');
            $table->string('path');
            $table->string('mime', 100);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('checksum', 64)->nullable()->index();
            $table->string('alt_text')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('media_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('creator')->nullable();
            $table->string('source_url')->nullable();
            $table->foreignId('license_id')->nullable()->constrained('media_licenses')->nullOnDelete();
            $table->text('attribution_text')->nullable();
            $table->timestamps();
        });

        Schema::create('plant_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30)->default('whole');
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
            $table->unique(['plant_id', 'media_id']);
            $table->index(['plant_id', 'is_primary']);
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('publisher')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('license', 60)->nullable();
            $table->string('language', 8)->nullable();
            $table->date('accessed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('plant_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('source_id')->constrained()->cascadeOnDelete();
            $table->string('citation')->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('fact_references', function (Blueprint $table) {
            $table->id();
            $table->string('fact_type', 60);
            $table->unsignedBigInteger('fact_id');
            $table->foreignId('source_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['fact_type', 'fact_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fact_references');
        Schema::dropIfExists('plant_references');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('plant_media');
        Schema::dropIfExists('media_attributions');
        Schema::dropIfExists('media');
        Schema::dropIfExists('media_licenses');
    }
};
