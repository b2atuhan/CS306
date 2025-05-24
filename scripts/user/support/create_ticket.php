<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug information
echo "Debug: Starting script execution<br>";

require_once '../db_mongo.php';

// Debug MongoDB connection
try {
    echo "Debug: Testing MongoDB connection<br>";
    $mongoDB->command(['ping' => 1]);
    echo "Debug: MongoDB connection successful<br>";
} catch (Exception $e) {
    echo "Debug: MongoDB connection error - " . $e->getMessage() . "<br>";
    die();
}

$username = isset($_GET['username']) ? $_GET['username'] : (isset($_POST['username']) ? $_POST['username'] : '');
$message = '';
$success = false;

// Debug variables
echo "Debug: Username from GET/POST: " . htmlspecialchars($username) . "<br>";
echo "Debug: Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $ticket_message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    if ($ticket_message && $username) {
        $result = $mongoDB->tickets->insertOne([
            'username' => $username,
            'message' => $ticket_message,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'status' => true,
            'comments' => []
        ]);

        if ($result->getInsertedCount() === 1) {
            $success = true;
            $message = 'Ticket created successfully!';
        } else {
            $message = 'Failed to create ticket.';
        }
    } else {
        $message = 'Please provide both username and message.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Support Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .section { margin-bottom: 30px; }
        .msg { color: #c0392b; margin: 10px 0; }
        .success { color: #27ae60; margin: 10px 0; }
        textarea { width: 100%; height: 150px; }
        input[type="submit"] { padding: 10px 20px; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
        input[type="text"] { width: 100%; padding: 8px; margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Create Support Ticket</h1>
    
    <?php if ($message): ?>
        <div class="<?= $success ? 'success' : 'msg' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="post">
            <div class="section">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="section">
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" required></textarea>
            </div>
            <div class="section">
                <input type="submit" value="Create Ticket">
            </div>
        </form>
    <?php endif; ?>

    <div class="section">
        <a href="tickets.php<?= $username ? '?username=' . urlencode($username) : '' ?>">View All Tickets</a>
    </div>
</body>
</html>