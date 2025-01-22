<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidateController extends Controller
{
    public function newCandidates()
    {
        $newCandidates = Applicant::with(['user', 'jobVacancy'])  // Load relasi user
            ->where('status', 0)  // Status new
            ->paginate(10)// Pagination 10 data
            ->through(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'user' => [
                        'name' => $candidate->user->name
                    ],
                    'degree' => $candidate->degree,
                    'job_vacancy' => $candidate->jobVacancy->name,
                    'created_at' => $candidate->created_at->diffForHumans()
                ];
            });

            // dd($newCandidates->items()); 
            
        return Inertia::render('AdminNewCandidates', [
            'candidates' => $newCandidates
        ]);
    }
    public function screenedCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminScreenedCandidates');
    }
    public function interviewCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminInterviewCandidates');
    }
    public function rejectedCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminRejectedCandidates');
    }


}
