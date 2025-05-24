<?php
require_once 'db_mysql.php';

// Description of the stored procedure
$procedure_description = "This stored procedure, get_user_orders, takes a user_id and returns all orders for that user.";
$member_name = "Ahmet Batuhan Baykal";

$result_message = '';
$orders = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $sql = "CALL get_user_orders(?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $orders[] = $row;
                }
                $result_message = "Orders for user ID $user_id:";
            } else {
                $result_message = "No orders found for user ID $user_id.";
            }
        } else {
            $result_message = "Error executing procedure: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $result_message = "Error preparing statement: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Procedure 1 Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .desc { margin-bottom: 20px; }
        .msg { margin: 20px 0; color: #2c3e50; font-weight: bold; }
        form { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 60%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        a { color: #2980b9; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Procedure 1 Demo</h1>
    <div class="desc">
        <strong>Description:</strong> <?= htmlspecialchars($procedure_description) ?>
        <br>
        <strong>Team Member:</strong> <?= htmlspecialchars($member_name) ?>
    </div>
    <form method="post">
        <label for="user_id">User ID:</label>
        <input type="number" name="user_id" id="user_id" required>
        <button type="submit">Get Orders</button>
    </form>
    <?php if ($result_message): ?>
        <div class="msg">Result: <?= htmlspecialchars($result_message) ?></div>
    <?php endif; ?>
    <?php if (!empty($orders)): ?>
        <table>
            <tr>
                <?php foreach (array_keys($orders[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <?php foreach ($order as $val): ?>
                        <td><?= htmlspecialchars($val) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <a href="index.php">&larr; Back to Homepage</a>
</body>
</html> 