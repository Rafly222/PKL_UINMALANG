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
$table->integer('target_peserta')->nullable()->default(100); // Kuota target untuk rasio
$table->string('status')->default('aktif'); // aktif / nonaktif
$table->timestamps();
});
}
public function down(): void
{
Schema::dropIfExists('events');
}
};