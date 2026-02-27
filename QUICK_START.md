# 🚀 Quick Start Guide - Coffee Roasting Classifier

## Ringkasan Sistem

Sistem ini terdiri dari 2 komponen:
1. **Laravel (Frontend & Backend)** - `C:\Users\HP\klasifikasibeans\`
2. **Flask API (ML Service)** - `D:\flask-api\` (akan dibuat di drive D)

**Kenapa Flask di Drive D?**
- TensorFlow + dependencies: ~2-3 GB
- Virtual environment: ~1-2 GB  
- Model files: ~100-500 MB
- **Total: ~3-5 GB** - Lebih aman di drive D untuk menghemat space drive C

## 📋 Prerequisites

- [x] PHP 8.2+ (sudah ada)
- [x] Composer (sudah ada)
- [x] Node.js & NPM (sudah ada)
- [ ] Python 3.8+ ([Download](https://www.python.org/downloads/))

## ⚡ Setup Cepat (5 Langkah)

### 1️⃣ Setup Flask API Backend

**Opsi A: Menggunakan Script Otomatis (Recommended)**

```bash
# Di folder klasifikasibeans, double-click:
setup_flask.bat
```

**Opsi B: Manual**

```bash
# Buat folder di Drive D
D:
mkdir flask-api
cd flask-api

# Copy files
copy C:\Users\HP\klasifikasibeans\flask_app_dual_model.py app.py
copy C:\Users\HP\klasifikasibeans\flask_requirements.txt requirements.txt

# Setup Python
python -m venv venv
venv\Scripts\activate
python -m pip install --upgrade pip
pip install -r requirements.txt

# Buat folder models
mkdir models
```

**Storage yang dibutuhkan:**
- Virtual environment: ~1-2 GB
- TensorFlow: ~2 GB
- Dependencies lain: ~500 MB
- **Total: ~3-5 GB**

### 2️⃣ Jalankan Flask API

**Opsi A: Menggunakan Script**
```bash
# Di folder klasifikasibeans, double-click:
run_flask.bat
```

**Opsi B: Manual**
```bash
D:
cd flask-api
venv\Scripts\activate
python app.py
```

Server akan jalan di: **http://localhost:5000**

### 3️⃣ Setup Laravel (Jika Belum)

```bash
cd C:\Users\HP\klasifikasibeans

# Install dependencies
composer install
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Tambahkan ke .env:
FLASK_API_URL=http://localhost:5000
FLASK_API_TIMEOUT=60

# Jalankan migrasi
php artisan migrate
php artisan storage:link
```

### 4️⃣ Jalankan Laravel

**Terminal 1: Laravel Server**
```bash
php artisan serve
```

**Terminal 2: Vite (Tailwind)**
```bash
npm run dev
```

### 5️⃣ Test Sistem

1. **Test Flask API:**
   - Buka: http://localhost:5000/health
   - Harus return: `{"status": "ok", ...}`

2. **Test Laravel:**
   - Buka: http://localhost:8000
   - Akan redirect ke: http://localhost:8000/coffee

3. **Test Upload:**
   - Klik "Tambah Data Baru"
   - Upload gambar biji kopi
   - Lihat hasil klasifikasi dari kedua model

## 📁 Struktur Folder Final

```
C:\Users\HP\
└── klasifikasibeans\              # Laravel Project (~500 MB)
    ├── app\
    ├── database\
    ├── resources\
    ├── routes\
    ├── .env
    ├── setup_flask.bat           # Script setup Flask
    ├── run_flask.bat             # Script run Flask
    └── FLASK_SETUP_GUIDE.md      # Panduan lengkap

D:\                                # Drive D untuk storage besar
└── flask-api\                     # Flask Backend (~3-5 GB)
    ├── app.py                     # Main Flask app
    ├── requirements.txt           # Python dependencies
    ├── venv\                      # Virtual environment (~2 GB)
    │   ├── Lib\
    │   ├── Scripts\
    │   └── ...
    └── models\                    # Model files (tambahkan nanti)
        ├── mobilenetv3_small.h5   # Model Small (~50-100 MB)
        └── mobilenetv3_large.h5   # Model Large (~100-200 MB)
