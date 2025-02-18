<?php

namespace App\Http\Controllers;

use App\Mail\CandidateEmail;
use App\Models\Applicant;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        try {
            Log::info('Send email request:', $request->all());
    
            $validated = $request->validate([
                'applicant_id' => 'required',
                'subject' => 'required|string',
                'messages' => 'required|string',
                'status' => 'required|string',
                'interviewSchedule' => 'nullable|string',
                'email' => 'required|email'
            ]);
            
    
            $applicant = Applicant::findOrFail($validated['applicant_id']);

            $emailData = array_merge($validated, ['messages' => $validated['messages']]);
            
            Log::info('Applicant data:', [
                'applicant' => $applicant->toArray(),
                'email_data' => $emailData
            ]);
    
            Mail::to($validated['email'])
                ->send(new CandidateEmail($applicant, $emailData));
    
            // Simpan history email
            Email::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $applicant->users_id,
                'subject' => $validated['subject'],
                'body' => $validated['messages'],
                'status' => 'sent'
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Send email error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function preview(Request $request)
    {
        try {
            Log::info('Preview request:', $request->all());
            
            $validated = $request->validate([
                'applicant_id' => 'required',
                'subject' => 'required|string',
                'messages' => 'required|string',  // sesuaikan dengan vue
                'status' => 'required|string',
                'interviewSchedule' => 'nullable|string'
            ]);
    
            $applicant = Applicant::findOrFail($validated['applicant_id']);
            
            // Ubah key messages ke message untuk view
            return view('emails.candidate', [
                'name' => $applicant->user ?? 'Candidate',
                'position' => $applicant->job_vacancy ?? 'Applied Position',
                'status' => $validated['status'] ?? 'Pending',
                'messages' => $validated['messages'] ?? '',  // sesuaikan
                'interviewSchedule' => $validated['interviewSchedule'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Preview error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function page()
    {
        $email = Email::with(['sender', 'receiver'])
            ->where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('AdminEmail', [
            'emails' => $email
        ]);
    }
}