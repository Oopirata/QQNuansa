<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailController extends Controller
{
    public function email(){
        return Inertia::render('resources/js/Pages/AdminEmail');
    }
}
