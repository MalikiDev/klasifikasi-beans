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
        Schema::create('batch_confusion_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            
            // MobileNetV3 Small confusion matrix
            $table->string('confusion_matrix_small_path')->nullable();
            $table->json('confusion_matrix_small_data')->nullable();
            $table->decimal('accuracy_small', 5, 2)->nullable();
            $table->json('per_class_accuracy_small')->nullable();
            
            // MobileNetV3 Large confusion matrix
            $table->string('confusion_matrix_large_path')->nullable();
            $table->json('confusion_matrix_large_data')->nullable();
            $table->decimal('accuracy_large', 5, 2)->nullable();
            $table->json('per_class_accuracy_large')->nullable();
            
            // Metadata
            $table->integer('total_images');
            $table->json('class_distribution')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_confusion_matrices');
    }
};
