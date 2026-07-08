<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    

        // Super Admin 2: Moch Rafly (Akun Kedua)
        User::create([
            'name' => 'Moch Rafly R.A (Super Admin)',
            'nip' => '199807212023031002', 
            'nik' => '3573012345670002',
            'email' => 'rafly.admin@malangkota.go.id',
            'password' => Hash::make('passwordadmin2'),
            'role' => 'admin',
        ]);
    }
}
