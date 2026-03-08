# 📊 Confusion Matrix Implementation Guide

## 🎯 Overview

Confusion Matrix akan di-generate otomatis saat melakukan batch classification, memberikan visualisasi performa model dalam bentuk heatmap yang mudah dipahami.

---

## 🔄 Alur Kerja

```
┌─────────────────────────────────────────────────────────────┐
│  1. User Upload Batch Folder                                │
│     - Multiple images (10-50 images recommended)            │
│     - Optional: Provide ground truth labels                 │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  2. Laravel Collect Images & Labels                         │
│     - Store images to storage                               │
│     - Prepare image paths array                             │
│     - Prepare labels array (if provided)                    │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  3. Send to Flask API (/api/classify-batch)                 │
│     POST with:                                              │
│     - images: [file1, file2, ...]                          │
│     - labels: ['Light Roast', 'Medium Roast', ...]         │
│     - model_type: 'both'                                    │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  4. Flask Process Each Image                                │
│     For each image:                                         │
│     - Classify with MobileNetV3 Small                       │
│     - Classify with MobileNetV3 Large                       │
│     - Store predictions                                     │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  5. Flask Generate Confusion Matrices                       │
│     If labels provided:                                     │
│     - Calculate confusion matrix for Small model            │
│     - Calculate confusion matrix for Large model            │
│     - Generate heatmap visualization (PNG)                  │
│     - Convert to base64                                     │
│     - Calculate accuracy metrics                            │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  6. Laravel Receive Response                                │
│     - Individual predictions for each image                 │
│     - Batch statistics                                      │
│     - Confusion matrix images (base64)                      │
│     - Accuracy metrics                                      │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  7. Laravel Save Everything                                 │
│     - Save each image classification to coffee_beans        │
│     - Decode base64 confusion matrix images                 │
│     - Save confusion matrix PNGs to storage                 │
│     - Save confusion matrix data to batch_confusion_matrices│
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  8. Display Batch Results Page                              │
│     - Show batch statistics                                 │
│     - Display confusion matrix images                       │
│     - Show accuracy metrics                                 │
│     - Grid view of all classifications                      │
└─────────────────────────────────────────────────────────────┘
```

---

## 💾 Database Structure

### **Table: `batch_confusion_matrices`**

```sql
CREATE TABLE batch_confusion_matrices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    batch_id VARCHAR(255) UNIQUE,
    
    -- MobileNetV3 Small
    confusion_matrix_small_path VARCHAR(255),
    confusion_matrix_small_data JSON,
    accuracy_small DECIMAL(5,2),
    per_class_accuracy_small JSON,
    
    -- MobileNetV3 Large
    confusion_matrix_large_path VARCHAR(255),
    confusion_matrix_large_data JSON,
    accuracy_large DECIMAL(5,2),
    per_class_accuracy_large JSON,
    
    -- Metadata
    total_images INT,
    class_distribution JSON,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX(batch_id)
);
```

### **Example Data:**

```json
{
  "id": 1,
  "batch_id": "BATCH-20260306120000-abc123",
  "confusion_matrix_small_path": "confusion-matrices/small-BATCH-20260306120000-abc123.png",
  "confusion_matrix_small_data": [[8,1,0,0], [0,9,1,0], [1,0,7,1], [0,0,1,8]],
  "accuracy_small": 85.00,
  "per_class_accuracy_small": {
    "Light Roast": 88.89,
    "Medium Roast": 90.00,
    "Medium Dark Roast": 77.78,
    "Dark Roast": 88.89
  },
  "confusion_matrix_large_path": "confusion-matrices/large-BATCH-20260306120000-abc123.png",
  "confusion_matrix_large_data": [[9,0,0,0], [0,10,0,0], [0,1,8,0], [0,0,0,9]],
  "accuracy_large": 94.74,
  "per_class_accuracy_large": {
    "Light Roast": 100.00,
    "Medium Roast": 100.00,
    "Medium Dark Roast": 88.89,
    "Dark Roast": 100.00
  },
  "total_images": 38,
  "class_distribution": {
    "Light Roast": 9,
    "Medium Roast": 10,
    "Medium Dark Roast": 9,
    "Dark Roast": 10
  }
}
```

---

## 🎨 Confusion Matrix Visualization

### **Generated Image Example:**

```
┌─────────────────────────────────────────────────────────────┐
│  Confusion Matrix - MobileNetV3 Small                       │
│  Accuracy: 85.00%                                           │
│                                                             │
│              Light   Medium  Med-Dark  Dark                 │
│  Light        8       1        0       0     (88.89%)      │
│  Medium       0       9        1       0     (90.00%)      │
│  Med-Dark     1       0        7       1     (77.78%)      │
│  Dark         0       0        1       8     (88.89%)      │
│                                                             │
│  True Label ↓                                               │
│  Predicted Label →                                          │
└─────────────────────────────────────────────────────────────┘
```

### **Color Scheme:**
- **Dark Blue**: High values (correct predictions)
- **Light Blue**: Medium values
- **White**: Low/zero values (misclassifications)

---

## 📊 Metrics Explained

### **1. Overall Accuracy**
```
Accuracy = (Correct Predictions) / (Total Predictions)
         = (8 + 9 + 7 + 8) / 38
         = 32 / 38
         = 84.21%
```

### **2. Per-Class Accuracy**
```
Light Roast Accuracy = 8 / (8+1+0+0) = 88.89%
Medium Roast Accuracy = 9 / (0+9+1+0) = 90.00%
Medium Dark Roast Accuracy = 7 / (1+0+7+1) = 77.78%
Dark Roast Accuracy = 8 / (0+0+1+8) = 88.89%
```

### **3. Confusion Matrix Interpretation**

