<?php

namespace App\Http\Controllers;

use App\Models\CoffeeBeans;
use App\Services\FlaskApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoffeeBeansController extends Controller
{
    protected $flaskApi;

    public function __construct(FlaskApiService $flaskApi)
    {
        $this->flaskApi = $flaskApi;
    }

    /**
     * Display a listing of coffee beans
     */
    public function index()
    {
        $coffeeBeans = CoffeeBeans::latest()->paginate(12);
        return view('coffee.index', compact('coffeeBeans'));
    }

    /**
     * Show the form for creating a new coffee bean
     */
    public function create()
    {
        return view('coffee.create');
    }

    /**
     * Store a newly created coffee bean
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Upload image
        $imagePath = $request->file('image')->store('coffee-beans', 'public');
        $fullPath = storage_path('app/public/' . $imagePath);

        // Classify dengan Flask API (dual model)
        $classification = $this->flaskApi->classifyImage($fullPath);

        if (!$classification['success']) {
            // Hapus gambar jika klasifikasi gagal
            Storage::disk('public')->delete($imagePath);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal melakukan klasifikasi: ' . $classification['error']);
        }

        $data = $classification['data'];
        
        // Determine final classification (consensus or highest confidence)
        $modelsAgree = $data['small']['class'] === $data['large']['class'];
        $finalClass = $modelsAgree ? $data['small']['class'] : 
            ($data['small']['confidence'] > $data['large']['confidence'] ? 
                $data['small']['class'] : $data['large']['class']);
        
        // Generate nama otomatis
        $timestamp = now()->format('YmdHis');
        $autoName = "Biji Kopi {$finalClass} - {$timestamp}";
        
        // Deskripsi dari final classification
        $description = \App\Helpers\RoastingHelper::getDescription($finalClass);
        
        // Calculate confidence difference
        $confidenceDiff = abs($data['small']['confidence'] - $data['large']['confidence']);

        $coffeeBean = CoffeeBeans::create([
            'name' => $autoName,
            'variety' => null,
            'origin' => null,
            'description' => $description,
            'image_path' => $imagePath,
            
            // MobileNetV3 Small
            'classification_small' => $data['small']['class'],
            'confidence_small' => $data['small']['confidence'],
            'predictions_small' => $data['small']['predictions'],
            'processing_time_small' => $data['small']['processing_time'] ?? null,
            
            // MobileNetV3 Large
            'classification_large' => $data['large']['class'],
            'confidence_large' => $data['large']['confidence'],
            'predictions_large' => $data['large']['predictions'],
            'processing_time_large' => $data['large']['processing_time'] ?? null,
            
            // Comparison
            'models_agree' => $modelsAgree,
            'final_classification' => $finalClass,
            'confidence_difference' => $confidenceDiff,
            'comparison_analysis' => $data['comparison'] ?? null
        ]);

        return redirect()->route('coffee.show', $coffeeBean)
            ->with('success', 'Data biji kopi berhasil ditambahkan dan diklasifikasi dengan 2 model!');
    }

    /**
     * Display the specified coffee bean
     */
    public function show(CoffeeBeans $coffeeBean)
    {
        return view('coffee.show', compact('coffeeBean'));
    }

    /**
     * Show the form for editing the specified coffee bean
     */
    public function edit(CoffeeBeans $coffeeBean)
    {
        return view('coffee.edit', compact('coffeeBean'));
    }

    /**
     * Update the specified coffee bean
     */
    public function update(Request $request, CoffeeBeans $coffeeBean)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'variety' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $validated['name'] ?? $coffeeBean->name,
            'variety' => $validated['variety'] ?? null,
            'origin' => $validated['origin'] ?? null,
            'description' => $validated['description'] ?? $coffeeBean->description,
        ];

        // Jika ada image baru
        if ($request->hasFile('image')) {
            // Hapus image lama
            if ($coffeeBean->image_path) {
                Storage::disk('public')->delete($coffeeBean->image_path);
            }

            // Upload image baru
            $imagePath = $request->file('image')->store('coffee-beans', 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            // Re-classify dengan Flask API
            $classification = $this->flaskApi->classifyImage($fullPath);

            if ($classification['success']) {
                $roastLevel = $classification['data']['class'] ?? 'Unknown';
                
                // Update nama jika masih menggunakan nama auto-generated
                if (strpos($coffeeBean->name, 'Biji Kopi') === 0) {
                    $timestamp = now()->format('YmdHis');
                    $data['name'] = "Biji Kopi {$roastLevel} - {$timestamp}";
                }
                
                // Update deskripsi dengan hasil baru
                $data['description'] = $classification['data']['description'] ?? \App\Helpers\RoastingHelper::getDescription($roastLevel);
                $data['image_path'] = $imagePath;
                $data['classification'] = $roastLevel;
                $data['confidence'] = $classification['data']['confidence'] ?? null;
                $data['analysis_result'] = $classification['data'];
            } else {
                // Jika klasifikasi gagal, tetap simpan gambar tapi tanpa update klasifikasi
                $data['image_path'] = $imagePath;
            }
        }

        $coffeeBean->update($data);

        return redirect()->route('coffee.show', $coffeeBean)
            ->with('success', 'Data biji kopi berhasil diupdate!');
    }

    /**
     * Remove the specified coffee bean
     */
    public function destroy(CoffeeBeans $coffeeBean)
    {
        // Hapus image
        if ($coffeeBean->image_path) {
            Storage::disk('public')->delete($coffeeBean->image_path);
        }

        $coffeeBean->delete();

        return redirect()->route('coffee.index')
            ->with('success', 'Data biji kopi berhasil dihapus!');
    }

    /**
     * Reclassify image dengan Flask API (dual model)
     */
    public function reclassify(CoffeeBeans $coffeeBean)
    {
        if (!$coffeeBean->image_path) {
            return back()->with('error', 'Tidak ada gambar untuk diklasifikasi!');
        }

        $fullPath = storage_path('app/public/' . $coffeeBean->image_path);
        $classification = $this->flaskApi->classifyImage($fullPath);

        if ($classification['success']) {
            $data = $classification['data'];
            
            $modelsAgree = $data['small']['class'] === $data['large']['class'];
            $finalClass = $modelsAgree ? $data['small']['class'] : 
                ($data['small']['confidence'] > $data['large']['confidence'] ? 
                    $data['small']['class'] : $data['large']['class']);
            
            $confidenceDiff = abs($data['small']['confidence'] - $data['large']['confidence']);

            $coffeeBean->update([
                'classification_small' => $data['small']['class'],
                'confidence_small' => $data['small']['confidence'],
                'predictions_small' => $data['small']['predictions'],
                'processing_time_small' => $data['small']['processing_time'] ?? null,
                
                'classification_large' => $data['large']['class'],
                'confidence_large' => $data['large']['confidence'],
                'predictions_large' => $data['large']['predictions'],
                'processing_time_large' => $data['large']['processing_time'] ?? null,
                
                'models_agree' => $modelsAgree,
                'final_classification' => $finalClass,
                'confidence_difference' => $confidenceDiff,
                'comparison_analysis' => $data['comparison'] ?? null
            ]);

            return back()->with('success', 'Klasifikasi berhasil diperbarui dengan kedua model!');
        }

        return back()->with('error', 'Gagal melakukan klasifikasi: ' . $classification['error']);
    }
}
