<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\OauthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadCVController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\PsychotestController;
use App\Http\Controllers\TestResultController;
use App\Models\Email;
use App\Http\Controllers\API\CalendarController;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return Inertia::render('LandingPage');
})->name('landing.page');

// Route::get('/statuscv', function () {
//     return Inertia::render('StatusCV');
// })->middleware(['auth', 'verified']);

Route::get('/companyprofile', function () {
    return Inertia::render('CompanyProfile');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/uploadCV/Applicant/{id}', [UploadCVController::class, 'upload'])->name('cv.upload');

    // Psychotest Routes for Users
    Route::get('/psychotest/entry', [PsychotestController::class, 'showEntryForm'])->name('psychotest.entry');
    Route::get('/psychotest/test', [PsychotestController::class, 'showTestForm'])->name('psychotest.test');
    Route::get('/psychotest/results/{participant}', [PsychotestController::class, 'showResults'])->name('psychotest.result');
    Route::get('/test-result/{id}/download', [TestResultController::class, 'downloadTestResult']);
});

//GGOOOGLE LOGIN
Route::middleware('guest')->group(function () {
    Route::get('oauth/google', [\App\Http\Controllers\OauthController::class, 'redirectToProvider'])->name('oauth.google');
    Route::get('oauth/google/callback', [\App\Http\Controllers\OauthController::class, 'handleProviderCallback'])->name('oauth.google.callback');
});

// PUBLIC ROUTES

// Route::get('/landingpage', function(){
//     return Inertia::render('LandingPage');
// });

Route::get('/calendar-data', [CalendarController::class, 'getCalendarData']);

Route::get('/legalitas', function () {
    return Inertia::render('Legalitas');
});

Route::get('/missions', function () {
    return Inertia::render('Missions');
});

Route::get('/services', function () {
    return Inertia::render('Services');
});

// ROUTES FOR TAGS IN SERVICES
Route::get('/serviceassesment', function () {
    return Inertia::render('ServicesAssesment');
})->name('serviceassesment');

Route::get('/servicepsikotest', function () {
    return Inertia::render('ServicesPsikotest');
})->name('servicepsikotest');

Route::get('/servicekonseling', function () {
    return Inertia::render('ServicesKonseling');
})->name('/serviceskonseling');

Route::get('/servicecoaching', function () {
    return Inertia::render('ServicesCoaching');
})->name('/servicecoaching');

Route::get('/servicesoutbond', function () {
    return Inertia::render('ServicesOutbond');
})->name('/servicesoutbond');

Route::get('/servicesseminar', function () {
    return Inertia::render('ServicesSeminar');
})->name('/servicesseminar');

Route::get('/servicestraining', function () {
    return Inertia::render('ServicesTraining');
})->name('/servicestraining');

Route::get('/serviceshypnotheraphy', function () {
    return Inertia::render('ServicesHypnotheraphy');
})->name('/serviceshypnotheraphy');

Route::get('/servicesSTIFIN', function () {
    return Inertia::render('ServicesSTIFIN');
})->name('/servicesSTIFIN');

Route::get('/hiring', [JobVacancyController::class, 'show']);

Route::get('/uploadcv/{id}', [UploadCVController::class, 'uploadcv_page']);

Route::get('/statuscv', function () {
    return Inertia::render('StatusCV');
})->middleware(['auth', 'verified']);

//Form 

Route::get('/formtest', function () {
    return Inertia::render('FormPage');
})->name('/formtest');


// API untuk operasi psikotest
Route::prefix('api/psychotest')->middleware('web')->group(function () {
    // Verifikasi kode akses
    Route::post('/verify-code', [PsychotestController::class, 'verifyCode']);

    // Memulai test dengan data pengguna
    Route::post('/start', [PsychotestController::class, 'startTest']);

    // Menyimpan progres jawaban
    Route::post('/save-progress', [PsychotestController::class, 'saveProgress']);

    // Mendapatkan jawaban yang tersimpan
    Route::get('/answers/{participant}', [PsychotestController::class, 'getAnswers']);

    // Menyelesaikan test
    Route::post('/complete', [PsychotestController::class, 'completeTest']);
});

Route::get('/laporan-psikotes', function () {
    return view('test-result');
});

