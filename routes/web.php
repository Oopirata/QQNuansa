<?php

use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\LegalitasController;
use App\Http\Controllers\MissionsController;
use App\Http\Controllers\PosisiCVController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StatusCVController;
use App\Http\Controllers\UploadCVController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('loginpage');
});

Route::get('/registrasi',[RegistrasiController::class,"registrasi_page"]);


Route::get('/companyprofile', [CompanyProfileController::class, "companyprofile_page"]);
Route::get('/legalitas', [LegalitasController::class, "legalitas_page"]);
Route::get('/missions', [MissionsController::class, "missions_page"]);
Route::get('/services', [ServicesController::class, "services_page"]);
Route::get('/uploadcv', [UploadCVController::class, "uploadcv_page"]);
Route::get('/statuscv', [StatusCVController::class, "statuscv_page"]);
Route::get('/posisicv', [PosisiCVController::class, "posisicv_page"]);
Route::get('/positions', [PosisiCVController::class, 'index'])->name('positions.index');
Route::post('/positions/apply', [PosisiCVController::class, 'apply'])->name('positions.apply');
Route::post('/cv/upload', [UploadCVController::class, 'upload'])->name('cv.upload');


