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

    public function jobList()
    {
        $jobs = JobVacancy::with(['applicants'])->get();

        return Inertia::render('AdminJobList', [
            'jobs' => $jobs
        ]);
    }

    public function jobDetail($id)
    {
        $jobs = JobVacancy::with(['salaryRanges', 'questions', 'applicants'])
        ->where('id', $id)
        ->first();

        return Inertia::render('AdminJobDetail', [
            'jobs' => $jobs
        ]);
    }

    public function jobEdit($id)
    {
        $jobs = JobVacancy::with(['salaryRanges', 'questions', 'applicants'])
        ->where('id', $id)
        ->first();

        return Inertia::render('AdminJobEdit', [
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
                'status' => 1
            ]);
        
            // Buat questions
            foreach ($validated['additional_questions'] as $questionText) {
                $job->questions()->create([
                    'question_text' => $questionText
                ]);
            }
        
            // Sort salary ranges berdasarkan min_salary
            $sortedSalaryRanges = collect($validated['salary_ranges'])
                ->sortBy('min_salary')
                ->values()
                ->all();
        
            // Buat salary ranges yang sudah terurut
            foreach ($sortedSalaryRanges as $range) {
                $job->salaryRanges()->create([
                    'min_salary' => $range['min_salary'],
                    'max_salary' => $range['max_salary']
                ]);
            }
        });

        return redirect()->back();
    }

    public function update(Request $request, JobVacancy $job)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($job) {
                    $exists = JobVacancy::whereRaw('LOWER(title) = LOWER(?)', [$value])
                        ->where('id', '!=', $job->id) // Exclude current job
                        ->exists();
                    if ($exists) {
                        $fail('Sudah ada Job Vacancy dengan judul yang sama.');
                    }
                }
            ],
            'description' => 'required|string',
            'status' => 'required|boolean',
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

        DB::transaction(function () use ($validated, $job) {
            // Update job vacancy
            $job->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'] ? 1 : 0,
            ]);
        
            // Delete existing questions and create new ones
            $job->questions()->delete();
            foreach ($validated['additional_questions'] as $questionText) {
                $job->questions()->create([
                    'question_text' => $questionText
                ]);
            }
        
            // Delete existing salary ranges
            $job->salaryRanges()->delete();
            
            // Sort salary ranges berdasarkan min_salary
            $sortedSalaryRanges = collect($validated['salary_ranges'])
                ->sortBy('min_salary')
                ->values()
                ->all();
        
            // Create new sorted salary ranges
            foreach ($sortedSalaryRanges as $range) {
                $job->salaryRanges()->create([
                    'min_salary' => $range['min_salary'],
                    'max_salary' => $range['max_salary']
                ]);
            }
        });

        return redirect()->back();
    }

    public function destroy(JobVacancy $job)
    {
        try {
            $job->delete();
            
            return redirect()->back()->with('success', 'Job vacancy berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus job vacancy');
        }
    }
}