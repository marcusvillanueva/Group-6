<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>

    <section class="account-card registration-page-card">
        <p class="eyebrow">User management · <?= $type === 'customers' ? 'Customer' : 'Employee' ?> #<?= $id ?></p>
        <h1>Edit <?= esc($person['firstname'] . ' ' . $person['lastname']) ?></h1>
        <p>Username: <strong><?= esc($person['username']) ?></strong> · Status: <?= $person['is_active'] ? status_badge('active') : status_badge('deactivated') ?></p>

        <?= $this->include('partials/flash') ?>

        <form class="registration-form" method="post" action="<?= site_url('admin/users/' . $type . '/' . $id . '/edit') ?>">
            <?= csrf_field() ?>
            <div class="registration-grid">
                <?= $this->include('partials/person_fields') ?>
                <label class="registration-full">Address *<input type="text" name="address" value="<?= esc(old('address', $person['address']), 'attr') ?>" required></label>
                <?php if ($type === 'employees'): ?>
                    <?= $this->include('partials/employee_fields') ?>
                <?php endif; ?>
                <label class="registration-full">Reset password <span>(leave blank to keep the current password)</span><input type="password" name="new_password" autocomplete="new-password" minlength="8"></label>
            </div>
            <div class="registration-actions"><button class="btn btn-primary" type="submit">Save changes</button><a class="btn btn-light" href="<?= site_url('admin/users?type=' . $type) ?>">Cancel</a></div>
        </form>
    </section>
</main>
<?= $this->endSection() ?>
