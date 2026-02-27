<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoffeeBeans extends Model
{
    protected $fillable = [
        'name',
        'variety',
        'origin',
        'description',
        'image_path',
        'classification_small',
        'confidence_small',
        'predictions_small',
        'classification_large',
        'confidence_large',
        'predictions_large',
        'models_agree',
        'final_classification',
        'confidence_difference',
        'comparison_analysis',
        'processing_time_small',
        'processing_time_large'
    ];

    protected $casts = [
        'predictions_small' => 'array',
        'predictions_large' => 'array',
        'comparison_analysis' => 'array',
        'confidence_small' => 'decimal:2',
        'confidence_large' => 'decimal:2',
        'confidence_difference' => 'decimal:2',
        'models_agree' => 'boolean'
    ];
    
    /**
     * Get the better performing model for this classification
     */
    public function getBetterModel()
    {
        if ($this->confidence_small > $this->confidence_large) {
            return 'small';
        } elseif ($this->confidence_large > $this->confidence_small) {
            return 'large';
        }
        return 'equal';
    }
    
    /**
     * Get model agreement status
     */
    public function getAgreementStatus()
    {
        if ($this->models_agree) {
            return 'Kedua model setuju';
        }
        return 'Model berbeda pendapat';
    }
}
