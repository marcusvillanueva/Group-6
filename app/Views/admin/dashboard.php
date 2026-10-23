<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card dashboard-card">
        <p class="eyebrow">Restricted access · <?= esc(role_label(session('role'))) ?></p>
        <h1>Welcome, <?= esc(session('name')) ?>.</h1>
        <p>Here is what needs attention at Sun Son Solar today, <?= date('l, F j, Y') ?>.</p>
        <?php if (staff_can('users')): ?>
            <a class="btn btn-primary" href="<?= site_url('admin/users/create') ?>">Create an employee account</a>
        <?php endif; ?>
    </section>

    <div class="stat-grid">
        <?php foreach ($stats as $label => $value): ?>
            <div class="stat-card"><span><?= esc($label) ?></span><strong><?= esc((string) $value) ?></strong></div>
        <?php endforeach; ?>
    </div>

    <div class="dash-grid">
        <?php if (staff_can('schedules')): ?>
            <section class="account-card">
                <div class="card-head"><h2>Bookings waiting for approval</h2><a href="<?= site_url('admin/schedules') ?>">Calendar</a></div>
                <?php if (empty($pending)): ?>
                    <p class="empty-state">No pending bookings.</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($pending as $item): ?>
                            <li>
                                <div>
                                    <a href="<?= site_url('admin/schedules/' . $item['schedule_id']) ?>"><strong>#<?= $item['schedule_id'] ?> · <?= esc($item['service_name']) ?></strong></a>
                                    <span><?= esc($item['customer_name']) ?> · <?= nice_date($item['schedule_date']) ?> <?= nice_time($item['schedule_time']) ?></span>
                                </div>
                                <a class="btn btn-light btn-small" href="<?= site_url('admin/schedules/' . $item['schedule_id']) ?>">Review</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if (staff_can('issues')): ?>
            <section class="account-card">
                <div class="card-head"><h2>Field reports from employees</h2><a href="<?= site_url('admin/issues') ?>">View all</a></div>
                <?php if (empty($issues)): ?>
                    <p class="empty-state">No open reports.</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($issues as $issue): ?>
                            <li>
                                <div>
                                    <strong><?= esc($issue['category']) ?> · <?= esc($issue['employee_name']) ?></strong>
                                    <span><?= esc(mb_strimwidth($issue['description'], 0, 90, '...')) ?></span>
                                </div>
                                <?= status_badge($issue['status']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <section class="account-card">
            <div class="card-head"><h2>Latest site check-ins</h2><a href="<?= site_url('admin/attendance') ?>">Attendance</a></div>
            <?php if (empty($checkins)): ?>
                <p class="empty-state">No check-ins yet.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($checkins as $row): ?>
                        <li>
                            <div><strong><?= esc($row['employee_name']) ?></strong><span><?= nice_datetime($row['time_in']) ?></span></div>
                            <?php if ($row['latitude']): ?>
                                <a href="https://www.google.com/maps?q=<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" target="_blank" rel="noopener">Map</a>
                            <?php else: ?><small>No location</small><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
