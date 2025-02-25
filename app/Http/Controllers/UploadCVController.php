<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadCVController extends Controller
{
    function uploadcv_page($id){
        $jobVacancy = JobVacancy::with(['salaryRanges', 'questions', 'applicants'])
        ->where('id', $id)
        ->first();

        $job = [
            'id' => $jobVacancy->id,
            'title' => $jobVacancy->title,
            'description' => $jobVacancy->description,
            'salary_ranges' => $jobVacancy->salaryRanges,
            'questions' => $jobVacancy->questions,
        ];

        return Inertia::render('UploadCV', [
            'job' => $job
        ]);
    }

    public function upload(Request $request, $id)
    {
        $cvPath = null;
        try {
            // Validate request
            $validated = $request->validate([
                'cv_file' => 'required|mimes:pdf,doc,docx|max:5120',
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email',
                'lokasi' => 'required|string|max:255',
                'IPK' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        // Replace comma with dot for validation
                        $ipk = str_replace(',', '.', $value);
                        
                        // Check if it's a valid decimal number
                        if (!is_numeric($ipk)) {
                            $fail('Format IPK tidak valid.');
                            return;
                        }
                        
                        // Convert to float for comparison
                        $ipkFloat = (float) $ipk;
                        
                        // Check range
                        if ($ipkFloat < 0 || $ipkFloat > 4) {
                            $fail('IPK harus bernilai antara 0 sampai 4.');
                            return;
                        }
                        
                        // Check decimal places (max 1 decimal place)
                        if (strlen(substr(strrchr($ipk, "."), 1)) > 1) {
                            $fail('IPK hanya boleh memiliki 1 angka di belakang koma.');
                        }
                    },
                ],
                'degree' => 'required|string|max:255',
                'nomer_telepon' => 'required|string|max:20',
                'linkedin_url' => 'nullable|url',
                'salary_range_id' => 'required|exists:salary_ranges,id',
            ]);

            $cvPath = null;

            return DB::transaction(function () use ($request, $id, &$cvPath) {
                try {
                    // Store the file first
                    $cvPath = $request->file('cv_file')->store('cv_files', 'public');

                    // Split location
                    $locationParts = explode(', ', $request->lokasi);
                    if (count($locationParts) !== 2) {
                        throw new \Exception('Invalid location format');
                    }

                    // Insert applicant data
                    $applicant = DB::table('applicants')->insertGetId([
                        'users_id' => Auth::user()->id,
                        'job_vacancy_id' => $id,
                        'name' => $request->nama_lengkap,
                        'degree' => $request->degree,
                        'province' => $locationParts[0],
                        'city' => $locationParts[1],
                        'ipk' => str_replace(',', '.', $request->IPK),
                        'nomor_hp' => $request->nomer_telepon,
                        'salary_range_id' => $request->salary_range_id,
                        'applicant_email' => $request->email,
                        'linkedin' => $request->linkedin_url,
                        'cv_path' => $cvPath,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Insert answers
                    $answersData = collect($request->answers)->map(function ($answer, $questionId) {
                        return [
                            'question_id' => $questionId,
                            'user_id' => Auth::user()->id,
                            'answer_text' => $answer,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    })->all();

                    if (!empty($answersData)) {
                        DB::table('answers')->insert($answersData);
                    }

                    return redirect()->back()->with('success', 'Application submitted successfully');

                } catch (\Exception $e) {
                    // If any error occurs during database operations, delete the uploaded file
                    if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                        Storage::disk('public')->delete($cvPath);
                    }
                    throw $e;
                }
            });

        } catch (\Exception $e) {
            // If file was uploaded but transaction failed, ensure it's cleaned up
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to submit application. ' . $e->getMessage()])
                ->withInput();
        }
    }
}
