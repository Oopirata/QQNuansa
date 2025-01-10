<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MissionsController extends Controller
{
    function missions_page(){
        return view('/companyprofile/missions');
    }
}
