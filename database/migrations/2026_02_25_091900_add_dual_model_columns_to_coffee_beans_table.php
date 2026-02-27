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
            // Drop old single model columns if they exist
            if (Schema::hasColumn('coffee_beans', 'classification')) {
                $table->dropColumn(['classification', 'confidence', 'analysis_result']);
            }
            
            // MobileNetV3 Small Results
            $table->string('classification_small')->nullable()->after('image_path');
            $table->decimal('confidence_small', 5, 2)->nullable()->after('classification_small');
            $table->json('predictions_small')->nullable()->after('confidence_small');
            $table->integer('processing_time_small')->nullable()->after('predictions_small');
            
            // MobileNetV3 Large Results
            $table->string('classification_large')->nullable()->after('processing_time_small');
            $table->decimal('confidence_large', 5, 2)->nullable()->after('classification_large');
            $table->json('predictions_large')->nullable()->after('confidence_large');
            $table->integer('processing_time_large')->nullable()->after('predictions_large');
            
            // Comparison & Analysis
            $table->boolean('models_agree')->nullable()->after('processing_time_large');
            $table->string('final_classification')->nullable()->after('models_agree');
            $table->decimal('confidence_difference', 5, 2)->nullable()->after('final_classification');
            $table->json('comparison_analysis')->nullable()->after('confidence_difference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_beans', function (Blueprint $table) {
            $table->dropColumn([
                'classification_small',
                'confidence_small',
                'predictions_small',
                'processing_time_small',
                'classification_large',
                'confidence_large',
                'predictions_large',
                'processing_time_large',
                'models_agree',
                'final_classification',
                'confidence_difference',
                'comparison_analysis'
            ]);
            
            // Restore old columns
            $table->string('classification')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->json('analysis_result')->nullable();
        });
    }
};
