<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'uuid', 'event_id', 'name', 'institution', 'phone', 'nip', 'data_presensi', 'photo', 'signature'
    ];

    protected static function booted()
    {
        static::creating(function ($presence) {
            if (empty($presence->uuid)) {
                $presence->uuid = (string) \Illuminate\Support\Str::uuid();
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