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

    // Batch size yang valid — harus sinkron dengan BATCH_SIZES di Flask
    const VALID_BATCH_SIZES = [16, 32, 64];
    const DEFAULT_BATCH_SIZE = 32;

    public function __construct()
    {
        $this->baseUrl       = config('services.flask.url', 'http://localhost:5000');
        $this->timeout       = config('services.flask.timeout', 60);
        $this->batchTimeout  = config('services.flask.batch_timeout', 300);
        $this->folderTimeout = config('services.flask.folder_timeout', 600);
    }

    // ══════════════════════════════════════════════════════════
    // HELPER — Validasi parameter sebelum kirim ke Flask
    // ══════════════════════════════════════════════════════════

    /**
     * Validasi dan sanitasi batch_size.
     * Kalau tidak valid, fallback ke default (32).
     */
    protected function sanitizeBatchSize($batchSize): int
    {
        $bs = (int) $batchSize;
        return in_array($bs, self::VALID_BATCH_SIZES) ? $bs : self::DEFAULT_BATCH_SIZE;
    }

    /**
     * Konversi use_tta ke string 'true'/'false' untuk form-data Flask.
     */
    protected function sanitizeUseTta($useTta): string
    {
        return filter_var($useTta, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    // ══════════════════════════════════════════════════════════
    // CLASSIFY DUAL — Small + Large paralel (endpoint utama)
    // ══════════════════════════════════════════════════════════

    /**
     * Klasifikasi dengan Small + Large secara paralel.
     * Pipeline: Quality Check → CLAHE+Sharpening → TTA → Prediksi
     *
     * @param string $imagePath  Path file gambar
     * @param int    $batchSize  Batch size model: 16, 32, atau 64 (default 32)
     * @param bool   $useTta     Aktifkan TTA (default true)
     */
    public function classifyImage($imagePath, int $batchSize = self::DEFAULT_BATCH_SIZE, bool $useTta = true)
    {
        try {
            $bs  = $this->sanitizeBatchSize($batchSize);
            $tta = $this->sanitizeUseTta($useTta);

            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify-dual", [
                    'batch_size' => $bs,
                    'use_tta'    => $tta,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            // Tangani quality check error (HTTP 422)
            if ($response->status() === 422) {
                $body = $response->json();
                return [
                    'success'          => false,
                    'quality_rejected' => true,
                    'error'            => $body['error'] ?? 'Kualitas gambar tidak memenuhi syarat.',
                    'quality_warnings' => $body['quality_warnings'] ?? [],
                    'suggestion'       => $body['suggestion'] ?? 'Coba foto ulang dengan pencahayaan lebih baik.',
                ];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask classifyImage Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════════════════
    // CLASSIFY SINGLE — Satu model saja
    // ══════════════════════════════════════════════════════════

    /**
     * Klasifikasi dengan satu model saja (small atau large).
     *
     * @param string $imagePath  Path file gambar
     * @param string $modelType  'small' atau 'large'
     * @param int    $batchSize  Batch size: 16, 32, atau 64 (default 32)
     * @param bool   $useTta     Aktifkan TTA (default true)
     */
    public function classifyWithModel($imagePath, string $modelType = 'small', int $batchSize = self::DEFAULT_BATCH_SIZE, bool $useTta = true)
    {
        try {
            if (!in_array($modelType, ['small', 'large'])) {
                return ['success' => false, 'error' => 'modelType harus "small" atau "large"'];
            }

            $bs  = $this->sanitizeBatchSize($batchSize);
            $tta = $this->sanitizeUseTta($useTta);

            $response = Http::timeout($this->timeout)
                ->attach('image', file_get_contents($imagePath), basename($imagePath))
                ->post("{$this->baseUrl}/api/classify/{$modelType}", [
                    'batch_size' => $bs,
                    'use_tta'    => $tta,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            if ($response->status() === 422) {
                $body = $response->json();
                return [
                    'success'          => false,
                    'quality_rejected' => true,
                    'error'            => $body['error'] ?? 'Kualitas gambar tidak memenuhi syarat.',
                    'quality_warnings' => $body['quality_warnings'] ?? [],
                ];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask classifyWithModel Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════════════════
    // CLASSIFY BATCH — Banyak gambar sekaligus
    // ══════════════════════════════════════════════════════════

    /**
     * Klasifikasi banyak gambar sekaligus.
     *
     * @param array      $imagePaths  Array path file gambar
     * @param array|null $labels      Label ground truth (opsional, untuk confusion matrix)
     * @param int        $batchSize   Batch size: 16, 32, atau 64 (default 32)
     * @param bool       $useTta      Aktifkan TTA (default true)
     */
    public function classifyBatch(array $imagePaths, ?array $labels = null, int $batchSize = self::DEFAULT_BATCH_SIZE, bool $useTta = true)
    {
        try {
            $bs  = $this->sanitizeBatchSize($batchSize);
            $tta = $this->sanitizeUseTta($useTta);

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
            $multipart[] = ['name' => 'batch_size',  'contents' => (string) $bs];
            $multipart[] = ['name' => 'use_tta',     'contents' => $tta];

            $response = Http::timeout($this->batchTimeout)
                ->asMultipart()
                ->post("{$this->baseUrl}/api/classify-batch", $multipart);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->json()['error'] ?? 'Unknown error'];

        } catch (\Exception $e) {
            Log::error('Flask classifyBatch Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════════════════
    // CLASSIFY FOLDER — Upload ZIP
    // ══════════════════════════════════════════════════════════

    /**
     * Klasifikasi folder gambar via ZIP.
     *
     * @param string $zipPath    Path file ZIP
     * @param int    $batchSize  Batch size: 16, 32, atau 64 (default 32)
     * @param bool   $useTta     Aktifkan TTA (default true)
     */
    public function classifyFolder($zipPath, int $batchSize = self::DEFAULT_BATCH_SIZE, bool $useTta = true)
    {
        try {
            $bs       = $this->sanitizeBatchSize($batchSize);
            $tta      = $this->sanitizeUseTta($useTta);
            $fileSize = filesize($zipPath);
            $fileName = basename($zipPath);

            Log::info("Sending ZIP to Flask: {$fileName} (" . round($fileSize / 1024 / 1024, 2) . " MB) | BS={$bs} | TTA={$tta}");

            $response = Http::timeout($this->folderTimeout)
                ->attach('folder', file_get_contents($zipPath), $fileName)
                ->post("{$this->baseUrl}/api/classify-folder", [
                    'model_type' => 'both',
                    'batch_size' => $bs,
                    'use_tta'    => $tta,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return [
                'success' => false,
                'error'   => $response->json()['error'] ?? "HTTP {$response->status()}"
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Flask Folder Timeout: ' . $e->getMessage());
            return [
                'success' => false,
                'error'   => 'Koneksi ke Flask timeout. Coba kurangi jumlah gambar dalam ZIP.'
            ];
        } catch (\Exception $e) {
            Log::error('Flask classifyFolder Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════════════════
    // UTILITY
    // ══════════════════════════════════════════════════════════

    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getModelInfo(): array
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

    /**
     * Ambil daftar batch size yang tersedia (untuk populate dropdown di frontend).
     */
    public function getAvailableBatchSizes(): array
    {
        return [
            'batch_sizes'    => self::VALID_BATCH_SIZES,
            'default'        => self::DEFAULT_BATCH_SIZE,
            'recommendations' => [
                16 => 'Akurasi tertinggi — disarankan untuk produksi',
                32 => 'Seimbang antara akurasi dan kecepatan (default)',
                64 => 'Eksperimental — Small model kurang akurat',
            ],
        ];
    }

    public function saveConfusionMatrixImage($base64Image, $filename): ?string
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
}