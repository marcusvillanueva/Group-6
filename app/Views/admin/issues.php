<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <p class="eyebrow">Report submissions</p>
        <h2>Problems reported from the field</h2>

        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>#</th><th>Reported</th><th>Employee</th><th>Type</th><th>Related job</th><th>Details</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr class="<?= $report['status'] === 'resolved' ? 'row-muted' : '' ?>">
                        <td><?= $report['report_id'] ?></td>
                        <td><?= nice_datetime($report['created_at']) ?></td>
                        <td><?= esc($report['employee_name']) ?></td>
                        <td><?= esc($report['category']) ?></td>
                        <td>
                            <?php if ($report['schedule_id']): ?>
                                <?php if (staff_can('schedules')): ?>
                                    <a href="<?= site_url('admin/schedules/' . $report['schedule_id']) ?>">#<?= $report['schedule_id'] ?> <?= esc($report['service_name']) ?></a>
                                <?php else: ?>#<?= $report['schedule_id'] ?><?php endif; ?>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td class="wrap-text"><?= nl2br(esc($report['description'])) ?></td>
                        <td><?= status_badge($report['status']) ?></td>
                        <td>
                            <?php if ($report['status'] === 'open'): ?>
                                <form method="post" action="<?= site_url('admin/issues/' . $report['report_id'] . '/resolve') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-light btn-small" type="submit">Mark resolved</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reports)): ?>
                    <tr><td colspan="8" class="empty-state">No reports yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
