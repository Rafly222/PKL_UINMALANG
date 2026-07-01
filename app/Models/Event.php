<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Event extends Model
{
use HasFactory;
protected $fillable = ['nama_event', 'tanggal_event', 'target_peserta', 'status'];
// Relasi ke tabel presensi
public function presences()
{
return $this->hasMany(Presence::class);
}
}