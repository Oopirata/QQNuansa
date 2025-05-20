<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\JobVacancy;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class CandidateController extends Controller
{
    public function newCandidates(Request $request)
    {
        $search = $request->input('search');
        $selectedJobTitles = $request->input('jobTitles', []);
        $selectedDegrees = $request->input('degrees', []);
        $tagsInput = $request->input('tags', '[]');
        $tags = is_string($tagsInput) ? json_decode($tagsInput, true) : $tagsInput;

        // Get IPK range
        $ipkMin = $request->input('ipkMin');
        $ipkMax = $request->input('ipkMax');

        // Get Salary range
        $salaryMin = $request->input('salaryMin');
        $salaryMax = $request->input('salaryMax');

        $query = Applicant::with(['user', 'jobVacancy', 'salaryRange'])
            ->where('status', 0);

        // Validasi tags
        $validTags = [];
        foreach ($tags as $tag) {
            if (isset($tag['type']) && isset($tag['value'])) {
                $validTags[] = $tag;
            }
        }
        $tags = $validTags;

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
        }

        // Filter berdasarkan job titles jika ada yang dipilih
        if (!empty($selectedJobTitles)) {
            $query->whereHas('jobVacancy', function ($q) use ($selectedJobTitles) {
                $q->whereIn('id', $selectedJobTitles);
            });
        }

        // Filter berdasarkan degree dengan pendekatan yang lebih fleksibel
        if (!empty($selectedDegrees)) {
            // Check if we have legacy degree IDs or actual degree strings
            $legacyIds = array_filter($selectedDegrees, function ($id) {
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

            // PERBAIKAN: Ambil langsung nilai dari tag degree
            foreach ($tags as $tag) {
                if (isset($tag['type']) && isset($tag['value']) && $tag['type'] === 'Degree') {
                    $degreeValues[] = $tag['value'];
                }
            }

            // Untuk ID yang tidak ada dalam tags (misalnya dari availableDegrees)
            $allDegrees = Applicant::select('degree')
                ->distinct()
                ->orderBy('degree')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'id' => 'degree-' . ($index + 1),  // Simple ID generation
                        'name' => $item->degree,
                        'color' => 'blue'
                    ];
                });

            foreach ($selectedDegrees as $degreeId) {
                if (strpos($degreeId, 'degree-') === 0 && !strpos($degreeId, 'degree-new-') === 0) {
                    $degree = $allDegrees->firstWhere('id', $degreeId);
                    if ($degree && !in_array($degree['name'], $degreeValues)) {
                        $degreeValues[] = $degree['name'];
                    }
                }
            }

            $degreeValues = array_unique(array_filter($degreeValues));

            if (!empty($degreeValues)) {
                $query->where(function ($q) use ($degreeValues) {
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
            $query->whereHas('salaryRange', function ($q) use ($salaryMin, $salaryMax) {
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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('jobVacancy', function ($query) use ($search) {
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
            ->map(function ($item, $index) {
                return [
                    'id' => 'degree-' . ($index + 1),  // Simple ID generation
                    'name' => $item->degree,
                    'color' => 'blue'
                ];
            });

        $jobTitles = JobVacancy::select('id', 'title')
            ->where('status', 1)
            ->get()
            ->map(function ($job) {
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
        // Ambil data kandidat dan relasi user, job, dan jawaban + pertanyaan
        $newCandidate = Applicant::with(['user', 'jobVacancy', 'user.answers.question'])
            ->findOrFail($id);
        // dd($newCandidate)->all();

        // Transformasi data kandidat
        $candidate = [
            'id' => $newCandidate->id,
            'user_id' => $newCandidate->user->id,
            'user' => $newCandidate->name,
            'email' => $newCandidate->user->email,
            'city' => $newCandidate->city,
            'phone' => $newCandidate->nomor_hp,
            'degree' => $newCandidate->degree,
            'job_vacancy' => $newCandidate->jobVacancy->title,
            'cv_path' => $newCandidate->cv_path,
            'linkedin' => $newCandidate->linkedin,
            'status' => $newCandidate->status,
            'created_at' => $newCandidate->created_at,
        ];

        // Ambil screening questions dan jawaban kandidat untuk job yang dilamar
        $screenings = $newCandidate->user->answers
            ->filter(function ($answer) use ($newCandidate) {
                return $answer->question->job_vacancy_id === $newCandidate->job_vacancy_id;
            })
            ->map(function ($answer) {
                return [
                    'question' => $answer->question->question_text,
                    'answer' => $answer->answer_text,
                ];
            })
            ->values(); // reset key menjadi array biasa

        // dd($screenings)->all();

        // Kirim ke Vue
        return Inertia::render('AdminDetailNewCandidates', [
            'candidates' => $candidate,
            'screenings' => $screenings
        ]);
    }


    public function moveToScreened($user_id)
    {
        // dd($user_id);
        $candidate = Applicant::where('users_id', $user_id)->first();
        // dd($candidate);
        $candidate->status = 1;
        $candidate->save();

        return redirect()->route('adminScreenedCandidates');
    }

    public function screenedCandidates(Request $request)
    {
        $search = $request->input('search');
        $selectedJobTitles = $request->input('jobTitles', []);
        $selectedDegrees = $request->input('degrees', []);
        $tagsInput = $request->input('tags', '[]');
        $tags = is_string($tagsInput) ? json_decode($tagsInput, true) : $tagsInput;

        // Get IPK range
        $ipkMin = $request->input('ipkMin');
        $ipkMax = $request->input('ipkMax');

        // Get Salary range
        $salaryMin = $request->input('salaryMin');
        $salaryMax = $request->input('salaryMax');

        $query = Applicant::with(['user', 'jobVacancy', 'salaryRange'])
            ->where('status', 1);

        // Validasi tags
        $validTags = [];
        foreach ($tags as $tag) {
            if (isset($tag['type']) && isset($tag['value'])) {
                $validTags[] = $tag;
            }
        }
        $tags = $validTags;

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
        }

        // Filter berdasarkan job titles jika ada yang dipilih
        if (!empty($selectedJobTitles)) {
            $query->whereHas('jobVacancy', function ($q) use ($selectedJobTitles) {
                $q->whereIn('id', $selectedJobTitles);
            });
        }

        // Filter berdasarkan degree dengan pendekatan yang lebih fleksibel
        if (!empty($selectedDegrees)) {
            // Check if we have legacy degree IDs or actual degree strings
            $legacyIds = array_filter($selectedDegrees, function ($id) {
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

            // PERBAIKAN: Ambil langsung nilai dari tag degree
            foreach ($tags as $tag) {
                if (isset($tag['type']) && isset($tag['value']) && $tag['type'] === 'Degree') {
                    $degreeValues[] = $tag['value'];
                }
            }

            // Untuk ID yang tidak ada dalam tags (misalnya dari availableDegrees)
            $allDegrees = Applicant::select('degree')
                ->distinct()
                ->orderBy('degree')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'id' => 'degree-' . ($index + 1),  // Simple ID generation
                        'name' => $item->degree,
                        'color' => 'blue'
                    ];
                });

            foreach ($selectedDegrees as $degreeId) {
                if (strpos($degreeId, 'degree-') === 0 && !strpos($degreeId, 'degree-new-') === 0) {
                    $degree = $allDegrees->firstWhere('id', $degreeId);
                    if ($degree && !in_array($degree['name'], $degreeValues)) {
                        $degreeValues[] = $degree['name'];
                    }
                }
            }

            $degreeValues = array_unique(array_filter($degreeValues));

            if (!empty($degreeValues)) {
                $query->where(function ($q) use ($degreeValues) {
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
            $query->whereHas('salaryRange', function ($q) use ($salaryMin, $salaryMax) {
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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('jobVacancy', function ($query) use ($search) {
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
            ->map(function ($item, $index) {
                return [
                    'id' => 'degree-' . ($index + 1),  // Simple ID generation
                    'name' => $item->degree,
                    'color' => 'blue'
                ];
            });

        $jobTitles = JobVacancy::select('id', 'title')
            ->where('status', 1)
            ->get()
            ->map(function ($job) {
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

        return Inertia::render('AdminScreenedCandidates', [
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

    public function screenCandidateDetail($user_id)
    {
        $screenedCandidate = Applicant::with(['user.answers.question', 'jobVacancy'])
            ->findOrFail($user_id);

        $candidate = [
            'id' => $screenedCandidate->id,
            'user_id' => $screenedCandidate->user->id,
            'user' => $screenedCandidate->name,
            'email' => $screenedCandidate->user->email,
            'city' => $screenedCandidate->city,
            'phone' => $screenedCandidate->nomor_hp,
            'degree' => $screenedCandidate->degree,
            'job_vacancy' => $screenedCandidate->jobVacancy->title,
            'cv_path' => $screenedCandidate->cv_path,
            'linkedin' => $screenedCandidate->linkedin,
            'status' => $screenedCandidate->status,
            'created_at' => $screenedCandidate->created_at
        ];

        $screenings = $screenedCandidate->user->answers
            ->filter(fn($answer) => $answer->question->job_vacancy_id === $screenedCandidate->job_vacancy_id)
            ->map(fn($answer) => [
                'question' => $answer->question->question_text,
                'answer' => $answer->answer_text,
            ])
            ->values();

        return Inertia::render('AdminDetailScreenedCandidates', [
            'candidates' => $candidate,
            'screenings' => $screenings
        ]);
    }


    public function moveToInterview($user_id)
    {
        $candidate = Applicant::where('users_id', $user_id)->first();
        $candidate->status = 2;
        $candidate->save();

        return redirect()->route('adminInterviewCandidates');
    }

    public function interviewCandidates(Request $request)
    {
        $search = $request->input('search');
        $selectedJobTitles = $request->input('jobTitles', []);
        $selectedDegrees = $request->input('degrees', []);
        $tagsInput = $request->input('tags', '[]');
        $tags = is_string($tagsInput) ? json_decode($tagsInput, true) : $tagsInput;

        // Get IPK range
        $ipkMin = $request->input('ipkMin');
        $ipkMax = $request->input('ipkMax');

        // Get Salary range
        $salaryMin = $request->input('salaryMin');
        $salaryMax = $request->input('salaryMax');

        $query = Applicant::with(['user', 'jobVacancy', 'salaryRange'])
            ->where('status', 2);

        // Validasi tags
        $validTags = [];
        foreach ($tags as $tag) {
            if (isset($tag['type']) && isset($tag['value'])) {
                $validTags[] = $tag;
            }
        }
        $tags = $validTags;

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
        }

        // Filter berdasarkan job titles jika ada yang dipilih
        if (!empty($selectedJobTitles)) {
            $query->whereHas('jobVacancy', function ($q) use ($selectedJobTitles) {
                $q->whereIn('id', $selectedJobTitles);
            });
        }

        // Filter berdasarkan degree dengan pendekatan yang lebih fleksibel
        if (!empty($selectedDegrees)) {
            // Check if we have legacy degree IDs or actual degree strings
            $legacyIds = array_filter($selectedDegrees, function ($id) {
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

            // PERBAIKAN: Ambil langsung nilai dari tag degree
            foreach ($tags as $tag) {
                if (isset($tag['type']) && isset($tag['value']) && $tag['type'] === 'Degree') {
                    $degreeValues[] = $tag['value'];
                }
            }

            // Untuk ID yang tidak ada dalam tags (misalnya dari availableDegrees)
            $allDegrees = Applicant::select('degree')
                ->distinct()
                ->orderBy('degree')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'id' => 'degree-' . ($index + 1),  // Simple ID generation
                        'name' => $item->degree,
                        'color' => 'blue'
                    ];
                });

            foreach ($selectedDegrees as $degreeId) {
                if (strpos($degreeId, 'degree-') === 0 && !strpos($degreeId, 'degree-new-') === 0) {
                    $degree = $allDegrees->firstWhere('id', $degreeId);
                    if ($degree && !in_array($degree['name'], $degreeValues)) {
                        $degreeValues[] = $degree['name'];
                    }
                }
            }

            $degreeValues = array_unique(array_filter($degreeValues));

            if (!empty($degreeValues)) {
                $query->where(function ($q) use ($degreeValues) {
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
            $query->whereHas('salaryRange', function ($q) use ($salaryMin, $salaryMax) {
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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('jobVacancy', function ($query) use ($search) {
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
            ->map(function ($item, $index) {
                return [
                    'id' => 'degree-' . ($index + 1),  // Simple ID generation
                    'name' => $item->degree,
                    'color' => 'blue'
                ];
            });

        $jobTitles = JobVacancy::select('id', 'title')
            ->where('status', 1)
            ->get()
            ->map(function ($job) {
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

        return Inertia::render('AdminInterviewCandidates', [
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

    public function interviewCandidatesDetail($id)
    {
        $interviewCandidate = Applicant::with(['user.answers.question', 'jobVacancy'])
            ->findOrFail($id);

        $candidate = [
            'id' => $interviewCandidate->id,
            'user_id' => $interviewCandidate->user->id,
            'user' => $interviewCandidate->name,
            'email' => $interviewCandidate->user->email,
            'city' => $interviewCandidate->city,
            'phone' => $interviewCandidate->nomor_hp,
            'degree' => $interviewCandidate->degree,
            'job_vacancy' => $interviewCandidate->jobVacancy->title,
            'cv_path' => $interviewCandidate->cv_path,
            'linkedin' => $interviewCandidate->linkedin,
            'status' => $interviewCandidate->status,
            'created_at' => $interviewCandidate->created_at,
        ];

        $screenings = $interviewCandidate->user->answers
            ->filter(fn($answer) => $answer->question->job_vacancy_id === $interviewCandidate->job_vacancy_id)
            ->map(fn($answer) => [
                'question' => $answer->question->question_text,
                'answer' => $answer->answer_text,
            ])
            ->values();

        return Inertia::render('AdminDetailInterviewCandidates', [
            'candidates' => $candidate,
            'screenings' => $screenings
        ]);
    }


    public function moveToHired($user_id)
    {
        // dd($user_id);
        $candidate = Applicant::where('users_id', $user_id)->first();
        $candidate->status = 3;
        $candidate->save();

        return redirect()->route('adminInterviewCandidates');
    }

    public function rejectedCandidates(Request $request)
    {
        $search = $request->input('search');
        $selectedJobTitles = $request->input('jobTitles', []);
        $selectedDegrees = $request->input('degrees', []);
        $tagsInput = $request->input('tags', '[]');
        $tags = is_string($tagsInput) ? json_decode($tagsInput, true) : $tagsInput;

        // Get IPK range
        $ipkMin = $request->input('ipkMin');
        $ipkMax = $request->input('ipkMax');

        // Get Salary range
        $salaryMin = $request->input('salaryMin');
        $salaryMax = $request->input('salaryMax');

        $query = Applicant::with(['user', 'jobVacancy', 'salaryRange'])
            ->where('status', 4);

        // Validasi tags
        $validTags = [];
        foreach ($tags as $tag) {
            if (isset($tag['type']) && isset($tag['value'])) {
                $validTags[] = $tag;
            }
        }
        $tags = $validTags;

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
        }

        // Filter berdasarkan job titles jika ada yang dipilih
        if (!empty($selectedJobTitles)) {
            $query->whereHas('jobVacancy', function ($q) use ($selectedJobTitles) {
                $q->whereIn('id', $selectedJobTitles);
            });
        }

        // Filter berdasarkan degree dengan pendekatan yang lebih fleksibel
        if (!empty($selectedDegrees)) {
            // Check if we have legacy degree IDs or actual degree strings
            $legacyIds = array_filter($selectedDegrees, function ($id) {
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

            // PERBAIKAN: Ambil langsung nilai dari tag degree
            foreach ($tags as $tag) {
                if (isset($tag['type']) && isset($tag['value']) && $tag['type'] === 'Degree') {
                    $degreeValues[] = $tag['value'];
                }
            }

            // Untuk ID yang tidak ada dalam tags (misalnya dari availableDegrees)
            $allDegrees = Applicant::select('degree')
                ->distinct()
                ->orderBy('degree')
                ->get()
                ->map(function ($item, $index) {
                    return [
                        'id' => 'degree-' . ($index + 1),  // Simple ID generation
                        'name' => $item->degree,
                        'color' => 'blue'
                    ];
                });

            foreach ($selectedDegrees as $degreeId) {
                if (strpos($degreeId, 'degree-') === 0 && !strpos($degreeId, 'degree-new-') === 0) {
                    $degree = $allDegrees->firstWhere('id', $degreeId);
                    if ($degree && !in_array($degree['name'], $degreeValues)) {
                        $degreeValues[] = $degree['name'];
                    }
                }
            }

            $degreeValues = array_unique(array_filter($degreeValues));

            if (!empty($degreeValues)) {
                $query->where(function ($q) use ($degreeValues) {
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
            $query->whereHas('salaryRange', function ($q) use ($salaryMin, $salaryMax) {
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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('jobVacancy', function ($query) use ($search) {
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
            ->map(function ($item, $index) {
                return [
                    'id' => 'degree-' . ($index + 1),  // Simple ID generation
                    'name' => $item->degree,
                    'color' => 'blue'
                ];
            });

        $jobTitles = JobVacancy::select('id', 'title')
            ->where('status', 1)
            ->get()
            ->map(function ($job) {
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

        return Inertia::render('AdminRejectedCandidates', [
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

    public function rejectedCandidatesDetail($id)
    {
        $rejectedCandidate = Applicant::with(['user.answers.question', 'jobVacancy'])
            ->findOrFail($id);

        $candidate = [
            'id' => $rejectedCandidate->id,
            'user_id' => $rejectedCandidate->user->id,
            'user' => $rejectedCandidate->name,
            'email' => $rejectedCandidate->user->email,
            'city' => $rejectedCandidate->city,
            'phone' => $rejectedCandidate->nomor_hp,
            'degree' => $rejectedCandidate->degree,
            'job_vacancy' => $rejectedCandidate->jobVacancy->title,
            'cv_path' => $rejectedCandidate->cv_path,
            'linkedin' => $rejectedCandidate->linkedin,
            'status' => $rejectedCandidate->status,
            'created_at' => $rejectedCandidate->created_at,
        ];

        $screenings = $rejectedCandidate->user->answers
            ->filter(fn($answer) => $answer->question->job_vacancy_id === $rejectedCandidate->job_vacancy_id)
            ->map(fn($answer) => [
                'question' => $answer->question->question_text,
                'answer' => $answer->answer_text,
            ])
            ->values();

        return Inertia::render('AdminDetailRejectedCandidates', [
            'candidates' => $candidate,
            'screenings' => $screenings
        ]);
    }


    public function disqualify($user_id)
    {
        $candidate = Applicant::where('users_id', $user_id)->first();
        $candidate->status = 4; // Disqualified
        $candidate->save();

        return redirect()->route('adminRejectedCandidates');
    }

    public function dashboard()
    {
        // Fetch upcoming interviews (next 6)
        $upcomingInterviews = Schedule::whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->take(6)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'judul' => $schedule->judul,
                    'tanggal' => $schedule->tanggal,
                    'time' => date('H:i', strtotime($schedule->jam_mulai)) . " - " .
                        date('H:i', strtotime($schedule->jam_selesai)),
                    'deskripsi' => $schedule->deskripsi,
                ];
            });

        // Hitung kandidat baru dalam 7 hari terakhir
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $newCandidatesLast7Days = Applicant::where('status', 0)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->count();

        // Hitung total karyawan (sesuaikan dengan logika aplikasi Anda)
        $totalEmployees = Applicant::where('status', 3) // Asumsi status 3 adalah hired/employee
            ->count();

        return Inertia::render('AdminDashboard', [
            'upcomingInterviews' => $upcomingInterviews,
            'analyticsData' => [
                'newCandidatesLast7Days' => $newCandidatesLast7Days,
                'totalEmployees' => $totalEmployees
            ]
        ]);
    }
}
