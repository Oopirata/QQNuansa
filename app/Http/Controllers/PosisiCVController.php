<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosisiCVController extends Controller
{
   function posisicv_page(){
    return view('/cv/posisiCV');
   }

   public function index()
    {
        return view('positions.index');
    }

    // public function apply(Request $request)
    // {
    //     // Handle position submission
    //     return redirect()->back()->with('success', 'Aplikasi untuk posisi ' . $request->position . ' telah diterima');
    // }
    
}
