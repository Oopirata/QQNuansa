<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed roles
        $roles = ['admin', 'user', 'applicant'];
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role_name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed admin user
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role
        DB::table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => 1, // admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed sample jobs
        $jobs = [
            [
                'title' => 'Software Engineer',
                'description' => 'We are looking for a skilled Software Engineer to join our team.',
            ],
            [
                'title' => 'Product Manager',
                'description' => 'Experienced Product Manager needed for our growing product team.',
            ],
        ];

        foreach ($jobs as $job) {
            $jobId = DB::table('job_vacancies')->insertGetId([
                'title' => $job['title'],
                'description' => $job['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add sample questions for each job
            $questions = [
                'What is your experience with our required technologies?',
                'Why do you want to work with us?',
                'What are your salary expectations?'
            ];

            foreach ($questions as $question) {
                DB::table('questions')->insert([
                    'job_vacancy_id' => $jobId,
                    'question_text' => $question,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
