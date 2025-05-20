<?php

namespace App\Console\Commands;

use App\Models\PsychotestSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePsychotestSessions extends Command
{
    protected $signature = 'psychotest:update-sessions';
    protected $description = 'Update psychotest session status based on current time';

    public function handle()
    {
        $now = now();

        $this->info("Updating psychotest sessions at {$now}");

        // Aktifkan sesi yang waktunya sudah dimulai dan belum berakhir
        $activatedCount = PsychotestSession::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('is_active', false)
            ->update(['is_active' => true]);

        // Nonaktifkan sesi yang waktunya sudah berakhir
        $deactivatedCount = PsychotestSession::where('end_date', '<', $now)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info("Sessions activated: {$activatedCount}");
        $this->info("Sessions deactivated: {$deactivatedCount}");

        Log::info("Psychotest sessions updated", [
            'activated' => $activatedCount,
            'deactivated' => $deactivatedCount,
            'time' => $now->toDateTimeString()
        ]);

        return Command::SUCCESS;
    }
}
