<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique();
            $table->string('name');
            $table->string('native_name');
            $table->string('direction', 3)->default('ltr');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->string('default_locale', 8)->default('bn');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('country_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('country_regions')->nullOnDelete();
            $table->string('type', 20)->default('district');
            $table->string('name');
            $table->string('code', 20)->nullable();
            $table->timestamps();
            $table->index(['country_id', 'type']);
        });

        Schema::create('country_languages', function (Blueprint $table) {
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('language_code', 8);
            $table->boolean('is_default')->default(false);
            $table->primary(['country_id', 'language_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_languages');
        Schema::dropIfExists('country_regions');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('languages');
    }
};
