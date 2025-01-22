<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'users_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'deskripsi'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'tanggal' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
