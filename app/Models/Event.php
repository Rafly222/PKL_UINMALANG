<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'nama_event',
        'tanggal_event',
        'time_start',
        'time_end',
        'audience_type',
        'access_type',
        'password_akses',
        'form_fields'
    ];
    // Otomatis ubah JSON di database menjadi array PHP saat dipanggil 
    protected $casts = [
        'form_fields' => 'array',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
