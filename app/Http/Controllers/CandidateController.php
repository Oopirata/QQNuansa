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
    public function screenedCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminScreenedCandidates');
    }
    public function interviewCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminInterviewCandidates');
    }
    public function rejectedCandidates()
    {
        return Inertia::render('resources/js/Pages/AdminRejectedCandidates');
    }


}
