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
        Schema::create('coffee_beans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('variety')->nullable();
            $table->string('origin')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            
            // MobileNetV3 Small Results
            $table->string('classification_small')->nullable();
            $table->decimal('confidence_small', 5, 2)->nullable();
            $table->json('predictions_small')->nullable();
            
            // MobileNetV3 Large Results
            $table->string('classification_large')->nullable();
            $table->decimal('confidence_large', 5, 2)->nullable();
            $table->json('predictions_large')->nullable();
            
            // Comparison & Analysis
            $table->boolean('models_agree')->nullable(); // Apakah kedua model setuju
            $table->string('final_classification')->nullable(); // Klasifikasi final (consensus atau highest confidence)
            $table->decimal('confidence_difference', 5, 2)->nullable(); // Selisih confidence
            $table->json('comparison_analysis')->nullable(); // Analisis perbandingan lengkap
            
            // Processing Time
            $table->integer('processing_time_small')->nullable(); // ms
            $table->integer('processing_time_large')->nullable(); // ms
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffee_beans');
    }
};
