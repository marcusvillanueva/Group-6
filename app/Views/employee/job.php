<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page">
    <?= $this->include('partials/flash') ?>

    <section class="account-card dashboard-card">
        <p class="eyebrow">Job #<?= $job['schedule_id'] ?> · view only</p>
        <h1><?= esc($job['service_name']) ?></h1>
        <?= status_badge($job['status']) ?>

        <!-- Only what the technician needs: name, address, service. No email, phone or payments. -->
        <div class="dashboard-details">
            <p><strong>Customer:</strong> <?= esc($job['customer_name']) ?></p>
            <p><strong>Address:</strong> <?= esc($job['address']) ?> · <a href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode($job['address']) ?>" target="_blank" rel="noopener">Open in Maps</a></p>
            <p><strong>Service type:</strong> <?= esc($job['service_name']) ?></p>
            <p><strong>Schedule:</strong> <?= nice_date($job['schedule_date']) ?> at <?= nice_time($job['schedule_time']) ?></p>
            <?php if ($job['notes']): ?><p><strong>Customer notes:</strong> <?= nl2br(esc($job['notes'])) ?></p><?php endif; ?>
            <?php if ($job['admin_note']): ?><p><strong>Note from dispatch:</strong> <?= esc($job['admin_note']) ?></p><?php endif; ?>
        </div>

        <div class="registration-actions">
            <?php if ($job['status'] === 'approved'): ?>
                <form method="post" action="<?= site_url('employee/jobs/' . $job['schedule_id'] . '/complete') ?>" onsubmit="return confirm('Mark this job as completed?');">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary" type="submit">Mark job as completed</button>
                </form>
            <?php endif; ?>
            <a class="btn btn-light" href="<?= site_url('employee/dashboard') ?>#report">Report a problem</a>
            <a class="btn btn-light" href="<?= site_url('employee/dashboard') ?>">Back</a>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
