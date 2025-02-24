<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Applicant extends Model
{
    protected $fillable = [
        'users_id',
        'job_vacancy_id',
        'salary_range_id',
        'name',
        'degree',
        'ipk',
        'nomor_hp',
        'province',
        'city',
        'applicant_email',
        'linkedin',
        'cv_path',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function curriculumVitae(): HasOne
    {
        return $this->hasOne(CurriculumVitae::class);
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    public function salaryRange()
    {
        return $this->belongsTo(SalaryRange::class);
    }
}
