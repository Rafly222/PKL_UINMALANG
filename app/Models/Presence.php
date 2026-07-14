<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'event_id', 'name', 'institution', 'phone', 'nip', 'data_presensi', 'photo', 'signature'
    ];

    protected $casts = [
        'data_presensi' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}