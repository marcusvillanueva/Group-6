<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/flash') ?>

    <section class="account-card dashboard-card">
        <p class="eyebrow">Employee access</p>
        <h1>Welcome, <?= esc(session('name')) ?>.</h1>
        <p>You are successfully logged in with an employee account.</p>
        <div class="dashboard-details"><p><strong>Username:</strong> <?= esc(session('username')) ?></p><p><strong>Department:</strong> <?= esc(session('department')) ?> · <?= esc(role_label(session('role'))) ?></p></div>
        <?php if (staff_can('schedules')): ?>
            <a class="btn btn-secondary" href="<?= site_url('admin/schedules') ?>">Open schedule overview (dispatch)</a>
        <?php endif; ?>
    </section>

    <!-- Site time-in: one button that records name, time and coordinates -->
    <section class="account-card timein-card">
        <div>
            <p class="eyebrow">Arrived at the site?</p>
            <h2>Time in at site</h2>
            <p>Press the button when you arrive. It saves your name, the time, and your location.</p>
            <?php if ($lastTimeIn): ?>
                <p class="small-note">Last time in: <strong><?= nice_datetime($lastTimeIn['time_in']) ?></strong><?= $lastTimeIn['latitude'] ? '' : ' (no location)' ?></p>
            <?php endif; ?>
        </div>
        <form id="timein-form" method="post" action="<?= site_url('employee/time-in') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="latitude" value="">
            <input type="hidden" name="longitude" value="">
            <button class="timein-button" type="submit">TIME IN</button>
            <p id="timein-status" class="small-note" aria-live="polite"></p>
        </form>
    </section>

    <div class="dash-grid">
        <section class="account-card">
            <p class="eyebrow">My jobs</p>
            <h2>Assigned to me</h2>
            <?php if (empty($jobs)): ?>
                <p class="empty-state">No upcoming jobs assigned to you.</p>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($jobs as $job): ?>
                        <li>
                            <div>
                                <a href="<?= site_url('employee/jobs/' . $job['schedule_id']) ?>"><strong><?= esc($job['service_name']) ?> · <?= esc($job['customer_name']) ?></strong></a>
                                <span><?= nice_date($job['schedule_date']) ?> at <?= nice_time($job['schedule_time']) ?> · <?= esc($job['address']) ?></span>
                            </div>
                            <a class="btn btn-light btn-small" href="<?= site_url('employee/jobs/' . $job['schedule_id']) ?>">Details</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="account-card">
            <p class="eyebrow">Messages from Admin / HR</p>
            <h2>Announcements</h2>
            <?php if (empty($announcements)): ?>
                <p class="empty-state">No announcements yet.</p>
            <?php else: ?>
                <ul class="notice-list">
                    <?php foreach ($announcements as $post): ?>
                        <li class="notice notice-<?= esc(strtolower($post['category']), 'attr') ?>">
                            <div class="notice-top">
                                <span class="badge badge-<?= esc(strtolower($post['category']), 'attr') ?>"><?= esc($post['category']) ?></span>
                                <small><?= nice_datetime($post['created_at']) ?> · <?= esc($post['posted_by']) ?></small>
                            </div>
                            <h3><?= esc($post['title']) ?></h3>
                            <p><?= nl2br(esc($post['message'])) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>

    <section id="report" class="account-card section-gap">
        <div class="dash-grid">
            <div>
                <p class="eyebrow">Report submission</p>
                <h2>Report a problem</h2>
                <p>Equipment issues, customer concerns, or safety problems go straight to the admin dashboard.</p>
                <form method="post" action="<?= site_url('employee/report') ?>" class="registration-form">
                    <?= csrf_field() ?>
                    <label>Related job <span>(optional)</span>
                        <select name="schedule_id">
                            <option value="">Not about a specific job</option>
                            <?php foreach ($jobs as $job): ?>
                                <option value="<?= $job['schedule_id'] ?>" <?= old('schedule_id') == $job['schedule_id'] ? 'selected' : '' ?>>#<?= $job['schedule_id'] ?> · <?= esc($job['service_name']) ?> · <?= esc($job['customer_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Type of problem *
                        <select name="category" required>
                            <option value="">Choose one</option>
                            <?php foreach (config('SunSon')->issueCategories as $category): ?>
                                <option value="<?= esc($category, 'attr') ?>" <?= old('category') === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>What happened? *<textarea name="description" rows="4" required><?= esc(old('description')) ?></textarea></label>
                    <button class="btn btn-primary" type="submit">Send report</button>
                </form>
            </div>
            <div>
                <h3>My recent reports</h3>
                <?php if (empty($reports)): ?>
                    <p class="empty-state">You have not sent any reports.</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($reports as $report): ?>
                            <li>
                                <div><strong><?= esc($report['category']) ?></strong><span><?= nice_datetime($report['created_at']) ?></span></div>
                                <?= status_badge($report['status']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Time-in button: ask the phone/browser for the location first, then send the form.
// If location is blocked or unavailable, the time is still recorded.
document.getElementById('timein-form').addEventListener('submit', function (event) {
    event.preventDefault();
    var form = this;
    var status = document.getElementById('timein-status');
    form.querySelector('button').disabled = true;

    if (!navigator.geolocation) {
        form.submit();
        return;
    }

    status.textContent = 'Getting your location...';
    navigator.geolocation.getCurrentPosition(function (position) {
        form.latitude.value = position.coords.latitude.toFixed(7);
        form.longitude.value = position.coords.longitude.toFixed(7);
        form.submit();
    }, function () {
        status.textContent = 'Location not available. Saving your time in anyway...';
        form.submit();
    }, { enableHighAccuracy: true, timeout: 15000 });
});
</script>
<?= $this->endSection() ?>
