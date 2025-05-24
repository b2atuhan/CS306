<?php
require_once 'db_mysql.php';

$proc_title = "Stored Procedure 4 (by Dana)";
$proc_description = "This procedure adds a review for a product.";
$member_name = "Ahmet Batuhan Baykal";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['product_id'], $_POST['review_text'])) {
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $review_text = trim($_POST['review_text']);
    $sql = "CALL add_review(?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('iis', $user_id, $product_id, $review_text);
        if ($stmt->execute()) {
            $message = "Review added successfully.";
        } else {
            $message = "Error executing procedure: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
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
        .proc-inputs input, .proc-inputs textarea { display: block; margin-bottom: 2px; width: 200px; }
        .proc-inputs button { display: block; margin-bottom: 2px; width: 140px; }
        .msg { margin: 18px 0 0 0; color: #2c3e50; font-weight: bold; }
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
            <input type="number" name="user_id" placeholder="User ID" required>
            <input type="number" name="product_id" placeholder="Product ID" required>
            <textarea name="review_text" placeholder="Review Text" required></textarea>
            <button type="submit">Call Procedure</button>
        </form>
        <?php if ($message): ?>
            <div class="msg">Result: <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 