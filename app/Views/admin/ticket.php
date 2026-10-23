<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">Ticket #<?= $ticket['ticket_id'] ?> · <?= esc($ticket['category']) ?></p>
                <h2><?= esc($ticket['subject']) ?></h2>
                <p class="small-note"><?= esc($ticket['customer_name']) ?> · <?= esc($ticket['email']) ?> · <?= esc($ticket['phone']) ?></p>
            </div>
            <?= status_badge($ticket['status']) ?>
        </div>

        <?= $this->include('partials/ticket_thread') ?>

        <?php if ($ticket['status'] !== 'closed'): ?>
            <form method="post" action="<?= site_url('admin/support/' . $ticket['ticket_id']) ?>" class="chat-reply">
                <?= csrf_field() ?>
                <textarea name="message" rows="3" placeholder="Reply to the customer..." required></textarea>
                <button class="btn btn-primary" type="submit">Send reply</button>
            </form>
            <form method="post" action="<?= site_url('admin/support/' . $ticket['ticket_id'] . '/close') ?>" onsubmit="return confirm('Close this ticket?');">
                <?= csrf_field() ?>
                <button class="link-button" type="submit">Close ticket</button>
            </form>
        <?php endif; ?>
        <a class="back-link" href="<?= site_url('admin/support') ?>">← All tickets</a>
    </section>
</main>
<?= $this->endSection() ?>
