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
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropColumn('is_cache_hit');
            $table->unsignedInteger('swapi_cache_hits')->default(0)->after('status_code');
            $table->unsignedInteger('swapi_cache_misses')->default(0)->after('swapi_cache_hits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropColumn(['swapi_cache_hits', 'swapi_cache_misses']);
            $table->boolean('is_cache_hit')->default(false)->after('status_code');
        });
    }
};
