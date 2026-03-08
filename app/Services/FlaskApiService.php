<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlaskApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $batchTimeout;
    protected $folderTimeout;

    public function __construct()
    {
        $this->baseUrl       = config('services.flask.url', 'http://localhost:5000');
        $this->timeout       = config('services.flask.timeout', 60);
        $this->batchTimeout  = config('services.flask.batch_timeout', 300); // 5 menit untuk batch
        $this->folderTimeout = config('services.flask.folder_timeout', 600); // 10 menit untuk folder
    }

    public function classifyImage($imagePath)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify-dual");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function classifyWithModel($imagePath, $modelType = 'small')
    {
        try {
            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify/{$modelType}");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getModelInfo()
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/model-info");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'Failed to get model info'];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function classifyBatch(array $imagePaths, ?array $labels = null)
    {
        try {
            $multipart = [];

            foreach ($imagePaths as $imagePath) {
                $multipart[] = [
                    'name'     => 'images',
                    'contents' => fopen($imagePath, 'r'),
                    'filename' => basename($imagePath),
                ];
            }

            if ($labels && count($labels) === count($imagePaths)) {
                foreach ($labels as $label) {
                    $multipart[] = ['name' => 'labels[]', 'contents' => $label];
                }
            }

            $multipart[] = ['name' => 'model_type', 'contents' => 'both'];

            $response = Http::timeout($this->batchTimeout)  // ✅ pakai batchTimeout (5 menit)
                ->asMultipart()
                ->post("{$this->baseUrl}/api/classify-batch", $multipart);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask Batch API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Classify folder ZIP — gunakan timeout yang lebih besar
     * karena Flask perlu extract ZIP + proses semua gambar
     */
    public function classifyFolder($zipPath)
    {
        try {
            $fileSize = filesize($zipPath);
            $fileName = basename($zipPath);

            Log::info("Sending ZIP to Flask: {$fileName} (" . round($fileSize/1024/1024, 2) . " MB)");

            $response = Http::timeout($this->folderTimeout)  // ✅ pakai folderTimeout (10 menit)
                ->attach('folder', file_get_contents($zipPath), $fileName)
                ->post("{$this->baseUrl}/api/classify-folder", [
                    'model_type' => 'both',
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return [
                'success' => false,
                'error'   => $response->json()['error'] ?? "HTTP {$response->status()}"
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Timeout atau koneksi gagal
            Log::error('Flask Folder Timeout: ' . $e->getMessage());
            return [
                'success' => false,
                'error'   => 'Koneksi ke Flask timeout. Coba kurangi jumlah gambar dalam ZIP, atau periksa apakah Flask sedang berjalan.'
            ];
        } catch (\Exception $e) {
            Log::error('Flask Folder API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function saveConfusionMatrixImage($base64Image, $filename)
    {
        try {
            $imageData = base64_decode($base64Image);
            $path      = 'confusion-matrices/' . $filename;
            \Storage::disk('public')->put($path, $imageData);
            return $path;
        } catch (\Exception $e) {
            Log::error('Error saving confusion matrix: ' . $e->getMessage());
            return null;
        }
    }

    protected function analyzeComparison($smallResult, $largeResult)
    {
        return [
            'agreement'    => $smallResult['class'] === $largeResult['class'],
            'better_model' => $smallResult['confidence'] > $largeResult['confidence'] ? 'small' : 'large',
            'faster_model' => $smallResult['processing_time'] < $largeResult['processing_time'] ? 'small' : 'large',
        ];
    }
}