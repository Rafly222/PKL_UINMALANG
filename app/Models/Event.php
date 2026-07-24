<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'name', 'date', 'date_end', 'time_start', 'time_end', 
        'access_type', 'password', 'audience_type', 'fields', 'custom_fields'
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->uuid)) {
                $event->uuid = (string) Str::uuid();
            }
        });
    }

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
        $now = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse($this->date . ' ' . $this->time_start, 'Asia/Jakarta');
        $endDate = $this->date_end ?? $this->date;
        $end = Carbon::parse($endDate . ' ' . $this->time_end, 'Asia/Jakarta');

        return $now->between($start, $end) ? 'Berlaku' : 'Tidak Berlaku';
    }

    // Accessor untuk format rentang tanggal event (misal: 01 - 31 Juli 2026 atau 21 Juli 2026)
    public function getFormattedDateRangeAttribute()
    {
        $startDate = Carbon::parse($this->date);
        if ($this->date_end && $this->date_end !== $this->date) {
            $endDate = Carbon::parse($this->date_end);
            if ($startDate->format('m Y') === $endDate->format('m Y')) {
                return $startDate->translatedFormat('d') . ' - ' . $endDate->translatedFormat('d F Y');
            }
            return $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        }
        return $startDate->translatedFormat('d F Y');
    }

    // Accessor untuk mendapatkan password terdekripsi jika event privat
    public function getDecryptedPasswordAttribute()
    {
        if ($this->access_type === 'privat' && $this->password) {
            try {
                return decrypt($this->password);
            } catch (\Exception $e) {
                return (string) $this->password;
            }
        }
        return '';
    }
}