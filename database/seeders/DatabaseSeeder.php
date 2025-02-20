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
        // Active = 1, Inactive = 0
        $jobs = [
            [
                'title' => 'Software Engineer',
                'description' => 'We are looking for a skilled Software Engineer to join our team.',
                'status' => 1,
            ],
            [
                'title' => 'Product Manager',
                'description' => 'Experienced Product Manager needed for our growing product team.',
                'status' => 1, 
            ],
        ];

        $jobIds = [];
        foreach ($jobs as $job) {
            $jobId = DB::table('job_vacancies')->insertGetId([
                'title' => $job['title'],
                'description' => $job['description'],
                'status' => $job['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $jobIds[] = $jobId;

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

            // Add salary ranges for each job
            $numberOfRanges = rand(2, 3);
            for ($i = 0; $i < $numberOfRanges; $i++) {
                $minSalary = rand(5000000, 12000000);
                $maxSalary = $minSalary + rand(2000000, 5000000);
                
                DB::table('salary_ranges')->insert([
                    'job_vacancy_id' => $jobId,
                    'min_salary' => $minSalary,
                    'max_salary' => $maxSalary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $faker = \Faker\Factory::create('id_ID'); // Gunakan lokalisasi Indonesia

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
                'job_vacancy_id' => $jobIds[array_rand($jobIds)],
                'name' => $applicant['name'],
                'degree' => $applicant['degree'],
                'ipk' => $faker->randomFloat(2, 2.5, 4.0),
                'nomor_hp' => $faker->phoneNumber,
                'province' => $faker->state,
                'city' => $faker->city,
                'expected_salary' => $applicant['expected_salary'],
                'applicant_email' => $applicant['email'],
                'linkedin' => 'https://linkedin.com/in/' . $faker->userName,
                'cv_path' => 'cv_files/sample.pdf',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Generate random applicants
        for ($i = 1; $i <= 30; $i++) {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign applicant role
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create applicant record
            DB::table('applicants')->insert([
                'users_id' => $userId,
                'job_vacancy_id' => $jobIds[array_rand($jobIds)],
                'name' => $faker->name,
                'degree' => $faker->randomElement([
                    'Bachelor of Science',
                    'Master of Science',
                    'Bachelor of Arts',
                    'Master of Business Administration',
                    'Bachelor of Engineering'
                ]),
                'ipk' => $faker->randomFloat(2, 2.5, 4.0),
                'nomor_hp' => $faker->phoneNumber,
                'province' => $faker->state,
                'city' => $faker->city,
                'expected_salary' => rand(5000000, 15000000),
                'applicant_email' => $faker->email,
                'linkedin' => 'https://linkedin.com/in/' . $faker->userName,
                'cv_path' => 'cv_files/sample.pdf',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed schedules
        $this->seedSchedules($faker);
    }

    private function seedSchedules($faker)
    {
        // Get all user IDs
        $userIds = DB::table('users')->pluck('id')->toArray();
        
        // Days of the week in Indonesian
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Sample descriptions for schedules
        $descriptions = [
            'Meeting dengan tim',
            'Interview calon karyawan',
            'Review project',
            'Training karyawan baru',
            'Diskusi dengan client',
            'Presentasi project',
            'Brainstorming',
            'Evaluasi kinerja',
            'Webinar',
            'Workshop'
        ];

        // Generate 50 random schedules
        for ($i = 0; $i < 50; $i++) {
            // Select random user
            $userId = $userIds[array_rand($userIds)];
            
            // Generate random date within next 30 days
            $date = now()->addDays(rand(1, 30))->format('Y-m-d');
            
            // Generate random start time between 8:00 and 16:00
            $startHour = rand(8, 16);
            $startMinute = rand(0, 11) * 5; // 5-minute intervals
            $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
            
            // Calculate end time (1-3 hours after start time)
            $duration = rand(1, 3);
            $endTime = sprintf('%02d:%02d:00', $startHour + $duration, $startMinute);
            
            // Build full datetime strings
            $startDateTime = $date . ' ' . $startTime;
            $endDateTime = $date . ' ' . $endTime;
            
            // Get day of week in Indonesian
            $dayOfWeek = $days[date('N', strtotime($date)) - 1];
            
            // Insert the schedule
            DB::table('schedules')->insert([
                'users_id' => $userId,
                'hari' => $dayOfWeek,
                'jam_mulai' => $startDateTime,
                'jam_selesai' => $endDateTime,
                'tanggal' => $date,
                'deskripsi' => $descriptions[array_rand($descriptions)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}