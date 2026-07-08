<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'name', 'date', 'time_start', 'time_end', 
        'access_type', 'password', 'audience_type', 'fields', 'custom_fields'
    ];

    // Mengubah data JSON otomatis menjadi tipe Array di PHP
    protected $casts = [
        'fields' => 'array',
        'custom_fields' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    // Accessor untuk mendapatkan status form presensi (Berlaku / Tidak Berlaku)
    public function getStatusAbsensiAttribute()
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $start = \Carbon\Carbon::parse($this->date . ' ' . $this->time_start, 'Asia/Jakarta');
        $end = \Carbon\Carbon::parse($this->date . ' ' . $this->time_end, 'Asia/Jakarta');

        return $now->between($start, $end) ? 'Berlaku' : 'Tidak Berlaku';
    }
}