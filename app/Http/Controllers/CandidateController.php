<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\JobVacancy;

class CandidateController extends Controller
{
    public function newCandidates(Request $request)
    {
        $search = $request->input('search');
        $selectedJobTitles = $request->input('jobTitles', []);
        $selectedDegrees = $request->input('degrees', []);
        $tags = json_decode($request->input('tags', '[]'), true);
        
        // Get IPK range
        $ipkMin = $request->input('ipkMin');
        $ipkMax = $request->input('ipkMax');
        
        // Get Salary range
        $salaryMin = $request->input('salaryMin');
        $salaryMax = $request->input('salaryMax');
    
        $query = Applicant::with(['user', 'jobVacancy', 'salaryRange'])
            ->where('status', 0);
    
        // Process tags first (they might contain additional filters)
        foreach ($tags as $tag) {
            if ($tag['type'] === 'Degree') {
                // Add the degree to the queries - this would be done with whereIn later
                if (!in_array($tag['value'], $selectedDegrees)) {
                    $selectedDegrees[] = $tag['value'];
                }
            } elseif ($tag['type'] === 'Job Title' && is_numeric($tag['value'])) {
                // Add the job title ID to the queries
                if (!in_array($tag['value'], $selectedJobTitles)) {
                    $selectedJobTitles[] = $tag['value'];
                }
            }
            // Search tags will be handled by the regular search parameter
        }
    
        // Filter berdasarkan job titles jika ada yang dipilih
        if (!empty($selectedJobTitles)) {
            $query->whereHas('jobVacancy', function($q) use ($selectedJobTitles) {
                $q->whereIn('id', $selectedJobTitles);
            });
        }
    
        // Filter berdasarkan degree dengan pendekatan yang lebih fleksibel
        if (!empty($selectedDegrees)) {
            // Check if we have legacy degree IDs or actual degree strings
            $legacyIds = array_filter($selectedDegrees, function($id) {
                return is_numeric($id) && in_array($id, [1, 2]); // Our legacy IDs are 1 (Bachelor) and 2 (Master)
            });
            
            $degreeValues = [];
            
            // Handle legacy IDs
            if (!empty($legacyIds)) {
                $degreeMappings = [
                    1 => 'Bachelor',
                    2 => 'Master'
                ];
                
                foreach ($legacyIds as $id) {
                    if (isset($degreeMappings[$id])) {
                        $degreeValues[] = $degreeMappings[$id];
                    }
                }
            }
            
            // Handle direct degree values (from tags/autocomplete)
            $directValues = array_filter($selectedDegrees, function($value) {
                return !is_numeric($value) || (is_numeric($value) && !in_array($value, [1, 2]));
            });
            
            // For direct values, we need to decode the IDs if they're in md5 format
            // In a real implementation, you would store and retrieve these from the database
            // Here we'll assume any non-numeric ID is a direct degree string
            foreach ($directValues as $value) {
                foreach ($tags as $tag) {
                    if ($tag['type'] === 'Degree') {
                        $degreeValues[] = $tag['value'];
                    }
                }
            }
            
            $degreeValues = array_unique(array_filter($degreeValues));
            
            if (!empty($degreeValues)) {
                $query->where(function($q) use ($degreeValues) {
                    foreach ($degreeValues as $degree) {
                        $q->orWhere('degree', 'like', "%{$degree}%");
                    }
                });
            }
        }
    
        // Filter berdasarkan IPK range
        if ($ipkMin !== null) {
            $query->where('ipk', '>=', $ipkMin);
        }
        
        if ($ipkMax !== null) {
            $query->where('ipk', '<=', $ipkMax);
        }
    
        // Filter berdasarkan salary range
        if ($salaryMin !== null || $salaryMax !== null) {
            $query->whereHas('salaryRange', function($q) use ($salaryMin, $salaryMax) {
                if ($salaryMin !== null) {
                    $q->where('min_salary', '>=', $salaryMin);
                }
                
                if ($salaryMax !== null) {
                    $q->where('max_salary', '<=', $salaryMax);
                }
            });
        }
    
        // Menambahkan kondisi pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('jobVacancy', function($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->orWhere('degree', 'like', "%{$search}%");
            });
        }
    
        $newCandidates = $query->paginate(10);
        
        // Get all unique degrees from the database for autocomplete
        $allDegrees = Applicant::select('degree')
            ->distinct()
            ->orderBy('degree')
            ->get()
            ->map(function($item, $index) {
                return [
                    'id' => 'degree-' . ($index + 1),  // Simple ID generation
                    'name' => $item->degree,
                    'color' => 'blue'
                ];
            });
    
        $jobTitles = JobVacancy::select('id', 'title')
            ->where('status', 1)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'name' => $job->title,
                    'color' => 'blue'
                ];
            });
    
        // Transform the data manually
        $transformedCandidates = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($newCandidates->items())->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'user' => $candidate->name,
                    'degree' => $candidate->degree,
                    'job_vacancy' => $candidate->jobVacancy->title,
                    'ipk' => $candidate->ipk,
                    'salary_range' => $candidate->salaryRange ? [
                        'min' => $candidate->salaryRange->min_salary,
                        'max' => $candidate->salaryRange->max_salary
                    ] : null,
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
                'jobTitles' => $selectedJobTitles,
                'degrees' => $selectedDegrees,
                'tags' => $tags,
                'ipkRange' => [
                    'min' => $ipkMin,
                    'max' => $ipkMax
                ],
                'salaryRange' => [
                    'min' => $salaryMin,
                    'max' => $salaryMax
                ]
            ],
            'jobTitles' => $jobTitles,
            'allDegrees' => $allDegrees  // Pass all degrees for use in autocomplete
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
