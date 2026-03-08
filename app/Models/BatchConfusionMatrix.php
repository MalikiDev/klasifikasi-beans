<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchConfusionMatrix extends Model
{
    protected $fillable = [
        'batch_id',
        'confusion_matrix_small_path',
        'confusion_matrix_small_data',
        'accuracy_small',
        'per_class_accuracy_small',
        'confusion_matrix_large_path',
        'confusion_matrix_large_data',
        'accuracy_large',
        'per_class_accuracy_large',
        'total_images',
        'class_distribution'
    ];

    protected $casts = [
        'confusion_matrix_small_data' => 'array',
        'per_class_accuracy_small' => 'array',
        'confusion_matrix_large_data' => 'array',
        'per_class_accuracy_large' => 'array',
        'class_distribution' => 'array',
        'accuracy_small' => 'decimal:2',
        'accuracy_large' => 'decimal:2'
    ];
    
    /**
     * Get the batch items associated with this confusion matrix
     */
    public function batchItems()
    {
        return CoffeeBeans::where('batch_id', $this->batch_id)->get();
    }
    
    /**
     * Get confusion matrix image URL
     */
    public function getConfusionMatrixSmallUrl()
    {
        return $this->confusion_matrix_small_path 
            ? asset('storage/' . $this->confusion_matrix_small_path)
            : null;
    }
    
    public function getConfusionMatrixLargeUrl()
    {
        return $this->confusion_matrix_large_path 
            ? asset('storage/' . $this->confusion_matrix_large_path)
            : null;
    }
}
