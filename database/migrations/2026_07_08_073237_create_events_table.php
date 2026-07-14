// database/migrations/2026_07_08_000003_create_events_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->enum('access_type', ['publik', 'privat'])->default('publik');
            $table->string('password')->nullable();
            $table->enum('audience_type', ['umum', 'pegawai', 'semua'])->default('semua');
            $table->json('fields')->nullable(); // Menyimpan checkbox semi-custom (cth: ["sc-phone", "sc-photo"])
            $table->json('custom_fields')->nullable(); // Menyimpan input kustom dinamis (cth: [{"label": "Bidang", "type": "text"}])
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};