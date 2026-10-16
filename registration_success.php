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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created | Sun Son Solar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <a class="brand" href="index.php">SUN SON <span>SOLAR</span></a>
            <div class="nav-actions">
                <span class="signed-in-label">Customer: <?php echo h($_SESSION["name"]); ?></span>
                <a class="btn btn-primary" href="logout.php">Log out</a>
            </div>
        </header>

        <main class="confirmation-page">
            <section class="account-card confirmation-card" role="status">
                <p class="eyebrow">Registration complete</p>
                <h1>Your account is ready, <?php echo h($_SESSION["name"]); ?>.</h1>
                <p>Your customer account was created successfully. You are now signed in.</p>
                <a class="btn btn-primary" href="index.php">Return to home</a>
            </section>
        </main>
    </div>
</body>
</html>