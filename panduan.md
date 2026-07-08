# Panduan Pengembangan Proyek E-Presensi Diskominfo Malang

Aplikasi ini adalah sistem presensi kustom multi-role berbasis web yang dibangun menggunakan **Laravel 11** dengan arsitektur **MVC (Model-View-Controller)**.

## 👥 Sistem Pengguna & Hak Akses (Multi-role)
1. **Tamu (Guest):** Hanya bisa mengisi presensi pada event yang aktif dan sesuai jadwal.
2. **User (Pegawai/Pembuat Event):** 
   - Register menggunakan NIK dan NIP.
   - Bisa login dan akses dashboard untuk membuat/mengelola event milik sendiri.
   - Hanya bisa mengakses data presensi dari event yang mereka buat.
3. **Super Admin:** Akses penuh ke semua event, manajemen CRUD User, manajemen log, dan fitur blacklist user yang dihapus agar tidak bisa mendaftar kembali.

## 📅 Logika Bisnis Akses Event (Link & Keamanan)
- Pilihan event tidak menggunakan dropdown, melainkan via **Direct Link / URL unik** per event.
- **Event Publik:** Tamu bisa langsung masuk ke formulir presensi.
- **Event Privat:** Tamu wajib memasukkan password event (dibuat di dashboard user) sebelum bisa masuk ke formulir presensi.
- **Validasi Waktu:** Halaman presensi tamu hanya bisa diakses jika waktu lokal sudah masuk dalam rentang `time_start` dan `time_end` pada tanggal event. Kreator event (User) bisa mengakses formulir kapan saja tanpa batasan waktu dan tanpa password untuk keperluan testing.

## 🛠️ Formulir Presensi Dinamis (Custom Fields)
Setiap event memiliki konfigurasi kolom input yang dinamis (disimpan dalam tipe data JSON):
1. **Semi-Custom:** Opsi ceklis untuk input standar (Nama, No HP, Jenis Kelamin, Instansi, Foto Wajah, TTD Digital). Jika kategori peserta adalah pegawai, input NIP wajib diisi.
2. **Full-Custom:** Pengguna bisa menambah jenis inputan baru secara bebas dengan menentukan nama field dan tipe datanya (text, number, dll).

## 🚀 Aturan Kerja untuk Gemini Agent
- Selalu patuhi arsitektur MVC Laravel yang bersih.
- Prioritaskan penyelesaian seluruh logika backend (Controller, Model, Validasi, Request) terlebih dahulu sebelum menyentuh View.
- Untuk tampilan UI/Frontend, aplikasi ini nantinya akan menggunakan tema resmi **Argon Dashboard 2**. Jangan buat styling CSS kustom yang rumit sebelum aset Argon diterapkan.