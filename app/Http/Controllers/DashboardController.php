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
        // Fetch all schedules for the user
        $schedules = Schedule::all()
            ->map(function($schedule) {
                // Transform the data to match the format expected by the frontend
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->deskripsi,
                    'date' => date('Y-m-d', strtotime($schedule->tanggal)),
                    'time' => date('H:i', strtotime($schedule->jam_mulai)),
                    'description' => "From " . date('H:i', strtotime($schedule->jam_mulai)) . 
                                    " to " . date('H:i', strtotime($schedule->jam_selesai)) . 
                                    " (" . $schedule->hari . ")"
                ];
            });
        
        return Inertia::render('AdminDashboardSchedule', [
            'initialEvents' => $schedules
        ]);
    }
}