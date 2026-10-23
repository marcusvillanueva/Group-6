<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card dashboard-card">
        <p class="eyebrow">Customer account</p>
        <h1>Welcome, <?= esc(session('name')) ?>.</h1>
        <p>You are successfully logged in to your Sun Son Solar customer account.</p>
        <div class="dashboard-details"><p><strong>Username:</strong> <?= esc(session('username')) ?></p><p><strong>Email:</strong> <?= esc(session('email')) ?></p></div>
        <div class="registration-actions">
            <a class="btn btn-primary" href="<?= site_url('customer/schedules') ?>#book">Book a service</a>
            <a class="btn btn-light" href="<?= site_url('customer/support') ?>">Contact support</a>
        </div>
    </section>

    <div class="dash-grid">
        <section class="account-card">
            <div class="card-head">
                <h2>Upcoming schedule</h2>
                <a href="<?= site_url('customer/schedules') ?>">Manage</a>
            </div>
            <?php if (empty($upcoming)): ?>
                <p>No upcoming services yet. Book a consultation, installation, or maintenance visit anytime.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($upcoming as $item): ?>
                        <li>
                            <div>
                                <strong><?= esc($item['service_name']) ?></strong>
                                <span><?= nice_date($item['schedule_date']) ?> at <?= nice_time($item['schedule_time']) ?></span>
                            </div>
                            <?= status_badge($item['status']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="account-card">
            <div class="card-head">
                <h2>Latest notifications</h2>
                <a href="<?= site_url('customer/notifications') ?>">View all</a>
            </div>
            <?php if (empty($latest)): ?>
                <p>You have no notifications yet.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($latest as $note): ?>
                        <li class="<?= $note['is_read'] ? '' : 'unread' ?>">
                            <div>
                                <strong><?= esc($note['title']) ?></strong>
                                <span><?= nice_datetime($note['created_at']) ?></span>
                            </div>
                            <span class="badge badge-type"><?= esc(config('SunSon')->notificationTypes[$note['type']] ?? $note['type']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($openTickets > 0): ?>
                <p class="small-note">You have <?= $openTickets ?> open support ticket<?= $openTickets > 1 ? 's' : '' ?>. <a href="<?= site_url('customer/support') ?>">Check replies</a></p>
            <?php endif; ?>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