Route::middleware([\App\Http\Middleware\AdminMiddleware::class . ':1'])->group(function () {
    // ADMIN ROUTES
    // Route::get('/adminDashboard', function () {
    //     return Inertia::render('AdminDashboard');
    // })->name('admin.dashboard');
    Route::get('/admin/dashboard', [CandidateController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/adminDashboardSchedule', [DashboardController::class, 'schedule'])->name('admin.dashboard.schedule');

    Route::get('/adminNewJob', function () {
        return Inertia::render('AdminNewJob');
    })->name('adminNewJob');

    Route::get('/adminNewCandidates', [CandidateController::class, 'newCandidates'])->name('adminNewCandidates');

    Route::get('/adminDetailNewCandidates/{id}', [CandidateController::class, 'newCandidatesDetail'])->name('adminDetailNewCandidates');

    Route::post('/moveToScreened/{user_id}', [CandidateController::class, 'moveToScreened'])->name('moveToScreened');

    Route::get('/adminScreenedCandidates', [CandidateController::class, 'screenedCandidates'])->name('adminScreenedCandidates');

    Route::get('/adminDetailScreenedCandidates/{id}', [CandidateController::class, 'screenCandidateDetail'])->name('adminDetailScreenedCandidates');

    Route::post('/moveToInterview/{user_id}', [CandidateController::class, 'moveToInterview'])->name('moveToInterview');

    Route::get('/adminInterviewCandidates', [CandidateController::class, 'interviewCandidates'])->name('adminInterviewCandidates');

    Route::get('/adminDetailInterviewCandidates/{id}', [CandidateController::class, 'interviewCandidatesDetail'])->name('adminDetailInterviewCandidates');

    Route::get('/moveToHired/{user_id}', [CandidateController::class, 'moveToHired'])->name('moveToHired');

    Route::get('/adminKaryawan', [CandidateController::class, 'showEmployees'])->name('adminKaryawan');

    Route::get('/adminRejectedCandidates', [CandidateController::class, 'rejectedCandidates'])->name('adminRejectedCandidates');

    Route::get('/adminDetailRejectedCandidates/{id}', [CandidateController::class, 'rejectedCandidatesDetail'])->name('adminDetailRejectedCandidates');

    Route::post('/disqualify/{user_id}', [CandidateController::class, 'disqualify'])->name('disqualify');

    Route::get('/adminEmail', [EmailController::class, 'page'])->name('adminEmail');

    Route::get('/adminJobList', [JobVacancyController::class, 'jobList'])->name('adminJobList');
    Route::get('/adminJobDetail/{id}', [JobVacancyController::class, 'jobDetail'])->name('adminJobDetail');
    Route::get('/adminJobDetail/{id}/edit', [JobVacancyController::class, 'jobEdit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobVacancyController::class, 'update'])->name('jobs.update');
    Route::delete('/admin/jobs/{job}', [JobVacancyController::class, 'destroy'])->name('adminJobDelete');
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

    //EMAIL
    Route::post('/preview-email', [EmailController::class, 'preview'])->name('preview.email');
    Route::post('/send-email', [EmailController::class, 'send'])->name('send.email');

    //POST JOB
    Route::get('/jobs/create', [JobVacancyController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobVacancyController::class, 'store'])->name('jobs.store');
    Route::get('/jobs', [JobVacancyController::class, 'index'])->name('jobs.index');

    //PYCHOTEST
    Route::get('/admin/psychotest', [PsychotestController::class, 'index'])->name('admin.psychotest.index');
    Route::get('/admin/psychotest/create', [PsychotestController::class, 'create'])->name('admin.psychotest.create');
    Route::post('/admin/psychotest', [PsychotestController::class, 'store'])->name('admin.psychotest.store');
    Route::get('/admin/psychotest/{id}', [PsychotestController::class, 'show'])->name('admin.psychotest.show');
    Route::get('/admin/psychotest/{sessionId}/results', [PsychotestController::class, 'viewResults'])->name('admin.psychotest.viewResults');



    // PYSCHOTEST API Routes for Admin
    Route::prefix('api/psychotest')->group(function () {
        Route::patch('/sessions/{id}/toggle', [PsychotestController::class, 'toggleStatus']);
        Route::get('/sessions/{id}/export', [PsychotestController::class, 'exportResults']);
        Route::post('/codes/{id}/reset', [PsychotestController::class, 'resetCode']);
    });
});

require __DIR__ . '/auth.php';
