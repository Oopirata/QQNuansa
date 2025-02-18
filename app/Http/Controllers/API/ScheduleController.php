<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:Y-m-d H:i:s',
            'jam_selesai' => 'required|date_format:Y-m-d H:i:s',
            'hari' => 'required|string',
        ]);
        
        $schedule = Schedule::create([
            'users_id' => Auth::id(),
            'deskripsi' => $validated['deskripsi'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'hari' => $validated['hari'],
        ]);
        
        return response()->json([
            'message' => 'Schedule created successfully',
            'id' => $schedule->id
        ], 201);
    }

    public function destroy(Schedule $schedule)
    {  
        $schedule->delete();
        
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}