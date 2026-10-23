<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $types = config('SunSon')->notificationTypes; ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid dash-grid-wide-left">
        <section class="account-card">
            <p class="eyebrow">Notifications control panel</p>
            <h2>Send a notification to customers</h2>
            <form method="post" action="<?= site_url('admin/notifications/send') ?>" class="registration-form">
                <?= csrf_field() ?>
                <div class="registration-grid">
                    <label>Type *
                        <select name="type" required>
                            <?php foreach ($types as $value => $label): ?>
                                <option value="<?= $value ?>" <?= old('type') === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Send to *
                        <select name="audience" id="audience" required>
                            <option value="all">All active customers</option>
                            <option value="one" <?= old('audience') === 'one' ? 'selected' : '' ?>>One customer</option>
                        </select>
                    </label>
                    <label class="registration-full" id="customer-picker">Customer
                        <select name="customer_id">
                            <option value="">Choose a customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= old('customer_id') == $customer['customer_id'] ? 'selected' : '' ?>><?= esc($customer['lastname'] . ', ' . $customer['firstname'] . ' (' . $customer['username'] . ')') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="registration-full">Title *<input type="text" name="title" value="<?= esc(old('title'), 'attr') ?>" maxlength="150" required placeholder="e.g. 10% off maintenance this November"></label>
                    <label class="registration-full">Message *<textarea name="message" rows="4" required><?= esc(old('message')) ?></textarea></label>
                </div>
                <button class="btn btn-primary" type="submit">Send notification</button>
            </form>
        </section>

        <div>
            <section class="account-card">
                <h3>Automatic alerts</h3>
                <form method="post" action="<?= site_url('admin/notifications/settings') ?>" class="mini-form">
                    <?= csrf_field() ?>
                    <label class="check-row"><input type="checkbox" name="auto_schedule_updates" value="1" <?= $settings->isOn('auto_schedule_updates') ? 'checked' : '' ?>> Notify customers when a booking is approved, declined, rescheduled, assigned, or completed</label>
                    <label class="check-row"><input type="checkbox" name="auto_payment_receipts" value="1" <?= $settings->isOn('auto_payment_receipts') ? 'checked' : '' ?>> Notify customers when a payment is recorded</label>
                    <button class="btn btn-secondary" type="submit">Save settings</button>
                </form>
            </section>

            <section class="account-card section-gap">
                <h3>Booking reminders</h3>
                <p><?= $tomorrowJobs ?> approved booking(s) tomorrow. Send each customer a reminder (only once, and only if they allow reminders).</p>
                <form method="post" action="<?= site_url('admin/notifications/reminders') ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary" type="submit">Send tomorrow's reminders</button>
                </form>
            </section>
        </div>
    </div>

    <section class="account-card section-gap">
        <h2>Recently sent</h2>
        <?php if (empty($recent)): ?>
            <p class="empty-state">Nothing sent yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Sent</th><th>Customer</th><th>Type</th><th>Title</th><th>Read?</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent as $note): ?>
                        <tr>
                            <td><?= nice_datetime($note['created_at']) ?></td>
                            <td><?= esc($note['customer_name']) ?></td>
                            <td><?= esc($types[$note['type']] ?? $note['type']) ?></td>
                            <td><?= esc($note['title']) ?></td>
                            <td><?= $note['is_read'] ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Only show the customer list when "One customer" is chosen.
var audience = document.getElementById('audience');
function toggleCustomerPicker() {
    document.getElementById('customer-picker').style.display = audience.value === 'one' ? '' : 'none';
}
audience.addEventListener('change', toggleCustomerPicker);
toggleCustomerPicker();
</script>
<?= $this->endSection() ?>
