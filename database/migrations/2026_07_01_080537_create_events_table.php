<?php
// create events table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
public function up(): void
{
Schema::create('events', function (Blueprint $table) {
$table->id();
$table->string('nama_event'); // Contoh: "Presensi Kehadiran Praktikan UIN Malang"
$table->date('tanggal_event'); 
$table->string('status')->default('aktif'); // aktif / nonaktif
$table->timestamps();
});

Schema::table('events', function (Blueprint $table) { 
$table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembuat event 
$table->time('time_start'); 
$table->time('time_end'); 
$table->enum('audience_type', ['umum', 'pegawai', 'semua'])->default('umum'); 
$table->enum('access_type', ['publik', 'privat'])->default('publik'); 
$table->string('password_akses')->nullable(); 
$table->json('form_fields')->nullable(); // Menyimpan konfigurasi checklist dan full-custom 
});
}

public function down(): void
{
Schema::dropIfExists('events');
}
};