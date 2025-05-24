<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>MongoDB Extension Test</h1>";

// Check if MongoDB extension is loaded
if (extension_loaded('mongodb')) {
    echo "MongoDB extension is loaded<br>";
    echo "MongoDB extension version: " . phpversion('mongodb') . "<br>";
} else {
    echo "MongoDB extension is NOT loaded<br>";
}

// Check if Composer autoload exists
$autoloadFile = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoloadFile)) {
    echo "Composer autoload file exists<br>";
    require_once $autoloadFile;
    
    // Test MongoDB client class
    if (class_exists('MongoDB\Client')) {
        echo "MongoDB\Client class is available<br>";
        
        try {
            $client = new MongoDB\Client('mongodb://localhost:27017');
            echo "Successfully created MongoDB client<br>";
            
            $databases = $client->listDatabases();
            echo "Available databases:<br>";
            foreach ($databases as $db) {
                echo "- " . $db->getName() . "<br>";
            }
        } catch (Exception $e) {
            echo "Error connecting to MongoDB: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "MongoDB\Client class is NOT available<br>";
    }
} else {
    echo "Composer autoload file NOT found at: $autoloadFile<br>";
}
?> 