```
                Predicted
              L    M    MD   D
True    L    [8]   1    0    0    ← 8 correct, 1 misclassified as Medium
        M     0   [9]   1    0    ← 9 correct, 1 misclassified as Med-Dark
        MD    1    0   [7]   1    ← 7 correct, 1 as Light, 1 as Dark
        D     0    0    1   [8]   ← 8 correct, 1 misclassified as Med-Dark
```

**Diagonal values** = Correct predictions
**Off-diagonal values** = Misclassifications

---

## 🔧 Implementation Steps

### **Step 1: Update Controller untuk Generate Confusion Matrix**

Modify `storeBatch()` method in `CoffeeBeansController.php`:

```php
protected function storeBatch(Request $request)
{
    // ... existing code untuk validate dan collect images ...
    
    // Collect image paths and labels (if provided)
    $imagePaths = [];
    $labels = [];
    
    foreach ($images as $image) {
        $imagePath = $image->store('coffee-beans', 'public');
        $fullPath = storage_path('app/public/' . $imagePath);
        $imagePaths[] = $fullPath;
        
        // Optional: Get label from request if provided
        // $labels[] = $request->input("label_{$index}");
    }
    
    // Call Flask batch API with confusion matrix generation
    $batchResult = $this->flaskApi->classifyBatch($imagePaths, $labels);
    
    if (!$batchResult['success']) {
        return redirect()->route('coffee.create')
            ->with('error', 'Batch classification failed: ' . $batchResult['error']);
    }
    
    $data = $batchResult['data'];
    
    // Save individual classifications
    foreach ($data['results'] as $index => $result) {
        // ... save to coffee_beans table ...
    }
    
    // Save confusion matrices if available
    if (isset($data['confusion_matrix'])) {
        $this->saveConfusionMatrices($batchId, $data['confusion_matrix'], $data['statistics']);
    }
    
    return redirect()->route('coffee.batch-results', $batchId);
}

protected function saveConfusionMatrices($batchId, $confusionMatrices, $statistics)
{
    $cmData = [
        'batch_id' => $batchId,
        'total_images' => $statistics['total'],
        'class_distribution' => $statistics['classifications']
    ];
    
    // Save Small model confusion matrix
    if (isset($confusionMatrices['small'])) {
        $small = $confusionMatrices['small'];
        $smallPath = $this->flaskApi->saveConfusionMatrixImage(
            $small['image_base64'],
            "small-{$batchId}.png"
        );
        
        $cmData['confusion_matrix_small_path'] = $smallPath;
        $cmData['confusion_matrix_small_data'] = $small['matrix'];
        $cmData['accuracy_small'] = $small['accuracy'];
        $cmData['per_class_accuracy_small'] = $small['per_class_accuracy'];
    }
    
    // Save Large model confusion matrix
    if (isset($confusionMatrices['large'])) {
        $large = $confusionMatrices['large'];
        $largePath = $this->flaskApi->saveConfusionMatrixImage(
            $large['image_base64'],
            "large-{$batchId}.png"
        );
        
        $cmData['confusion_matrix_large_path'] = $largePath;
        $cmData['confusion_matrix_large_data'] = $large['matrix'];
        $cmData['accuracy_large'] = $large['accuracy'];
        $cmData['per_class_accuracy_large'] = $large['per_class_accuracy'];
    }
    
    \App\Models\BatchConfusionMatrix::create($cmData);
}
```

### **Step 2: Update Batch Results View**

Add confusion matrix display to `batch-results.blade.php`:

```blade
<!-- Confusion Matrices Section -->
@if($confusionMatrix)
<div class="mt-10">
    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Confusion Matrices</h3>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Small Model -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Small</h4>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                    {{ $confusionMatrix->accuracy_small }}% Accuracy
                </span>
            </div>
            
            <img src="{{ $confusionMatrix->getConfusionMatrixSmallUrl() }}" 
                 alt="Confusion Matrix Small"
                 class="w-full rounded-lg border border-gray-200">
            
            <!-- Per-class accuracy -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach($confusionMatrix->per_class_accuracy_small as $class => $acc)
                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">{{ $class }}</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $acc }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Large Model -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">MobileNetV3 Large</h4>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                    {{ $confusionMatrix->accuracy_large }}% Accuracy
                </span>
            </div>
            
            <img src="{{ $confusionMatrix->getConfusionMatrixLargeUrl() }}" 
                 alt="Confusion Matrix Large"
                 class="w-full rounded-lg border border-gray-200">
            
            <!-- Per-class accuracy -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach($confusionMatrix->per_class_accuracy_large as $class => $acc)
                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">{{ $class }}</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $acc }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
```

---

## 🚀 Usage

### **With Ground Truth Labels (Recommended for Testing)**

```php
// In your form, add label inputs
<input type="hidden" name="labels[]" value="Light Roast">
<input type="hidden" name="labels[]" value="Medium Roast">
// ... for each image
```

### **Without Labels (Production Use)**

Confusion matrix won't be generated, but you'll still get:
- Individual classifications
- Batch statistics
- Classification distribution

---

## 📈 Benefits

✅ **Visual Performance Evaluation**: Easy to see where model makes mistakes
✅ **Model Comparison**: Compare Small vs Large model performance
✅ **Class-specific Insights**: Identify which roast levels are harder to classify
✅ **Production Ready**: Automatic generation during batch processing
✅ **Historical Tracking**: All confusion matrices saved for future reference

---

## 🎯 Next Steps

1. ✅ Migration created
2. ✅ Model created
3. ✅ Service methods added
4. ⏳ Update Controller with confusion matrix logic
5. ⏳ Update batch results view
6. ⏳ Implement Flask endpoint
7. ⏳ Test with sample batch

