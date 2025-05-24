<?php
require_once 'db_mysql.php';

$trigger_title = "Trigger 2";
$trigger_description = "This trigger fires on UPDATE to the 'users' table. It tracks email changes.";
$member_name = "Cağlar Uysal";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['case'])) {
        if ($_POST['case'] === 'case1') {
            // Case 1: Normal email update
            $sql = "UPDATE users SET email = 'alice.new@example.com' WHERE id = 1";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 1: Normal email update. Trigger should log the change.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($_POST['case'] === 'case2') {
            // Case 2: Invalid email format
            $sql = "UPDATE users SET email = 'invalid-email-format' WHERE id = 1";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 2: Invalid email format update. Trigger should log the change.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($_POST['case'] === 'case3') {
            // Case 3: Duplicate email (using Bob's email)
            $sql = "UPDATE users SET email = 'bob@example.com' WHERE id = 1";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 3: Duplicate email update. Trigger should log the change.";
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