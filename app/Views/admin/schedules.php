<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">Schedule overview</p>
                <h2><?= date('F Y', strtotime($month . '-01')) ?></h2>
            </div>
            <div class="toolbar-inline">
                <a class="btn btn-light btn-small" href="<?= site_url('admin/schedules?month=' . $prevMonth . '&status=' . $status) ?>">← Prev</a>
                <a class="btn btn-light btn-small" href="<?= site_url('admin/schedules') ?>">Today</a>
                <a class="btn btn-light btn-small" href="<?= site_url('admin/schedules?month=' . $nextMonth . '&status=' . $status) ?>">Next →</a>
            </div>
        </div>

        <div class="pill-tabs">
            <a href="<?= site_url('admin/schedules?month=' . $month) ?>" class="<?= $status === '' ? 'active' : '' ?>">All</a>
            <?php foreach (config('SunSon')->scheduleStatuses as $option): ?>
                <a href="<?= site_url('admin/schedules?month=' . $month . '&status=' . $option) ?>" class="<?= $status === $option ? 'active' : '' ?>"><?= ucfirst($option) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="calendar" role="table" aria-label="Bookings calendar">
            <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName): ?>
                <div class="calendar-head" role="columnheader"><?= $dayName ?></div>
            <?php endforeach; ?>

            <?php foreach ($weeks as $week): ?>
                <?php foreach ($week as $date): ?>
                    <?php if ($date === null): ?>
                        <div class="calendar-day empty"></div>
                    <?php else: ?>
                        <div class="calendar-day <?= $date === date('Y-m-d') ? 'today' : '' ?>">
                            <span class="day-number"><?= (int) substr($date, 8, 2) ?></span>
                            <?php foreach ($byDate[$date] ?? [] as $booking): ?>
                                <a class="cal-item cal-<?= esc($booking['status'], 'attr') ?>" href="<?= site_url('admin/schedules/' . $booking['schedule_id']) ?>" title="<?= esc($booking['customer_name'] . ' · ' . ucfirst($booking['status']), 'attr') ?>">
                                    <?= nice_time($booking['schedule_time']) ?> <?= esc($booking['service_name']) ?>
                                    <small><?= esc($booking['customer_name']) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <p class="legend"><span class="cal-item cal-pending">Pending</span><span class="cal-item cal-approved">Approved</span><span class="cal-item cal-completed">Completed</span><span class="cal-item cal-declined">Declined</span><span class="cal-item cal-cancelled">Cancelled</span></p>
    </section>

    <section class="account-card section-gap">
        <h2>All requests waiting for approval (<?= count($pending) ?>)</h2>
        <?php if (empty($pending)): ?>
            <p class="empty-state">Nothing to approve right now.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Customer</th><th>Service</th><th>Requested date</th><th>Address</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($pending as $item): ?>
                        <tr>
                            <td><?= $item['schedule_id'] ?></td>
                            <td><?= esc($item['customer_name']) ?></td>
                            <td><?= esc($item['service_name']) ?></td>
                            <td><?= nice_date($item['schedule_date']) ?> · <?= nice_time($item['schedule_time']) ?></td>
                            <td><?= esc($item['address']) ?></td>
                            <td><a class="btn btn-primary btn-small" href="<?= site_url('admin/schedules/' . $item['schedule_id']) ?>">Review</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<?= $this->endSection() ?>
