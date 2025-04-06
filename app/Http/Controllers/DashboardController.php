<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function schedule()
    {        
        $schedules = Schedule::all()
            ->map(function($schedule) {
                // Transform the data to match the format expected by the frontend
                // Using judul for title now instead of deskripsi
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->judul, // Changed from deskripsi to judul
                    'date' => date('Y-m-d', strtotime($schedule->tanggal)),
                    'time' => date('H:i', strtotime($schedule->jam_mulai)) . " - " . 
                              date('H:i', strtotime($schedule->jam_selesai)),
                    'description' => $schedule->deskripsi // Using deskripsi for the description field
                ];
            });
        
        return Inertia::render('AdminDashboardSchedule', [
            'initialEvents' => $schedules
        ]);
    }
}