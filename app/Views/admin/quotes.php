<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <p class="eyebrow">From the homepage</p>
        <h2>Free quote requests</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>#</th><th>Received</th><th>Name</th><th>Contact</th><th>Interested in</th><th>Monthly bill</th><th>Message</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($quotes as $quote): ?>
                    <tr class="<?= $quote['status'] === 'contacted' ? 'row-muted' : '' ?>">
                        <td><?= $quote['quote_id'] ?></td>
                        <td><?= nice_datetime($quote['created_at']) ?></td>
                        <td><?= esc($quote['fullname']) ?><br><small><?= esc($quote['address']) ?></small></td>
                        <td><a href="mailto:<?= esc($quote['email'], 'attr') ?>"><?= esc($quote['email']) ?></a><br><small><?= esc($quote['phone']) ?></small></td>
                        <td><?= esc($quote['service_interest']) ?></td>
                        <td><?= $quote['monthly_bill'] !== null ? peso($quote['monthly_bill']) : '—' ?></td>
                        <td class="wrap-text"><?= esc((string) $quote['message']) ?></td>
                        <td><?= status_badge($quote['status']) ?></td>
                        <td>
                            <?php if ($quote['status'] === 'new'): ?>
                                <form method="post" action="<?= site_url('admin/quotes/' . $quote['quote_id'] . '/contacted') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-light btn-small" type="submit">Mark contacted</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($quotes)): ?>
                    <tr><td colspan="9" class="empty-state">No quote requests yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
