# Sistem Klasifikasi Roasting Biji Kopi

Sistem informasi untuk manajemen dan klasifikasi tingkat roasting biji kopi menggunakan Laravel dan Flask AI.

## Kelas Klasifikasi

Sistem mengklasifikasikan biji kopi berdasarkan tingkat roasting:
- 🟢 **Green** - Biji kopi mentah/hijau (belum di-roasting)
- 🟡 **Light** - Roasting ringan (light roast)
- 🟠 **Medium** - Roasting sedang (medium roast)
- 🟤 **Dark** - Roasting gelap (dark roast)

## Fitur

- ✅ Upload gambar biji kopi (tanpa input manual lainnya)
- ✅ Klasifikasi otomatis tingkat roasting menggunakan AI (Flask API)
- ✅ Auto-generate nama dan deskripsi dari hasil klasifikasi
- ✅ CRUD (Create, Read, Update, Delete) data biji kopi
- ✅ Tampilan responsif dengan Tailwind CSS
- ✅ Drag & drop upload gambar
- ✅ Reklasifikasi gambar
- ✅ Detail analisis hasil klasifikasi dengan confidence score
- ✅ Badge warna sesuai tingkat roasting

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Tailwind CSS + Vite
- **Database:** SQLite (default)
- **AI/ML:** Flask API (eksternal)

## Instalasi

### 1. Clone & Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Konfigurasi Environment

```bash
# Copy .env.example
cp .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi Flask API di .env
FLASK_API_URL=http://localhost:5000
FLASK_API_TIMEOUT=30
```

### 3. Setup Database

```bash
# Jalankan migrasi
php artisan migrate

# (Optional) Seed data
php artisan db:seed
```

### 4. Setup Storage

```bash
# Create storage link
php artisan storage:link
```

### 5. Jalankan Development Server

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite
npm run dev

# Terminal 3: Flask API (di folder eksternal)
# Lihat FLASK_API_SPEC.md untuk detail
python app.py
```

## Struktur Project

```
├── app/
│   ├── Http/Controllers/
│   │   └── CoffeeBeansController.php
│   ├── Models/
│   │   └── CoffeeBeans.php
│   └── Services/
│       └── FlaskApiService.php
├── database/
│   └── migrations/
│       └── xxxx_create_coffee_beans_table.php
├── resources/
│   ├── css/
│   │   └── app.css
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── coffee/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── show.blade.php
│           └── edit.blade.php
├── routes/
│   └── web.php
└── FLASK_API_SPEC.md
```

## Endpoints

### Web Routes

- `GET /` - Halaman welcome
- `GET /coffee` - List semua biji kopi
- `GET /coffee/create` - Form tambah data
- `POST /coffee` - Simpan data baru
- `GET /coffee/{id}` - Detail biji kopi
- `GET /coffee/{id}/edit` - Form edit data
- `PUT /coffee/{id}` - Update data
- `DELETE /coffee/{id}` - Hapus data
- `POST /coffee/{id}/reclassify` - Klasifikasi ulang

## Flask API Integration

Sistem ini terintegrasi dengan Flask API untuk klasifikasi gambar biji kopi. 

**Lihat `FLASK_API_SPEC.md` untuk:**
- Spesifikasi lengkap API endpoints
- Contoh implementasi Flask
- Setup instructions
- Request/Response format

## Database Schema

### Table: coffee_beans

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Nama biji kopi |
| variety | varchar(255) | Varietas (nullable) |
| origin | varchar(255) | Asal/origin (nullable) |
| description | text | Deskripsi (nullable) |
| image_path | varchar(255) | Path gambar (nullable) |
| classification | varchar(255) | Hasil klasifikasi tingkat roasting (Green/Light/Medium/Dark) (nullable) |
| confidence | decimal(5,2) | Confidence score (nullable) |
| analysis_result | json | Full result dari API (nullable) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## Development

### Build untuk Production

```bash
npm run build
```

### Testing

```bash
php artisan test
```

## Troubleshooting

### Flask API tidak terhubung

1. Pastikan Flask API berjalan di `http://localhost:5000`
2. Cek konfigurasi `FLASK_API_URL` di `.env`
3. Test dengan: `curl http://localhost:5000/health`

### Storage link error

```bash
php artisan storage:link
```

### Permission error pada storage

```bash
chmod -R 775 storage bootstrap/cache
```

## License

Open-sourced software licensed under the MIT license.

