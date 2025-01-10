<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusCVController extends Controller
{
    function statuscv_page(){
        return view('cv.statusCV');
    }
}
