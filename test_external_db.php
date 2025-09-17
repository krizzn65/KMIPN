<?php

// Test external database connection
$host = 'db.devonjago.site';
$dbname = 'n8n_db';
$username = 'root'; // You'll need to provide the actual username
$password = ''; // You'll need to provide the actual password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "External database connection successful!\n\n";

    // Check if sensors table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'sensors'");
    if ($stmt->rowCount() > 0) {
        echo "Sensors table exists in external database.\n";

        // Show table structure
        $stmt = $pdo->query('DESCRIBE sensors');
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\nExternal table structure:\n";
        foreach ($columns as $column) {
            echo "{$column['Field']} - {$column['Type']} - {$column['Null']}\n";
        }

        // Show some sample data
        $stmt = $pdo->query('SELECT * FROM sensors LIMIT 5');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\nExternal sample data:\n";
        print_r($data);
    } else {
        echo "Sensors table does not exist in external database.\n";
    }

} catch (PDOException $e) {
    echo 'External connection failed: '.$e->getMessage()."\n";
    echo "Please check the database credentials (username/password) for the external database.\n";
}
