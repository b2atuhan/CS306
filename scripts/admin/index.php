<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../user/db_mongo.php';

$tickets = $mongoDB->tickets->find(['status' => true])->toArray();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Ticket List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Admin: Active Support Tickets</h1>
    <?php if (count($tickets) > 0): ?>
        <table>
            <tr><th>Username</th><th>Created At</th><th>Message</th><th>Details</th></tr>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['username']) ?></td>
                    <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                    <td><?= htmlspecialchars($ticket['message']) ?></td>
                    <td><a href="ticket_detail.php?id=<?= $ticket['_id'] ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div>No active tickets in the system.</div>
    <?php endif; ?>
    <div style="margin-top:30px;">
        <a href="../user/index.php">&larr; Back to User Homepage</a>
    </div>
</body>
</html> 