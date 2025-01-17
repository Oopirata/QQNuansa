<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadCVController extends Controller
{
    function uploadcv_page(){
        return view('/cv/uploadCV');
    }

    public function upload(Request $request)
{
    $request->validate([
        'cv_file' => 'required|mimes:pdf,doc,docx|max:5120', // max 5MB
        'nama-lengkap' => 'required|string|max:255',
        'email' => 'required|email',
        'lokasi' => 'required|string|max:255',
        'nomer-telepon' => 'required|string|max:20',
        'linkedin-url' => 'required|url',
    ]);

    // Store the file
    $path = $request->file('cv_file')->store('cv_files', 'public');

    // Store other form data in database
    // Your database logic here

    return redirect()->route('statuscv')->with('success', 'CV berhasil diunggah!');
}
}
