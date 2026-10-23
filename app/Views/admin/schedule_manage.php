<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $slots = config('SunSon')->timeSlots; ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid dash-grid-wide-left">
        <section class="account-card">
            <div class="card-head">
                <div>
                    <p class="eyebrow">Booking #<?= $booking['schedule_id'] ?></p>
                    <h2><?= esc($booking['service_name']) ?></h2>
                </div>
                <?= status_badge($booking['status']) ?>
            </div>

            <form method="post" action="<?= site_url('admin/schedules/' . $booking['schedule_id']) ?>" class="registration-form">
                <?= csrf_field() ?>
                <div class="registration-grid">
                    <label>Status *
                        <select name="status" required>
                            <?php foreach (config('SunSon')->scheduleStatuses as $option): ?>
                                <option value="<?= $option ?>" <?= old('status', $booking['status']) === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Assigned technician
                        <select name="technician_id">
                            <option value="">Not assigned</option>
                            <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['employee_id'] ?>" <?= (string) old('technician_id', (string) $booking['technician_id']) === (string) $tech['employee_id'] ? 'selected' : '' ?>>
                                    <?= esc($tech['firstname'] . ' ' . $tech['lastname']) ?> (<?= esc(role_label($tech['role'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Date *<input type="date" name="schedule_date" value="<?= esc(old('schedule_date', $booking['schedule_date']), 'attr') ?>" required></label>
                    <label>Time *
                        <select name="schedule_time" required>
                            <?php foreach ($slots as $value => $label): ?>
                                <option value="<?= $value ?>" <?= old('schedule_time', $booking['schedule_time']) === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="registration-full">Note to customer and technician <span>(optional)</span><input type="text" name="admin_note" value="<?= esc(old('admin_note', $booking['admin_note']), 'attr') ?>" maxlength="255" placeholder="e.g. Please prepare roof access"></label>
                </div>
                <p class="small-note">The customer is notified automatically when the status, schedule, or technician changes.</p>
                <div class="registration-actions">
                    <button class="btn btn-primary" type="submit">Save booking</button>
                    <a class="btn btn-light" href="<?= site_url('admin/schedules?month=' . substr($booking['schedule_date'], 0, 7)) ?>">Back to calendar</a>
                </div>
            </form>
        </section>

        <div>
            <section class="account-card">
                <h3>Customer</h3>
                <div class="dashboard-details">
                    <p><strong>Name:</strong> <?= esc($customer['firstname'] . ' ' . $customer['lastname']) ?></p>
                    <p><strong>Phone:</strong> <?= esc($customer['phone']) ?></p>
                    <p><strong>Email:</strong> <?= esc($customer['email']) ?></p>
                    <p><strong>Service address:</strong> <?= esc($booking['address']) ?></p>
                    <?php if ($booking['notes']): ?><p><strong>Customer notes:</strong> <?= nl2br(esc($booking['notes'])) ?></p><?php endif; ?>
                    <p><strong>Requested on:</strong> <?= nice_datetime($booking['created_at']) ?></p>
                </div>
            </section>

            <section class="account-card section-gap">
                <h3>Payment</h3>
                <?php if ($payment): ?>
                    <p><?= status_badge('paid') ?> <?= peso($payment['amount']) ?> via <?= esc($payment['method']) ?></p>
                    <p class="small-note">Receipt <?= esc($payment['receipt_no']) ?> · <?= nice_datetime($payment['paid_at']) ?> · recorded by <?= esc($payment['recorded_by']) ?></p>
                <?php elseif ($booking['status'] === 'completed'): ?>
                    <form method="post" action="<?= site_url('admin/schedules/' . $booking['schedule_id'] . '/payment') ?>" class="mini-form">
                        <?= csrf_field() ?>
                        <label>Amount paid (₱) *<input type="number" name="amount" min="0.01" step="0.01" value="<?= esc(old('amount', $booking['service_price'] ?? ''), 'attr') ?>" required></label>
                        <label>Method *
                            <select name="method" required>
                                <?php foreach (config('SunSon')->paymentMethods as $method): ?>
                                    <option value="<?= esc($method, 'attr') ?>"><?= esc($method) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <button class="btn btn-primary" type="submit">Record payment and issue receipt</button>
                    </form>
                <?php else: ?>
                    <p class="empty-state">Payments can be recorded once the service is completed.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
