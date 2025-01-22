<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidateController extends Controller
{
    public function newCandidates()
    {
        $newCandidates = Applicant::with(['user', 'jobVacancy'])
            ->where('status', 0)
            ->paginate(10);

        // Transform the data manually
        $transformedCandidates = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($newCandidates->items())->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'user' => $candidate->user->name,
                    'degree' => $candidate->degree,
                    'job_vacancy' => $candidate->jobVacancy->title,
                    'created_at' => $candidate->created_at
                ];
            }),
            $newCandidates->total(),
            $newCandidates->perPage(),
            $newCandidates->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
        // dd($transformedCandidates->items());
        return Inertia::render('AdminNewCandidates', [
            'candidates' => $transformedCandidates
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
