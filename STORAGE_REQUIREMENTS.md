# 💾 Storage Requirements & Optimization

## 📊 Storage Breakdown

### Laravel Project (C:\Users\HP\klasifikasibeans)
```
vendor/                 ~150 MB   (Composer dependencies)
node_modules/          ~200 MB   (NPM dependencies)
storage/               ~10 MB    (Logs, cache, uploads)
database/              ~1 MB     (SQLite database)
app/ + resources/      ~5 MB     (Source code)
public/                ~2 MB     (Assets)
--------------------------------
Total Laravel:         ~370 MB
```

### Flask API (D:\flask-api) - RECOMMENDED LOCATION
```
venv/                  ~2 GB     (Python virtual environment)
  ├── Lib/            ~1.8 GB   (TensorFlow, NumPy, etc)
  └── Scripts/        ~200 MB   (Python executables)

models/                ~200-500 MB (ML models)
  ├── mobilenetv3_small.h5  ~50-100 MB
  └── mobilenetv3_large.h5  ~100-200 MB

app.py + others        ~1 MB     (Source code)
--------------------------------
Total Flask:           ~2.5-3 GB
```

### Total System Requirements
```
Minimum:  3 GB   (tanpa model files)
Recommended: 5 GB   (dengan model files)
Optimal: 10 GB   (untuk development & testing)
```

## 🎯 Kenapa Flask di Drive D?

### Alasan Teknis:
1. **TensorFlow sangat besar** (~2 GB)
2. **Virtual environment** butuh ~1-2 GB
3. **Model files** bisa 200-500 MB
4. **Drive C** biasanya untuk system & programs
5. **Drive D** lebih lega untuk data & development

### Perbandingan:
```
Scenario 1: Semua di Drive C
C:\ (System)
├── Windows/           ~20-30 GB
├── Program Files/     ~10-20 GB
├── Users/            ~10-20 GB
├── klasifikasibeans/  ~370 MB
└── flask-api/        ~3 GB      ❌ Bisa bikin C: penuh!

Scenario 2: Flask di Drive D (RECOMMENDED)
C:\ (System)
├── Windows/           ~20-30 GB
├── Program Files/     ~10-20 GB
├── Users/            ~10-20 GB
└── klasifikasibeans/  ~370 MB   ✅ C: tetap lega

D:\ (Data)
└── flask-api/        ~3 GB      ✅ D: masih banyak space
```

## 🔧 Optimasi Storage

### 1. Gunakan TensorFlow CPU-only (Jika tidak butuh GPU)
```bash
# Lebih kecil ~500 MB
pip install tensorflow-cpu==2.15.0
```

### 2. Clean Cache setelah Install
```bash
pip cache purge
```

### 3. Gunakan Model Quantized (Jika memungkinkan)
- Model quantized bisa 4x lebih kecil
- Contoh: 200 MB → 50 MB

### 4. Compress Model Files
```python
# Saat save model
model.save('model.h5', save_format='h5', compression='gzip')
```

### 5. Cleanup Development Files
```bash
# Hapus __pycache__
find . -type d -name __pycache__ -exec rm -rf {} +

# Hapus .pyc files
find . -name "*.pyc" -delete
```

## 📈 Storage Monitoring

### Check Storage Usage

**Windows:**
```bash
# Check drive space
wmic logicaldisk get size,freespace,caption

# Check folder size
dir /s D:\flask-api
```

**PowerShell:**
```powershell
# Check drive space
Get-PSDrive

# Check folder size
Get-ChildItem D:\flask-api -Recurse | Measure-Object -Property Length -Sum
```

### Expected Sizes After Installation:

```
D:\flask-api\
├── venv\                      2.1 GB
│   ├── Lib\site-packages\
│   │   ├── tensorflow\        1.8 GB
│   │   ├── numpy\            50 MB
│   │   ├── PIL\              20 MB
│   │   └── ...
│   └── Scripts\              200 MB
├── models\                    300 MB (with both models)
├── app.py                     15 KB
└── requirements.txt           1 KB
```

## 🚨 Troubleshooting Storage Issues

### Error: "No space left on device"

**Solution 1: Move to larger drive**
```bash
# Move entire flask-api folder
move C:\flask-api D:\flask-api

# Update run_flask.bat to point to D:\flask-api
```

**Solution 2: Use external drive**
```bash
# Install to external drive
E:
mkdir flask-api
cd flask-api
# ... continue setup
```

**Solution 3: Use lightweight alternatives**
```bash
# Use TensorFlow Lite instead
pip install tensorflow-lite

# Or use ONNX Runtime (smaller)
pip install onnxruntime
```

### Error: "Disk quota exceeded"

**Check quota:**
```bash
fsutil quota query D:
```

**Request more quota or use different drive**

## 💡 Best Practices

### Development Environment:
```
C:\                          (System Drive - SSD recommended)
└── Users\HP\
    └── klasifikasibeans\    (Laravel - needs fast access)

D:\                          (Data Drive - HDD ok)
└── flask-api\               (Flask - size matters more than speed)
```

### Production Environment:
```
Server with adequate storage (20+ GB recommended)
├── /var/www/laravel/        (Laravel)
└── /opt/flask-api/          (Flask)
```

## 📋 Pre-Installation Checklist

Before running `setup_flask.bat`:

```
[ ] Check available space on D: (need 5+ GB)
[ ] Ensure stable internet (will download 2-3 GB)
[ ] Close unnecessary programs (free up RAM)
[ ] Disable antivirus temporarily (speeds up installation)
[ ] Have backup plan if D: is full (use E: or external drive)
```

## 🎓 Alternative Configurations

### Configuration 1: Minimal (Testing Only)
```
Location: C:\flask-api
Size: ~500 MB
Method: Use mock predictions only (no TensorFlow)
```

### Configuration 2: Standard (Recommended)
```
Location: D:\flask-api
Size: ~3 GB
Method: Full TensorFlow with CPU support
```

### Configuration 3: Full (Production)
```
Location: D:\flask-api
Size: ~5 GB
Method: TensorFlow GPU + CUDA + cuDNN
```

### Configuration 4: Cloud
```
Location: Cloud VM (AWS, GCP, Azure)
Size: Unlimited
Method: Deploy to cloud, Laravel calls via API
```

## 📞 Need Help?

If you're running out of space:
1. Check STORAGE_REQUIREMENTS.md (this file)
2. Try optimization tips above
3. Consider cloud deployment
4. Use external drive as last resort

---

**Remember: Flask API di Drive D = Happy Drive C! 😊**
