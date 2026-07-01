<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Presence extends Model
{
use HasFactory;
protected $fillable = [
'event_id',
'kategori_peserta',
'nama_lengkap',
'nip',
'instansi',
'no_wa',
'foto_capture',
'tanda_tangan',
'waktu_absensi'
];
// Relasi balik ke Event
public function event()
{
return $this->belongsTo(Event::class);
}
}