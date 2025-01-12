<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return Inertia::render('CompanyProfile');
});

Route::get('/companyProfileAuth', function () {
    return Inertia::render('CompanyProfileAuth');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/legalitasAuth', function () {
        return Inertia::render('Auth/LegalitasAuth');
    });
    Route::get('/missionsAuth', function () {
        return Inertia::render('Auth/MissionsAuth');
    });
    Route::get('/servicesAuth', function () {
        return Inertia::render('Auth/ServicesAuth');
    });
    Route::get('/hiring', function () {
        return Inertia::render('Auth/PosisiCVAuth');
    });
    Route::get('/statuscv', function () {
        return Inertia::render('Auth/StatusCVAuth');
    });
    Route::get('/uploadcv', function () {
        return Inertia::render('Auth/UploadCVAuth');
    });
});


// ADMIN ROUTES
Route::get('/companyprofile', function () {
    return Inertia::render('CompanyProfile');
});
Route::get('/legalitas', function () {
    return Inertia::render('Legalitas');
});
Route::get('/missions', function () {
    return Inertia::render('Missions');
});
Route::get('/services', function () {
    return Inertia::render('Services');
});
Route::get('/hiringn', function () {
    return Inertia::render('PosisiCV');
});
Route::get('uploadcvn', function () {
    return Inertia::render('UploadCV');
});
Route::get('/adminDashboard', function () {
    return Inertia::render('AdminDashboard');
});
Route::get('/adminDashboardSchedule', function () {
    return Inertia::render('AdminDashboardSchedule');
});
Route::get('/adminEmail', function () {
    return Inertia::render('AdminEmail');
});
Route::get('/adminNewJob', function () {
    return Inertia::render('AdminNewJob');
});
// Route::get('/adminNewCandidates', function () {
//     return Inertia::render('AdminNewCandidates');
// });
Route::get('/newCandidateDetails', function () {
    return Inertia::render('CandidatesDetail');
});

Route::get('/adminNewCandidates', function () {
    return Inertia::render('AdminNewCandidates');
})->name('adminNewCandidates');


require __DIR__.'/auth.php';
