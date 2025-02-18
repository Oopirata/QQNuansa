<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'cv_file' => 'required|mimes:pdf,doc,docx|max:5120',
            'nama_lengkap' => 'required|string|max:255',  // Sesuaikan dengan v-model
            'email' => 'required|email',
            'lokasi' => 'required|string|max:255',
            'IPK' => 'required|numeric|between:0,4',
            'nomer_telepon' => 'required|string|max:20',  // Sesuaikan dengan v-model
            'linkedin_url' => 'nullable|url',             // Sesuaikan dengan v-model
            'salary_range_id' => 'required|exists:salary_ranges,id',
        ]);

        // Store the file
        $path = $request->file('cv_file')->store('cv_files', 'public');

        // Insert data applicant
        DB::table('applicants')->insert([
            'users_id' => Auth::user()->id,
            'job_vacancy_id' => $id,
            'name' => $request->nama_lengkap,
            'degree' => $request->degree,
            'province' => explode(', ', $request->lokasi)[0],  // Ambil bagian province
            'city' => explode(', ', $request->lokasi)[1],
            'degree' => $request->degree,
            'ipk' => $request->IPK,
            'nomor_hp' => $request->nomer_telepon,
            'expected_salary' => $request->salary_range_id,
            'applicant_email' => $request->email,
            'linkedin' => $request->linkedin_url,
            'cv_path' => $path,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert answers menggunakan tabel yang sudah ada
        $answersData = [];
        foreach ($request->answers as $questionId => $answer) {
            $answersData[] = [
                'question_id' => $questionId,
                'user_id' => Auth::user()->id,
                'answer_text' => $answer,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert batch answers
        if (!empty($answersData)) {
            DB::table('answers')->insert($answersData);
        }
        return redirect()->back();
    }
}
