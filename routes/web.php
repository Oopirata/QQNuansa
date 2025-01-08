<?php

use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\LegalitasController;
use App\Http\Controllers\MissionsController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('loginpage');
});

Route::get('/registrasi',[RegistrasiController::class,"registrasi_page"]);


Route::get('/companyprofile', [CompanyProfileController::class, "companyprofile_page"]);
Route::get('/legalitas', [LegalitasController::class, "legalitas_page"]);
Route::get('/missions', [MissionsController::class, "missions_page"]);
Route::get('/services', [ServicesController::class, "services_page"]);

