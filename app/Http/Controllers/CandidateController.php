<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidateController extends Controller
{
    public function newCandidates(Request $request)
    {
        $search = $request->input('search');

        $query = Applicant::with(['user', 'jobVacancy'])
            ->where('status', 0);

        // Menambahkan kondisi pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%") // Mencari langsung di kolom name Applicant
                  ->orWhereHas('jobVacancy', function($query) use ($search) {
                      $query->where('title', 'like', "%{$search}%");
                  })
                  ->orWhere('degree', 'like', "%{$search}%");
            });
        }

        $newCandidates = $query->paginate(10);

        // Transform the data manually
        $transformedCandidates = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($newCandidates->items())->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'user' => $candidate->name,
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

        return Inertia::render('AdminNewCandidates', [
            'candidates' => $transformedCandidates,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function newCandidatesDetail($id)
    {
        $newCandidate = Applicant::with(['user', 'jobVacancy'])
        ->where('id', $id)
        ->first();

        $candidate = [
            'id' => $newCandidate->id,
            'user_id' => $newCandidate->user->id,
            'user' => $newCandidate->name,
            'email' => $newCandidate->user->email,
            'degree' => $newCandidate->degree,
            'job_vacancy' => $newCandidate->jobVacancy->title,
            'status' => $newCandidate->status,
            'created_at' => $newCandidate->created_at
        ];
        // dd($newCandidate);

        return Inertia::render('AdminDetailNewCandidates', [
            'candidates' => $candidate
        ]);
    }

    public function moveToScreened($user_id)
    {
        $candidate = Applicant::find($user_id);
        $candidate->status = 1;
        $candidate->save();

        return redirect()->route('adminNewCandidates');
    }

    public function screenedCandidates()
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

    public function screenCandidateDetail($user_id) {
        $newCandidate = Applicant::with(['user', 'jobVacancy'])
        ->where('id', $user_id)
        ->first();

        $candidate = [
            'id' => $newCandidate->id,
            'user_id' => $newCandidate->user->id,
            'user' => $newCandidate->user->name,
            'email' => $newCandidate->user->email,
            'degree' => $newCandidate->degree,
            'job_vacancy' => $newCandidate->jobVacancy->title,
            'status' => $newCandidate->status,
            'created_at' => $newCandidate->created_at
        ];
        // dd($newCandidate);

        return Inertia::render('AdminDetailNewCandidates', [
            'candidates' => $candidate
        ]);
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
