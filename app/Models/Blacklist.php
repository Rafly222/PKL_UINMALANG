<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, tapi aman)
    protected $table = 'blacklists';

    // Kolom yang diizinkan untuk diisi secara massal saat proses Super Admin melakukan blacklist
    protected $fillable = [
        'nik',
        'nip'
    ];
}