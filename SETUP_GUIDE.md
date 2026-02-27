# Panduan Setup Sistem Klasifikasi Roasting Biji Kopi

## Tentang Sistem

Sistem ini mengklasifikasikan biji kopi berdasarkan tingkat roasting menggunakan Machine Learning:
- 🟢 **Green** - Biji kopi mentah/hijau (belum di-roasting)
- 🟡 **Light** - Roasting ringan (light roast)
- 🟠 **Medium** - Roasting sedang (medium roast)
- 🟤 **Dark** - Roasting gelap (dark roast)

## Langkah-langkah Setup

### 1. Konfigurasi Environment

Tambahkan konfigurasi Flask API ke file `.env`:

```bash
FLASK_API_URL=http://localhost:5000
FLASK_API_TIMEOUT=30
```

### 2. Jalankan Migrasi Database

```bash
php artisan migrate
```

Ini akan membuat tabel `coffee_beans` dengan struktur:
- id
- name
- variety
- origin
- description
- image_path
- classification (hasil dari Flask AI)
- confidence (confidence score)
- analysis_result (JSON full result)
- timestamps

### 3. Buat Storage Link

```bash
php artisan storage:link
```

Ini akan membuat symbolic link dari `public/storage` ke `storage/app/public` untuk akses gambar.

### 4. Jalankan Development Server

**Terminal 1 - Laravel:**
```bash
php artisan serve
```
Akses: http://localhost:8000

**Terminal 2 - Vite (Tailwind CSS):**
```bash
npm run dev
```

**Terminal 3 - Flask API (di folder eksternal):**
```bash
cd /path/to/flask-api
python app.py
```
Akses: http://localhost:5000

### 5. Test Aplikasi

1. Buka browser: http://localhost:8000/coffee
2. Klik "Tambah Data Baru"
3. Isi form dan upload gambar biji kopi
4. Sistem akan otomatis mengirim gambar ke Flask API untuk klasifikasi
5. Lihat hasil klasifikasi di halaman detail

## Struktur Folder yang Dibuat

```
storage/
└── app/
    └── public/
        └── coffee-beans/  (folder untuk menyimpan gambar)
```

## Testing Flask API

### Test Health Check:
```bash
curl http://localhost:5000/health
```

Expected response:
```json
{
  "status": "ok",
  "message": "Flask API is running"
}
```

### Test Classification (dengan curl):
```bash
curl -X POST http://localhost:5000/api/classify \
  -F "image=@/path/to/coffee-image.jpg"
```

Expected response:
```json
{
  "success": true,
  "class": "Medium",
  "confidence": 92.45,
  "predictions": [
    {"class": "Medium", "confidence": 92.45},
    {"class": "Dark", "confidence": 4.32},
    {"class": "Light", "confidence": 2.18},
    {"class": "Green", "confidence": 1.05}
  ],
  "roast_level": "Medium",
  "timestamp": "2024-02-23T14:30:00Z"
}
```

## Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1 no such table: coffee_beans"
**Solusi:** Jalankan `php artisan migrate`

### Error: "The file ... does not exist"
**Solusi:** Jalankan `php artisan storage:link`

### Error: "Connection refused" saat klasifikasi
**Solusi:** 
1. Pastikan Flask API berjalan di port 5000
2. Cek `FLASK_API_URL` di `.env`
3. Test dengan: `curl http://localhost:5000/health`

### Gambar tidak muncul
**Solusi:**
1. Pastikan storage link sudah dibuat: `php artisan storage:link`
2. Cek permission folder storage: `chmod -R 775 storage`

### Error: "Class 'App\Services\FlaskApiService' not found"
**Solusi:** Jalankan `composer dump-autoload`

## Fitur yang Tersedia

### 1. List Data Biji Kopi
- URL: `/coffee`
- Menampilkan semua data dalam grid card
- Pagination otomatis
- Badge warna sesuai tingkat roasting
- Legend tingkat roasting

### 2. Info Roasting
- URL: `/roasting-info`
- Penjelasan lengkap 4 tingkat roasting
- Karakteristik masing-masing level
- Cara kerja sistem klasifikasi

### 2. Tambah Data Baru
- URL: `/coffee/create`
- **Hanya perlu upload gambar**
- Sistem otomatis generate nama dan deskripsi
- Preview gambar sebelum upload
- Drag & drop support
- Loading indicator saat klasifikasi

### 3. Detail Data
- URL: `/coffee/{id}`
- Menampilkan semua informasi
- Hasil klasifikasi AI dengan confidence score
- Tombol "Klasifikasi Ulang"
- Detail analisis JSON

### 4. Edit Data
- URL: `/coffee/{id}/edit`
- Update nama (opsional, bisa dikosongkan untuk auto-generate)
- Update deskripsi (opsional, bisa dikosongkan untuk auto-generate)
- Tambah informasi varietas dan asal
- Ganti gambar (otomatis reklasifikasi)

### 5. Hapus Data
- Menghapus data dan gambar dari storage
- Konfirmasi sebelum hapus

### 6. Reklasifikasi
- URL: `POST /coffee/{id}/reclassify`
- Mengirim ulang gambar ke Flask API
- Update hasil klasifikasi

## Next Steps

1. ✅ Setup environment variables
2. ✅ Jalankan migrasi database
3. ✅ Buat storage link
4. ✅ Jalankan Laravel server
5. ✅ Jalankan Vite dev server
6. ⏳ Setup Flask API (lihat FLASK_API_SPEC.md)
7. ⏳ Test integrasi Laravel-Flask
8. ⏳ Mulai input data biji kopi

## Catatan Penting

- Pastikan Flask API berjalan sebelum melakukan klasifikasi
- Gambar maksimal 2MB (JPEG, PNG, JPG)
- Hasil klasifikasi disimpan di database untuk history
- Bisa reklasifikasi kapan saja jika model diupdate
