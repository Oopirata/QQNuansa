<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'code', 'status'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PsychotestSession::class, 'session_id');
    }

    public function participant(): HasOne
    {
        return $this->hasOne(PsychotestParticipant::class, 'access_code_id');
    }
}
