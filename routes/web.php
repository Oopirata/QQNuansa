<?php

use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\LegalitasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('loginpage');
});

Route::get('/registrasi',[RegistrasiController::class,"registrasi_page"]);


Route::get('/companyprofile', [CompanyProfileController::class, "companyprofile_page"]);
Route::get('/legalitas', [LegalitasController::class, "legalitas_page"]);

