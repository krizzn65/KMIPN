<?php

// Test database connection
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=n8n_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!\n\n";

    // Check if sensors table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'sensors'");
    if ($stmt->rowCount() > 0) {
        echo "Sensors table exists.\n";

        // Show table structure
        $stmt = $pdo->query('DESCRIBE sensors');
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\nTable structure:\n";
        foreach ($columns as $column) {
            echo "{$column['Field']} - {$column['Type']} - {$column['Null']}\n";
        }

        // Show some sample data
        $stmt = $pdo->query('SELECT * FROM sensors LIMIT 5');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\nSample data:\n";
        print_r($data);
    } else {
        echo "Sensors table does not exist.\n";
    }

} catch (PDOException $e) {
    echo 'Connection failed: '.$e->getMessage()."\n";
}
