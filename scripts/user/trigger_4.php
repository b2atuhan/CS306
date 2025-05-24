<?php
require_once 'db_mysql.php';

$trigger_title = "Trigger 4";
$trigger_description = "This trigger fires on INSERT to the 'reviews' table. It checks for spam reviews.";
$member_name = "Ahmet Batuhan Baykal";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['case'])) {
        if ($_POST['case'] === 'case1') {
            // Case 1: Normal review
            $review_text = "Great product! Really enjoyed using it.";
            $sql = "INSERT INTO reviews (user_id, product_id, review_text) VALUES (1, 1, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('s', $review_text);
                if ($stmt->execute()) {
                    $message = "Case 1: Normal review added. Trigger should not flag this as spam.";
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($_POST['case'] === 'case2') {
            // Case 2: Obvious spam review
            $review_text = "SPAM SPAM SPAM! Buy cheap products at spammy-site.com!";
            $sql = "INSERT INTO reviews (user_id, product_id, review_text) VALUES (1, 1, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('s', $review_text);
                if ($stmt->execute()) {
                    $message = "Case 2: Spam review added. Trigger should flag this as spam.";
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($_POST['case'] === 'case3') {
            // Case 3: Suspicious review with multiple red flags
            $review_text = "FREE OFFER! Click here: bit.ly/suspicious-link Buy now spam-product cheap!";
            $sql = "INSERT INTO reviews (user_id, product_id, review_text) VALUES (1, 1, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('s', $review_text);
                if ($stmt->execute()) {
                    $message = "Case 3: Suspicious review added. Trigger should flag this for multiple spam indicators.";
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Get current reviews and spam flags for display
$reviews_result = $conn->query("
    SELECT r.*, u.name as username, p.name as product_name, 
           CASE WHEN s.review_id IS NOT NULL THEN 'Yes' ELSE 'No' END as is_spam
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN products p ON r.product_id = p.id
    LEFT JOIN spam_reviews s ON r.id = s.review_id
    ORDER BY r.id DESC
    LIMIT 10
");
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
            max-width: 800px;
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
        .reviews-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .reviews-table th, .reviews-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .reviews-table th {
            background-color: #f5f5f5;
        }
        .spam-yes {
            color: #e74c3c;
            font-weight: bold;
        }
        .spam-no {
            color: #27ae60;
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
        
        <h3>Recent Reviews</h3>
        <table class="reviews-table">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Review</th>
                <th>Spam?</th>
            </tr>
            <?php while ($row = $reviews_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['review_text']) ?></td>
                    <td class="<?= $row['is_spam'] === 'Yes' ? 'spam-yes' : 'spam-no' ?>">
                        <?= htmlspecialchars($row['is_spam']) ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 