<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="stat-grid">
        <div class="stat-card"><span>Completed services</span><strong><?= count($completed) ?></strong></div>
        <div class="stat-card"><span>Payments made</span><strong><?= count($payments) ?></strong></div>
        <div class="stat-card"><span>Total paid</span><strong><?= peso($totalPaid) ?></strong></div>
    </div>

    <section class="account-card">
        <p class="eyebrow">History</p>
        <h2>Completed services</h2>
        <?php if (empty($completed)): ?>
            <p class="empty-state">No completed services yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Service</th><th>Date</th><th>Technician</th><th>Payment</th></tr></thead>
                    <tbody>
                    <?php foreach ($completed as $item): ?>
                        <tr>
                            <td><?= $item['schedule_id'] ?></td>
                            <td><?= esc($item['service_name']) ?></td>
                            <td><?= nice_date($item['schedule_date']) ?></td>
                            <td><?= esc($item['technician_name'] ?? '—') ?></td>
                            <td><?= $item['payment_id'] ? 'Paid · <a href="' . site_url('customer/receipt/' . $item['payment_id']) . '">' . esc($item['receipt_no']) . '</a>' : 'Awaiting payment' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="account-card section-gap">
        <h2>Payments and receipts</h2>
        <?php if (empty($payments)): ?>
            <p class="empty-state">No payments recorded yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Receipt no.</th><th>Service</th><th>Paid on</th><th>Method</th><th>Amount</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= esc($payment['receipt_no']) ?></td>
                            <td><?= esc($payment['service_name']) ?></td>
                            <td><?= nice_datetime($payment['paid_at']) ?></td>
                            <td><?= esc($payment['method']) ?></td>
                            <td><?= peso($payment['amount']) ?></td>
                            <td><a href="<?= site_url('customer/receipt/' . $payment['payment_id']) ?>">View receipt</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<?= $this->endSection() ?>
