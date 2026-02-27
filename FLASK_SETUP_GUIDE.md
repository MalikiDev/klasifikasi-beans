# Panduan Setup Flask API Backend

## 📁 Struktur Folder yang Direkomendasikan

```
C:\Users\HP\
└── klasifikasibeans\          # Laravel project (sudah ada)

D:\                            # Drive D untuk storage besar
└── flask-api\                 # Flask backend (~2-3 GB)
    ├── app.py
    ├── requirements.txt
    ├── venv\                  # Virtual environment (~2 GB)
    └── models\                # Model files
        ├── mobilenetv3_small.h5
        └── mobilenetv3_large.h5
```

**Kenapa di Drive D?**
- TensorFlow + dependencies: ~2-3 GB
- Virtual environment: ~1-2 GB
- Model files: ~100-500 MB
- Total: ~3-5 GB
- Drive C biasanya lebih terbatas untuk system

## 🚀 Langkah-langkah Setup

### Langkah 1: Buat Folder Flask API di Drive D

Buka Command Prompt atau PowerShell, lalu:

```bash
D:
mkdir flask-api
cd flask-api
```

**Kenapa Drive D?**
- Dependencies deep learning butuh 2-3 GB storage
- Virtual environment butuh 1-2 GB
- Model files butuh 100-500 MB
- Total ~3-5 GB, lebih aman di drive D

### Langkah 2: Copy File dari Laravel ke Flask

```bash
# Copy file Flask app
copy C:\Users\HP\klasifikasibeans\flask_app_dual_model.py app.py

# Copy requirements
copy C:\Users\HP\klasifikasibeans\flask_requirements.txt requirements.txt
```

### Langkah 3: Install Python (Jika Belum)

1. Download Python dari https://www.python.org/downloads/
2. Install dengan centang "Add Python to PATH"
3. Verify instalasi:
```bash
python --version
```

### Langkah 4: Buat Virtual Environment

```bash
# Buat virtual environment
python -m venv venv

# Aktivasi virtual environment
# Windows CMD:
venv\Scripts\activate.bat

# Windows PowerShell:
venv\Scripts\Activate.ps1

# Jika error di PowerShell, jalankan dulu:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Langkah 5: Install Dependencies

```bash
# Upgrade pip dulu
python -m pip install --upgrade pip

# Install dependencies (akan download ~2-3 GB)
pip install -r requirements.txt
```

**Note:** 
- Instalasi TensorFlow bisa memakan waktu 5-10 menit
- Akan download ~2-3 GB packages
- Pastikan koneksi internet stabil
- Total storage yang dibutuhkan: ~3-5 GB

### Langkah 6: Test Run (Mode Mock)

```bash
python app.py
```

Anda akan melihat output:
```
======================================================================
Coffee Roasting Classifier - Dual Model System
======================================================================
Models:
  - MobileNetV3-Small: Fast & Efficient
  - MobileNetV3-Large: Accurate & Robust

Classes: Green, Light, Medium, Dark

Endpoints:
  - GET  /health                    : Health check
  - GET  /api/model-info            : Model information
  - POST /api/classify-dual         : Classify with both models
  - POST /api/classify/small        : Classify with Small model only
  - POST /api/classify/large        : Classify with Large model only
======================================================================
Starting server on http://0.0.0.0:5000
======================================================================
```

### Langkah 7: Test API

Buka browser atau terminal baru, test:

```bash
# Health check
curl http://localhost:5000/health

# Model info
curl http://localhost:5000/api/model-info
```

## 🎯 Implementasi dengan Model Asli

### Persiapan Model

1. **Simpan Model Anda di Drive D**
   ```
   D:\flask-api\
   └── models\
       ├── mobilenetv3_small.h5    # Model Small Anda
       └── mobilenetv3_large.h5    # Model Large Anda
   ```

2. **Format Model**
   - Format: Keras (.h5) atau SavedModel
   - Input shape: (224, 224, 3)
   - Output: 4 classes (Green, Light, Medium, Dark)

### Update app.py untuk Model Asli

Buka `app.py` dan tambahkan di bagian atas (setelah imports):

```python
import tensorflow as tf
from PIL import Image
import numpy as np
import os

# Load models saat startup
print("Loading models...")
MODEL_DIR = 'models'

if os.path.exists(os.path.join(MODEL_DIR, 'mobilenetv3_small.h5')):
    model_small = tf.keras.models.load_model(os.path.join(MODEL_DIR, 'mobilenetv3_small.h5'))
    print("✓ MobileNetV3-Small loaded")
else:
    model_small = None
    print("⚠ MobileNetV3-Small not found, using mock")

if os.path.exists(os.path.join(MODEL_DIR, 'mobilenetv3_large.h5')):
    model_large = tf.keras.models.load_model(os.path.join(MODEL_DIR, 'mobilenetv3_large.h5'))
    print("✓ MobileNetV3-Large loaded")
else:
    model_large = None
    print("⚠ MobileNetV3-Large not found, using mock")

