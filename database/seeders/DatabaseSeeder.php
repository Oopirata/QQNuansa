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

        // Seed sample jobs with more variety
        $jobs = [
            [
                'title' => 'Software Engineer',
                'description' => 'We are looking for a skilled Software Engineer to join our team. The ideal candidate should have experience with modern web technologies and frameworks.',
                'status' => 1,
            ],
            [
                'title' => 'Product Manager',
                'description' => 'Experienced Product Manager needed for our growing product team. You will be responsible for guiding product development and strategy.',
                'status' => 1, 
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'Creative UI/UX Designer with a portfolio of user-centered design projects. You will work closely with our development team to create seamless user experiences.',
                'status' => 1,
            ],
            [
                'title' => 'Data Scientist',
                'description' => 'Data Scientist with strong analytical skills needed. Experience with machine learning algorithms and data visualization required.',
                'status' => 1,
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'DevOps Engineer with knowledge of CI/CD pipelines, container technologies, and cloud platforms. You will help automate our infrastructure.',
                'status' => 1,
            ],
        ];

        $jobIds = [];
        $allSalaryRangeIds = [];
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
                'What are your salary expectations?',
                'Tell us about a challenging project you worked on',
                'How do you keep up with industry trends?'
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
            $jobSalaryRanges = [];
            $numberOfRanges = rand(2, 4);
            
            // Set different salary ranges based on job title
            switch ($job['title']) {
                case 'Software Engineer':
                    $baseSalary = rand(8000000, 12000000);
                    break;
                case 'Product Manager':
                    $baseSalary = rand(12000000, 18000000);
                    break;
                case 'UI/UX Designer':
                    $baseSalary = rand(8000000, 14000000);
                    break;
                case 'Data Scientist':
                    $baseSalary = rand(10000000, 15000000);
                    break;
                case 'DevOps Engineer':
                    $baseSalary = rand(10000000, 15000000);
                    break;
                default:
                    $baseSalary = rand(5000000, 10000000);
            }
            
            for ($i = 0; $i < $numberOfRanges; $i++) {
                // Generate increasingly higher salary ranges
                $minSalary = $baseSalary + ($i * 2000000);
                $maxSalary = $minSalary + rand(2000000, 5000000);
                
                $salaryRangeId = DB::table('salary_ranges')->insertGetId([
                    'job_vacancy_id' => $jobId,
                    'min_salary' => $minSalary,
                    'max_salary' => $maxSalary,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $jobSalaryRanges[] = $salaryRangeId;
                $allSalaryRangeIds[$jobId][] = $salaryRangeId;
            }
        }

        $faker = \Faker\Factory::create('id_ID'); // Gunakan lokalisasi Indonesia

        // List of universities in Indonesia
        $universities = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Airlangga',
            'Universitas Diponegoro',
            'Universitas Brawijaya',
            'Universitas Padjadjaran',
            'Universitas Hasanuddin',
            'Bina Nusantara University',
        ];
        
        // List of common degrees with variations
        $degrees = [
            'Bachelor' => [
                'Bachelor of Computer Science',
                'Bachelor of Information Technology',
                'Bachelor of Engineering',
                'Bachelor of Business Administration',
                'Bachelor of Economics',
                'Bachelor of Communication',
                'Bachelor of Design',
                'Bachelor of Science in Statistics',
                'Bachelor of Psychology',
                'Bachelor of Accounting',
            ],
            'Master' => [
                'Master of Computer Science',
                'Master of Information Technology',
                'Master of Business Administration',
                'Master of Data Science',
                'Master of Finance',
                'Master of Engineering',
                'Master of Management',
                'Master of Public Relations',
                'Master of Marketing',
                'Master of Human Resource Management',
            ],
            'Doctorate' => [
                'Doctor of Computer Science',
                'Doctor of Business Administration',
                'Doctor of Engineering',
                'Doctor of Information Technology',
            ],
        ];
        
        // List of Indonesian provinces and major cities
        $provinces = [
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cimahi', 'Tasikmalaya'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Magelang', 'Pekalongan', 'Salatiga'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Kediri', 'Madiun', 'Mojokerto'],
            'Banten' => ['Serang', 'Tangerang', 'Cilegon', 'Tangerang Selatan'],
            'DI Yogyakarta' => ['Yogyakarta', 'Sleman', 'Bantul', 'Kulon Progo'],
            'Bali' => ['Denpasar', 'Badung', 'Gianyar', 'Tabanan'],
            'Sumatera Utara' => ['Medan', 'Binjai', 'Pematang Siantar', 'Tebing Tinggi'],
            'Sumatera Barat' => ['Padang', 'Bukittinggi', 'Payakumbuh', 'Padang Panjang'],
            'Sulawesi Selatan' => ['Makassar', 'Parepare', 'Palopo', 'Maros'],
        ];

        // Seed 50 applicant users
        for ($i = 1; $i <= 50; $i++) {
            // Generate random dates within the last 3 months
            $createdAt = now()->subDays(rand(0, 90));
            
            // Choose a random degree level and specific degree
            $degreeLevel = array_rand($degrees);
            $specificDegree = $degrees[$degreeLevel][array_rand($degrees[$degreeLevel])];
            
            // Choose a random province and city
            $province = array_rand($provinces);
            $city = $provinces[$province][array_rand($provinces[$province])];
            
            // Create a unique email
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = $firstName . ' ' . $lastName;
            $emailUsername = strtolower(str_replace(' ', '.', $name)) . $i;
            $email = $emailUsername . '@' . $faker->freeEmailDomain;
            
            // Create user
            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        
            // Assign applicant role
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 3, // applicant role
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        
            // Select a random job
            $jobVacancyId = $jobIds[array_rand($jobIds)];
            
            // Select a salary range that matches the job
            if (isset($allSalaryRangeIds[$jobVacancyId]) && !empty($allSalaryRangeIds[$jobVacancyId])) {
                $salaryRangeId = $allSalaryRangeIds[$jobVacancyId][array_rand($allSalaryRangeIds[$jobVacancyId])];
            } else {
                // Fallback if no salary ranges found
                $salaryRangeId = DB::table('salary_ranges')->inRandomOrder()->value('id');
            }
            
            // Generate university
            $university = $universities[array_rand($universities)];
            
            // Generate IPK based on degree level (higher degrees generally have higher IPK)
            $baseIpk = 2.5;
            switch ($degreeLevel) {
                case 'Master':
                    $baseIpk = 3.0;
                    break;
                case 'Doctorate':
                    $baseIpk = 3.3;
                    break;
            }
            $ipk = $faker->randomFloat(2, $baseIpk, 4.0);
            
            // Generate status (mostly new applicants, some in other stages)
            $statusDistribution = [0, 0, 0, 0, 0, 0, 0, 1, 1, 2]; // 70% new, 20% screened, 10% other
            $status = $statusDistribution[array_rand($statusDistribution)];
            
            // Create LinkedIn URL based on name
            $linkedinUsername = strtolower(str_replace(' ', '-', $name)) . '-' . $faker->randomNumber(5);
            
            // Create applicant record
            DB::table('applicants')->insert([
                'users_id' => $userId,
                'job_vacancy_id' => $jobVacancyId,
                'salary_range_id' => $salaryRangeId,
                'name' => $name,
                'degree' => $specificDegree . ' (' . $university . ')',
                'ipk' => $ipk,
                'nomor_hp' => $faker->phoneNumber,
                'province' => $province,
                'city' => $city,
                'applicant_email' => $email,
                'linkedin' => 'https://linkedin.com/in/' . $linkedinUsername,
                'cv_path' => 'cv_files/sample_' . rand(1, 5) . '.pdf',
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }        

        // Seed schedules
        $this->seedSchedules($faker);
    }

    private function seedSchedules($faker)
    {
        // Days of the week in Indonesian
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Sample titles for schedules
        $titles = [
            'Meeting Tim',
            'Interview Kandidat',
            'Review Project',
            'Training Karyawan',
            'Diskusi Client',
            'Presentasi Proyek',
            'Brainstorming Session',
            'Evaluasi Kinerja',
            'Webinar HR',
            'Workshop Teknis',
            'Screening CV',
            'Orientasi Karyawan',
            'Rapat Manajemen',
            'Technical Interview',
            'HR Interview',
            'Final Interview',
            'Rapat Internal',
            'Koordinasi Departemen',
            'Follow-up Kandidat',
            'Review Aplikasi'
        ];
        
        // Sample descriptions for schedules
        $descriptions = [
            'Pertemuan rutin untuk membahas progress mingguan dan kendala yang dihadapi.',
            'Mewawancarai kandidat untuk posisi yang sedang dibuka.',
            'Meninjau progress dan hasil proyek yang sedang berjalan.',
            'Pelatihan untuk karyawan baru mengenai sistem dan prosedur perusahaan.',
            'Berdiskusi dengan client tentang kebutuhan dan feedback mereka.',
            'Mempresentasikan hasil proyek kepada stakeholders.',
            'Sesi untuk menghasilkan ide-ide baru untuk proyek mendatang.',
            'Mengevaluasi kinerja tim dan individu selama periode tertentu.',
            'Webinar tentang perkembangan terbaru di bidang HR.',
            'Workshop untuk meningkatkan keterampilan teknis tim.',
            'Proses screening CV para pelamar kerja.',
            'Orientasi untuk karyawan yang baru bergabung.',
            'Rapat dengan jajaran manajemen untuk membahas strategi perusahaan.',
            'Wawancara teknis untuk menguji kemampuan teknis kandidat.',
            'Wawancara dengan tim HR untuk menilai kecocokan kandidat dengan budaya perusahaan.',
            'Tahap wawancara final dengan calon karyawan potensial.',
            'Diskusi internal antar anggota tim.',
            'Koordinasi dengan departemen lain untuk memastikan kelancaran proyek.',
            'Tindak lanjut dengan kandidat potensial yang telah melewati tahap screening.',
            'Meninjau aplikasi pelamar kerja yang masuk.'
        ];

        // Define the date range (March to May 2024)
        $startDate = new \DateTime('2025-03-01');
        $endDate = new \DateTime('2025-05-31');
        $dateInterval = $startDate->diff($endDate);
        $dateRange = $dateInterval->days;

        // Generate 50 random schedules
        for ($i = 0; $i < 50; $i++) {
            
            // Generate random date within March to May range
            $randomDay = rand(0, $dateRange);
            $date = (clone $startDate)->modify("+{$randomDay} days")->format('Y-m-d');
            
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
            
            // Select random title and description
            $titleIndex = array_rand($titles);
            
            // Insert the schedule with the new 'judul' field
            DB::table('schedules')->insert([
                'hari' => $dayOfWeek,
                'jam_mulai' => $startDateTime,
                'jam_selesai' => $endDateTime,
                'tanggal' => $date,
                'judul' => $titles[$titleIndex],
                'deskripsi' => $descriptions[$titleIndex], // Match description with title
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}