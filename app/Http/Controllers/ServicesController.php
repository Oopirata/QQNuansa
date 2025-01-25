<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    function services_page(){
        return view ('/companyprofile/services');
    }
}
