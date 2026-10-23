<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $types = config('SunSon')->notificationTypes; ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>

    <section class="account-card">
        <p class="eyebrow">Notifications panel</p>
        <h2>Reminders, updates and offers</h2>
        <p>Schedule changes, reminders, and promotions from Sun Son Solar appear here. You can turn reminders or promotions off in your <a href="<?= site_url('customer/profile') ?>#preferences">profile preferences</a>.</p>

        <?php if (empty($notifications)): ?>
            <p class="empty-state">You have no notifications yet.</p>
        <?php else: ?>
            <ul class="notice-list">
                <?php foreach ($notifications as $note): ?>
                    <li class="notice notice-<?= esc($note['type'], 'attr') ?> <?= $note['is_read'] ? '' : 'unread' ?>">
                        <div class="notice-top">
                            <span class="badge badge-type"><?= esc($types[$note['type']] ?? $note['type']) ?></span>
                            <?php if (! $note['is_read']): ?><span class="new-dot">New</span><?php endif; ?>
                            <small><?= nice_datetime($note['created_at']) ?></small>
                        </div>
                        <h3><?= esc($note['title']) ?></h3>
                        <p><?= nl2br(esc($note['message'])) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
<?= $this->endSection() ?>
