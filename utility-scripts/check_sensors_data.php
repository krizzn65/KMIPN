
<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking sensors data...\n";

$data = DB::table('sensors')->limit(5)->get();

if ($data->count() > 0) {
    echo 'Found '.$data->count()." records:\n";
    foreach ($data as $record) {
        print_r((array) $record);
    }
} else {
    echo "No data found in sensors table.\n";
}

echo "\nTable structure:\n";
