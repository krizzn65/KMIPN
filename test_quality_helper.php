<?php

require_once 'vendor/autoload.php';

// Test the QualityHelper functionality
echo "Testing QualityHelper Parameter Status Determination\n";
echo "===================================================\n\n";

// Include the QualityHelper class
require_once 'app/Helpers/QualityHelper.php';

use App\Helpers\QualityHelper;

// Test cases for different parameter values
$testCases = [
    // Normal cases
    [
        'ph' => 8.0,
        'suhu' => 30.0,
        'kekeruhan' => 200.0,
        'expected' => 'Normal'
    ],
    // Warning cases
    [
        'ph' => 7.2,
        'suhu' => 26.5,
        'kekeruhan' => 450.0,
        'expected' => 'Warning'
    ],
    // Danger cases
    [
        'ph' => 6.8,
        'suhu' => 35.0,
        'kekeruhan' => 650.0,
        'expected' => 'Danger'
    ],
    // Mixed cases
    [
        'ph' => 8.2, // Normal
        'suhu' => 33.5, // Warning
        'kekeruhan' => 300.0, // Normal
        'expected' => 'Warning'
    ]
];

foreach ($testCases as $index => $testCase) {
    echo "Test Case " . ($index + 1) . ":\n";
    echo "pH: " . $testCase['ph'] . ", Suhu: " . $testCase['suhu'] . "°C, Kekeruhan: " . $testCase['kekeruhan'] . " NTU\n";
    
    // Get individual parameter statuses
    $phStatus = QualityHelper::determineParameterStatus('ph', $testCase['ph']);
    $suhuStatus = QualityHelper::determineParameterStatus('suhu', $testCase['suhu']);
    $kekeruhanStatus = QualityHelper::determineParameterStatus('kekeruhan', $testCase['kekeruhan']);
    
    echo "pH Status: " . $phStatus['status'] . " (" . $phStatus['message'] . ")\n";
    echo "Suhu Status: " . $suhuStatus['status'] . " (" . $suhuStatus['message'] . ")\n";
    echo "Kekeruhan Status: " . $kekeruhanStatus['status'] . " (" . $kekeruhanStatus['message'] . ")\n";
    
    // Get overall status
    $parameterStatuses = [
        'ph' => $phStatus,
        'suhu' => $suhuStatus,
        'kekeruhan' => $kekeruhanStatus
    ];
    
    $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);
    echo "Overall Status: " . $overallStatus['status'] . " (" . $overallStatus['message'] . ")\n";
    
    // Check if expected matches actual
    if ($overallStatus['status'] === $testCase['expected']) {
        echo "✅ PASS\n";
    } else {
        echo "❌ FAIL - Expected: " . $testCase['expected'] . ", Got: " . $overallStatus['status'] . "\n";
    }
    
    echo str_repeat("-", 60) . "\n\n";
}

// Test threshold values
echo "Testing Threshold Values:\n";
echo "=========================\n\n";

$thresholdTests = [
    ['ph', 7.0, 'Danger'],
    ['ph', 7.4, 'Warning'],
    ['ph', 7.5, 'Normal'],
    ['ph', 8.5, 'Normal'],
    ['ph', 8.6, 'Warning'],
    ['ph', 9.0, 'Danger'],
    
    ['suhu', 25.9, 'Danger'],
    ['suhu', 26.0, 'Warning'],
    ['suhu', 27.9, 'Warning'],
    ['suhu', 28.0, 'Normal'],
    ['suhu', 32.0, 'Normal'],
    ['suhu', 33.0, 'Warning'],
    ['suhu', 34.0, 'Danger'],
    
    ['kekeruhan', 24.9, 'Normal'],
    ['kekeruhan', 25.0, 'Normal'],
    ['kekeruhan', 400.0, 'Normal'],
    ['kekeruhan', 401.0, 'Warning'],
    ['kekeruhan', 600.0, 'Danger'],
    ['kekeruhan', 601.0, 'Danger'],
];

foreach ($thresholdTests as $test) {
    $status = QualityHelper::determineParameterStatus($test[0], $test[1]);
    $result = $status['status'] === $test[2] ? '✅ PASS' : '❌ FAIL';
    echo "{$test[0]} = {$test[1]}: {$status['status']} (Expected: {$test[2]}) - {$result}\n";
}

echo "\nQuality Helper Test Completed!\n";