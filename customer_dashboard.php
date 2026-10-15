<?php
session_start();

if (!isset($_SESSION["account_type"]) || $_SESSION["account_type"] != "customer") {
    header("Location: index.php#login");
    exit;
}

function h($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Customer Account | Sun Son Solar</title><link rel="stylesheet" href="styles.css"></head>
<body>
    <div class="page-shell">
        <header class="site-header"><a class="brand" href="index.php">SUN SON SOLAR</a><nav class="main-nav"><a href="index.php#services">Services</a><a href="index.php#support">Support</a></nav><div class="nav-actions"><a class="btn btn-light" href="logout.php">Log out</a></div></header>
        <main class="dashboard-page">
            <section class="account-card dashboard-card">
                <p class="eyebrow">Customer account</p>
                <h1>Welcome, <?php echo h($_SESSION["name"]); ?>.</h1>
                <p>You are successfully logged in to your Sun Son Solar customer account.</p>
                <div class="dashboard-details"><p><strong>Username:</strong> <?php echo h($_SESSION["username"]); ?></p><p><strong>Email:</strong> <?php echo h($_SESSION["email"] ?? ""); ?></p></div>
                <p>Service-request and account-management features can be connected here as the project adds them.</p>
                <a class="btn btn-primary" href="index.php#services">Explore services</a>
            </section>
        </main>
    </div>
</body>
</html>
