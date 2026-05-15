<?php
// database/migrations/2026_01_01_000002_add_features_to_slow_logs.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('slow_logs', function (Blueprint $table) {
            $table->string('route')->nullable()->after('recommendation');
            $table->string('ip_address')->nullable()->after('route');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->integer('affected_rows')->default(0)->after('user_agent');
        });

        // Create indexes table for performance monitoring
        Schema::create('index_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->string('column_name');
            $table->string('query_hash')->unique();
            $table->integer('frequency')->default(1);
            $table->boolean('applied')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('slow_logs', function (Blueprint $table) {
            $table->dropColumn(['route', 'ip_address', 'user_agent', 'affected_rows']);
        });
        Schema::dropIfExists('index_suggestions');
    }
};