<?php
// Tabs for the staff area. Each tab only shows if the role is allowed
// (see $staffPermissions in app/Config/SunSon.php).
$active = $active ?? '';
$tabs   = [
    'dashboard'     => ['Overview', 'admin/dashboard'],
    'users'         => ['Users', 'admin/users'],
    'schedules'     => ['Schedules', 'admin/schedules'],
    'reports'       => ['Reports', 'admin/reports'],
    'notifications' => ['Notifications', 'admin/notifications'],
    'announcements' => ['Announcements', 'admin/announcements'],
    'attendance'    => ['Attendance', 'admin/attendance'],
    'issues'        => ['Field reports', 'admin/issues'],
    'support'       => ['Support tickets', 'admin/support'],
    'quotes'        => ['Quote requests', 'admin/quotes'],
    'settings'      => ['Settings', 'admin/settings'],
];
?>
<nav class="dash-nav" aria-label="Staff area">
    <?php foreach ($tabs as $key => [$label, $path]): ?>
        <?php if (staff_can($key)): ?>
            <a href="<?= site_url($path) ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= esc($label) ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php if (session('role') === 'dispatcher'): ?>
        <a href="<?= site_url('employee/dashboard') ?>">My employee page</a>
    <?php endif; ?>
</nav>
