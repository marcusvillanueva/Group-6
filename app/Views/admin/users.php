<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <section class="account-card">
        <div class="card-head">
            <div>
                <p class="eyebrow">User management</p>
                <h2><?= $type === 'customers' ? 'Customer accounts' : 'Employee accounts' ?></h2>
            </div>
            <a class="btn btn-primary" href="<?= site_url('admin/users/create') ?>">+ Add employee</a>
        </div>

        <div class="toolbar">
            <div class="pill-tabs">
                <a href="<?= site_url('admin/users?type=employees') ?>" class="<?= $type === 'employees' ? 'active' : '' ?>">Employees</a>
                <?php if (session('role') === 'admin'): ?>
                    <a href="<?= site_url('admin/users?type=customers') ?>" class="<?= $type === 'customers' ? 'active' : '' ?>">Customers</a>
                <?php endif; ?>
            </div>
            <form method="get" action="<?= site_url('admin/users') ?>" class="search-form">
                <input type="hidden" name="type" value="<?= esc($type, 'attr') ?>">
                <input type="search" name="q" value="<?= esc($q, 'attr') ?>" placeholder="Search name, username or email">
                <button class="btn btn-light" type="submit">Search</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th><th>Username</th><th>Email / phone</th>
                        <?php if ($type === 'employees'): ?><th>Department</th><th>Access</th><?php endif; ?>
                        <th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <?php $id = $type === 'customers' ? $user['customer_id'] : $user['employee_id']; ?>
                    <tr class="<?= $user['is_active'] ? '' : 'row-muted' ?>">
                        <td><strong><?= esc($user['firstname'] . ' ' . $user['lastname']) ?></strong></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['email']) ?><br><small><?= esc($user['phone']) ?></small></td>
                        <?php if ($type === 'employees'): ?>
                            <td><?= esc($user['department']) ?></td>
                            <td><?= esc(role_label($user['role'])) ?></td>
                        <?php endif; ?>
                        <td><?= $user['is_active'] ? status_badge('active') : status_badge('deactivated') ?></td>
                        <td class="actions">
                            <a href="<?= site_url('admin/users/' . $type . '/' . $id . '/edit') ?>">Edit</a>
                            <?php if (! ($type === 'employees' && (int) $id === (int) session('user_id'))): ?>
                                <form method="post" action="<?= site_url('admin/users/' . $type . '/' . $id . '/toggle') ?>" onsubmit="return confirm('<?= $user['is_active'] ? 'Deactivate' : 'Reactivate' ?> this account?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="link-button <?= $user['is_active'] ? 'danger' : '' ?>"><?= $user['is_active'] ? 'Deactivate' : 'Reactivate' ?></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="empty-state">No accounts found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<?= $this->endSection() ?>
