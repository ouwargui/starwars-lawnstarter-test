<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method', 10);
            $table->string('query')->nullable();
            $table->string('search_type')->nullable();
            $table->unsignedInteger('resource_id')->nullable();
            $table->string('resource_name')->nullable();
            $table->string('referer')->nullable();
            $table->unsignedInteger('duration_ms');
            $table->unsignedSmallInteger('status_code');
            $table->boolean('is_cache_hit')->default(false);
            $table->boolean('is_error')->default(false);
            $table->timestamp('created_at');

            $table->index('created_at');
            $table->index(['endpoint', 'is_error']);
            $table->index(['resource_id', 'endpoint']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
