<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getCalendarData()
    {
        // Get current date information
        $now = Carbon::now();
        
        // Get month names based on locale
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $months[] = Carbon::create(null, $i + 1)->translatedFormat('F');
        }
        
        // Generate years (5 years before and after current year)
        $currentYear = $now->year;
        $years = range($currentYear - 5, $currentYear + 4);
        
        return response()->json([
            'months' => $months,
            'years' => $years,
            'currentMonth' => $now->month - 1, // JavaScript months are 0-indexed
            'currentYear' => $currentYear,
            'today' => $now->toDateString(),
            // You could add more data here if needed
        ]);
    }
}