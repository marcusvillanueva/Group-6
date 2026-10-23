<?php
// Shared header for every page.
// When someone is logged in, the "Login" button is replaced by their name
// and role (it links to their dashboard) plus a "Log out" button.
$home = site_url('/');
?>
<header class="site-header">
    <a class="brand" href="<?= $home ?>" aria-label="Sun Son Solar home">SUN SON SOLAR</a>

    <nav class="main-nav" aria-label="Main navigation">
        <a href="<?= $home ?>#services">Services</a>
        <a href="<?= $home ?>#products">Products</a>
        <a href="<?= $home ?>#about">Learn about the company</a>
        <a href="<?= $home ?>#support">Reach out support</a>
    </nav>

    <div class="nav-actions">
        <?php if (is_logged_in()): ?>
            <?php $name = (string) session('name'); ?>
            <a href="<?= dashboard_url() ?>" class="btn btn-light user-chip" title="Go to my dashboard">
                <span class="user-avatar" aria-hidden="true"><?= esc(strtoupper(mb_substr($name, 0, 1))) ?></span>
                <span class="user-chip-text">
                    <strong><?= esc($name) ?></strong>
                    <small><?= esc(role_label(session('role'))) ?> · My dashboard</small>
                </span>
            </a>
            <a href="<?= site_url('logout') ?>" class="btn btn-primary">Log out</a>
        <?php else: ?>
            <a href="<?= $home ?>#login" class="btn btn-light">Login</a>
            <a href="<?= site_url('quote') ?>" class="btn btn-primary">Get a quote</a>
        <?php endif; ?>
    </div>
</header>
