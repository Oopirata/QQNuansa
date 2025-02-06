<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $applicantData;
    protected $emailContent;

    public function __construct($applicant, $emailData)
    {
        // Konversi data ke string jika perlu
        $this->applicantData = [
            'name' => is_object($applicant->user) ? $applicant->user->name : (string)$applicant->user,
            'position' => is_object($applicant->job_vacancy) ? $applicant->job_vacancy->title : (string)$applicant->job_vacancy
        ];

        $this->emailContent = [
            'subject' => (string)$emailData['subject'],
            'messages' => (string)$emailData['messages'],
            'status' => (string)$emailData['status'],
            'interviewSchedule' => $emailData['interviewSchedule'] ? (string)$emailData['interviewSchedule'] : null
        ];
    }

    public function build()
    {
        return $this->subject($this->emailContent['subject'])
                    ->view('emails.candidate')
                    ->with([
                        'name' => $this->applicantData['name'],
                        'position' => $this->applicantData['position'],
                        'messages' => $this->emailContent['messages'],
                        'status' => $this->emailContent['status'],
                        'interviewSchedule' => $this->emailContent['interviewSchedule']
                    ]);
    }
}