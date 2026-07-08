// database/migrations/2026_07_08_000004_create_presences_table.php
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
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('nik', 16)->nullable();
            $table->string('institution')->nullable();
            $table->string('phone')->nullable();
            $table->string('nip', 18)->nullable();
            $table->json('data_presensi')->nullable(); // Menyimpan isian dinamis & custom fields lainnya
            $table->text('photo')->nullable(); // Menyimpan base64 String foto wajah
            $table->text('signature')->nullable(); // Menyimpan base64 String tanda tangan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
