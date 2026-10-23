<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <p class="eyebrow">Customer support</p>
        <h2>Support tickets</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>#</th><th>Customer</th><th>Subject</th><th>Category</th><th>Last update</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr class="<?= $ticket['status'] === 'closed' ? 'row-muted' : '' ?>">
                        <td><?= $ticket['ticket_id'] ?></td>
                        <td><?= esc($ticket['customer_name']) ?></td>
                        <td><?= esc($ticket['subject']) ?></td>
                        <td><?= esc($ticket['category']) ?></td>
                        <td><?= nice_datetime($ticket['updated_at']) ?></td>
                        <td><?= status_badge($ticket['status']) ?></td>
                        <td><a class="btn btn-light btn-small" href="<?= site_url('admin/support/' . $ticket['ticket_id']) ?>">Open</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tickets)): ?>
                    <tr><td colspan="7" class="empty-state">No tickets yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
