<?php

namespace App\Console\Commands;

use App\Models\PsychotestSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePsychotestStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psychotest:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update psychotest session status based on date/time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // Aktifkan sesi yang waktunya sudah dimulai
        $activated = PsychotestSession::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('is_active', false)
            ->update(['is_active' => true]);

        // Nonaktifkan sesi yang waktunya sudah berakhir
        $deactivated = PsychotestSession::where('end_date', '<', $now)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info("Updated psychotest session status: {$activated} activated, {$deactivated} deactivated");

        // Log untuk debugging
        Log::info("Psychotest session status updated at {$now}: {$activated} activated, {$deactivated} deactivated");

        return Command::SUCCESS;
    }
}
