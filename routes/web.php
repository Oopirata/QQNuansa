<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\OauthController;
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

Route::get('/hiring', function () {
    return Inertia::render('PosisiCV');
});

Route::get('/uploadcv', function () {
    return Inertia::render('UploadCV');
});

Route::get('/statuscv', function () {
    return Inertia::render('StatusCV');
})->middleware(['auth', 'verified']);

Route::middleware([\App\Http\Middleware\AdminMiddleware::class . ':1'])->group(function () {
    // ADMIN ROUTES
    Route::get('/adminDashboard', function () {
        return Inertia::render('AdminDashboard');
    })->name('admin.dashboard');

    Route::get('/adminDashboardSchedule', function () {
        return Inertia::render('AdminDashboardSchedule');
    });

    Route::get('/adminEmail', function () {
        return Inertia::render('AdminEmail');
    });

    Route::get('/adminNewJob', function () {
        return Inertia::render('AdminNewJob');
    });

    Route::get('/adminNewCandidates', [CandidateController::class, 'newCandidates'])->name('adminNewCandidates');

    Route::get('/adminScreenedCandidates', function () {
        return Inertia::render('AdminScreenedCandidates');
    })->name('adminScreenedCandidates');

    Route::get('/adminInterviewCandidates', function () {
        return Inertia::render('AdminInterviewCandidates');
    })->name('adminInterviewCandidates');

    Route::get('/adminRejectedCandidates', function () {
        return Inertia::render('AdminRejectedCandidates');
    })->name('adminRejectedCandidates');

    Route::get('/adminEmail', function () {
        return Inertia::render('AdminEmail');
    })->name('adminEmail');

    Route::get('/adminDetailNewCandidates/{id}', [CandidateController::class, 'newCandidatesDetail'])->name('adminDetailNewCandidates');

    Route::post('/moveToScreened/{user_id}', [CandidateController::class, 'moveToScreened'])->name('moveToScreened');

    Route::get('/adminDetailInterviewCandidates', function () {
        return Inertia::render('AdminDetailInterviewCandidates');
    });

    Route::get('/adminDetailRejectedCandidates', function () {
        return Inertia::render('AdminDetailRejectedCandidates');
    });
});

require __DIR__.'/auth.php';