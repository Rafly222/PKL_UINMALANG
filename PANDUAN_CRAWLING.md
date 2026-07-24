# Panduan Crawling & Pengamanan Aset Media Presensi (E-Presensi)

Dokumen ini menjelaskan alur kerja, panduan pengoperasian, serta penanganan masalah terkait fitur crawling/pemindahan berkas fisik (foto wajah dan tanda tangan digital) dari server lama ke penyimpanan lokal yang aman.

---

## 📌 1. Deskripsi Perintah Crawling
Pemindahan berkas media dari server lama (`asustor.malangkota.go.id`) dilakukan secara otomatis menggunakan perintah command line Artisan Laravel:

*   **Nama Perintah**: `presences:download-media`
*   **Berkas Program**: `app/Console/Commands/DownloadPresenceMedia.php`
*   **Fungsi**: Membaca nama berkas foto dan tanda tangan di database, mengunduhnya dari domain lama, lalu menyimpannya ke folder private lokal.

---

## ⚙️ 2. Cara Menjalankan Perintah Unduhan
Buka aplikasi **Laragon** (atau terminal proyek Anda), lalu jalankan perintah berikut:

```bash
php artisan presences:download-media
```

### Penjelasan Logika Kerja Program:
1.  **Pengambilan Data**: Program menyeleksi baris data pada tabel `presences` yang kolom `photo`-nya atau `signature`-nya tidak bernilai kosong (`NULL`).
2.  **Cek Duplikasi**: Sebelum mengunduh, program memeriksa apakah berkas tersebut sudah ada di server baru (`Storage::exists($localPath)`). Jika sudah ada, file dilewati agar hemat kuota dan waktu.
3.  **Proses Download**: Program melakukan HTTP GET Request ke URL server lama:
    *   Foto: `https://asustor.malangkota.go.id/pictures/{nama_file}`
    *   Tanda Tangan: `https://asustor.malangkota.go.id/pictures/{nama_file}`
4.  **Penyimpanan**: Berkas biner diunduh lalu disimpan ke folder lokal:
    *   Foto: `storage/app/presences/photos/`
    *   Tanda Tangan: `storage/app/presences/signatures/`

---

## 🔒 3. Keamanan & Pembatasan Akses Media
Aset media yang diunduh disimpan di luar folder `public` sehingga tidak bisa diakses secara langsung oleh publik lewat URL (misalnya `domain/pictures/nama_file.jpg`). Akses dikunci dengan aturan ketat:

1.  **Grup Middleware `auth`**: Rute untuk menampilkan gambar (`presence.photo` dan `presence.signature`) dimasukkan di dalam middleware login pada `routes/web.php`. Pengguna yang belum masuk akan langsung diarahkan ke halaman login.
2.  **Otorisasi Obyek (MediaController)**: Di dalam `app/Http/Controllers/MediaController.php`, akses diverifikasi dengan aturan:
    *   **Admin/Super Admin**: Diizinkan melihat semua foto & tanda tangan.
    *   **Pembuat Kegiatan (Event Creator)**: Diizinkan melihat foto & tanda tangan dari semua staf yang absen di kegiatannya.
    *   **Staf Bersangkutan (Owner)**: Hanya diizinkan melihat foto & tanda tangan miliknya sendiri (berdasarkan kesesuaian NIP login).
    *   **Lainnya**: Akses dibatalkan dengan error status `403 Forbidden` (Akses Ditolak).

---

## 🛠️ 4. Penanganan Masalah (Troubleshooting)

### A. Error cURL 77 (Error setting certificate file)
**Penyebab**: Terjadi pada server lokal Windows (seperti Laragon/XAMPP) karena PHP cURL tidak dapat menemukan berkas sertifikat SSL (`cacert.pem`) yang terdaftar di konfigurasi `php.ini`.

**Solusi**: Program crawling telah dilengkapi dengan baris kode khusus `.withoutVerifying()` pada panggilan HTTP client:
```php
Http::withoutVerifying()->timeout(10)->get($url);
```
Baris ini menginstruksikan PHP untuk mengabaikan pemeriksaan sertifikat SSL saat mengunduh berkas dari server `asustor.malangkota.go.id`, sehingga error 77 teratasi sepenuhnya secara otomatis tanpa perlu mengubah berkas `php.ini`.

### B. Gambar Tampil Rusak / Silang Merah di Excel
*   **Penyebab**: Excel memblokir rendering gambar berbasis data Base64 karena masalah keamanan bawaan aplikasi Microsoft Excel.
*   **Solusi**: Pada ekspor Excel (`presence_excel.blade.php`), kita mengarahkan gambar menggunakan tag `<img>` dengan tautan biner dinamis (`route('presence.photo', $presence->id)`). Excel akan mengunduh berkas biner tersebut secara aman di latar belakang (melalui sesi login yang sah) dan menampilkannya sebagai gambar fisik di dalam sel Excel.
