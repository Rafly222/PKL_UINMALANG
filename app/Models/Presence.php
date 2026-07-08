<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'event_id',
        'kategori_peserta',
        'nama_lengkap',
        'nip',
        'instansi',
        'no_wa',
        'data_presensi',
        'foto_capture',
        'tanda_tangan'
    ];
    protected $casts = [
        'data_presensi' => 'array',
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
