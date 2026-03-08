<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coffee_beans', function (Blueprint $table) {
            // Batch tracking
            $table->string('batch_id')->nullable()->after('id');
            $table->integer('batch_sequence')->nullable()->after('batch_id');
            $table->integer('batch_total')->nullable()->after('batch_sequence');
            $table->enum('upload_mode', ['single', 'batch'])->default('single')->after('batch_total');
            
            // Add index for faster batch queries
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('coffee_beans', function (Blueprint $table) {
            $table->dropIndex(['batch_id']);
            $table->dropColumn(['batch_id', 'batch_sequence', 'batch_total', 'upload_mode']);
        });
    }
};
