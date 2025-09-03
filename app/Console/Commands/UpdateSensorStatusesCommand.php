<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Helpers\QualityHelper;

class UpdateSensorStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensors:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing sensor data with parameter status information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating sensor data with status information...');

        $sensors = Sensor::whereNull('ph_status')
            ->orWhereNull('suhu_status')
            ->orWhereNull('kekeruhan_status')
            ->orWhereNull('overall_status')
            ->get();

        $bar = $this->output->createProgressBar($sensors->count());
        $bar->start();

        $updatedCount = 0;

        foreach ($sensors as $sensor) {
            if ($sensor->ph && $sensor->suhu && $sensor->kekeruhan) {
                // Get parameter statuses
                $parameterStatuses = QualityHelper::getAllParameterStatuses(
                    $sensor->ph,
                    $sensor->suhu,
                    $sensor->kekeruhan
                );

                $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

                // Update sensor with status information
                $sensor->ph_status = $parameterStatuses['ph']['status'];
                $sensor->suhu_status = $parameterStatuses['suhu']['status'];
                $sensor->kekeruhan_status = $parameterStatuses['kekeruhan']['status'];
                $sensor->overall_status = $overallStatus['status'];

                $sensor->save();
                $updatedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Successfully updated {$updatedCount} sensor records with status information.");
        $this->info('All existing sensor data now includes parameter status categorization.');

        return Command::SUCCESS;
    }
}