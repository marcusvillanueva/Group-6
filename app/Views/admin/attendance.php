<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>

    <section class="account-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">Field attendance</p>
                <h2>Site time-in records</h2>
            </div>
            <form method="get" action="<?= site_url('admin/attendance') ?>" class="search-form">
                <input type="date" name="date" value="<?= esc($date, 'attr') ?>" aria-label="Filter by date">
                <button class="btn btn-light" type="submit">Filter</button>
                <?php if ($date): ?><a href="<?= site_url('admin/attendance') ?>">Show all</a><?php endif; ?>
            </form>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Employee</th><th>Time in</th><th>Coordinates</th><th>Map</th></tr></thead>
                <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?= esc($row['employee_name']) ?></td>
                        <td><?= nice_datetime($row['time_in']) ?></td>
                        <td><?= $row['latitude'] ? esc($row['latitude'] . ', ' . $row['longitude']) : '<small>Location not shared</small>' ?></td>
                        <td><?php if ($row['latitude']): ?><a href="https://www.google.com/maps?q=<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" target="_blank" rel="noopener">Open map</a><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                    <tr><td colspan="4" class="empty-state">No time-in records<?= $date ? ' for ' . nice_date($date) : '' ?>.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
