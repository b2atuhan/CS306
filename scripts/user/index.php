<?php
// Placeholder arrays for triggers and procedures
$triggers = [
    ["name" => "Trigger 1", "desc" => "Fires on INSERT to 'orders'. Logs warning if amount > 1000.", "member" => "Ahmet Batuhan Baykal", "link" => "trigger_1.php"],
    ["name" => "Trigger 2", "desc" => "Fires on UPDATE to 'users'. Tracks email changes.", "member" => "Cağlar Uysal", "link" => "trigger_2.php"],
    ["name" => "Trigger 3", "desc" => "Fires on DELETE from 'products'. Archives deleted products.", "member" => "Doğukan Doğan", "link" => "trigger_3.php"],
    ["name" => "Trigger 4", "desc" => "Fires on INSERT to 'reviews'. Checks for spam.", "member" => "Ahmet Batuhan Baykal", "link" => "trigger_4.php"],
];
$procedures = [
    ["name" => "Procedure 1", "desc" => "get_user_orders: Returns all orders for a user.", "member" => "Ahmet Batuhan Baykal", "link" => "procedure_1.php"],
    ["name" => "Procedure 2", "desc" => "update_product_price: Updates price for a product.", "member" => "Cağlar Uysal", "link" => "procedure_2.php"],
    ["name" => "Procedure 3", "desc" => "get_active_tickets: Lists all active support tickets.", "member" => "Doğukan Doğan", "link" => "procedure_3.php"],
    ["name" => "Procedure 4", "desc" => "add_review: Adds a review for a product.", "member" => "Ahmet Batuhan Baykal", "link" => "procedure_4.php"],
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Homepage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #2c3e50; }
        .container { border: 1px solid #222; padding: 18px; margin: 18px 0; border-radius: 8px; background: #fafbfc; }
        .section-title { margin-top: 30px; margin-bottom: 10px; }
        .feature-list { display: flex; flex-wrap: wrap; gap: 18px; }
        .feature-box { border: 1px solid #2980b9; padding: 14px; border-radius: 6px; background: #f4f8fb; min-width: 260px; flex: 1 1 260px; }
        .feature-box h3 { margin: 0 0 8px 0; color: #2980b9; }
        .feature-box .desc { margin-bottom: 8px; }
        .feature-box .member { font-size: 0.95em; color: #555; margin-bottom: 8px; }
        .feature-box a { color: #2980b9; font-weight: bold; }
        .support-link { margin-top: 30px; display: block; }
    </style>
</head>
<body>
    <h1>User Homepage</h1>
    <div class="container">
        <h2 class="section-title">Triggers</h2>
        <div class="feature-list">
            <?php foreach ($triggers as $t): ?>
                <div class="feature-box">
                    <h3><?= htmlspecialchars($t["name"]) ?></h3>
                    <div class="desc"><?= htmlspecialchars($t["desc"]) ?></div>
                    <div class="member">Responsible: <?= htmlspecialchars($t["member"]) ?></div>
                    <a href="<?= htmlspecialchars($t["link"]) ?>">Go to Page</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container">
        <h2 class="section-title">Stored Procedures</h2>
        <div class="feature-list">
            <?php foreach ($procedures as $p): ?>
                <div class="feature-box">
                    <h3><?= htmlspecialchars($p["name"]) ?></h3>
                    <div class="desc"><?= htmlspecialchars($p["desc"]) ?></div>
                    <div class="member">Responsible: <?= htmlspecialchars($p["member"]) ?></div>
                    <a href="<?= htmlspecialchars($p["link"]) ?>">Go to Page</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <a class="support-link" href="support/tickets.php">Go to Support Ticket System</a>
</body>
</html> 