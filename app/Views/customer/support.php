<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid dash-grid-wide-left">
        <section class="account-card">
            <p class="eyebrow">Support / help</p>
            <h2>My tickets</h2>
            <?php if (empty($tickets)): ?>
                <p class="empty-state">No tickets yet. If something goes wrong, send us a message and our team will reply here.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($tickets as $ticket): ?>
                        <li>
                            <div>
                                <a href="<?= site_url('customer/support/' . $ticket['ticket_id']) ?>"><strong>#<?= $ticket['ticket_id'] ?> · <?= esc($ticket['subject']) ?></strong></a>
                                <span><?= esc($ticket['category']) ?> · last update <?= nice_datetime($ticket['updated_at']) ?></span>
                            </div>
                            <?= status_badge($ticket['status']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <p class="small-note">Prefer to call? Reach us at 0929 123 0983.</p>
        </section>

        <section class="account-card">
            <p class="eyebrow">Need help?</p>
            <h2>Open a ticket</h2>
            <form method="post" action="<?= site_url('customer/support') ?>" class="registration-form">
                <?= csrf_field() ?>
                <label>Subject *<input type="text" name="subject" value="<?= esc(old('subject'), 'attr') ?>" required></label>
                <label>Category *
                    <select name="category" required>
                        <option value="">Choose one</option>
                        <?php foreach (config('SunSon')->ticketCategories as $category): ?>
                            <option value="<?= esc($category, 'attr') ?>" <?= old('category') === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Message *<textarea name="message" rows="5" required><?= esc(old('message')) ?></textarea></label>
                <button class="btn btn-primary" type="submit">Send to support</button>
            </form>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
