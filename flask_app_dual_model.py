"""
Flask API untuk Klasifikasi Roasting Biji Kopi
Menggunakan 2 Model: MobileNetV3 Small dan MobileNetV3 Large
Dengan Analisis Perbandingan

Kelas Klasifikasi:
- Green: Biji kopi mentah/hijau
- Light: Roasting ringan
- Medium: Roasting sedang
- Dark: Roasting gelap
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from datetime import datetime, timezone
import random
import time

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

def mock_predict_mobilenet(image_file, model_type='small'):
    Mock prediction function untuk MobileNetV3
    
    Args:
        image_file: Image file object
        model_type: 'small' or 'large'
    
    Returns:
        tuple: (predicted_class, confidence, all_predictions, processing_time)
    
    Replace this with actual model prediction:
    
    # Load models (do this once at startup)
    model_small = tf.keras.models.load_model('models/mobilenetv3_small.h5')
    model_large = tf.keras.models.load_model('models/mobilenetv3_large.h5')
    
    # Preprocess image
    img = Image.open(image_file)
    img = img.resize((224, 224))
    img_array = np.array(img) / 255.0
    img_array = np.expand_dims(img_array, axis=0)
    
    # Predict
    start_time = time.time()
    if model_type == 'small':
        predictions = model_small.predict(img_array)[0]
    else:
        predictions = model_large.predict(img_array)[0]
    processing_time = int((time.time() - start_time) * 1000)  # ms
    
    
    # Simulate processing time (Small lebih cepat dari Large)
    start_time = time.time()
    if model_type == 'small':
        time.sleep(random.uniform(0.05, 0.15))  # 50-150ms
    else:
        time.sleep(random.uniform(0.15, 0.30))  # 150-300ms
    processing_time = int((time.time() - start_time) * 1000)
    
    # Simulate random prediction
    predicted_class = random.choice(ROAST_CLASSES)
    
    # Generate mock confidence scores
    # Large model biasanya lebih confident
    base_confidence = random.uniform(85, 98) if model_type == 'large' else random.uniform(80, 95)
    
    confidences = {}
    remaining = 100.0
    
    for cls in ROAST_CLASSES:
        if cls == predicted_class:
            confidences[cls] = base_confidence
        else:
            max_conf = (100 - base_confidence) / (len(ROAST_CLASSES) - 1)
            confidences[cls] = round(random.uniform(0.5, max_conf), 2)
    
    # Normalize to 100%
    total = sum(confidences.values())
    confidences = {k: round((v/total)*100, 2) for k, v in confidences.items()}
    
    # Sort by confidence
    predictions = [
        {'class': cls, 'confidence': conf} 
        for cls, conf in sorted(confidences.items(), key=lambda x: x[1], reverse=True)
    ]
    
    return predicted_class, predictions[0]['confidence'], predictions, processing_time

def analyze_models_comparison(small_result, large_result):
    """Analyze comparison between two models"""
    
    models_agree = small_result['class'] == large_result['class']
    confidence_diff = abs(small_result['confidence'] - large_result['confidence'])
    time_diff = abs(small_result['processing_time'] - large_result['processing_time'])
    
    # Determine better model
    if models_agree:
        better_model = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
        agreement_status = 'full_agreement'
    else:
        better_model = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
        agreement_status = 'disagreement'
    
    # Speed comparison
    faster_model = 'small' if small_result['processing_time'] < large_result['processing_time'] else 'large'
    speed_improvement = round((time_diff / max(small_result['processing_time'], large_result['processing_time'])) * 100, 2)
    
    # Confidence comparison
    more_confident = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
    confidence_improvement = round(confidence_diff, 2)
    
    # Generate recommendation
    if models_agree and confidence_diff < 5:
        recommendation = "Kedua model sangat konsisten. Gunakan Small untuk efisiensi."
    elif models_agree and confidence_diff >= 5:
        recommendation = f"Kedua model setuju, tapi {more_confident.upper()} lebih yakin. Gunakan {more_confident.upper()} untuk akurasi lebih tinggi."
    else:
        recommendation = f"Model berbeda pendapat. {better_model.upper()} lebih confident ({small_result['confidence'] if better_model == 'small' else large_result['confidence']}%). Pertimbangkan untuk review manual."
    
    analysis = {
        'models_agree': models_agree,
        'agreement_status': agreement_status,
        'confidence_difference': confidence_diff,
        'better_model': better_model,
        'more_confident_model': more_confident,
        'confidence_improvement': confidence_improvement,
        'faster_model': faster_model,
        'speed_improvement_percent': speed_improvement,
        'time_difference_ms': time_diff,
        'recommendation': recommendation,
        'final_classification': small_result['class'] if models_agree else (
            small_result['class'] if small_result['confidence'] > large_result['confidence'] 
            else large_result['class']
        )
    }
    
    return analysis

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'message': 'Flask API is running',
        'models': ['MobileNetV3-Small', 'MobileNetV3-Large'],
        'timestamp': datetime.now(timezone.utc).isoformat()
    })

@app.route('/api/classify-dual', methods=['POST'])
def classify_dual():
    """
    Classify dengan kedua model (Small & Large) dan bandingkan hasilnya
    
    Response:
        - small: hasil dari MobileNetV3 Small
        - large: hasil dari MobileNetV3 Large
        - comparison: analisis perbandingan
        - final_classification: klasifikasi final (consensus atau highest confidence)
    """
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
        
        if not allowed_file(image_file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file type. Allowed: PNG, JPG, JPEG'
            }), 400
        
        # Classify dengan MobileNetV3 Small
        class_small, conf_small, pred_small, time_small = mock_predict_mobilenet(image_file, 'small')
        
        # Reset file pointer untuk model kedua
        image_file.seek(0)
        
        # Classify dengan MobileNetV3 Large
        class_large, conf_large, pred_large, time_large = mock_predict_mobilenet(image_file, 'large')
        
        # Prepare results
        small_result = {
            'model': 'MobileNetV3-Small',
            'class': class_small,
            'confidence': conf_small,
            'predictions': pred_small,
            'processing_time': time_small,
            'description': get_roast_description(class_small)
        }
        
        large_result = {
            'model': 'MobileNetV3-Large',
            'class': class_large,
            'confidence': conf_large,
            'predictions': pred_large,
            'processing_time': time_large,
            'description': get_roast_description(class_large)
        }
        
        # Analyze comparison
        comparison = analyze_models_comparison(small_result, large_result)
        
        result = {
            'success': True,
            'small': small_result,
            'large': large_result,
            'comparison': comparison,
            'final_classification': comparison['final_classification'],
            'timestamp': datetime.now(timezone.utc).isoformat()
        }
        
        return jsonify(result), 200
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/classify/<model_type>', methods=['POST'])
def classify_single(model_type):
    """
    Classify dengan model spesifik (untuk testing)
    
    Args:
        model_type: 'small' or 'large'
    """
    try:
        if model_type not in ['small', 'large']:
            return jsonify({
                'success': False,
                'error': 'Invalid model type. Use "small" or "large"'
            }), 400
        
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        
        if image_file.filename == '' or not allowed_file(image_file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file'
            }), 400
        
        predicted_class, confidence, predictions, processing_time = mock_predict_mobilenet(
            image_file, model_type
        )
        
        result = {
            'success': True,
            'model': f'MobileNetV3-{model_type.capitalize()}',
            'class': predicted_class,
            'confidence': confidence,
            'predictions': predictions,
            'processing_time': processing_time,
            'description': get_roast_description(predicted_class),
            'timestamp': datetime.now(timezone.utc).isoformat()
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
        'models': {
            'small': {
                'name': 'MobileNetV3-Small',
                'architecture': 'MobileNetV3',
                'variant': 'Small',
                'parameters': '~2.5M',
                'input_shape': [224, 224, 3],
                'advantages': [
                    'Lebih cepat dalam inference',
                    'Lebih ringan (cocok untuk mobile/edge)',
                    'Konsumsi memori lebih rendah'
                ]
            },
            'large': {
                'name': 'MobileNetV3-Large',
                'architecture': 'MobileNetV3',
                'variant': 'Large',
                'parameters': '~5.4M',
                'input_shape': [224, 224, 3],
                'advantages': [
                    'Akurasi lebih tinggi',
                    'Confidence score lebih stabil',
                    'Lebih baik untuk kasus kompleks'
                ]
            }
        },
        'classes': ROAST_CLASSES,
        'num_classes': len(ROAST_CLASSES),
        'framework': 'TensorFlow',
        'description': 'Dual model classification system for coffee roasting level',
        'comparison_features': [
            'Accuracy comparison',
            'Speed comparison',
            'Confidence analysis',
            'Agreement detection',
            'Automatic recommendation'
        ]
    })

if __name__ == '__main__':
    print("=" * 70)
    print("Coffee Roasting Classifier - Dual Model System")
    print("=" * 70)
    print("Models:")
    print("  - MobileNetV3-Small: Fast & Efficient")
    print("  - MobileNetV3-Large: Accurate & Robust")
    print(f"\nClasses: {', '.join(ROAST_CLASSES)}")
    print("\nEndpoints:")
    print("  - GET  /health                    : Health check")
    print("  - GET  /api/model-info            : Model information")
    print("  - POST /api/classify-dual         : Classify with both models")
    print("  - POST /api/classify/small        : Classify with Small model only")
    print("  - POST /api/classify/large        : Classify with Large model only")
    print("=" * 70)
    print("Starting server on http://0.0.0.0:5000")
    print("=" * 70)
    
    app.run(debug=True, host='0.0.0.0', port=5000)
"""
Flask API untuk Klasifikasi Roasting Biji Kopi
Menggunakan 2 Model: MobileNetV3 Small dan MobileNetV3 Large
Dengan Analisis Perbandingan

