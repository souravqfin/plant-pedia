<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxon_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('status', 20)->default('draft')->index();
            $table->string('verification_status', 30)->default('unverified')->index();
            $table->unsignedTinyInteger('completeness_score')->default(0);
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_sensitive')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('plant_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('common_name')->nullable();
            $table->text('description')->nullable();
            $table->text('habitat')->nullable();
            $table->text('uses')->nullable();
            $table->text('care_summary')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();
            $table->unique(['plant_id', 'locale']);
            $table->index(['locale', 'common_name']);
        });

        Schema::create('plant_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('name')->index();
            $table->string('type', 20)->default('common');
            $table->foreignId('region_id')->nullable()->constrained('country_regions')->nullOnDelete();
            $table->foreignId('source_id')->nullable()->index();
            $table->timestamps();
            $table->index(['plant_id', 'locale']);
        });

        Schema::create('plant_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('plant_categories')->nullOnDelete();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('plant_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('plant_categories')->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();
            $table->unique(['category_id', 'locale']);
        });

        Schema::create('plant_category_map', function (Blueprint $table) {
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('plant_categories')->cascadeOnDelete();
            $table->primary(['plant_id', 'category_id']);
        });

        Schema::create('plant_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('habit', 40)->nullable();
            $table->unsignedInteger('height_min_cm')->nullable();
            $table->unsignedInteger('height_max_cm')->nullable();
            $table->string('leaf_type', 40)->nullable();
            $table->string('leaf_arrangement', 40)->nullable();
            $table->string('flower_color', 60)->nullable();
            $table->string('flower_shape', 40)->nullable();
            $table->string('fruit_type', 60)->nullable();
            $table->string('bark_texture', 60)->nullable();
            $table->string('root_type', 40)->nullable();
            $table->timestamps();
        });

        Schema::create('plant_care_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('light', 30)->nullable();
            $table->string('water', 30)->nullable();
            $table->string('soil', 60)->nullable();
            $table->smallInteger('temperature_min_c')->nullable();
            $table->smallInteger('temperature_max_c')->nullable();
            $table->string('humidity', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('plant_seasonality', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('country_regions')->nullOnDelete();
            $table->string('event', 20);
            $table->unsignedTinyInteger('start_month');
            $table->unsignedTinyInteger('end_month');
            $table->timestamps();
            $table->index(['plant_id', 'event']);
        });

        Schema::create('plant_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('region_id')->nullable()->constrained('country_regions')->nullOnDelete();
            $table->string('establishment_means', 20)->default('native')->index();
            $table->string('occurrence_status', 20)->default('present');
            $table->boolean('is_sensitive')->default(false);
            $table->foreignId('source_id')->nullable()->index();
            $table->timestamps();
            $table->unique(['plant_id', 'country_id', 'region_id']);
        });

        Schema::create('plant_conservation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->string('iucn_category', 4)->nullable();
            $table->string('national_status', 40)->nullable();
            $table->foreignId('source_id')->nullable()->index();
            $table->date('assessed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('plant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->string('key', 60);
            $table->text('value')->nullable();
            $table->foreignId('source_id')->nullable()->index();
            $table->timestamps();
            $table->unique(['plant_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_attributes');
        Schema::dropIfExists('plant_conservation_records');
        Schema::dropIfExists('plant_distributions');
        Schema::dropIfExists('plant_seasonality');
        Schema::dropIfExists('plant_care_guides');
        Schema::dropIfExists('plant_characteristics');
        Schema::dropIfExists('plant_category_map');
        Schema::dropIfExists('plant_category_translations');
        Schema::dropIfExists('plant_categories');
        Schema::dropIfExists('plant_names');
        Schema::dropIfExists('plant_translations');
        Schema::dropIfExists('plants');
    }
};
