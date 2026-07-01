<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
public function up(): void
{
Schema::create('presences', function (Blueprint $table) {
$table->id();
// Menghubungkan presensi ke tabel events secara otomatis
$table->foreignId('event_id')->constrained()->onDelete('cascade');
$table->string('kategori_peserta'); // 'pegawai' atau 'tamu'
$table->string('nama_lengkap');
$table->string('nip')->nullable(); // Khusus pegawai, nullable untuk tamu
$table->string('instansi');
$table->string('no_wa');
$table->text('foto_capture'); // Menyimpan base64 data gambar atau path file gambar
$table->text('tanda_tangan'); // Menyimpan koordinat gambar TTD dalam format Base64PNG
$table->timestamp('waktu_absensi');
$table->timestamps();
});
}
public function down(): void
{
Schema::dropIfExists('presences');
}
};
