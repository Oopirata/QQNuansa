<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidateController extends Controller
{
    public function newCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminNewCandidates');
    }
}
