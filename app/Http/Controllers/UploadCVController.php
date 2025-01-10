<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadCVController extends Controller
{
    function uploadcv_page(){
        return view('/cv/uploadCV');
    }
}
