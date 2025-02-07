<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    protected $fillable = ['title', 'description'];

    public function salaryRanges(): HasMany
    {
        return $this->hasMany(SalaryRange::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }
}
