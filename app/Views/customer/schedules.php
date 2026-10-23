<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $slots = config('SunSon')->timeSlots; ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid dash-grid-wide-left">
        <section class="account-card">
            <p class="eyebrow">Schedule section</p>
            <h2>My scheduled services</h2>

            <?php if (empty($schedules)): ?>
                <p>You have not booked any services yet. Use the form to book your first visit.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>#</th><th>Service</th><th>Date &amp; time</th><th>Technician</th><th>Status</th><th>Manage</th></tr></thead>
                        <tbody>
                        <?php foreach ($schedules as $item): ?>
                            <tr>
                                <td><?= $item['schedule_id'] ?></td>
                                <td><?= esc($item['service_name']) ?></td>
                                <td><?= nice_date($item['schedule_date']) ?><br><small><?= nice_time($item['schedule_time']) ?></small></td>
                                <td><?= esc($item['technician_name'] ?? 'Not assigned yet') ?></td>
                                <td><?= status_badge($item['status']) ?><?php if ($item['admin_note']): ?><br><small><?= esc($item['admin_note']) ?></small><?php endif; ?></td>
                                <td>
                                    <?php if (in_array($item['status'], ['pending', 'approved'], true)): ?>
                                        <details class="inline-manage">
                                            <summary>Change</summary>
                                            <form method="post" action="<?= site_url('customer/schedules/' . $item['schedule_id'] . '/reschedule') ?>" class="mini-form">
                                                <?= csrf_field() ?>
                                                <label>New date<input type="date" name="schedule_date" min="<?= $minDate ?>" value="<?= esc($item['schedule_date'], 'attr') ?>" required></label>
                                                <label>New time
                                                    <select name="schedule_time" required>
                                                        <?php foreach ($slots as $value => $label): ?>
                                                            <option value="<?= $value ?>" <?= $item['schedule_time'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </label>
                                                <button class="btn btn-secondary" type="submit">Request new time</button>
                                            </form>
                                            <form method="post" action="<?= site_url('customer/schedules/' . $item['schedule_id'] . '/cancel') ?>" onsubmit="return confirm('Cancel this booking?');">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-light btn-danger-text" type="submit">Cancel booking</button>
                                            </form>
                                        </details>
                                    <?php elseif ($item['status'] === 'completed' && $item['payment_id']): ?>
                                        <a href="<?= site_url('customer/receipt/' . $item['payment_id']) ?>">Receipt</a>
                                    <?php else: ?>
                                        <small>—</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section id="book" class="account-card">
            <p class="eyebrow">New booking</p>
            <h2>Book a service</h2>
            <form method="post" action="<?= site_url('customer/schedules') ?>" class="registration-form">
                <?= csrf_field() ?>
                <label>Service *
                    <select name="service_id" required>
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['service_id'] ?>" <?= old('service_id') == $service['service_id'] ? 'selected' : '' ?>>
                                <?= esc($service['name']) ?> (<?= esc(peso($service['price'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Preferred date *<input type="date" name="schedule_date" min="<?= $minDate ?>" value="<?= esc(old('schedule_date'), 'attr') ?>" required></label>
                <label>Preferred time *
                    <select name="schedule_time" required>
                        <?php foreach ($slots as $value => $label): ?>
                            <option value="<?= $value ?>" <?= old('schedule_time') === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Service address *<input type="text" name="address" value="<?= esc(old('address', $address), 'attr') ?>" required></label>
                <label>Notes for our team <span>(optional)</span><textarea name="notes" rows="3" placeholder="Roof type, best contact time, concerns..."><?= esc(old('notes')) ?></textarea></label>
                <button class="btn btn-primary" type="submit">Send booking request</button>
            </form>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
