<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'pdf_path'
    ];

    // If you want to define the relationship between TestResult and User (Participant)
    public function participant()
    {
        return $this->belongsTo(PsychotestParticipant::class, 'participant_id');
    }
}
