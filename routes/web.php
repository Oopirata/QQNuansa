<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('CompanyProfileAuth');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ADMIN ROUTES
Route::get('/companyprofile', function () {
    return Inertia::render('CompanyProfile');
});
Route::get('/companyprofilee', function () {
    return Inertia::render('CompanyProfileAuth');
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
