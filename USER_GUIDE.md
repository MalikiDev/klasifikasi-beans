# Panduan Pengguna - Sistem Klasifikasi Roasting Biji Kopi

## Cara Menggunakan Sistem

### 1. Menambah Data Biji Kopi Baru

#### Langkah-langkah:

1. **Akses Halaman Tambah Data**
   - Klik tombol "Tambah Data Baru" di halaman utama
   - Atau akses menu "Tambah Data" di navigation bar

2. **Upload Gambar**
   - Klik area upload atau drag & drop gambar
   - Format yang didukung: JPG, JPEG, PNG
   - Ukuran maksimal: 2MB
   - Preview gambar akan muncul setelah dipilih

3. **Klik "Klasifikasi Sekarang"**
   - Sistem akan mengirim gambar ke AI untuk analisis
   - Tunggu beberapa detik (loading indicator akan muncul)
   - Sistem otomatis akan:
     - Mengklasifikasikan tingkat roasting
     - Generate nama otomatis (contoh: "Biji Kopi Medium - 20240223140530")
     - Generate deskripsi sesuai tingkat roasting

4. **Lihat Hasil**
   - Anda akan diarahkan ke halaman detail
   - Lihat hasil klasifikasi dengan confidence score
   - Badge warna menunjukkan tingkat roasting

### 2. Melihat Daftar Data

- Halaman utama menampilkan semua data dalam bentuk grid card
- Setiap card menampilkan:
  - Gambar biji kopi
  - Nama
  - Badge tingkat roasting dengan warna
  - Confidence score
  - Tombol Detail dan Edit

### 3. Melihat Detail Data

Klik tombol "Detail" pada card untuk melihat:
- Gambar biji kopi ukuran besar
- Nama lengkap
- Hasil klasifikasi AI dengan:
  - Tingkat roasting (Green/Light/Medium/Dark)
  - Confidence score dengan progress bar
  - Deskripsi karakteristik
- Informasi tambahan (jika ada):
  - Varietas
  - Asal/Origin
  - Deskripsi custom
- Waktu ditambahkan
- Detail analisis JSON (untuk advanced user)

### 4. Mengedit Data

#### Anda bisa mengedit:

1. **Nama**
   - Kosongkan untuk menggunakan nama auto-generate
   - Atau isi dengan nama custom Anda

2. **Varietas**
   - Contoh: Arabica, Robusta, Liberica
   - Opsional

3. **Asal/Origin**
   - Contoh: Aceh, Toraja, Java
   - Opsional

4. **Deskripsi**
   - Kosongkan untuk menggunakan deskripsi otomatis
   - Atau isi dengan deskripsi custom

5. **Gambar**
   - Upload gambar baru untuk klasifikasi ulang
   - Sistem akan otomatis mengklasifikasi gambar baru

### 5. Klasifikasi Ulang

Jika Anda ingin mengklasifikasi ulang gambar yang sama:
1. Buka halaman detail data
2. Klik tombol "Klasifikasi Ulang"
3. Sistem akan mengirim ulang gambar ke AI
4. Hasil klasifikasi akan diperbarui

### 6. Menghapus Data

1. Buka halaman detail data
2. Klik tombol "Hapus"
3. Konfirmasi penghapusan
4. Data dan gambar akan dihapus permanen

## Tips Mendapatkan Hasil Klasifikasi Terbaik

### 📸 Kualitas Foto

1. **Pencahayaan**
   - Gunakan cahaya yang cukup dan merata
   - Hindari bayangan yang terlalu gelap
   - Cahaya alami lebih baik

2. **Fokus**
   - Pastikan biji kopi dalam fokus
   - Hindari foto blur atau buram
   - Ambil dari jarak yang cukup dekat

3. **Background**
   - Gunakan background yang kontras
   - Background putih atau netral lebih baik
   - Hindari background yang ramai

4. **Komposisi**
   - Biji kopi harus jelas terlihat
   - Isi frame dengan biji kopi
   - Hindari objek lain dalam foto

### 🎯 Contoh Foto yang Baik

**✅ BAIK:**
- Biji kopi mengisi sebagian besar frame
- Pencahayaan merata
- Fokus tajam
- Background netral
- Warna biji kopi terlihat jelas

**❌ HINDARI:**
- Foto terlalu jauh
- Pencahayaan kurang
- Blur atau tidak fokus
- Background ramai
- Warna tidak akurat

