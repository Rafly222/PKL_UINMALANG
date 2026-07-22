<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Presence extends Model
{
    protected $fillable = [
        'uuid', 'event_id', 'name', 'institution', 'phone', 'nip', 'data_presensi', 'photo', 'signature'
    ];

    protected static function booted()
    {
        static::creating(function ($presence) {
            if (empty($presence->uuid)) {
                $presence->uuid = (string) Str::uuid();
            }
        });
    }

    protected $casts = [
        'data_presensi' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}