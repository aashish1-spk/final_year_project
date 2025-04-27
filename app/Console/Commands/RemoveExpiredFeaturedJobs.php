<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;

class RemoveExpiredFeaturedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-expired-featured-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove jobs that have expired as featured jobs (after 1 minute)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find and update featured jobs that have expired for 1 month
        Job::where('isFeatured', true)
            ->where('featured_until', '<', now()->subMonth())  // Check if expired for 1 month
            ->update([
                'isFeatured' => false,
                'featured_until' => null,
            ]);
    
        $this->info('Expired featured jobs that were featured for more than 1 month have been removed.');
    }
    
}