Kelas Klasifikasi:
- Green: Biji kopi mentah/hijau
- Light: Roasting ringan
- Medium: Roasting sedang
- Dark: Roasting gelap
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from datetime import datetime, timezone
import random
import time

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

def mock_predict_mobilenet(image_file, model_type='small'):
    Mock prediction function untuk MobileNetV3
    
    Args:
        image_file: Image file object
        model_type: 'small' or 'large'
    
    Returns:
        tuple: (predicted_class, confidence, all_predictions, processing_time)
    
    Replace this with actual model prediction:
    
    # Load models (do this once at startup)
    model_small = tf.keras.models.load_model('models/mobilenetv3_small.h5')
    model_large = tf.keras.models.load_model('models/mobilenetv3_large.h5')
    
    # Preprocess image
    img = Image.open(image_file)
    img = img.resize((224, 224))
    img_array = np.array(img) / 255.0
    img_array = np.expand_dims(img_array, axis=0)
    
    # Predict
    start_time = time.time()
    if model_type == 'small':
        predictions = model_small.predict(img_array)[0]
    else:
        predictions = model_large.predict(img_array)[0]
    processing_time = int((time.time() - start_time) * 1000)  # ms
    
    
    # Simulate processing time (Small lebih cepat dari Large)
    start_time = time.time()
    if model_type == 'small':
        time.sleep(random.uniform(0.05, 0.15))  # 50-150ms
    else:
        time.sleep(random.uniform(0.15, 0.30))  # 150-300ms
    processing_time = int((time.time() - start_time) * 1000)
    
    # Simulate random prediction
    predicted_class = random.choice(ROAST_CLASSES)
    
    # Generate mock confidence scores
    # Large model biasanya lebih confident
    base_confidence = random.uniform(85, 98) if model_type == 'large' else random.uniform(80, 95)
    
    confidences = {}
    remaining = 100.0
    
    for cls in ROAST_CLASSES:
        if cls == predicted_class:
            confidences[cls] = base_confidence
        else:
            max_conf = (100 - base_confidence) / (len(ROAST_CLASSES) - 1)
            confidences[cls] = round(random.uniform(0.5, max_conf), 2)
    
    # Normalize to 100%
    total = sum(confidences.values())
    confidences = {k: round((v/total)*100, 2) for k, v in confidences.items()}
    
    # Sort by confidence
    predictions = [
        {'class': cls, 'confidence': conf} 
        for cls, conf in sorted(confidences.items(), key=lambda x: x[1], reverse=True)
    ]
    
    return predicted_class, predictions[0]['confidence'], predictions, processing_time

