
<?php
// Simple connection test
$host = 'db.devonjago.site';
$dbname = 'n8n_db';
$username = 'n8nuser';
$password = 'n8npassword';

echo "Testing connection to: $host\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "SUCCESS: Connected to database!\n";
} catch (PDOException $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
?>
