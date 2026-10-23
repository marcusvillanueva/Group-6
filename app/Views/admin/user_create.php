<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>

    <section class="account-card registration-page-card">
        <p class="eyebrow">Restricted access · HR / Admin</p>
        <h1>Create an employee account</h1>
        <p>Only signed-in HR/Admin accounts can use this form. New accounts are created here instead of allowing employees to sign themselves up.</p>

        <?= $this->include('partials/flash') ?>

        <form class="registration-form" method="post" action="<?= site_url('admin/users/create') ?>">
            <?= csrf_field() ?>
            <div class="registration-grid">
                <?= $this->include('partials/person_fields') ?>
                <label>Username *<input type="text" name="username" value="<?= esc(old('username'), 'attr') ?>" autocomplete="off" required></label>
                <label class="registration-full">Address *<input type="text" name="address" value="<?= esc(old('address'), 'attr') ?>" required></label>
                <?= $this->include('partials/employee_fields') ?>
                <label class="registration-full">Temporary password * <span>(at least 8 characters)</span><input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
            </div>
            <p class="privacy-note">Share the temporary login details with the employee through an appropriate private channel.</p>
            <div class="registration-actions"><button class="btn btn-primary" type="submit">Create account</button><a class="btn btn-light" href="<?= site_url('admin/users') ?>">Back to user list</a></div>
        </form>
    </section>
</main>
<?= $this->endSection() ?>
