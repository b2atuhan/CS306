<?php
// MySQL connection settings (replace with your actual credentials)
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cs306_project';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die('MySQL Connection failed: ' . $conn->connect_error);
}
?> 