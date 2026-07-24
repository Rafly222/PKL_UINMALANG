# Panduan Fitur: Manajemen Event & QR Code (E-Presensi)

Dokumen ini berisi panduan teknis yang menjelaskan bagaimana sistem mengelola pembuatan event, format penanggalan (1-31 hari), perenderan QR Code dengan logo Pemerintah Kota Malang di tengahnya, serta tombol unduh beresolusi tinggi.

---

## 📌 1. Dashboard & Pembuatan Event
Fitur Manajemen Event dibagi menjadi dua sisi dashboard:
1.  **Dashboard Admin**: Terletak di file 📄 [admin.blade.php](file:///c:/laragon/www/git/PKL_UINMALANG/resources/views/dashboard/admin.blade.php).
2.  **Dashboard User/Pegawai**: Terletak di file 📄 [user.blade.php](file:///c:/laragon/www/git/PKL_UINMALANG/resources/views/dashboard/user.blade.php).

Kedua halaman ini menyajikan daftar event presensi dalam bentuk tabel serta modal untuk melihat preview dan mengunduh QR Code dari tiap event.

---

## 📅 2. Manajemen Tanggal Event (Rentang Hari 1 ~ 31)
Model database event mendukung penanggalan dinamis dari tanggal awal (`date`) sampai tanggal akhir (`date_end`), yang secara otomatis dikonversi menjadi rentang tanggal yang rapi.

Logika pemformatan tanggal diimplementasikan menggunakan **Carbon** (library pengolah tanggal PHP) di model 📄 [Event.php](file:///c:/laragon/www/git/PKL_UINMALANG/app/Models/Event.php#L53-L64) melalui accessor `getFormattedDateRangeAttribute`:

```php
public function getFormattedDateRangeAttribute()
{
    $startDate = Carbon::parse($this->date);
    if ($this->date_end && $this->date_end !== $this->date) {
        $endDate = Carbon::parse($this->date_end);
        // Jika rentang tanggal berada di bulan & tahun yang sama
        if ($startDate->format('m Y') === $endDate->format('m Y')) {
            return $startDate->format('d') . ' - ' . $endDate->format('d F Y'); 
            // Output Contoh: "01 - 31 Juli 2026"
        }
        // Jika beda bulan
        return $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        // Output Contoh: "28 Juli 2026 - 02 Agustus 2026"
    }
    // Jika event satu hari saja
    return $startDate->format('d F Y');
}
```

Format ini dipanggil pada blade view menggunakan sintaks `{{ $event->formatted_date_range }}`.

---

## 📱 3. Library QR Code
Proyek ini menggunakan library JavaScript sisi klien (browser) untuk merender QR Code secara instan:
*   **Nama File**: `qrcode.min.js`
*   **Path**: `public/assets/argon-dashboard-pro-html-v2.0.5/assets/js/plugins/qrcode.min.js`
*   **Sumber Pustaka**: Pustaka open-source JavaScript QRCode (oleh Davidson) yang bekerja dengan cara merender gambar QR ke dalam elemen `<canvas>` dan `<img>` secara dinamis.

---

## 🔗 4. Perenderan Link URL Event ke QR Code
Setiap QR Code mewakili link presensi unik untuk setiap event.
*   **Link Presensi**: Diambil dari route Laravel `route('presence.form', $event->uuid)` (Contoh: `http://127.0.0.1:8000/presensi/598b05f8-6fae-4b57-a9c3-f1abfc9d6966`).
*   **Atribut Data**: URL ini disematkan pada elemen modal melalui atribut `data-url`:
    ```html
    <div class="modal" id="qrModal-{{ $event->id }}" data-url="{{ route('presence.form', $event->uuid) }}">
    ```
*   **Proses Render JS**: Saat modal ditampilkan (`shown.bs.modal`), JavaScript mengambil nilai dari atribut `data-url` dan mengirimkannya ke objek instansiasi `QRCode`:
    ```javascript
    new QRCode(qrBox, {
      text: url, // link presensi unik event
      width: 180,
      height: 180,
      correctLevel : QRCode.CorrectLevel.H // Level koreksi error tinggi agar logo di tengah tidak merusak pembacaan QR
    });
    ```

---

## 🏛️ 5. Menampilkan Logo Pemerintah Kota Malang di Tengah QR Code
Agar QR Code memiliki identitas resmi, logo Pemkot Malang disematkan di tengah QR Code melalui 2 metode:

### A. Tampilan Visual Halaman (CSS Overlay)
Di dalam modal preview, logo ditampilkan di tengah QR Code menggunakan teknik penumpukan elemen absolut (CSS overlays) di atas container QR:
```html
<div class="position-relative d-inline-block p-2 bg-white rounded border">
  <div id="qrcode-box-{{ $event->id }}"></div>
  <!-- Logo overlay di tengah -->
  <div class="position-absolute start-50 top-50 translate-middle bg-white p-1 rounded-3" style="width: 42px; height: 42px;">
    <img src="{{ asset('assets/argon-dashboard-pro-html-v2.0.5/assets/img/logos/GKV307_Kota Malang-logobase.net.png') }}" style="width: 32px; height: 32px; object-fit: contain;">
  </div>
</div>
```

### B. Gambar Canvas JS (Penting untuk Fitur Copy & Save As)
Saat QR selesai dirender, script JS akan menggambar ulang logo Pemkot di atas canvas QR Code secara manual menggunakan koordinat 2D:
```javascript
const ctx = canvas.getContext('2d');
const logo = new Image();
logo.onload = function () {
  const logoSize = 36;
  const x = (canvas.width - logoSize) / 2;
  const y = (canvas.height - logoSize) / 2;

  // Gambar kotak putih sebagai latar logo
  ctx.fillStyle = '#ffffff';
  ctx.fillRect(x - 3, y - 3, logoSize + 6, logoSize + 6);

  // Gambar logo di tengah
  ctx.drawImage(logo, x, y, logoSize, logoSize);

  // Update src image di modal
  const img = qrBox.querySelector('img');
  if (img) img.src = canvas.toDataURL('image/png');
};
// Menggunakan URL relatif agar tidak melanggar batasan CORS (Tainted Canvas)
logo.src = new URL(logoSrc).pathname;
```

---

## 💾 6. Fitur Unduh QR Code Resolusi Tinggi (600x600 px)
Saat tombol **Unduh QR Code** ditekan, sistem tidak mendownload gambar kecil beresolusi 180x180 px dari modal. Sistem akan **membuat ulang** gambar dengan detail yang lebih tajam:

1.  **Instansiasi Canvas Baru**: Membuat canvas 600x600 px tersembunyi dengan link event yang sama.
2.  **Menggambar Lingkaran Putih**: Menggambar latar belakang lingkaran putih berdiameter 132px tepat di tengah koordinat `(300, 300)` dari canvas 600x600 px.
3.  **Menggambar Logo**: Menggambar logo resmi Pemkot Malang berukuran 110px di dalam lingkaran putih tersebut.
4.  **Memicu Download**: Mengubah canvas menjadi link unduhan bertipe PNG dengan nama file teratur:
    ```javascript
    const a = document.createElement('a');
    a.href = canvas.toDataURL('image/png');
    a.download = 'QR_Code_' + eventName + '.png'; // Output nama: QR_Code_nama-event.png
    a.click();
    ```

---

## 📋 Tampilan Informasi di Modal Preview
Di dalam modal preview, semua informasi yang diminta oleh pengguna disajikan secara lengkap:
*   **Nama Event**: Diambil dari data DB database `{{ $event->name }}`.
*   **QR Kode**: Hasil render dari `qrcode.min.js`.
*   **Tanggal 1 ~ 31**: Ditampilkan secara otomatis dari accessor format rentang tanggal event.
*   **Unduh QR Kode**: Tombol khusus berkelas `.download-qr-btn` untuk memicu pengunduhan file resolusi tinggi dengan logo Kota Malang tersemat di dalamnya secara permanen.
*   **Informasi Kata Sandi (Event Privat)**: Menampilkan label/badge berisi password kegiatan terdekripsi di dalam modal preview QR Code (di atas gambar QR Code). Hal ini memudahkan admin atau staf pembuat event untuk mengetahui password event privat tanpa harus membuka menu pengeditan data event.
