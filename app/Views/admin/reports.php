<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$maxUsage  = max(array_merge([1], array_column($serviceUsage, 'bookings')));
$maxStatus = max(array_merge([1], array_column($statusCounts, 'total')));
?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>

    <section class="account-card dashboard-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">Reports &amp; analytics</p>
                <h1>Business report</h1>
                <p>Customer activity, service usage, and revenue for the last 6 months. Generated <?= date('M j, Y g:i A') ?>.</p>
            </div>
            <button class="btn btn-primary no-print" type="button" onclick="window.print()">Print / save as PDF</button>
        </div>
    </section>

    <div class="stat-grid">
        <div class="stat-card"><span>Total customers</span><strong><?= (int) $totals['customers'] ?></strong></div>
        <div class="stat-card"><span>Total bookings</span><strong><?= (int) $totals['bookings'] ?></strong></div>
        <div class="stat-card"><span>Completed services</span><strong><?= (int) $totals['completed'] ?></strong></div>
        <div class="stat-card"><span>Total revenue</span><strong><?= peso($totals['revenue']) ?></strong></div>
    </div>

    <div class="dash-grid three">
        <section class="account-card">
            <h2>New customers</h2>
            <?= view('partials/column_chart', ['chart' => $newCustomers]) ?>
        </section>
        <section class="account-card">
            <h2>Bookings</h2>
            <?= view('partials/column_chart', ['chart' => $bookings]) ?>
        </section>
        <section class="account-card">
            <h2>Revenue</h2>
            <?= view('partials/column_chart', ['chart' => $revenue, 'money' => true]) ?>
        </section>
    </div>

    <div class="dash-grid">
        <section class="account-card">
            <h2>Service usage</h2>
            <div class="bar-chart">
                <?php foreach ($serviceUsage as $row): ?>
                    <div class="bar-row">
                        <span class="bar-label"><?= esc($row['name']) ?></span>
                        <div class="bar-track"><div class="bar-fill" style="width: <?= round($row['bookings'] / $maxUsage * 100) ?>%"></div></div>
                        <span class="bar-value"><?= (int) $row['bookings'] ?> · <?= peso($row['revenue']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="account-card">
            <h2>Bookings by status</h2>
            <?php if (empty($statusCounts)): ?>
                <p class="empty-state">No bookings yet.</p>
            <?php endif; ?>
            <div class="bar-chart">
                <?php foreach ($statusCounts as $row): ?>
                    <div class="bar-row">
                        <span class="bar-label"><?= esc(ucfirst($row['status'])) ?></span>
                        <div class="bar-track"><div class="bar-fill fill-<?= esc($row['status'], 'attr') ?>" style="width: <?= round($row['total'] / $maxStatus * 100) ?>%"></div></div>
                        <span class="bar-value"><?= (int) $row['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 class="section-gap">Most active customers</h2>
            <?php if (empty($topCustomers)): ?>
                <p class="empty-state">No customer bookings yet.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead><tr><th>Customer</th><th>Bookings</th><th>Total paid</th></tr></thead>
                    <tbody>
                    <?php foreach ($topCustomers as $row): ?>
                        <tr><td><?= esc($row['name']) ?></td><td><?= (int) $row['bookings'] ?></td><td><?= peso($row['spent']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