def analyze_models_comparison(small_result, large_result):
    """Analyze comparison between two models"""
    
    models_agree = small_result['class'] == large_result['class']
    confidence_diff = abs(small_result['confidence'] - large_result['confidence'])
    time_diff = abs(small_result['processing_time'] - large_result['processing_time'])
    
    # Determine better model
    if models_agree:
        better_model = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
        agreement_status = 'full_agreement'
    else:
        better_model = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
        agreement_status = 'disagreement'
    
    # Speed comparison
    faster_model = 'small' if small_result['processing_time'] < large_result['processing_time'] else 'large'
    speed_improvement = round((time_diff / max(small_result['processing_time'], large_result['processing_time'])) * 100, 2)
    
    # Confidence comparison
    more_confident = 'large' if large_result['confidence'] > small_result['confidence'] else 'small'
    confidence_improvement = round(confidence_diff, 2)
    
    # Generate recommendation
    if models_agree and confidence_diff < 5:
        recommendation = "Kedua model sangat konsisten. Gunakan Small untuk efisiensi."
    elif models_agree and confidence_diff >= 5:
        recommendation = f"Kedua model setuju, tapi {more_confident.upper()} lebih yakin. Gunakan {more_confident.upper()} untuk akurasi lebih tinggi."
    else:
        recommendation = f"Model berbeda pendapat. {better_model.upper()} lebih confident ({small_result['confidence'] if better_model == 'small' else large_result['confidence']}%). Pertimbangkan untuk review manual."
    
    analysis = {
        'models_agree': models_agree,
        'agreement_status': agreement_status,
        'confidence_difference': confidence_diff,
        'better_model': better_model,
        'more_confident_model': more_confident,
        'confidence_improvement': confidence_improvement,
        'faster_model': faster_model,
        'speed_improvement_percent': speed_improvement,
        'time_difference_ms': time_diff,
        'recommendation': recommendation,
        'final_classification': small_result['class'] if models_agree else (
            small_result['class'] if small_result['confidence'] > large_result['confidence'] 
            else large_result['class']
        )
    }
    
    return analysis

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'message': 'Flask API is running',
        'models': ['MobileNetV3-Small', 'MobileNetV3-Large'],
        'timestamp': datetime.now(timezone.utc).isoformat()
    })

