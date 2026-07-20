<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $fillable = ['nik', 'nip'];

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}