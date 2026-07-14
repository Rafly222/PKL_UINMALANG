// database/migrations/2026_07_08_000001_modify_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');

    $table->string('nip', 18)->unique()->nullable(); 
    $table->enum('role', ['user', 'admin'])->default('user'); 
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved'); 
    $table->rememberToken();
    $table->timestamps();
    });
 }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
