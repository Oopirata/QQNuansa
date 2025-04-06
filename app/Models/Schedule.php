<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'judul',
        'deskripsi'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'tanggal' => 'datetime'
    ];
}
