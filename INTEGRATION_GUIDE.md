# Panduan Integrasi Laravel - Flask API

## Overview

Sistem ini terdiri dari 2 komponen utama:
1. **Laravel (Frontend & Backend)** - Manajemen data dan UI
2. **Flask API (ML Service)** - Klasifikasi tingkat roasting biji kopi

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                         User Browser                         │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Application                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Controllers (CoffeeBeansController)                  │  │
│  └────────────────────┬─────────────────────────────────┘  │
│                       │                                      │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │  Services (FlaskApiService)                          │  │
│  └────────────────────┬─────────────────────────────────┘  │
│                       │ HTTP Request                         │
└───────────────────────┼──────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                      Flask API Server                        │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Endpoints (/api/classify, /api/model-info)          │  │
│  └────────────────────┬─────────────────────────────────┘  │
│                       │                                      │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │  ML Model (TensorFlow/PyTorch)                       │  │
│  │  - Image Preprocessing                                │  │
│  │  - Model Inference                                    │  │
│  │  - Result Processing                                  │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## Flow Klasifikasi

1. User upload gambar biji kopi (hanya gambar, tanpa input lain)
2. Laravel menyimpan gambar ke storage
3. Laravel memanggil `FlaskApiService->classifyImage()`
4. Service mengirim HTTP POST request ke Flask API
5. Flask API memproses gambar dengan ML model
6. Flask API mengembalikan:
   - Kelas roasting (Green/Light/Medium/Dark)
   - Confidence score
   - Deskripsi tingkat roasting
7. Laravel otomatis generate:
   - Nama: "Biji Kopi {RoastLevel} - {Timestamp}"
   - Deskripsi: Dari Flask API atau default helper
8. Data tersimpan ke database
9. User melihat hasil klasifikasi lengkap

## Setup Flask API

### 1. Persiapan Environment

```bash
# Buat virtual environment
python -m venv venv

# Aktivasi virtual environment
# Windows:
venv\Scripts\activate
# Linux/Mac:
source venv/bin/activate

# Install dependencies
pip install -r flask_requirements.txt
```

### 2. Struktur Folder Flask API (Rekomendasi)

```
flask-api/
├── app.py                  # Main Flask application
├── requirements.txt        # Python dependencies
├── models/
│   └── roasting_model.h5  # Trained ML model
├── utils/
│   ├── __init__.py
│   ├── image_processor.py # Image preprocessing
│   └── predictor.py       # Model prediction logic
├── config.py              # Configuration
└── .env                   # Environment variables
```

### 3. Implementasi dengan Model Asli

Ganti fungsi `mock_predict()` di `flask_app_example.py` dengan:

```python
import tensorflow as tf
from PIL import Image
import numpy as np

# Load model saat startup
model = tf.keras.models.load_model('models/roasting_model.h5')

def preprocess_image(image_file):
    """Preprocess image untuk model"""
    img = Image.open(image_file)
    img = img.resize((224, 224))  # Sesuaikan dengan input model
    img_array = np.array(img) / 255.0  # Normalisasi
    img_array = np.expand_dims(img_array, axis=0)
    return img_array

def predict_roasting(image_file):
    """Predict roasting level"""
    # Preprocess
    processed_image = preprocess_image(image_file)
    
    # Predict
    predictions = model.predict(processed_image)[0]
    
    # Get class with highest confidence
    class_idx = np.argmax(predictions)
    predicted_class = ROAST_CLASSES[class_idx]
    confidence = float(predictions[class_idx] * 100)
    
    # Format all predictions
    all_predictions = [
        {
            'class': ROAST_CLASSES[i],
            'confidence': round(float(predictions[i] * 100), 2)
        }
        for i in range(len(ROAST_CLASSES))
    ]
    all_predictions.sort(key=lambda x: x['confidence'], reverse=True)
    
    return predicted_class, confidence, all_predictions
```

### 4. Jalankan Flask API

```bash
# Development
python app.py

# Production dengan Gunicorn
gunicorn -w 4 -b 0.0.0.0:5000 app:app
```

## Setup Laravel

### 1. Environment Configuration

Tambahkan ke `.env`:

```env
FLASK_API_URL=http://localhost:5000
FLASK_API_TIMEOUT=30
```

### 2. Jalankan Migrasi

```bash
php artisan migrate
php artisan storage:link
```

