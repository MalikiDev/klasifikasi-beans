<?php

namespace App\Http\Controllers;

use App\Models\CoffeeBeans;
use App\Services\FlaskApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CoffeeBeansController extends Controller
{
    protected $flaskApi;

    public function __construct(FlaskApiService $flaskApi)
    {
        $this->flaskApi = $flaskApi;
    }

    public function index()
    {
        $coffeeBeans = CoffeeBeans::latest()->paginate(12);
        return view('coffee.index', compact('coffeeBeans'));
    }

    public function create()
    {
        return view('coffee.create');
    }

    public function store(Request $request)
    {
        $mode = $request->input('mode', 'single');
        return match ($mode) {
            'batch'  => $this->storeBatch($request),
            'folder' => $this->storeFolder($request),
            default  => $this->storeSingle($request),
        };
    }

    // ══════════════════════════════════════════════
    // MODE 1: SINGLE
    // ══════════════════════════════════════════════

    protected function storeSingle(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = $request->file('image')->store('coffee-beans', 'public');
        $fullPath  = storage_path('app/public/' . $imagePath);

        $classification = $this->flaskApi->classifyImage($fullPath);

        if (!$classification['success']) {
            Storage::disk('public')->delete($imagePath);
            return redirect()->route('coffee.create')
                ->withInput()
                ->with('error', 'Gagal klasifikasi: ' . $classification['error']);
        }

        $data       = $classification['data'];
        $finalClass = $this->resolveFinalClass($data['small'], $data['large']);
        $confDiff   = abs($data['small']['confidence'] - $data['large']['confidence']);

        $coffee = CoffeeBeans::create([
            'name'                  => "Biji Kopi {$finalClass} - " . now()->format('YmdHis'),
            'description'           => \App\Helpers\RoastingHelper::getDescription($finalClass),
            'image_path'            => $imagePath,
            'upload_mode'           => 'single',
            'classification_small'  => $data['small']['class'],
            'confidence_small'      => $data['small']['confidence'],
            'predictions_small'     => $data['small']['predictions'],
            'processing_time_small' => $data['small']['processing_time'] ?? null,
            'classification_large'  => $data['large']['class'],
            'confidence_large'      => $data['large']['confidence'],
            'predictions_large'     => $data['large']['predictions'],
            'processing_time_large' => $data['large']['processing_time'] ?? null,
            'models_agree'          => $data['small']['class'] === $data['large']['class'],
            'final_classification'  => $finalClass,
            'confidence_difference' => $confDiff,
            'comparison_analysis'   => $data['comparison'] ?? null,
        ]);

        return redirect()->route('coffee.show', $coffee->id)
            ->with('success', 'Berhasil diklasifikasi dengan 2 model!');
    }

    // ══════════════════════════════════════════════
    // MODE 2: BATCH
    // ══════════════════════════════════════════════

    protected function storeBatch(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'image'   => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $images = $request->file('image');
        if (!is_array($images)) $images = [$images];
        $images = array_values(array_filter($images, fn($f) =>
            $f && $f->isValid() && in_array($f->extension(), ['jpg', 'jpeg', 'png'])
        ));

        if (empty($images)) {
            return redirect()->route('coffee.create')->with('error', 'Tidak ada gambar valid!');
        }

        $batchId     = 'BATCH-' . now()->format('YmdHis') . '-' . uniqid();
        $totalImages = count($images);
        $imagePaths  = [];
        $tempFiles   = [];

        foreach ($images as $image) {
            $tempPath     = $image->store('temp', 'public');
            $imagePaths[] = storage_path('app/public/' . $tempPath);
            $tempFiles[]  = $tempPath;
        }

        $batchResult = $this->flaskApi->classifyBatch($imagePaths, null);
        foreach ($tempFiles as $tf) Storage::disk('public')->delete($tf);

        if (!$batchResult['success']) {
            return redirect()->route('coffee.create')
                ->with('error', 'Batch classification gagal: ' . $batchResult['error']);
        }

        $data = $batchResult['data'];

        foreach ($data['results'] as $index => $result) {
            try {
                $newPath    = $images[$index]->store('coffee-beans', 'public');
                $finalClass = $this->resolveFinalClass($result['small'], $result['large']);
                $confDiff   = abs($result['small']['confidence'] - $result['large']['confidence']);

                CoffeeBeans::create([
                    'name'                  => "Batch {$finalClass} - " . ($index + 1) . "/{$totalImages}",
                    'description'           => \App\Helpers\RoastingHelper::getDescription($finalClass),
                    'image_path'            => $newPath,
                    'upload_mode'           => 'batch',
                    'batch_id'              => $batchId,
                    'batch_sequence'        => $index + 1,
                    'batch_total'           => $totalImages,
                    'classification_small'  => $result['small']['class'],
                    'confidence_small'      => $result['small']['confidence'],
                    'predictions_small'     => $result['small']['predictions'],
                    'processing_time_small' => $result['small']['processing_time'] ?? null,
                    'classification_large'  => $result['large']['class'],
                    'confidence_large'      => $result['large']['confidence'],
                    'predictions_large'     => $result['large']['predictions'],
                    'processing_time_large' => $result['large']['processing_time'] ?? null,
                    'models_agree'          => $result['small']['class'] === $result['large']['class'],
                    'final_classification'  => $finalClass,
                    'confidence_difference' => $confDiff,
                ]);
            } catch (\Exception $e) {
                Log::error("Batch save error index {$index}: " . $e->getMessage());
            }
        }

        if (!empty($data['confusion_matrix'])) {
            $this->saveConfusionMatrices($batchId, $data['confusion_matrix'], $data['statistics']);
        }

        return redirect()->route('coffee.batch-results', $batchId)
            ->with('success', "{$totalImages} gambar selesai diklasifikasi!");
    }

    // ══════════════════════════════════════════════
    // MODE 3: FOLDER ZIP — FIXED
    // ══════════════════════════════════════════════

    protected function storeFolder(Request $request)
    {
        set_time_limit(600);

        $request->validate([
            'folder' => 'required|file|max:102400',
        ]);

        // 1. Simpan ZIP sementara
        $zipFile     = $request->file('folder');
        $zipPath     = $zipFile->store('temp-zips', 'public');
        $fullZipPath = storage_path('app/public/' . $zipPath);

        Log::info("[storeFolder] ZIP saved: {$fullZipPath}");

        // 2. Kirim ke Flask
        $folderResult = $this->flaskApi->classifyFolder($fullZipPath);
        Storage::disk('public')->delete($zipPath); // hapus ZIP temp

        if (!$folderResult['success']) {
            Log::error("[storeFolder] Flask error: " . $folderResult['error']);
            return redirect()->route('coffee.create')
                ->with('error', 'Folder classification gagal: ' . $folderResult['error']);
        }

        $data       = $folderResult['data'];
        $apiResults = $data['results'] ?? [];
        $total      = count($apiResults);

        Log::info("[storeFolder] Flask returned {$total} results");

        if ($total === 0) {
            return redirect()->route('coffee.create')
                ->with('error', 'Tidak ada gambar valid ditemukan dalam ZIP!');
        }

        // 3. Simpan semua hasil ke database
        $batchId      = 'FOLDER-' . now()->format('YmdHis') . '-' . uniqid();
        $successCount = 0;

        foreach ($apiResults as $index => $result) {
            try {
                // Guard: pastikan data model ada
                if (empty($result['small']['class']) || empty($result['large']['class'])) {
                    Log::warning("[storeFolder] Skipping index {$index} - incomplete result");
                    continue;
                }

                $finalClass = $this->resolveFinalClass($result['small'], $result['large']);
                $confDiff   = abs($result['small']['confidence'] - $result['large']['confidence']);

                CoffeeBeans::create([
                    'name'                  => "Folder {$finalClass} - " . ($index + 1) . "/{$total}",
                    'description'           => \App\Helpers\RoastingHelper::getDescription($finalClass),
                    'image_path'            => null,
                    'upload_mode'           => 'folder',
                    'batch_id'              => $batchId,
                    'batch_sequence'        => $index + 1,
                    'batch_total'           => $total,
                    'source_filename'       => $result['filename'] ?? null,
                    'classification_small'  => $result['small']['class'],
                    'confidence_small'      => $result['small']['confidence'],
                    'predictions_small'     => $result['small']['predictions'] ?? null,
                    'processing_time_small' => $result['small']['processing_time'] ?? null,
                    'classification_large'  => $result['large']['class'],
                    'confidence_large'      => $result['large']['confidence'],
                    'predictions_large'     => $result['large']['predictions'] ?? null,
                    'processing_time_large' => $result['large']['processing_time'] ?? null,
                    'models_agree'          => $result['small']['class'] === $result['large']['class'],
                    'final_classification'  => $finalClass,
                    'confidence_difference' => $confDiff,
                    'comparison_analysis'   => null,
                ]);

                $successCount++;

            } catch (\Exception $e) {
                Log::error("[storeFolder] Save error index {$index}: " . $e->getMessage());
            }
        }

        Log::info("[storeFolder] Saved {$successCount}/{$total}, batch_id={$batchId}");

        if ($successCount === 0) {
            return redirect()->route('coffee.create')
                ->with('error', 'Semua gambar gagal disimpan. Cek log untuk detail.');
        }

        // 4. Simpan confusion matrix
        if (!empty($data['confusion_matrix'])) {
            $this->saveConfusionMatrices($batchId, $data['confusion_matrix'], $data['statistics']);
        }

        // 5. Simpan folder meta ke Cache (bukan session — aman untuk data besar)
        \Cache::put("folder_meta_{$batchId}", [
            'structure'            => $data['folder_structure'] ?? 'flat',
            'auto_labels'          => $data['auto_labels_detected'] ?? false,
            'has_confusion_matrix' => !empty($data['confusion_matrix']),
            'total_images'         => $total,
            'success_count'        => $successCount,
        ], now()->addMinutes(30));

        return redirect()->route('coffee.folder-results', $batchId)
            ->with('success', "{$successCount} gambar dari ZIP berhasil diklasifikasi!");
    }

    // ══════════════════════════════════════════════
    // RESULTS PAGES
    // ══════════════════════════════════════════════

    public function batchResults($batchId)
    {
        $batchItems = CoffeeBeans::where('batch_id', $batchId)
            ->orderBy('batch_sequence')->get();

        if ($batchItems->isEmpty()) {
            return redirect()->route('coffee.index')->with('error', 'Batch tidak ditemukan!');
        }

        $stats           = $this->calculateStats($batchItems);
        $confusionMatrix = \App\Models\BatchConfusionMatrix::where('batch_id', $batchId)->first();

        return view('coffee.batch-results', compact('batchItems', 'stats', 'batchId', 'confusionMatrix'));
    }

    public function folderResults($batchId)
    {
        // ✅ Query dari DB — tidak bergantung session
        $folderItems = CoffeeBeans::where('batch_id', $batchId)
            ->where('upload_mode', 'folder')
            ->orderBy('batch_sequence')
            ->get();

        if ($folderItems->isEmpty()) {
            Log::warning("[folderResults] No items found for batch_id={$batchId}");
            return redirect()->route('coffee.index')
                ->with('error', 'Data folder tidak ditemukan!');
        }

        $stats           = $this->calculateStats($folderItems);
        $confusionMatrix = \App\Models\BatchConfusionMatrix::where('batch_id', $batchId)->first();

        // Ambil meta dari cache
        $folderMeta = \Cache::get("folder_meta_{$batchId}", [
            'structure'            => 'flat',
            'auto_labels'          => $confusionMatrix !== null,
            'has_confusion_matrix' => $confusionMatrix !== null,
            'total_images'         => $folderItems->count(),
            'success_count'        => $folderItems->count(),
        ]);

        return view('coffee.folder-results', compact(
            'folderItems', 'stats', 'batchId', 'confusionMatrix', 'folderMeta'
        ));
    }

    // ══════════════════════════════════════════════
    // CRUD STANDARD
    // ══════════════════════════════════════════════

    public function show(CoffeeBeans $coffee)
    {
        return view('coffee.show', compact('coffee'));
    }

    public function edit(CoffeeBeans $coffee)
    {
        return view('coffee.edit', compact('coffee'));
    }

    public function update(Request $request, CoffeeBeans $coffee)
    {
        $validated = $request->validate([
            'name'        => 'nullable|string|max:255',
            'variety'     => 'nullable|string|max:255',
            'origin'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name'        => $validated['name']        ?? $coffee->name,
            'variety'     => $validated['variety']     ?? null,
            'origin'      => $validated['origin']      ?? null,
            'description' => $validated['description'] ?? $coffee->description,
        ];

        if ($request->hasFile('image')) {
            if ($coffee->image_path) Storage::disk('public')->delete($coffee->image_path);

            $imagePath      = $request->file('image')->store('coffee-beans', 'public');
            $fullPath       = storage_path('app/public/' . $imagePath);
            $classification = $this->flaskApi->classifyImage($fullPath);

            if ($classification['success']) {
                $apiData    = $classification['data'];
                $finalClass = $this->resolveFinalClass($apiData['small'], $apiData['large']);

                if (str_starts_with($coffee->name, 'Biji Kopi')) {
                    $data['name'] = "Biji Kopi {$finalClass} - " . now()->format('YmdHis');
                }
                $data['description']          = \App\Helpers\RoastingHelper::getDescription($finalClass);
                $data['image_path']           = $imagePath;
                $data['final_classification'] = $finalClass;
                $data['classification_small'] = $apiData['small']['class'];
                $data['confidence_small']     = $apiData['small']['confidence'];
                $data['classification_large'] = $apiData['large']['class'];
                $data['confidence_large']     = $apiData['large']['confidence'];
                $data['models_agree']         = $apiData['small']['class'] === $apiData['large']['class'];
            } else {
                $data['image_path'] = $imagePath;
            }
        }

        $coffee->update($data);
        return redirect()->route('coffee.show', $coffee)->with('success', 'Data berhasil diupdate!');
    }

    public function destroy(CoffeeBeans $coffee)
    {
        if ($coffee->image_path) Storage::disk('public')->delete($coffee->image_path);
        $coffee->delete();
        return redirect()->route('coffee.index')->with('success', 'Data berhasil dihapus!');
    }

    public function reclassify(CoffeeBeans $coffee)
    {
        if (!$coffee->image_path) {
            return redirect()->route('coffee.show', $coffee)
                ->with('error', 'Tidak ada gambar untuk diklasifikasi!');
        }

        $fullPath       = storage_path('app/public/' . $coffee->image_path);
        $classification = $this->flaskApi->classifyImage($fullPath);

        if (!$classification['success']) {
            return redirect()->route('coffee.show', $coffee)
                ->with('error', 'Gagal klasifikasi: ' . $classification['error']);
        }

        $data       = $classification['data'];
        $finalClass = $this->resolveFinalClass($data['small'], $data['large']);
        $confDiff   = abs($data['small']['confidence'] - $data['large']['confidence']);

        $coffee->update([
            'classification_small'  => $data['small']['class'],
            'confidence_small'      => $data['small']['confidence'],
            'predictions_small'     => $data['small']['predictions'],
            'processing_time_small' => $data['small']['processing_time'] ?? null,
            'classification_large'  => $data['large']['class'],
            'confidence_large'      => $data['large']['confidence'],
            'predictions_large'     => $data['large']['predictions'],
            'processing_time_large' => $data['large']['processing_time'] ?? null,
            'models_agree'          => $data['small']['class'] === $data['large']['class'],
            'final_classification'  => $finalClass,
            'confidence_difference' => $confDiff,
            'comparison_analysis'   => $data['comparison'] ?? null,
        ]);

        return redirect()->route('coffee.show', $coffee)->with('success', 'Klasifikasi berhasil diperbarui!');
    }

    public function batches()
    {
        $batches = CoffeeBeans::whereNotNull('batch_id')
            ->select('batch_id', 'batch_total', 'upload_mode', 'created_at')
            ->groupBy('batch_id', 'batch_total', 'upload_mode', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $batches->getCollection()->transform(function ($batch) {
            $items        = CoffeeBeans::where('batch_id', $batch->batch_id)->get();
            $batch->stats = [
                'total'           => $items->count(),
                'classifications' => $items->groupBy('final_classification')->map->count(),
                'avg_confidence'  => round(($items->avg('confidence_small') + $items->avg('confidence_large')) / 2, 2),
            ];
            return $batch;
        });

        return view('coffee.batches', compact('batches'));
    }

    // ══════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════

    protected function resolveFinalClass(array $small, array $large): string
    {
        if ($small['class'] === $large['class']) return $small['class'];
        return $small['confidence'] > $large['confidence'] ? $small['class'] : $large['class'];
    }

    protected function saveConfusionMatrices(string $batchId, array $confusionMatrices, array $statistics): void
    {
        try {
            $cmData = [
                'batch_id'           => $batchId,
                'total_images'       => $statistics['total'],
                'class_distribution' => $statistics['classifications'],
            ];

            foreach (['small', 'large'] as $model) {
                if (!isset($confusionMatrices[$model])) continue;
                $cm   = $confusionMatrices[$model];
                $path = $this->flaskApi->saveConfusionMatrixImage($cm['image_base64'], "{$model}-{$batchId}.png");

                $cmData["confusion_matrix_{$model}_path"] = $path;
                $cmData["confusion_matrix_{$model}_data"] = $cm['matrix'];
                $cmData["accuracy_{$model}"]              = $cm['accuracy'];
                $cmData["per_class_accuracy_{$model}"]    = $cm['per_class_accuracy'];
            }

            \App\Models\BatchConfusionMatrix::create($cmData);
        } catch (\Exception $e) {
            Log::error("saveConfusionMatrices error: " . $e->getMessage());
        }
    }

    private function calculateStats($items): array
    {
        $total = $items->count();
        if ($total === 0) return [];

        return [
            'total'                     => $total,
            'classifications'           => $items->groupBy('final_classification')->map->count(),
            'avg_confidence_small'      => round($items->avg('confidence_small'), 2),
            'avg_confidence_large'      => round($items->avg('confidence_large'), 2),
            'models_agreement_rate'     => round($items->where('models_agree', true)->count() / $total * 100, 2),
            'avg_processing_time_small' => round($items->avg('processing_time_small'), 3),
            'avg_processing_time_large' => round($items->avg('processing_time_large'), 3),
        ];
    }
}