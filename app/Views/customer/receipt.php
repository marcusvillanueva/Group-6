<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page">
    <section class="account-card dashboard-card receipt">
        <div class="receipt-head">
            <div>
                <div class="brand">SUN SON SOLAR</div>
                <small>Pasig City, Metro Manila · 0929 123 0983</small>
            </div>
            <div class="receipt-no">
                <span>Official receipt</span>
                <strong><?= esc($payment['receipt_no']) ?></strong>
            </div>
        </div>

        <div class="dashboard-details receipt-details">
            <p><strong>Billed to:</strong> <?= esc($customer['firstname'] . ' ' . $customer['lastname']) ?></p>
            <p><strong>Service address:</strong> <?= esc($payment['address']) ?></p>
            <p><strong>Service:</strong> <?= esc($payment['service_name']) ?> (booking #<?= $payment['schedule_id'] ?>)</p>
            <p><strong>Service date:</strong> <?= nice_date($payment['schedule_date']) ?> at <?= nice_time($payment['schedule_time']) ?></p>
            <p><strong>Payment date:</strong> <?= nice_datetime($payment['paid_at']) ?></p>
            <p><strong>Payment method:</strong> <?= esc($payment['method']) ?></p>
            <p><strong>Received by:</strong> <?= esc($payment['recorded_by']) ?></p>
        </div>

        <div class="receipt-total">
            <span>Amount paid</span>
            <strong><?= peso($payment['amount']) ?></strong>
        </div>

        <div class="registration-actions no-print">
            <button class="btn btn-primary" type="button" onclick="window.print()">Print or save as PDF</button>
            <a class="btn btn-light" href="<?= site_url('customer/history') ?>">Back to history</a>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
