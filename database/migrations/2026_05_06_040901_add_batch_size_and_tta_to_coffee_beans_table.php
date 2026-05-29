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
        Schema::table('coffee_beans', function (Blueprint $table) {
            $table->integer('batch_size')->default(32)->after('upload_mode');
            $table->boolean('use_tta')->default(true)->after('batch_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_beans', function (Blueprint $table) {
            $table->dropColumn(['batch_size', 'use_tta']);
        });
    }
};
