<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ResetDailyChartData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chart:reset-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily chart data at midnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting daily chart data...');
        
        // Clear any cached chart data
        Cache::forget('daily_chart_data');
        Cache::forget('hourly_chart_data');
        
        // Reset any daily tracking data
        $this->resetDailyTracking();
        
        // Log the reset action
        Log::info('Daily chart data reset at ' . now()->format('Y-m-d H:i:s'));
        
        $this->info('Daily chart data reset successfully!');
    }
    
    /**
     * Reset daily tracking data
     */
    protected function resetDailyTracking()
    {
        // You can add any additional daily reset logic here
        // For example, reset daily counters or tracking variables
        
        // Store the date when the reset occurred
        Cache::put('last_daily_reset', now()->format('Y-m-d'), 86400); // 24 hours
    }
}