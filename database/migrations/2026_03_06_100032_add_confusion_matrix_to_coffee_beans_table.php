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
            // Confusion matrix images (stored as paths)
            $table->string('confusion_matrix_small_path')->nullable()->after('comparison_analysis');
            $table->string('confusion_matrix_large_path')->nullable()->after('confusion_matrix_small_path');
            
            // Confusion matrix data (JSON)
            $table->json('confusion_matrix_small_data')->nullable()->after('confusion_matrix_large_path');
            $table->json('confusion_matrix_large_data')->nullable()->after('confusion_matrix_small_data');
            
            // Batch accuracy metrics
            $table->decimal('batch_accuracy_small', 5, 2)->nullable()->after('confusion_matrix_large_data');
            $table->decimal('batch_accuracy_large', 5, 2)->nullable()->after('batch_accuracy_small');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_beans', function (Blueprint $table) {
            $table->dropColumn([
                'confusion_matrix_small_path',
                'confusion_matrix_large_path',
                'confusion_matrix_small_data',
                'confusion_matrix_large_data',
                'batch_accuracy_small',
                'batch_accuracy_large'
            ]);
        });
    }
};
