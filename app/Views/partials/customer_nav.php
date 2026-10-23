<?php
// Tabs at the top of every customer page. $active = which tab is open.
$active = $active ?? '';
$unread = (new \App\Models\NotificationModel())->unreadCount((int) session('user_id'));
$tabs   = [
    'dashboard'     => ['Overview', 'customer/dashboard'],
    'schedules'     => ['My schedules', 'customer/schedules'],
    'notifications' => ['Notifications', 'customer/notifications'],
    'history'       => ['History & receipts', 'customer/history'],
    'support'       => ['Support', 'customer/support'],
    'profile'       => ['Profile', 'customer/profile'],
];
?>
<nav class="dash-nav" aria-label="Customer account">
    <?php foreach ($tabs as $key => [$label, $path]): ?>
        <a href="<?= site_url($path) ?>" class="<?= $active === $key ? 'active' : '' ?>">
            <?= esc($label) ?>
            <?php if ($key === 'notifications' && $unread > 0): ?><span class="count-badge"><?= $unread ?></span><?php endif; ?>
        </a>
    <?php endforeach; ?>
</nav>
