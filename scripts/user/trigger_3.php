<?php
require_once 'db_mysql.php';

$trigger_title = "Trigger 3";
$trigger_description = "This trigger fires on DELETE from the 'products' table. It archives deleted products.";
$member_name = "Doğukan Doğan";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['case'])) {
        if ($_POST['case'] === 'case1') {
            // Case 1: Delete a normal product (Widget)
            $sql = "DELETE FROM products WHERE name = 'Widget'";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 1: Normal product deleted. Trigger should archive the product.";
            } else {
                $message = "Error: " . $conn->error;
            }
        } elseif ($_POST['case'] === 'case2') {
            // Case 2: Delete a product with reviews (requires cleanup)
            // First, insert a review for Gadget if it doesn't exist
            $conn->query("INSERT IGNORE INTO reviews (user_id, product_id, review_text) 
                         SELECT 1, id, 'Great gadget!' FROM products WHERE name = 'Gadget' LIMIT 1");
            
            // Then try to delete the product
            $sql = "DELETE FROM products WHERE name = 'Gadget'";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 2: Product with reviews deleted. Trigger should archive the product.";
            } else {
                $message = "Case 2: Cannot delete product with reviews (foreign key constraint). This shows data integrity.";
            }
        } elseif ($_POST['case'] === 'case3') {
            // Case 3: Delete multiple products at once
            $sql = "DELETE FROM products WHERE price < 20.00";
            if ($conn->query($sql) === TRUE) {
                $message = "Case 3: Multiple products deleted. Trigger should archive all deleted products.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}

// Get current products for display
$products_result = $conn->query("SELECT * FROM products");
$archived_result = $conn->query("SELECT * FROM archived_products");
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
        .tables {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .table-section {
            width: 45%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
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
        
        <div class="tables">
            <div class="table-section">
                <h3>Current Products</h3>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Price</th></tr>
                    <?php while ($row = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['price']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            
            <div class="table-section">
                <h3>Archived Products</h3>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Price</th><th>Deleted At</th></tr>
                    <?php while ($row = $archived_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['price']) ?></td>
                            <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    <a href="index.php">Go to homepage</a>
</body>
</html> 