<?php
require_once 'db_mysql.php';

$proc_title = "Stored Procedure 3 (by Charlie)";
$proc_description = "This procedure lists all active support tickets.";
$member_name = "Doğukan Doğan";

$results = [];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "CALL get_active_tickets()";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $results[] = $row;
        }
        $message = "Active tickets listed below.";
    } else {
        $message = "No active tickets found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($proc_title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .mainbox {
            border: 1px solid #3366cc;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px;
            max-width: 700px;
        }
        .title { font-weight: bold; border: none; }
        .desc { display: inline; margin-left: 6px; }
        .proc-inputs button { display: block; margin-bottom: 2px; width: 140px; }
        .msg { margin: 18px 0 0 0; color: #2c3e50; font-weight: bold; }
        .results-box { border: 1px solid #2980b9; background: #f4f8fb; padding: 10px; margin-top: 10px; border-radius: 6px; }
        a { color: #3366cc; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="mainbox">
        <span class="title"><?= htmlspecialchars($proc_title) ?>:</span>
        <div class="desc">
            <strong>Description:</strong> <?= htmlspecialchars($proc_description) ?>
            <br>
            <strong>Team Member:</strong> <?= htmlspecialchars($member_name) ?>
        </div>
        <form method="post" class="proc-inputs">
            <button type="submit">Call Procedure</button>
        </form>
        <?php if ($message): ?>
            <div class="msg">Result: <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if (!empty($results)): ?>
            <div class="results-box">
                <?php foreach ($results as $row): ?>
                    <div><?= htmlspecialchars(json_encode($row)) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 