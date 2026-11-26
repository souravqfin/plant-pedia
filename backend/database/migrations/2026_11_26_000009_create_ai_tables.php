<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->string('type', 40)->index();
            $table->string('status', 20)->default('queued')->index();
            $table->json('input')->nullable();
            $table->string('prompt_version', 40)->nullable();
            $table->text('error')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ai_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('ai_tasks')->cascadeOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('locale', 8)->default('bn');
            $table->longText('content')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ai_field_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('ai_tasks')->cascadeOnDelete();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->string('field', 60);
            $table->text('current_value')->nullable();
            $table->text('proposed_value')->nullable();
            $table->decimal('confidence', 4, 3)->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('ai_tasks')->cascadeOnDelete();
            $table->unsignedInteger('tokens_in')->default(0);
            $table->unsignedInteger('tokens_out')->default(0);
            $table->decimal('cost', 10, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
        Schema::dropIfExists('ai_field_suggestions');
        Schema::dropIfExists('ai_drafts');
        Schema::dropIfExists('ai_tasks');
        Schema::dropIfExists('ai_providers');
    }
};