```

**Storage Breakdown:**
- Laravel project: ~500 MB
- Flask venv + dependencies: ~3 GB
- Model files: ~200-500 MB
- **Total: ~3.5-4 GB**

## 🎯 Mode Operasi

### Mode 1: Testing (Mock Data) - SAAT INI
- Flask API menggunakan random predictions
- Tidak perlu model files
- Cocok untuk development Laravel

### Mode 2: Production (Real Models)
- Letakkan model files di `flask-api\models\`
- Update `app.py` (lihat FLASK_SETUP_GUIDE.md)
- Model akan di-load saat startup

## 🔍 Troubleshooting Cepat

### Flask API tidak jalan?
```bash
# Check Python
python --version

# Check virtual environment
D:
cd flask-api
venv\Scripts\activate
python app.py
```

### Laravel tidak bisa connect ke Flask?
```bash
# Test Flask health
curl http://localhost:5000/health

# Check .env Laravel
FLASK_API_URL=http://localhost:5000

# Clear cache Laravel
php artisan config:clear
```

### Port 5000 sudah dipakai?
Edit `flask-api\app.py`, ganti port:
```python
app.run(debug=True, host='0.0.0.0', port=5001)
```

Update `.env` Laravel:
```
FLASK_API_URL=http://localhost:5001
```

## 📊 Testing Workflow

### 1. Test Flask API Standalone
```bash
# Health check
curl http://localhost:5000/health

# Model info
curl http://localhost:5000/api/model-info

# Classify (dengan Postman atau curl)
curl -X POST http://localhost:5000/api/classify-dual -F "image=@test.jpg"
```

### 2. Test dari Laravel
```bash
php artisan tinker
```
```php
$service = app(\App\Services\FlaskApiService::class);
$health = $service->healthCheck();
var_dump($health); // true = OK
```

### 3. Test Full Integration
1. Buka http://localhost:8000/coffee/create
2. Upload gambar
3. Lihat hasil dari kedua model
4. Check detail perbandingan

## 🎓 Dokumentasi Lengkap

- **FLASK_SETUP_GUIDE.md** - Setup Flask API detail
- **FLASK_API_SPEC.md** - Spesifikasi API endpoints
- **INTEGRATION_GUIDE.md** - Integrasi Laravel-Flask
- **USER_GUIDE.md** - Panduan untuk end user
- **README.md** - Overview project

## 💡 Tips Development

1. **Jalankan Flask dulu**, baru Laravel
2. **Mode Mock** cukup untuk development UI
3. **Model asli** diperlukan untuk production
4. **Check logs** jika ada error:
   - Flask: Terminal output
   - Laravel: `storage/logs/laravel.log`

## 🚀 Next Steps

### Untuk Development (Sekarang)
- [x] Setup Flask API (mode mock)
- [x] Setup Laravel
- [x] Test upload & classification
- [ ] Customize UI sesuai kebutuhan

### Untuk Production (Nanti)
- [ ] Train model MobileNetV3 Small & Large
- [ ] Export model ke .h5 format
- [ ] Update Flask API dengan model asli
- [ ] Performance testing
- [ ] Deploy ke server

## 📞 Support

Jika ada masalah:
1. Check dokumentasi di folder ini
2. Check error logs
3. Test komponen secara terpisah (Flask, Laravel)
4. Pastikan semua service running

## ✅ Checklist Lengkap

**Flask API:**
- [ ] Python installed
- [ ] Virtual environment created
- [ ] Dependencies installed
- [ ] Server running on port 5000
- [ ] Health check returns OK

**Laravel:**
- [ ] Dependencies installed (composer & npm)
- [ ] .env configured
- [ ] Database migrated
- [ ] Storage linked
- [ ] Server running on port 8000
- [ ] Vite running

**Integration:**
- [ ] Flask API accessible from Laravel
- [ ] Upload gambar berhasil
- [ ] Klasifikasi berjalan
- [ ] Hasil ditampilkan dengan benar

---

**Selamat! Sistem Anda siap digunakan! 🎉**
