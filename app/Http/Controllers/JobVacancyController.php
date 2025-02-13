<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class JobVacancyController extends Controller
{
    public function show()
    {
        $jobs = JobVacancy::with(['salaryRanges', 'questions', 'applicants'])->get();

        return Inertia::render('PosisiCV', [
            'jobs' => $jobs
        ]);
    }
    
    public function index()
    {
        return Inertia::render('Jobs/Index', [
            'jobs' => JobVacancy::with('salaryRanges', 'questions')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Jobs/NewJobForm');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $exists = JobVacancy::whereRaw('LOWER(title) = LOWER(?)', [$value])->exists();
                        if ($exists) {
                            $fail('Sudah ada Job Vacancy dengan judul yang sama.');
                        }
                    }
                ],
            'description' => 'required|string',
            'additional_questions' => 'required|array|min:1',
            'additional_questions.*' => 'required|string|max:255',
            'salary_ranges' => 'required|array|min:1',
            'salary_ranges.*.min_salary' => 'required|numeric|min:0',
            'salary_ranges.*.max_salary' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $minSalary = $request->input("salary_ranges.{$index}.min_salary");
                    if ($value <= $minSalary) {
                        $fail('Maximum salary must be greater than minimum salary.');
                    }
                },
            ],
        ]);

        DB::transaction(function () use ($validated) {
            // Buat job vacancy
            $job = JobVacancy::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // Buat questions
            foreach ($validated['additional_questions'] as $questionText) {
                $job->questions()->create([
                    'question_text' => $questionText
                ]);
            }

            // Buat salary ranges
            foreach ($validated['salary_ranges'] as $range) {
                $job->salaryRanges()->create([
                    'min_salary' => $range['min_salary'],
                    'max_salary' => $range['max_salary']
                ]);
            }
        });

        return redirect()->back();
    }
}