## Memahami Hasil Klasifikasi

### Tingkat Roasting

#### 🟢 Green (Biji Mentah)
- **Karakteristik:**
  - Warna hijau keabu-abuan
  - Tekstur keras dan padat
  - Tidak memiliki aroma kopi
  - Belum bisa diseduh
- **Confidence Tinggi:** 85-100%
- **Confidence Rendah:** < 60% (mungkin perlu foto lebih baik)

#### 🟡 Light (Roasting Ringan)
- **Karakteristik:**
  - Warna cokelat muda
  - Rasa asam menonjol
  - Aroma floral dan fruity
  - Body ringan
  - Kafein tinggi
- **Cocok untuk:** Pour over, V60, Aeropress

#### 🟠 Medium (Roasting Sedang)
- **Karakteristik:**
  - Warna cokelat sedang
  - Keseimbangan asam dan pahit
  - Aroma karamel dan nutty
  - Body sedang
  - Paling populer
- **Cocok untuk:** Espresso, French press, Drip coffee

#### 🟤 Dark (Roasting Gelap)
- **Karakteristik:**
  - Warna cokelat gelap hingga hitam
  - Rasa pahit kuat
  - Aroma smoky dan bold
  - Body penuh dan kental
  - Kafein lebih rendah
- **Cocok untuk:** Espresso, Cold brew, Turkish coffee

### Confidence Score

- **90-100%:** Sangat Tinggi - Hasil sangat akurat
- **75-89%:** Tinggi - Hasil dapat dipercaya
- **60-74%:** Sedang - Hasil cukup baik, pertimbangkan foto lebih baik
- **< 60%:** Rendah - Disarankan upload foto baru dengan kualitas lebih baik

## FAQ (Frequently Asked Questions)

### Q: Apakah saya harus mengisi nama dan deskripsi?
**A:** Tidak! Sistem akan otomatis generate nama dan deskripsi berdasarkan hasil klasifikasi. Anda hanya perlu upload gambar.

### Q: Bagaimana jika hasil klasifikasi tidak akurat?
**A:** 
1. Coba upload foto dengan kualitas lebih baik
2. Gunakan pencahayaan yang lebih baik
3. Pastikan fokus pada biji kopi
4. Klik "Klasifikasi Ulang" untuk mencoba lagi

### Q: Apakah saya bisa mengubah hasil klasifikasi?
**A:** Hasil klasifikasi otomatis dari AI tidak bisa diubah manual. Tapi Anda bisa:
- Upload gambar baru untuk klasifikasi ulang
- Klik "Klasifikasi Ulang" untuk analisis ulang
- Edit nama dan deskripsi secara manual

### Q: Berapa lama proses klasifikasi?
**A:** Biasanya 2-5 detik, tergantung:
- Ukuran gambar
- Kecepatan koneksi internet
- Load server Flask API

### Q: Apakah data saya aman?
**A:** Ya! Gambar dan data disimpan di server lokal Anda. Tidak ada data yang dikirim ke pihak ketiga.

### Q: Bisakah saya mengklasifikasi banyak gambar sekaligus?
**A:** Saat ini sistem hanya support upload satu per satu. Fitur batch upload mungkin akan ditambahkan di versi mendatang.

### Q: Format gambar apa yang didukung?
**A:** JPG, JPEG, dan PNG dengan ukuran maksimal 2MB.

### Q: Bagaimana cara menghubungi support?
**A:** Jika ada masalah teknis, hubungi administrator sistem atau cek log error di `storage/logs/laravel.log`.

## Troubleshooting

### Masalah: "Gagal melakukan klasifikasi"
**Solusi:**
1. Pastikan Flask API berjalan
2. Cek koneksi internet
3. Coba upload gambar lagi
4. Hubungi administrator

### Masalah: Gambar tidak muncul
**Solusi:**
1. Refresh halaman
2. Clear browser cache
3. Cek apakah file masih ada di storage

### Masalah: Upload gagal
**Solusi:**
1. Cek ukuran file (max 2MB)
2. Cek format file (JPG, JPEG, PNG)
3. Coba compress gambar
4. Gunakan gambar lain

## Kontak & Support

Untuk bantuan lebih lanjut:
- Email: [admin@example.com]
- Dokumentasi: Lihat file README.md dan SETUP_GUIDE.md
- Issue: Laporkan bug atau request fitur ke administrator
