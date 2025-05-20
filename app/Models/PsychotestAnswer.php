<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PsychotestAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'question_id', 'answer'];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(PsychotestParticipant::class, 'participant_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
