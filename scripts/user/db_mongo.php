<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php'; // Composer autoload (adjust path if needed)

try {
    $mongoClient = new MongoDB\Client('mongodb://localhost:27017');
    $mongoDB = $mongoClient->selectDatabase('cs306_project');
    
    // Test the connection
    $mongoDB->command(['ping' => 1]);
    
} catch (Exception $e) {
    echo '<pre>';
    echo 'MongoDB Connection failed: ' . $e->getMessage() . "\n";
    echo 'Error details: ' . print_r($e, true);
    echo '</pre>';
    die();
}
?> 