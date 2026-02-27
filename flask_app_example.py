"""
Flask API untuk Klasifikasi Roasting Biji Kopi
Contoh implementasi dengan mock data untuk testing

Kelas Klasifikasi:
- Green: Biji kopi mentah/hijau
- Light: Roasting ringan
- Medium: Roasting sedang
- Dark: Roasting gelap
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from datetime import datetime
import random

app = Flask(__name__)
CORS(app)

# Konfigurasi
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg'}
ROAST_CLASSES = ['Green', 'Light', 'Medium', 'Dark']

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def get_roast_description(roast_level):
    """Get description for each roast level"""
    descriptions = {
        'Green': 'Biji kopi mentah/hijau yang belum di-roasting',
        'Light': 'Roasting ringan dengan rasa asam yang menonjol',
        'Medium': 'Roasting sedang dengan keseimbangan rasa yang baik',
        'Dark': 'Roasting gelap dengan rasa pahit dan body yang kuat'
    }
    return descriptions.get(roast_level, 'Unknown roast level')

def mock_predict(image_file):
    """
    Mock prediction function
    Replace this with actual model prediction
    
    Example with real model:
    from tensorflow.keras.models import load_model
    model = load_model('path/to/model.h5')
    predictions = model.predict(preprocessed_image)
    """
    # Simulate random prediction for testing
    predicted_class = random.choice(ROAST_CLASSES)
    
    # Generate mock confidence scores
    confidences = {}
    remaining = 100.0
    
    for cls in ROAST_CLASSES:
        if cls == predicted_class:
            confidences[cls] = round(random.uniform(85, 98), 2)
        else:
            max_conf = remaining - confidences.get(predicted_class, 0)
            confidences[cls] = round(random.uniform(0.5, min(10, max_conf)), 2)
    
    # Normalize to 100%
    total = sum(confidences.values())
    confidences = {k: round((v/total)*100, 2) for k, v in confidences.items()}
    
    # Sort by confidence
    predictions = [
        {'class': cls, 'confidence': conf} 
        for cls, conf in sorted(confidences.items(), key=lambda x: x[1], reverse=True)
    ]
    
    return predicted_class, predictions[0]['confidence'], predictions

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'message': 'Flask API is running',
        'timestamp': datetime.utcnow().isoformat() + 'Z'
    })

@app.route('/api/classify', methods=['POST'])
def classify_image():
    """
    Classify coffee bean roasting level from image
    
    Request:
        - image: File (multipart/form-data)
    
    Response:
        - success: bool
        - class: str (Green/Light/Medium/Dark)
        - confidence: float
        - predictions: list of all class predictions
        - roast_level: str
        - description: str
        - timestamp: str
    """
    try:
        # Check if image file is present
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        
        # Check if file is selected
        if image_file.filename == '':
            return jsonify({
                'success': False,
                'error': 'No image file selected'
            }), 400
        
        # Check file extension
        if not allowed_file(image_file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file type. Allowed: PNG, JPG, JPEG'
            }), 400
        
        # Process image and predict
        # In production, you would:
        # 1. Load and preprocess the image
        # 2. Run model prediction
        # 3. Get results
        
        predicted_class, confidence, predictions = mock_predict(image_file)
        
        result = {
            'success': True,
            'class': predicted_class,
            'confidence': confidence,
            'predictions': predictions,
            'roast_level': predicted_class,
            'description': get_roast_description(predicted_class),
            'timestamp': datetime.utcnow().isoformat() + 'Z'
        }
        
        return jsonify(result), 200
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/model-info', methods=['GET'])
def model_info():
    """Get model information"""
    return jsonify({
        'success': True,
        'model_name': 'Coffee Roasting Level Classifier',
        'model_version': '1.0.0',
        'classes': ROAST_CLASSES,
        'num_classes': len(ROAST_CLASSES),
        'input_shape': [224, 224, 3],
        'framework': 'TensorFlow',
        'description': 'Classifies coffee beans based on roasting level',
        'class_descriptions': {
            'Green': 'Biji kopi mentah/hijau yang belum di-roasting',
            'Light': 'Roasting ringan dengan rasa asam yang menonjol',
            'Medium': 'Roasting sedang dengan keseimbangan rasa yang baik',
            'Dark': 'Roasting gelap dengan rasa pahit dan body yang kuat'
        }
    })

@app.route('/api/batch-classify', methods=['POST'])
def batch_classify():
    """
    Classify multiple images at once
    Useful for batch processing
    """
    try:
        if 'images' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No images provided'
            }), 400
        
        images = request.files.getlist('images')
        results = []
        
        for image in images:
            if image and allowed_file(image.filename):
                predicted_class, confidence, predictions = mock_predict(image)
                results.append({
                    'filename': image.filename,
                    'class': predicted_class,
                    'confidence': confidence,
                    'predictions': predictions
                })
        
        return jsonify({
            'success': True,
            'count': len(results),
            'results': results,
            'timestamp': datetime.utcnow().isoformat() + 'Z'
        })
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

if __name__ == '__main__':
    print("=" * 60)
    print("Coffee Roasting Level Classifier API")
    print("=" * 60)
    print(f"Classes: {', '.join(ROAST_CLASSES)}")
    print("Endpoints:")
    print("  - GET  /health              : Health check")
    print("  - GET  /api/model-info      : Model information")
    print("  - POST /api/classify        : Classify single image")
    print("  - POST /api/batch-classify  : Classify multiple images")
    print("=" * 60)
    print("Starting server on http://0.0.0.0:5000")
    print("=" * 60)
    
    app.run(debug=True, host='0.0.0.0', port=5000)
