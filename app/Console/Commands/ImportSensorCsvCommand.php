<?php

namespace App\Console\Commands;

use App\Models\Sensor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImportSensorCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import-sensor-csv {file : Path to the CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sensor data from CSV file exported from external database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (! File::exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return Command::FAILURE;
        }

        $this->info("Importing sensor data from: {$filePath}");

        try {
            $handle = fopen($filePath, 'r');

            // Read header row
            $headers = fgetcsv($handle);

            if (! $headers) {
                $this->error('Invalid CSV file or empty file');

                return Command::FAILURE;
            }

            $this->info('CSV Headers: '.implode(', ', $headers));

            $importedCount = 0;
            $skippedCount = 0;

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($headers)) {
                    $this->warn('Skipping row with incorrect column count: '.implode(',', $row));
                    $skippedCount++;

                    continue;
                }

                $data = array_combine($headers, $row);

                // Clean and validate data
                $cleanData = $this->cleanData($data);

                if ($cleanData) {
                    try {
                        Sensor::create($cleanData);
                        $importedCount++;
                    } catch (\Exception $e) {
                        $this->warn('Failed to import row: '.$e->getMessage());
                        $skippedCount++;
                    }
                } else {
                    $skippedCount++;
                }
            }

            fclose($handle);

            $this->info('Import completed!');
            $this->info("Imported: {$importedCount} records");
            $this->info("Skipped: {$skippedCount} records");

            if ($importedCount > 0) {
                $this->info('Running status update command...');
                $this->call('sensors:update-statuses');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Import failed: '.$e->getMessage());
            Log::error('CSV import failed', ['error' => $e->getMessage()]);

            return Command::FAILURE;
        }
    }

    /**
     * Clean and validate CSV data
     */
    protected function cleanData(array $data): ?array
    {
        // Remove any empty values or invalid data
        $cleanData = array_filter($data, function ($value) {
            return $value !== '' && $value !== null;
        });

        // Required fields validation
        $required = ['ph', 'suhu', 'kekeruhan'];
        foreach ($required as $field) {
            if (! isset($cleanData[$field]) || ! is_numeric($cleanData[$field])) {
                $this->warn("Skipping row - missing or invalid required field: {$field}");

                return null;
            }
        }

        // Convert numeric values
        $cleanData['ph'] = (float) $cleanData['ph'];
        $cleanData['suhu'] = (float) $cleanData['suhu'];
        $cleanData['kekeruhan'] = (float) $cleanData['kekeruhan'];

        // Handle optional fields
        if (isset($cleanData['kualitas'])) {
            $cleanData['kualitas'] = (float) $cleanData['kualitas'];
        }

        // Remove unwanted fields that might come from export
        $allowedFields = ['ph', 'suhu', 'kekeruhan', 'kualitas', 'created_at', 'updated_at'];
        $cleanData = array_intersect_key($cleanData, array_flip($allowedFields));

        return $cleanData;
    }
}
