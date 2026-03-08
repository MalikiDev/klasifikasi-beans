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
        'processing_time_large',
        // Batch fields
        'batch_id',
        'batch_sequence',
        'batch_total',
        'upload_mode',
        'source_filename'
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
    
    /**
     * Check if this record is part of a batch
     */
    public function isBatch()
    {
        return $this->upload_mode === 'batch' && !is_null($this->batch_id);
    }
    
    /**
     * Check if this record is from folder upload
     */
    public function isFolder()
    {
        return $this->upload_mode === 'folder' && !is_null($this->batch_id);
    }
    
    /**
     * Check if this record is part of a batch or folder
     */
    public function isBatchOrFolder()
    {
        return in_array($this->upload_mode, ['batch', 'folder']) && !is_null($this->batch_id);
    }
    
    /**
     * Get all records in the same batch
     */
    public function batchSiblings()
    {
        if (!$this->isBatchOrFolder()) {
            return collect([]);
        }
        
        return self::where('batch_id', $this->batch_id)
            ->orderBy('batch_sequence')
            ->get();
    }
    
    /**
     * Get batch statistics
     */
    public function getBatchStats()
    {
        if (!$this->isBatchOrFolder()) {
            return null;
        }
        
        $siblings = $this->batchSiblings();
        
        return [
            'total' => $siblings->count(),
            'classifications' => $siblings->groupBy('final_classification')->map->count(),
            'avg_confidence_small' => $siblings->avg('confidence_small'),
            'avg_confidence_large' => $siblings->avg('confidence_large'),
            'models_agreement_rate' => $siblings->where('models_agree', true)->count() / $siblings->count() * 100,
        ];
    }
    
    /**
     * Get upload mode badge color
     */
    public function getUploadModeBadge()
    {
        return match($this->upload_mode) {
            'single' => ['text' => 'Single', 'color' => 'bg-blue-100 text-blue-800'],
            'batch' => ['text' => 'Batch', 'color' => 'bg-green-100 text-green-800'],
            'folder' => ['text' => 'Folder', 'color' => 'bg-purple-100 text-purple-800'],
            default => ['text' => 'Unknown', 'color' => 'bg-gray-100 text-gray-800'],
        };
    }
}
