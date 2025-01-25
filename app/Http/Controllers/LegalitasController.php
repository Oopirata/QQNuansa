<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalitasController extends Controller
{
    function legalitas_page(){
        return view ('/companyprofile/legalitas');
    }
}
