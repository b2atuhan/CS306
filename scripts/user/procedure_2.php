<?php
require_once 'db_mysql.php';

$proc_title = "Stored Procedure 2 (by Bob)";
$proc_description = "This procedure updates the price for a product.";
$member_name = "Cağlar Uysal";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['new_price'])) {
    $product_id = intval($_POST['product_id']);
    $new_price = floatval($_POST['new_price']);
    $sql = "CALL update_product_price(?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('id', $product_id, $new_price);
        if ($stmt->execute()) {
            $message = "Product price updated successfully.";
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
        .proc-inputs input { display: block; margin-bottom: 2px; width: 200px; }
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
            <input type="number" name="product_id" placeholder="Product ID" required>
            <input type="number" step="0.01" name="new_price" placeholder="New Price" required>
            <button type="submit">Call Procedure</button>
        </form>
        <?php if ($message): ?>
            <div class="msg">Result: <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 