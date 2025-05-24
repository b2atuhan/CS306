<?php
require_once '../db_mongo.php';

// Fetch usernames with active tickets
$ticketCollection = $mongoDB->tickets;
$usernames = $ticketCollection->distinct('username', ['status' => true]);

$selected_user = isset($_GET['username']) ? $_GET['username'] : '';
$tickets = [];
if ($selected_user) {
    $tickets = $ticketCollection->find([
        'username' => $selected_user,
        'status' => true
    ])->toArray();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Support Tickets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .section { margin-bottom: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Support Ticket List</h1>
    <div class="section">
        <form method="get">
            <label for="username">Select Username:</label>
            <select name="username" id="username" onchange="this.form.submit()">
                <option value="">-- Select --</option>
                <?php foreach ($usernames as $uname): ?>
                    <option value="<?= htmlspecialchars($uname) ?>" <?= $selected_user === $uname ? 'selected' : '' ?>><?= htmlspecialchars($uname) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <?php if ($selected_user): ?>
        <div class="section">
            <a href="create_ticket.php?username=<?= urlencode($selected_user) ?>">Create New Ticket</a>
        </div>
        <?php if (count($tickets) > 0): ?>
            <table>
                <tr><th>Created At</th><th>Message</th><th>Status</th><th>Details</th></tr>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                        <td><?= htmlspecialchars($ticket['message']) ?></td>
                        <td><?= $ticket['status'] ? 'Active' : 'Resolved' ?></td>
                        <td><a href="ticket_detail.php?id=<?= $ticket['_id'] ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div>No active tickets for this user.</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="section">
            <?php if (count($usernames) === 0): ?>
                <div>No active tickets in the system.</div>
            <?php endif; ?>
            <a href="create_ticket.php">Create New Ticket</a>
        </div>
    <?php endif; ?>
    <div class="section">
        <a href="../index.php">&larr; Back to Homepage</a>
    </div>
</body>
</html> 