<?php

namespace App\Console\Commands;

use App\Models\PsychotestSession;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DebugPsychotestSessions extends Command
{
    protected $signature = 'psychotest:debug-sessions';
    protected $description = 'Debug all psychotest sessions status';

    public function handle()
    {
        $now = now();
        $this->info("Current time: {$now}");

        $sessions = PsychotestSession::orderBy('created_at', 'desc')->get();

        $headers = ['ID', 'Name', 'Start Date', 'End Date', 'Is Active', 'Should Be Active', 'Status'];
        $rows = [];

        foreach ($sessions as $session) {
            $startDate = Carbon::parse($session->start_date);
            $endDate = Carbon::parse($session->end_date);
            $shouldBeActive = $now->between($startDate, $endDate);

            $status = 'Correct';
            if ($shouldBeActive !== $session->is_active) {
                $status = 'Needs Update';
            }

            $rows[] = [
                $session->id,
                $session->name,
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString(),
                $session->is_active ? 'Yes' : 'No',
                $shouldBeActive ? 'Yes' : 'No',
                $status
            ];
        }

        $this->table($headers, $rows);

        return Command::SUCCESS;
    }
}
