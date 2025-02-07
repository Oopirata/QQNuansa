<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryRange extends Model
{
    protected $fillable = ['job_vacancy_id', 'min_salary', 'max_salary'];

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function getFormattedRangeAttribute()
    {
        return 'Rp ' . number_format($this->min_salary) . ' - Rp ' . number_format($this->max_salary);
    }
}
