<?php
session_start();

if (!isset($_SESSION["account_type"]) || $_SESSION["account_type"] != "employee" || !isset($_SESSION["role"]) || $_SESSION["role"] != "employee") {
    header("Location: index.php#login");
    exit;
}

function h($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Employee Account | Sun Son Solar</title><link rel="stylesheet" href="styles.css"></head>
<body>
    <div class="page-shell">
        <header class="site-header"><a class="brand" href="index.php">SUN SON SOLAR</a><div class="nav-actions"><a class="btn btn-light" href="logout.php">Log out</a></div></header>
        <main class="dashboard-page">
            <section class="account-card dashboard-card">
                <p class="eyebrow">Employee access</p>
                <h1>Welcome, <?php echo h($_SESSION["name"]); ?>.</h1>
                <p>You are successfully logged in with an employee account.</p>
                <div class="dashboard-details"><p><strong>Username:</strong> <?php echo h($_SESSION["username"]); ?></p><p><strong>Department:</strong> <?php echo h($_SESSION["department"]); ?></p></div>
                <p>This page is the starting point for internal employee features that may be added in later activities.</p>
            </section>
        </main>
    </div>
</body>
</html>
