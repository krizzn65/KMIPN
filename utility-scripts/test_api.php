
<?php
// Test API endpoint
$url = 'http://localhost:8000/api/sensor';

// Test GET request (should show method not allowed)
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "GET Request to {$url}\n";