### 3. Test Koneksi

```bash
# Test health check
curl http://localhost:5000/health

# Test dari Laravel
php artisan tinker
>>> $service = app(\App\Services\FlaskApiService::class);
>>> $service->healthCheck();
```

## Testing

### 1. Test Flask API Langsung

```bash
# Health check
curl http://localhost:5000/health

# Model info
curl http://localhost:5000/api/model-info

# Classify image
curl -X POST http://localhost:5000/api/classify \
  -F "image=@/path/to/coffee-image.jpg"
```

### 2. Test dari Laravel

```php
// routes/web.php atau tinker
Route::get('/test-flask', function () {
    $service = app(\App\Services\FlaskApiService::class);
    
    // Health check
    $health = $service->healthCheck();
    
    // Model info
    $info = $service->getModelInfo();
    
    // Classify (dengan path gambar)
    $result = $service->classifyImage(storage_path('app/public/test-image.jpg'));
    
    return response()->json([
        'health' => $health,
        'info' => $info,
        'classification' => $result
    ]);
});
```

## Troubleshooting

### Flask API tidak bisa diakses

**Problem:** Connection refused atau timeout

**Solusi:**
1. Pastikan Flask berjalan: `curl http://localhost:5000/health`
2. Cek firewall/antivirus
3. Pastikan port 5000 tidak digunakan aplikasi lain
4. Coba ganti host dari `0.0.0.0` ke `127.0.0.1`

### Error CORS

**Problem:** CORS policy blocking request

**Solusi:**
```python
from flask_cors import CORS

app = Flask(__name__)
CORS(app, resources={
    r"/api/*": {
        "origins": ["http://localhost:8000"],
        "methods": ["GET", "POST"],
        "allow_headers": ["Content-Type"]
    }
})
```

### Image upload gagal

**Problem:** File tidak terkirim ke Flask

**Solusi:**
1. Cek max upload size di Flask:
```python
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB
```

2. Cek permission folder storage Laravel:
```bash
chmod -R 775 storage
```

### Model prediction error

**Problem:** Error saat model predict

**Solusi:**
1. Cek format input image sesuai dengan model
2. Pastikan model file ada dan bisa diload
3. Cek dependencies (TensorFlow/PyTorch version)
4. Tambahkan error handling:
```python
try:
    predictions = model.predict(image)
except Exception as e:
    app.logger.error(f"Prediction error: {str(e)}")
    return jsonify({'success': False, 'error': str(e)}), 500
```

## Production Deployment

### Flask API

```bash
# Install production server
pip install gunicorn

# Run with Gunicorn
gunicorn -w 4 -b 0.0.0.0:5000 --timeout 120 app:app

# Dengan systemd service
sudo nano /etc/systemd/system/flask-api.service
```

### Laravel

```bash
# Build assets
npm run build

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

## Monitoring

### Log Flask API

```python
import logging

logging.basicConfig(
    filename='flask_api.log',
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)

@app.route('/api/classify', methods=['POST'])
def classify_image():
    app.logger.info(f"Classification request from {request.remote_addr}")
    # ... rest of code
```

### Log Laravel

```php
// app/Services/FlaskApiService.php
use Illuminate\Support\Facades\Log;

public function classifyImage($imagePath)
{
    Log::info('Flask API classification request', [
        'image_path' => $imagePath,
        'timestamp' => now()
    ]);
    
    // ... rest of code
}
```

## Performance Tips

1. **Caching**: Cache model di memory (jangan load setiap request)
2. **Async Processing**: Gunakan queue untuk klasifikasi batch
3. **Image Optimization**: Resize image sebelum kirim ke API
4. **Connection Pooling**: Reuse HTTP connections
5. **Load Balancing**: Multiple Flask instances dengan nginx

## Security

1. **API Key**: Tambahkan authentication
2. **Rate Limiting**: Batasi request per IP
3. **Input Validation**: Validasi file type dan size
4. **HTTPS**: Gunakan SSL/TLS di production
5. **Environment Variables**: Jangan hardcode credentials

## Support

Untuk pertanyaan atau issue:
1. Cek log file (Laravel: `storage/logs/laravel.log`, Flask: `flask_api.log`)
2. Test endpoint secara terpisah
3. Verifikasi environment variables
4. Pastikan semua dependencies terinstall
