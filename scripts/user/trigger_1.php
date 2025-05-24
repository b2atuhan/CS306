<?php
require_once 'db_mysql.php';

$trigger_title = "Trigger 1";
$trigger_description = "This trigger fires on INSERT to the 'orders' table. If the order amount is greater than 1000, it logs a warning.";
$member_name = "Ahmet Batuhan Baykal";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['case'])) {
        if ($_POST['case'] === 'case1') {
            // Case 1: Normal insert (amount = 100)
            $sql = "INSERT INTO orders (user_id, amount) VALUES (1, 100)";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 1: Normal order inserted. Trigger should not log a warning.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($_POST['case'] === 'case2') {
            // Case 2: High amount (amount = 2000)
            $sql = "INSERT INTO orders (user_id, amount) VALUES (1, 2000)";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 2: High amount order inserted. Trigger should log a warning.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($_POST['case'] === 'case3') {
            // Case 3: Negative amount (amount = -50)
            $sql = "INSERT INTO orders (user_id, amount) VALUES (1, -50)";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 3: Negative amount order inserted. Trigger may block or log error.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($trigger_title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .mainbox {
            border: 1px solid #3366cc;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px;
            max-width: 700px;
        }
        .title {
            font-weight: bold;
            border: none;
        }
        .desc {
            display: inline;
            margin-left: 6px;
        }
        .case-btns button {
            display: block;
            margin-bottom: 2px;
            width: 140px;
        }
        .msg {
            margin: 18px 0 0 0;
            color: #2c3e50;
            font-weight: bold;
        }
        a { color: #3366cc; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="mainbox">
        <span class="title"><?= htmlspecialchars($trigger_title) ?>:</span>
        <div class="desc">
            <strong>Description:</strong> <?= htmlspecialchars($trigger_description) ?>
            <br>
            <strong>Team Member:</strong> <?= htmlspecialchars($member_name) ?>
        </div>
        <form method="post" class="case-btns">
            <button type="submit" name="case" value="case1">Case 1</button>
            <button type="submit" name="case" value="case2">Case 2</button>
            <button type="submit" name="case" value="case3">Case 3</button>
        </form>
        <?php if ($message): ?>
            <div class="msg">Result: <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 