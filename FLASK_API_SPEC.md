# Spesifikasi Flask API untuk Sistem Klasifikasi Roasting Biji Kopi

## Overview
Flask API ini menggunakan 2 model MobileNetV3 (Small dan Large) untuk klasifikasi tingkat roasting biji kopi dengan analisis perbandingan.

## Model Architecture
- **MobileNetV3-Small**: ~2.5M parameters, lebih cepat, cocok untuk inference real-time
- **MobileNetV3-Large**: ~5.4M parameters, lebih akurat, confidence lebih stabil

## Kelas Klasifikasi
Sistem mengklasifikasikan biji kopi berdasarkan tingkat roasting:
1. **Green** - Biji kopi mentah/hijau (belum di-roasting)
2. **Light** - Roasting ringan (light roast)
3. **Medium** - Roasting sedang (medium roast)
4. **Dark** - Roasting gelap (dark roast)

## Base URL
```
http://localhost:5000
```

## Endpoints

### 1. Health Check
**Endpoint:** `GET /health`

**Response:**
```json
{
  "status": "ok",
  "message": "Flask API is running"
}
```

---

### 2. Classify Image
**Endpoint:** `POST /api/classify`

**Request:**
- Method: POST
- Content-Type: multipart/form-data
- Body:
  - `image`: File (required) - Image file (JPEG, PNG, JPG)

**Success Response (200):**
```json
{
  "success": true,
  "class": "Medium",
  "confidence": 92.45,
  "predictions": [
    {
      "class": "Medium",
      "confidence": 92.45
    },
    {
      "class": "Dark",
      "confidence": 4.32
    },
    {
      "class": "Light",
      "confidence": 2.18
    },
    {
      "class": "Green",
      "confidence": 1.05
    }
  ],
  "roast_level": "Medium",
  "description": "Medium roast coffee beans with balanced flavor profile",
  "timestamp": "2024-02-23T14:30:00Z"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "error": "No image file provided"
}
```

**Error Response (500):**
```json
{
  "success": false,
  "error": "Internal server error message"
}
```

---

### 3. Get Model Info
**Endpoint:** `GET /api/model-info`

**Response:**
```json
{
  "success": true,
  "model_name": "Coffee Roasting Level Classifier",
  "model_version": "1.0.0",
  "classes": ["Green", "Light", "Medium", "Dark"],
  "num_classes": 4,
  "input_shape": [224, 224, 3],
  "framework": "TensorFlow",
  "description": "Classifies coffee beans based on roasting level"
}
```

---

## Example Flask Implementation

```python
from flask import Flask, request, jsonify
from flask_cors import CORS
import os
from datetime import datetime

app = Flask(__name__)
CORS(app)

# Load your ML model here
# model = load_model('path/to/model')

@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({
        'status': 'ok',
        'message': 'Flask API is running'
    })

@app.route('/api/classify', methods=['POST'])
def classify_image():
    try:
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        
        if image_file.filename == '':
            return jsonify({
                'success': False,
                'error': 'No image file selected'
            }), 400
        
        # Process image and predict
        # predictions = model.predict(image_file)
        
        # Mock response for testing
        # Replace this with actual model prediction
        result = {
            'success': True,
            'class': 'Medium',
            'confidence': 92.45,
            'predictions': [
                {'class': 'Medium', 'confidence': 92.45},
                {'class': 'Dark', 'confidence': 4.32},
                {'class': 'Light', 'confidence': 2.18},
                {'class': 'Green', 'confidence': 1.05}
            ],
            'roast_level': 'Medium',
            'description': 'Medium roast coffee beans with balanced flavor profile',
            'timestamp': datetime.utcnow().isoformat() + 'Z'
        }
        
        return jsonify(result)
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/model-info', methods=['GET'])
def model_info():
    return jsonify({
        'success': True,
        'model_name': 'Coffee Roasting Level Classifier',
        'model_version': '1.0.0',
        'classes': ['Green', 'Light', 'Medium', 'Dark'],
        'num_classes': 4,
        'input_shape': [224, 224, 3],
        'framework': 'TensorFlow',
        'description': 'Classifies coffee beans based on roasting level'
    })

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
```

## Setup Instructions

1. Install dependencies:
```bash
pip install flask flask-cors pillow numpy tensorflow
# or
pip install flask flask-cors pillow numpy torch torchvision
```

2. Run Flask server:
```bash
python app.py
```

3. Test the API:
```bash
curl http://localhost:5000/health
```

## Integration with Laravel

Laravel sudah dikonfigurasi untuk berkomunikasi dengan Flask API melalui:
- Service: `App\Services\FlaskApiService`
- Config: `config/services.php` (flask section)
- Environment: `.env` (FLASK_API_URL, FLASK_API_TIMEOUT)

Pastikan Flask API berjalan sebelum menggunakan fitur klasifikasi di Laravel.
