<?php

namespace Database\Seeders;

use App\Models\User;
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

        // Seed admin users
        $adminUsers = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($adminUsers as $admin) {
            $adminId = DB::table('users')->insertGetId([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => $admin['password'],
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
        }

        // Seed regular users
        $regularUsers = [
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'User Three',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($regularUsers as $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign user role
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 2, // user role
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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

        // Seed applicant users and their details
        $applicantUsers = [
            [
                'name' => 'Applicant One',
                'email' => 'applicant1@example.com',
                'password' => Hash::make('password'),
                'degree' => 'Bachelor of Computer Science',
                'expected_salary' => '8000000',
            ],
            [
                'name' => 'Applicant Two',
                'email' => 'applicant2@example.com',
                'password' => Hash::make('password'),
                'degree' => 'Master of Business Administration',
                'expected_salary' => '12000000',
            ],
            [
                'name' => 'Applicant Three',
                'email' => 'applicant3@example.com',
                'password' => Hash::make('password'),
                'degree' => 'Bachelor of Engineering',
                'expected_salary' => '10000000',
            ],
        ];

        foreach ($applicantUsers as $applicant) {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'name' => $applicant['name'],
                'email' => $applicant['email'],
                'password' => $applicant['password'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign applicant role
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 3, // applicant role
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create applicant record
            DB::table('applicants')->insert([
                'users_id' => $userId,
                'job_vacancy_id' => rand(1, 2),
                'degree' => $applicant['degree'],
                'expected_salary' => $applicant['expected_salary'],
                'status' => 0, // new status
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}