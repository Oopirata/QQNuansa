<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    function companyprofile_page(){
        return view ('companyprofile/companyprofile');
    }
}
