<?php
require_once '../db_mongo.php';

use MongoDB\BSON\ObjectId;

$ticket = null;
$message = '';
$comment_success = false;

if (!isset($_GET['id'])) {
    die('Ticket ID not specified.');
}
$ticket_id = $_GET['id'];

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $new_comment = trim($_POST['comment']);
    if ($new_comment) {
        $updateResult = $mongoDB->tickets->updateOne(
            ['_id' => new ObjectId($ticket_id)],
            ['$push' => ['comments' => $new_comment]]
        );
        if ($updateResult->getModifiedCount() === 1) {
            $comment_success = true;
        } else {
            $message = 'Failed to add comment.';
        }
    } else {
        $message = 'Comment cannot be empty.';
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
    <title>Ticket Details</title>
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
    <h1>Ticket Details</h1>
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
    <div class="section">
        <form method="post">
            <label for="comment">Add Comment:</label><br>
            <textarea name="comment" id="comment" required style="width:300px;height:60px;"></textarea><br>
            <button type="submit">Submit Comment</button>
        </form>
        <?php if ($comment_success): ?>
            <div class="success">Comment added successfully! Please refresh to see the latest comments.</div>
        <?php elseif ($message): ?>
            <div class="msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
    <div class="section">
        <a href="tickets.php?username=<?= urlencode($ticket['username']) ?>">&larr; Back to Ticket List</a>
 