@app.route('/api/classify-dual', methods=['POST'])
def classify_dual():
    """
    Classify dengan kedua model (Small & Large) dan bandingkan hasilnya
    
    Response:
        - small: hasil dari MobileNetV3 Small
        - large: hasil dari MobileNetV3 Large
        - comparison: analisis perbandingan
        - final_classification: klasifikasi final (consensus atau highest confidence)
    """
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
        
        if not allowed_file(image_file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file type. Allowed: PNG, JPG, JPEG'
            }), 400
        
        # Classify dengan MobileNetV3 Small
        class_small, conf_small, pred_small, time_small = mock_predict_mobilenet(image_file, 'small')
        
        # Reset file pointer untuk model kedua
        image_file.seek(0)
        
        # Classify dengan MobileNetV3 Large
        class_large, conf_large, pred_large, time_large = mock_predict_mobilenet(image_file, 'large')
        
        # Prepare results
        small_result = {
            'model': 'MobileNetV3-Small',
            'class': class_small,
            'confidence': conf_small,
            'predictions': pred_small,
            'processing_time': time_small,
            'description': get_roast_description(class_small)
        }
        
        large_result = {
            'model': 'MobileNetV3-Large',
            'class': class_large,
            'confidence': conf_large,
            'predictions': pred_large,
            'processing_time': time_large,
            'description': get_roast_description(class_large)
        }
        
        # Analyze comparison
        comparison = analyze_models_comparison(small_result, large_result)
        
        result = {
            'success': True,
            'small': small_result,
            'large': large_result,
            'comparison': comparison,
            'final_classification': comparison['final_classification'],
            'timestamp': datetime.now(timezone.utc).isoformat()
        }
        
        return jsonify(result), 200
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/classify/<model_type>', methods=['POST'])
def classify_single(model_type):
    """
    Classify dengan model spesifik (untuk testing)
    
    Args:
        model_type: 'small' or 'large'
    """
    try:
        if model_type not in ['small', 'large']:
            return jsonify({
                'success': False,
                'error': 'Invalid model type. Use "small" or "large"'
            }), 400
        
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        
        if image_file.filename == '' or not allowed_file(image_file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file'
            }), 400
        
        predicted_class, confidence, predictions, processing_time = mock_predict_mobilenet(
            image_file, model_type
        )
        
        result = {
            'success': True,
            'model': f'MobileNetV3-{model_type.capitalize()}',
            'class': predicted_class,
            'confidence': confidence,
            'predictions': predictions,
            'processing_time': processing_time,
            'description': get_roast_description(predicted_class),
            'timestamp': datetime.now(timezone.utc).isoformat()
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
        'models': {
            'small': {
                'name': 'MobileNetV3-Small',
                'architecture': 'MobileNetV3',
                'variant': 'Small',
                'parameters': '~2.5M',
                'input_shape': [224, 224, 3],
                'advantages': [
                    'Lebih cepat dalam inference',
                    'Lebih ringan (cocok untuk mobile/edge)',
                    'Konsumsi memori lebih rendah'
                ]
            },
            'large': {
                'name': 'MobileNetV3-Large',
                'architecture': 'MobileNetV3',
                'variant': 'Large',
                'parameters': '~5.4M',
                'input_shape': [224, 224, 3],
                'advantages': [
                    'Akurasi lebih tinggi',
                    'Confidence score lebih stabil',
                    'Lebih baik untuk kasus kompleks'
                ]
            }
        },
        'classes': ROAST_CLASSES,
        'num_classes': len(ROAST_CLASSES),
        'framework': 'TensorFlow',
        'description': 'Dual model classification system for coffee roasting level',
        'comparison_features': [
            'Accuracy comparison',
            'Speed comparison',
            'Confidence analysis',
            'Agreement detection',
            'Automatic recommendation'
        ]
    })

if __name__ == '__main__':
    print("=" * 70)
    print("Coffee Roasting Classifier - Dual Model System")
    print("=" * 70)
    print("Models:")
    print("  - MobileNetV3-Small: Fast & Efficient")
    print("  - MobileNetV3-Large: Accurate & Robust")
    print(f"\nClasses: {', '.join(ROAST_CLASSES)}")
    print("\nEndpoints:")
    print("  - GET  /health                    : Health check")
    print("  - GET  /api/model-info            : Model information")
    print("  - POST /api/classify-dual         : Classify with both models")
    print("  - POST /api/classify/small        : Classify with Small model only")
    print("  - POST /api/classify/large        : Classify with Large model only")
    print("=" * 70)
    print("Starting server on http://0.0.0.0:5000")
    print("=" * 70)
    
    app.run(debug=True, host='0.0.0.0', port=5000)
