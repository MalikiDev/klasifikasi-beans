<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlaskApiService
{
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.flask.url', 'http://localhost:5000');
        $this->timeout = config('services.flask.timeout', 60); // Increased for dual model
    }

    /**
     * Klasifikasi gambar biji kopi dengan kedua model (MobileNetV3 Small & Large)
     */
    public function classifyImage($imagePath)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify-dual");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            Log::error('Flask API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Klasifikasi dengan model spesifik (untuk testing)
     */
    public function classifyWithModel($imagePath, $modelType = 'small')
    {
        try {
            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify/{$modelType}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['error'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            Log::error('Flask API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Health check Flask API
     */
    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get model info dari Flask API
     */
    public function getModelInfo()
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/model-info");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get model info'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Analyze comparison between two models
     */
    protected function analyzeComparison($smallResult, $largeResult)
    {
        $analysis = [
            'agreement' => $smallResult['class'] === $largeResult['class'],
            'confidence_diff' => abs($smallResult['confidence'] - $largeResult['confidence']),
            'better_model' => $smallResult['confidence'] > $largeResult['confidence'] ? 'small' : 'large',
            'time_diff' => abs($smallResult['processing_time'] - $largeResult['processing_time']),
            'faster_model' => $smallResult['processing_time'] < $largeResult['processing_time'] ? 'small' : 'large'
        ];
        
        return $analysis;
    }
}

