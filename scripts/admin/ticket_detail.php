<?php
require_once '../user/db_mongo.php';

use MongoDB\BSON\ObjectId;

if (!isset($_GET['id'])) {
    die('Ticket ID not specified.');
}
$ticket_id = $_GET['id'];
$message = '';
$success = false;

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_comment'])) {
        $new_comment = trim($_POST['comment']);
        if ($new_comment) {
            $admin_comment = "[admin] " . $new_comment;
            $updateResult = $mongoDB->tickets->updateOne(
                ['_id' => new ObjectId($ticket_id)],
                ['$push' => ['comments' => $admin_comment]]
            );
            if ($updateResult->getModifiedCount() === 1) {
                $success = true;
                $message = 'Comment added successfully!';
            } else {
                $message = 'Failed to add comment.';
            }
        } else {
            $message = 'Comment cannot be empty.';
        }
    } elseif (isset($_POST['resolve'])) {
        $updateResult = $mongoDB->tickets->updateOne(
            ['_id' => new ObjectId($ticket_id)],
            ['$set' => ['status' => false]]
        );
        if ($updateResult->getModifiedCount() === 1) {
            $success = true;
            $message = 'Ticket marked as resolved.';
        } else {
            $message = 'Failed to resolve ticket.';
        }
    }
}

// Fetch the ticket
$ticket = $mongoDB->tickets->findOne(['_id' => new ObjectId($ticket_id)]);
if (!$ticket) {
    die('Ticket not found.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Ticket Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .section { margin-bottom: 30px; }
        .msg { color: #c0392b; margin: 10px 0; }
        .success { color: #27ae60; margin: 10px 0; }
        ul { padding-left: 20px; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Admin: Ticket Details</h1>
    <div class="section">
        <strong>Username:</strong> <?= htmlspecialchars($ticket['username']) ?><br>
        <strong>Created At:</strong> <?= htmlspecialchars($ticket['created_at']) ?><br>
        <strong>Status:</strong> <?= $ticket['status'] ? 'Active' : 'Resolved' ?><br>
        <strong>Message:</strong><br>
        <div style="margin-left:20px;"><?= nl2br(htmlspecialchars($ticket['message'])) ?></div>
    </div>
    <div class="section">
        <strong>Comments:</strong>
        <?php if (!empty($ticket['comments'])): ?>
            <ul>
                <?php foreach ($ticket['comments'] as $c): ?>
                    <li><?= htmlspecialchars($c) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div>No comments yet.</div>
        <?php endif; ?>
    </div>
    <?php if ($ticket['status']): ?>
        <div class="section">
            <form method="post">
                <label for="comment">Add Admin Comment:</label><br>
                <textarea name="comment" id="comment" required style="width:300px;height:60px;"></textarea><br>
                <button type="submit" name="add_comment">Submit Comment</button>
            </form>
            <form method="post" style="margin-top:10px;">
                <button type="submit" name="resolve" style="background:#27ae60;color:#fff;padding:8px 16px;">Mark as Resolved</button>
            </form>
        </div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="<?= $success ? 'success' : 'msg' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="section">
        <a href="index.php">&larr; Back to Admin Homepage</a>
    </div>
</body>
</html> 