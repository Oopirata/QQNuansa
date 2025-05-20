<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PsychotestParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_code_id',
        'name',
        'email',
        'birthdate',
        'gender',
        'education',
        'test_date',
        'start_time',
        'finish_time',
        'session_token'
    ];

    public function accessCode(): BelongsTo
    {
        return $this->belongsTo(AccessCode::class, 'access_code_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PsychotestAnswer::class, 'participant_id');
    }
}
