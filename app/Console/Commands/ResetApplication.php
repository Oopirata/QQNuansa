<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ResetApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate fresh and seed the database, and delete all uploaded files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Are you sure you want to reset the application? This will delete all data including uploaded files.')) {
            // Clear storage
            Storage::disk('public')->deleteDirectory('cv_files');
            Storage::disk('public')->makeDirectory('cv_files');
            
            // Fresh migrate and seed
            $this->call('migrate:fresh', ['--seed' => true]);
            
            $this->info('Application has been reset successfully!');
        }
    }
}
