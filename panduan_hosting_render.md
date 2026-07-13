# Panduan Hosting Laravel 11 di Render.com (Bebas Anti-Bot & Support Excel Image)

Render.com adalah platform cloud modern (PaaS) yang sangat direkomendasikan untuk men-deploy aplikasi Laravel. Berbeda dengan InfinityFree, Render **tidak memiliki sistem firewall anti-bot (testcookie)**, sehingga ekspor gambar ke Excel akan langsung terbaca otomatis secara online tanpa hambatan!

Berikut adalah langkah-langkah untuk melakukan hosting Laravel 11 Anda di Render.com secara gratis menggunakan metode **Docker** (karena metode Docker paling stabil dalam mengatur *Document Root* ke folder `public/` Laravel dan memasang ekstensi kamera/foto GD):

---

## Langkah 1: Tambahkan File `Dockerfile` di Root Proyek Anda

Untuk mempermudah deploy di Render, saya telah menyiapkan berkas `Dockerfile` standar industri untuk Laravel. Berkas ini otomatis mengatur Apache, folder `public/`, Composer, serta semua extension PHP (GD, PDO) yang dibutuhkan aplikasi.

*(File ini sudah otomatis saya buatkan di root proyek Laravel Anda dengan nama `Dockerfile`)*.

---

## Langkah 2: Unggah Proyek ke GitHub

Render melakukan deployment otomatis dengan membaca repositori GitHub Anda.
1. Buat repositori baru di [GitHub](https://github.com/) (atur ke **Private** agar `.env` lokal Anda tidak tersebar).
2. Di komputer lokal Anda (terminal Git Bash/Command Prompt), jalankan perintah berikut untuk mengunggah proyek ke GitHub:
   ```bash
   git init
   git add .
   git commit -m "Initial commit for Render deployment"
   git branch -M main
   git remote add origin https://github.com/USERNAME_ANDA/NAMA_REPO_ANDA.git
   git push -u origin main
   ```

---

## Langkah 3: Siapkan Database Online Gratis

Karena database MySQL bawaan InfinityFree memblokir koneksi dari luar (*remote connection*), Anda tidak bisa menghubungkan database InfinityFree ke Render. 

Ada **2 pilihan database gratis** yang bisa Anda gunakan:

### Opsi A: Menggunakan PostgreSQL Gratis dari Render (Sangat Direkomendasikan)
Laravel mendukung PostgreSQL secara bawaan. Anda tinggal membuat database langsung di Render:
1. Di dashboard Render, klik **New** -> **PostgreSQL**.
2. Beri nama database, lalu klik **Create Database** (pilih paket *Free*).
3. Setelah aktif, Render akan memberikan informasi koneksi database (Host, Database Name, Username, Password, Port 5432). Anda akan memasukkan data ini ke Environment Variables di Render nanti.

### Opsi B: Menggunakan MySQL Gratis dari Aiven.io / TiDB Cloud
Jika Anda tetap ingin menggunakan MySQL:
1. Daftar akun gratis di [Aiven.io](https://aiven.io/) atau [TiDB Cloud](https://pingcap.com/products/tidb-cloud).
2. Buat instance MySQL gratis. Anda akan mendapatkan Host, Port, Database Name, Username, dan Password yang mengizinkan akses *remote*.

---

## Langkah 4: Buat Web Service di Render.com

1. Masuk ke [Dashboard Render](https://dashboard.render.com/) dan login menggunakan akun GitHub Anda.
2. Klik tombol **New** di pojok kanan atas, lalu pilih **Web Service**.
3. Pilih opsi **Connect a repository** dan hubungkan dengan repositori GitHub proyek Laravel Anda yang sudah diunggah di Langkah 2.
4. Setel konfigurasi berikut:
   * **Name**: `epresensi-diskominfo` (sesuai keinginan Anda)
   * **Region**: Pilih yang terdekat (misal: *Singapore* atau *Oregon*)
   * **Branch**: `main`
   * **Runtime**: Pilih **Docker** (Render akan otomatis mendeteksi file `Dockerfile` kita).
   * **Instance Type**: Pilih **Free** ($0/month).

---

## Langkah 5: Konfigurasi Environment Variables (`.env`) di Render

Sebelum klik deploy, buka tab **Advanced** (atau masuk ke tab **Env Groups / Environment Variables** setelah service dibuat) untuk memasukkan isi file `.env` Anda secara aman di server:

Tambahkan key-value berikut satu per satu:
* `APP_NAME` = `E-Presensi Diskominfo`
* `APP_ENV` = `production`
* `APP_KEY` = *(Salin nilai APP_KEY dari file `.env` lokal Anda)*
* `APP_DEBUG` = `false`
* `APP_URL` = *(Akan disesuaikan dengan URL yang diberikan Render nanti)*
* `DB_CONNECTION` = `pgsql` *(jika menggunakan PostgreSQL Render)* atau `mysql`
* `DB_HOST` = *(Host database online Anda)*
* `DB_PORT` = `5432` *(PostgreSQL)* atau `3306` *(MySQL)*
* `DB_DATABASE` = *(Nama database online Anda)*
* `DB_USERNAME` = *(Username database online Anda)*
* `DB_PASSWORD` = *(Password database online Anda)*

---

## Langkah 6: Deploy & Migrasi Database

1. Klik **Create Web Service**. Render akan memulai proses build Docker (mengunduh PHP, menginstal composer dependencies, dan mengaktifkan server). Proses ini memakan waktu sekitar 3 - 7 menit.
2. Setelah statusnya menjadi **Live**, aplikasi Anda sudah aktif!
3. **Menjalankan Migrasi Database**:
   Karena database online Anda masih kosong, Anda perlu menjalankan migrasi. Di dashboard Render Web Service Anda:
   * Masuk ke tab **Shell** di sebelah kiri.
   * Jalankan perintah berikut di dalam shell untuk membuat tabel-tabel database:
     ```bash
     php artisan migrate --force
     ```
   * Jika ingin mengisi akun admin default rafly, jalankan seeder:
     ```bash
     php artisan db:seed --force
     ```

Selamat! Website presensi Anda kini sudah online di Render.com dengan URL berakhiran `.onrender.com`. Anda dapat mencobanya secara online, dan seluruh fitur ekspor Excel beserta gambarnya dijamin akan langsung tampil sempurna tanpa ada blokir anti-bot!
