<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">Ticket #<?= $ticket['ticket_id'] ?> · <?= esc($ticket['category']) ?></p>
                <h2><?= esc($ticket['subject']) ?></h2>
            </div>
            <?= status_badge($ticket['status']) ?>
        </div>

        <?= $this->include('partials/ticket_thread') ?>

        <?php if ($ticket['status'] !== 'closed'): ?>
            <form method="post" action="<?= site_url('customer/support/' . $ticket['ticket_id']) ?>" class="chat-reply">
                <?= csrf_field() ?>
                <textarea name="message" rows="3" placeholder="Type your reply..." required></textarea>
                <button class="btn btn-primary" type="submit">Send reply</button>
            </form>
        <?php else: ?>
            <p class="small-note">This ticket was closed by our team. <a href="<?= site_url('customer/support') ?>">Open a new ticket</a> if you still need help.</p>
        <?php endif; ?>
        <a class="back-link" href="<?= site_url('customer/support') ?>">← All tickets</a>
    </section>
</main>
<?= $this->endSection() ?>