def preprocess_image(image_file):
    """Preprocess image untuk model"""
    img = Image.open(image_file)
    img = img.convert('RGB')
    img = img.resize((224, 224))
    img_array = np.array(img) / 255.0
    img_array = np.expand_dims(img_array, axis=0)
    return img_array

def predict_with_real_model(image_file, model_type='small'):
    """Predict dengan model asli"""
    import time
    
    # Select model
    model = model_small if model_type == 'small' else model_large
    
    if model is None:
        # Fallback to mock if model not loaded
        return mock_predict_mobilenet(image_file, model_type)
    
    # Preprocess
    img_array = preprocess_image(image_file)
    
    # Predict dengan timing
    start_time = time.time()
    predictions = model.predict(img_array, verbose=0)[0]
    processing_time = int((time.time() - start_time) * 1000)
    
    # Get predicted class
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
    
    return predicted_class, confidence, all_predictions, processing_time
```

Kemudian ganti semua pemanggilan `mock_predict_mobilenet` menjadi `predict_with_real_model`:

```python
# Di fungsi classify_dual():
# Ganti:
class_small, conf_small, pred_small, time_small = mock_predict_mobilenet(image_file, 'small')
# Menjadi:
class_small, conf_small, pred_small, time_small = predict_with_real_model(image_file, 'small')

# Dan untuk large:
class_large, conf_large, pred_large, time_large = predict_with_real_model(image_file, 'large')
```

## 🔧 Troubleshooting

### 1. Error: "pip is not recognized"
```bash
python -m pip install -r requirements.txt
```

### 2. Error: TensorFlow installation failed
Coba install versi CPU-only:
```bash
pip install tensorflow-cpu==2.15.0
```

### 3. Error: Port 5000 already in use
Edit `app.py`, ganti port:
```python
app.run(debug=True, host='0.0.0.0', port=5001)
```

Jangan lupa update `.env` di Laravel:
```
FLASK_API_URL=http://localhost:5001
```

### 4. Error: Model loading failed
Pastikan:
- File model ada di folder `models/`
- Format file benar (.h5 atau SavedModel)
- TensorFlow version compatible

### 5. Error: CORS
Sudah di-handle oleh `flask-cors`, tapi jika masih error:
```python
CORS(app, resources={
    r"/api/*": {
        "origins": ["http://localhost:8000"],
        "methods": ["GET", "POST"],
        "allow_headers": ["Content-Type"]
    }
})
```

## 📊 Testing dengan Postman

1. Install Postman
2. Create new request:
   - Method: POST
   - URL: http://localhost:5000/api/classify-dual
   - Body: form-data
   - Key: image (type: File)
   - Value: Select image file

## 🚀 Production Deployment

### Menggunakan Gunicorn (Linux/Mac)

```bash
pip install gunicorn
gunicorn -w 4 -b 0.0.0.0:5000 --timeout 120 app:app
```

### Menggunakan Waitress (Windows)

```bash
pip install waitress
waitress-serve --host=0.0.0.0 --port=5000 app:app
```

### Systemd Service (Linux)

Create `/etc/systemd/system/flask-api.service`:

```ini
[Unit]
Description=Flask Coffee Classifier API
After=network.target

[Service]
User=your-user
WorkingDirectory=/path/to/flask-api
Environment="PATH=/path/to/flask-api/venv/bin"
ExecStart=/path/to/flask-api/venv/bin/gunicorn -w 4 -b 0.0.0.0:5000 --timeout 120 app:app

[Install]
WantedBy=multi-user.target
```

Enable dan start:
```bash
sudo systemctl enable flask-api
sudo systemctl start flask-api
```

## 📝 Checklist Setup

- [ ] Folder `flask-api` sudah dibuat
- [ ] File `app.py` dan `requirements.txt` sudah di-copy
- [ ] Virtual environment sudah dibuat dan diaktifkan
- [ ] Dependencies sudah terinstall
- [ ] Server Flask bisa jalan (mode mock)
- [ ] Health check endpoint bisa diakses
- [ ] Model files sudah disiapkan (jika ada)
- [ ] Model berhasil di-load (jika ada)
- [ ] Test classification berhasil
- [ ] Laravel bisa connect ke Flask API

## 🔗 Integrasi dengan Laravel

Setelah Flask API jalan, test dari Laravel:

```bash
cd C:\Users\HP\klasifikasibeans
php artisan tinker
```

```php
$service = app(\App\Services\FlaskApiService::class);
$health = $service->healthCheck();
var_dump($health); // Should return true
```

## 📚 Resources

- Flask Documentation: https://flask.palletsprojects.com/
- TensorFlow Documentation: https://www.tensorflow.org/
- MobileNetV3 Paper: https://arxiv.org/abs/1905.02244

## 💡 Tips

1. **Development**: Gunakan mode mock untuk development Laravel tanpa perlu model
2. **Testing**: Test Flask API secara terpisah sebelum integrasi
3. **Performance**: Model Large lebih akurat tapi lebih lambat
4. **Memory**: Jika RAM terbatas, load model on-demand
5. **Logging**: Tambahkan logging untuk debugging
